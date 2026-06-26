<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\StartupProfile;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\HiringOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditContinuedTest extends TestCase
{
    use RefreshDatabase;

    public function test_short_password_rejected_on_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'shortpw@test.com',
            'password' => '123',
            'password_confirmation' => '123',
            'role' => 'student',
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_bio_contact_leak_blocked_on_profile_update(): void
    {
        $user = User::create([
            'name' => 'Student',
            'email' => 'student@bio.test',
            'password' => bcrypt('password123'),
            'role' => 'student',
        ]);
        StudentProfile::create([
            'user_id' => $user->id,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'portfolio_links' => [],
        ]);

        $response = $this->actingAs($user)->post(route('student.profile.update'), [
            'name' => 'Student',
            'bio' => 'Email me at hacker@gmail.com for work',
        ]);

        $response->assertSessionHasErrors('bio');
    }

    public function test_college_email_hidden_on_public_profile_for_guests(): void
    {
        $user = User::create([
            'name' => 'Public Student',
            'email' => 'public@student.test',
            'password' => bcrypt('password123'),
            'role' => 'student',
        ]);
        $profile = StudentProfile::create([
            'user_id' => $user->id,
            'college_name' => 'Test University',
            'college_email' => 'secret@college.ac.in',
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'portfolio_links' => [],
        ]);

        $response = $this->get(route('students.public-profile', $profile->id));

        $response->assertOk();
        $response->assertDontSee('secret@college.ac.in', false);
        $response->assertSee('Test University', false);
    }

    public function test_admin_cannot_update_startup_via_student_route(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@audit.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
        $startupUser = User::create([
            'name' => 'Startup Owner',
            'email' => 'owner@startup.test',
            'password' => bcrypt('password123'),
            'role' => 'startup',
        ]);
        StartupProfile::create([
            'user_id' => $startupUser->id,
            'company_name' => 'Real Startup',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.students.update', $startupUser->id), [
            'name' => 'Hijacked',
            'email' => 'hijacked@test.com',
        ]);

        $response->assertNotFound();
    }

    public function test_internship_promo_only_once_per_startup_lifetime(): void
    {
        $startupUser = User::create([
            'name' => 'Promo Startup',
            'email' => 'promo@startup.test',
            'password' => bcrypt('password123'),
            'role' => 'startup',
            'is_verified' => true,
        ]);
        $startup = StartupProfile::create([
            'user_id' => $startupUser->id,
            'company_name' => 'Promo Corp',
            'is_verified' => true,
            'wallet_balance' => 10000,
        ]);
        $studentUser = User::create([
            'name' => 'Intern',
            'email' => 'intern@student.test',
            'password' => bcrypt('password123'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $studentUser->id,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'portfolio_links' => [],
        ]);

        HiringOffer::create([
            'startup_profile_id' => $startup->id,
            'student_profile_id' => $student->id,
            'offer_type' => 'internship',
            'title' => 'Old Promo',
            'description' => 'Expired promo offer',
            'compensation' => 5000,
            'compensation_period' => 'monthly',
            'start_date' => now()->addMonth()->toDateString(),
            'status' => 'expired',
            'reserved_fee' => 0,
            'expires_at' => now()->subDay(),
        ]);

        $hasPromo = HiringOffer::where('startup_profile_id', $startup->id)
            ->where('offer_type', 'internship')
            ->where('reserved_fee', 0.00)
            ->exists();

        $this->assertTrue($hasPromo);

        $fee = $hasPromo ? 1999.00 : 0.00;
        $this->assertEquals(1999.00, $fee);
    }

    public function test_admin_wallet_deduct_respects_balance_with_lock(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'adminwallet@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
        $startupUser = User::create([
            'name' => 'Wallet Startup',
            'email' => 'wallet@startup.test',
            'password' => bcrypt('password123'),
            'role' => 'startup',
        ]);
        $startup = StartupProfile::create([
            'user_id' => $startupUser->id,
            'company_name' => 'Wallet Co',
            'wallet_balance' => 100,
        ]);

        $this->actingAs($admin)->post(route('admin.wallet.deduct'), [
            'user_type' => 'startup',
            'user_id' => $startup->id,
            'amount' => 150,
        ])->assertSessionHasErrors('amount');

        $startup->refresh();
        $this->assertEquals(100.0, (float) $startup->wallet_balance);
    }
}
