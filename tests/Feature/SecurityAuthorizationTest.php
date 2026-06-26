<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Conversation;
use App\Models\Escrow;
use App\Models\HiringOffer;
use App\Models\StartupProfile;
use App\Models\StudentProfile;
use App\Models\Submission;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTopupRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $startupAUser;
    private StartupProfile $startupA;
    private User $startupBUser;
    private StartupProfile $startupB;
    private User $studentUser;
    private StudentProfile $student;
    private Task $task;
    private Application $application;
    private Submission $submission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->startupAUser = User::create([
            'name' => 'Startup A',
            'email' => 'startupa@test.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);
        $this->startupA = StartupProfile::create([
            'user_id' => $this->startupAUser->id,
            'company_name' => 'Startup A Inc',
            'is_verified' => true,
            'wallet_balance' => 1000,
        ]);

        $this->startupBUser = User::create([
            'name' => 'Startup B',
            'email' => 'startupb@test.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);
        $this->startupB = StartupProfile::create([
            'user_id' => $this->startupBUser->id,
            'company_name' => 'Startup B Inc',
            'is_verified' => true,
            'wallet_balance' => 1000,
        ]);

        $this->studentUser = User::create([
            'name' => 'Student One',
            'email' => 'student1@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $this->student = StudentProfile::create([
            'user_id' => $this->studentUser->id,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'portfolio_links' => [],
        ]);

        $this->task = Task::create([
            'startup_profile_id' => $this->startupA->id,
            'title' => 'Build API',
            'description' => 'Task for startup A',
            'status' => 'posted',
            'stipend' => 500,
            'escrow_amount' => 500,
            'escrow_locked' => true,
            'required_skills' => [],
            'domain' => 'Software Development',
            'role' => 'Backend Developer',
        ]);

        Escrow::create([
            'task_id' => $this->task->id,
            'amount' => 500,
            'status' => 'locked',
        ]);

        $this->application = Application::create([
            'task_id' => $this->task->id,
            'student_profile_id' => $this->student->id,
            'status' => 'approved',
        ]);

        $this->submission = Submission::create([
            'application_id' => $this->application->id,
            'content' => 'Done',
            'status' => 'submitted',
        ]);
    }

    public function test_startup_b_cannot_accept_submission_for_startup_a_task(): void
    {
        $this->mock(\App\Services\PortfolioAutomationService::class, fn ($m) => $m->shouldReceive('addVerifiedTaskToPortfolio')->andReturnNull());
        $this->mock(\App\Services\SkillVerificationService::class, fn ($m) => $m->shouldReceive('verifySkillByTaskCompletion')->andReturnNull());
        $this->mock(\App\Services\ReputationEngineService::class, fn ($m) => $m->shouldReceive('updateReputation')->andReturnNull());

        $response = $this->actingAs($this->startupBUser)->post(route('startup.submissions.accept', $this->submission->id));

        $response->assertForbidden();
        $this->submission->refresh();
        $this->assertEquals('submitted', $this->submission->status);
    }

    public function test_startup_b_cannot_reject_application_for_startup_a_task(): void
    {
        $response = $this->actingAs($this->startupBUser)->post(route('startup.applications.reject', $this->application->id));

        $response->assertForbidden();
        $this->application->refresh();
        $this->assertEquals('approved', $this->application->status);
    }

    public function test_student_cannot_submit_work_for_another_students_application(): void
    {
        $otherStudent = User::create([
            'name' => 'Student Two',
            'email' => 'student2@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        StudentProfile::create([
            'user_id' => $otherStudent->id,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'portfolio_links' => [],
        ]);

        $response = $this->actingAs($otherStudent)->post(route('submissions.store', $this->application->id), [
            'content' => 'Stolen work',
        ]);

        $response->assertForbidden();
        $this->assertEquals(1, Submission::count());
    }

    public function test_user_cannot_read_unrelated_conversation(): void
    {
        $conversation = Conversation::create([
            'student_profile_id' => $this->student->id,
            'startup_profile_id' => $this->startupA->id,
            'task_id' => $this->task->id,
        ]);

        $response = $this->actingAs($this->startupBUser)->get(route('messages.show', $conversation->id));

        $response->assertForbidden();
    }

    public function test_startup_b_cannot_rate_submission_for_startup_a_task(): void
    {
        $this->mock(\App\Services\PortfolioAutomationService::class, fn ($m) => $m->shouldReceive('updateRatingOnPortfolioItem')->andReturnNull());
        $this->mock(\App\Services\SkillVerificationService::class, fn ($m) => $m->shouldReceive('syncTaskRatingToSkills')->andReturnNull());
        $this->mock(\App\Services\ReputationEngineService::class, fn ($m) => $m->shouldReceive('updateReputation')->andReturnNull());

        $response = $this->actingAs($this->startupBUser)->post(route('startup.submissions.rate', $this->submission->id), [
            'rating' => 5,
        ]);

        $response->assertForbidden();
    }

    public function test_rejected_submission_cannot_be_accepted(): void
    {
        $this->submission->update(['status' => 'rejected']);

        $this->mock(\App\Services\PortfolioAutomationService::class, fn ($m) => $m->shouldReceive('addVerifiedTaskToPortfolio')->never());
        $this->mock(\App\Services\SkillVerificationService::class, fn ($m) => $m->shouldReceive('verifySkillByTaskCompletion')->never());
        $this->mock(\App\Services\ReputationEngineService::class, fn ($m) => $m->shouldReceive('updateReputation')->never());

        $response = $this->actingAs($this->startupAUser)->post(route('startup.submissions.accept', $this->submission->id));

        $response->assertStatus(400);
    }

    public function test_offer_refund_is_idempotent(): void
    {
        $this->startupA->update(['wallet_balance' => 5000]);

        $offer = HiringOffer::create([
            'startup_profile_id' => $this->startupA->id,
            'student_profile_id' => $this->student->id,
            'offer_type' => 'internship',
            'title' => 'Intern',
            'description' => 'Summer intern',
            'compensation' => 10000,
            'compensation_period' => 'monthly',
            'start_date' => now()->addMonth()->toDateString(),
            'status' => 'pending',
            'reserved_fee' => 1999,
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('student.offers.reject', $offer->id))->assertRedirect();

        $this->startupA->refresh();
        $balanceAfterFirst = (float) $this->startupA->wallet_balance;
        $this->assertEquals(6999.0, $balanceAfterFirst);

        \App\Services\OfferRefundService::refundReservedFee($offer->fresh(), 'Duplicate attempt');

        $this->startupA->refresh();
        $this->assertEquals($balanceAfterFirst, (float) $this->startupA->wallet_balance);
        $this->assertEquals(1, Transaction::where('reference_id', "offer_refund_{$offer->id}")->count());
    }

    public function test_topup_approve_is_idempotent_under_lock(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $topup = WalletTopupRequest::create([
            'startup_profile_id' => $this->startupA->id,
            'amount' => 500,
            'payment_method' => 'upi',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.topup.approve', $topup->id))->assertRedirect();
        $this->actingAs($admin)->post(route('admin.topup.approve', $topup->id))->assertRedirect();

        $this->startupA->refresh();
        $this->assertEquals(1500.0, (float) $this->startupA->wallet_balance);
        $this->assertEquals(1, Transaction::where('reference_id', 'topup_' . $topup->id)->count());
    }
}
