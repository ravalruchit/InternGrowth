<?php

namespace Tests\Feature;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function createStudent(): array
    {
        $user = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => false,
        ]);

        $profile = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => null,
            'portfolio_links' => [],
            'reliability_score' => 0,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
        ]);

        return [$user, $profile];
    }

    public function test_registration_sets_user_is_verified_false_for_students(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Student',
            'email' => 'newstudent@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
        ]);

        $response->assertRedirect();
        $this->assertFalse(User::where('email', 'newstudent@test.com')->first()->is_verified);
    }

    public function test_verify_email_legacy_route_does_not_verify_profile(): void
    {
        [$user, $profile] = $this->createStudent();
        $profile->update(['verification_token' => '123456', 'is_verified' => false]);

        $response = $this->get('/verify-email/123456');

        $response->assertRedirect(route('login'));
        $profile->refresh();
        $this->assertFalse($profile->is_verified);
        $this->assertEquals('123456', $profile->verification_token);
    }

    public function test_verify_code_succeeds_and_sets_method_and_expiry_cleared(): void
    {
        [$user, $profile] = $this->createStudent();
        $profile->update([
            'verification_token' => '654321',
            'verification_token_expires_at' => now()->addHours(24),
            'is_verified' => false,
        ]);

        $response = $this->actingAs($user)->post(route('student.verification.verify'), [
            'code' => '654321',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $profile->refresh();
        $this->assertTrue($profile->is_verified);
        $this->assertEquals('college_email', $profile->verification_method);
        $this->assertNull($profile->verification_token);
        $this->assertNull($profile->verification_token_expires_at);
    }

    public function test_expired_otp_is_rejected(): void
    {
        [$user, $profile] = $this->createStudent();
        $profile->update([
            'verification_token' => '111111',
            'verification_token_expires_at' => now()->subMinute(),
            'is_verified' => false,
        ]);

        $response = $this->actingAs($user)->post(route('student.verification.verify'), [
            'code' => '111111',
        ]);

        $response->assertRedirect();
        $profile->refresh();
        $this->assertFalse($profile->is_verified);
        $this->assertNull($profile->verification_token);
    }

    public function test_id_card_stored_on_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        [$user, $profile] = $this->createStudent();

        $file = \Illuminate\Http\UploadedFile::fake()->image('id-card.jpg', 800, 600);

        // Mock AI service to avoid Gemini API call
        $this->mock(\App\Services\AIVerificationService::class, function ($mock) {
            $mock->shouldReceive('verifyCollegeId')->once()->andReturn([
                'approved' => false,
                'confidence' => 75,
                'college_name' => 'Test College',
                'student_name' => 'Test Student',
                'roll_number' => '123',
                'validity' => 'valid',
                'name_match' => true,
                'authenticity' => 'high',
                'recommendation' => 'manual_review',
                'security_flags' => [],
                'reason' => 'Manual review',
                'raw' => [],
            ]);
        });

        $response = $this->actingAs($user)->post(route('student.verify-id.submit'), [
            'id_card_image' => $file,
            'graduation_year' => (int) date('Y'),
        ]);

        $response->assertRedirect(route('student.verify-id'));
        $profile->refresh();
        $this->assertNotNull($profile->id_card_path);
        Storage::disk('local')->assertExists($profile->id_card_path);
        Storage::disk('public')->assertMissing($profile->id_card_path);
    }

    public function test_ai_name_mismatch_prevents_auto_approve(): void
    {
        $service = new \App\Services\AIVerificationService();
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('buildResult');
        $method->setAccessible(true);

        $result = $method->invoke($service, [
            'confidence' => 95,
            'is_college_id' => true,
            'name_match' => false,
            'college_name' => 'Test U',
            'student_name' => 'Other Name',
        ]);

        $this->assertEquals('manual_review', $result['recommendation']);
        $this->assertFalse($result['approved']);
    }
}
