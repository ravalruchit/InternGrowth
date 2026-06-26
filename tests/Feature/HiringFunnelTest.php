<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\Interview;
use App\Models\HiringOffer;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HiringFunnelTest extends TestCase
{
    use RefreshDatabase;

    private $startupUser;
    private $startupProfile;
    private $studentUser;
    private $studentProfile;
    private $task;
    private $application;

    protected function setUp(): void
    {
        parent::setUp();

        $this->startupUser = User::create([
            'name' => 'Startup Founder',
            'email' => 'founder@test.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);

        $this->startupProfile = StartupProfile::create([
            'user_id' => $this->startupUser->id,
            'company_name' => 'Funnel Corp',
            'website' => 'https://funnelcorp.com',
            'credibility_score' => 0.90,
            'is_verified' => true,
            'contact_phone' => '+919999988888',
        ]);

        $this->studentUser = User::create([
            'name' => 'Alice Student',
            'email' => 'alice@student.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $this->studentProfile = StudentProfile::create([
            'user_id' => $this->studentUser->id,
            'college_name' => 'Tech University',
            'college_email' => 'alice@tech.edu',
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'portfolio_links' => ['https://github.com/alice'],
        ]);

        $this->task = Task::create([
            'startup_profile_id' => $this->startupProfile->id,
            'title' => 'Redesign Funnel Task',
            'description' => 'Fix the dropdown hiring stepper',
            'status' => 'posted',
            'domain' => 'Software Development',
            'role' => 'Backend Developer',
            'required_skills' => []
        ]);

        $this->application = Application::create([
            'student_profile_id' => $this->studentProfile->id,
            'task_id' => $this->task->id,
            'status' => 'applied',
        ]);
    }

    public function test_contact_details_masking_before_offer_accepted()
    {
        // View student profile before acceptance - details should be masked
        $response = $this->actingAs($this->startupUser)
            ->get(route('students.public-profile', $this->studentProfile->id));

        $response->assertStatus(200);
        $response->assertSee('🔒 Contact Information');
        $response->assertSee('••••••••@••••.•••');
        $response->assertSee('+91 ••••• •••••');
        $response->assertDontSee($this->studentUser->email);
    }

    public function test_contact_details_unmasked_after_offer_accepted()
    {
        $this->studentProfile->update(['phone_number' => '+91 9876543210']);

        // Accept the offer / update status to hired
        $this->application->update(['status' => 'hired']);

        $response = $this->actingAs($this->startupUser)
            ->get(route('students.public-profile', $this->studentProfile->id));

        $response->assertStatus(200);
        $response->assertSee('🔓 Contact Information');
        $response->assertSee($this->studentUser->email);
        $response->assertSee('+91 9876543210');
        $response->assertSee('https://github.com/alice');
    }

    public function test_close_task_resolves_relationship_without_recruitment()
    {
        // Approve task
        $this->application->update(['status' => 'approved']);

        // Create accepted submission
        Submission::create([
            'application_id' => $this->application->id,
            'content' => 'Completed work assignment',
            'status' => 'accepted',
        ]);

        // Post close task
        $response = $this->actingAs($this->startupUser)
            ->post(route('startup.applications.close-task', $this->application->id));

        $response->assertStatus(302);
        
        $this->application->refresh();
        $this->assertEquals('task_only', $this->application->startup_hiring_outcome);
    }

    public function test_reject_hiring_outcome()
    {
        $this->application->update(['status' => 'approved']);

        Submission::create([
            'application_id' => $this->application->id,
            'content' => 'Completed work assignment',
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($this->startupUser)
            ->post(route('startup.applications.reject-hiring', $this->application->id));

        $response->assertStatus(302);
        
        $this->application->refresh();
        $this->assertEquals('task_completed_rejected', $this->application->startup_hiring_outcome);
        $this->assertEquals('rejected', $this->application->status);
    }

    public function test_rate_hiring_success_updates_ratings()
    {
        $this->application->update([
            'status' => 'hired',
            'startup_hiring_outcome' => 'hired_job'
        ]);

        $response = $this->actingAs($this->startupUser)
            ->post(route('startup.applications.rate-hiring-success', $this->application->id), [
                'rating' => 'excellent'
            ]);

        $response->assertStatus(302);

        $this->application->refresh();
        $this->assertEquals('excellent', $this->application->hiring_success_rating);
        $this->assertNotNull($this->application->hiring_success_rated_at);
    }

    public function test_counter_offer_updates_offer_status()
    {
        // Create offer
        $offer = HiringOffer::create([
            'startup_profile_id' => $this->startupProfile->id,
            'student_profile_id' => $this->studentProfile->id,
            'offer_type' => 'internship',
            'title' => 'Marketing Intern',
            'description' => 'Help with social campaigns',
            'compensation' => 15000,
            'compensation_period' => 'monthly',
            'start_date' => now()->addDays(2),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
            'reserved_fee' => 0.00
        ]);

        $response = $this->actingAs($this->studentUser)
            ->post(route('student.offers.counter', $offer->id), [
                'counter_compensation' => 18000,
                'counter_note' => 'Would accept at eighteen thousand rupees.'
            ]);

        $response->assertStatus(302);

        $offer->refresh();
        $this->assertEquals('countered', $offer->status);
        $this->assertEquals(18000, $offer->counter_compensation);
        $this->assertEquals('Would accept at eighteen thousand rupees.', $offer->counter_note);
    }

    public function test_no_show_outcome_marks_startup_no_show()
    {
        $conversation = \App\Models\Conversation::create([
            'student_profile_id' => $this->studentProfile->id,
            'startup_profile_id' => $this->startupProfile->id,
            'task_id' => $this->task->id,
        ]);

        $interview = Interview::create([
            'conversation_id' => $conversation->id,
            'startup_profile_id' => $this->startupProfile->id,
            'student_profile_id' => $this->studentProfile->id,
            'task_id' => $this->task->id,
            'title' => 'funnel talk',
            'scheduled_at' => now(),
            'duration_minutes' => 30,
            'type' => 'online',
            'location' => 'google meet',
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($this->studentUser)
            ->post(route('student.interviews.noshow', $interview->id));

        $response->assertStatus(302);

        $interview->refresh();
        $this->assertEquals('no_show', $interview->status);
        $this->assertEquals('startup', $interview->no_show_by);
    }

    public function test_hiring_offer_joining_confirmation_flow()
    {
        // 1. Setup startup balance
        $this->startupProfile->update(['wallet_balance' => 5000.00]);

        // Create a direct transaction debit to represent reservation
        $offer = HiringOffer::create([
            'startup_profile_id' => $this->startupProfile->id,
            'student_profile_id' => $this->studentProfile->id,
            'offer_type' => 'internship',
            'title' => 'Backend Internship',
            'description' => 'Test offer description',
            'compensation' => 20000,
            'compensation_period' => 'monthly',
            'start_date' => now()->addDays(2),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
            'reserved_fee' => 1999.00
        ]);

        \App\Models\Transaction::create([
            'user_type' => 'startup',
            'user_id' => $this->startupProfile->id,
            'type' => 'debit',
            'amount' => 1999.00,
            'description' => "Reserved success fee",
            'reference_id' => "offer_{$offer->id}"
        ]);

        // 2. Student accepts the offer
        $response = $this->actingAs($this->studentUser)
            ->post(route('student.offers.accept', $offer->id));
        $response->assertStatus(302);

        $offer->refresh();
        $this->assertEquals('pending_joining', $offer->status);
        $this->assertEquals('pending', $offer->student_joining_status);
        $this->assertEquals('pending', $offer->startup_joining_status);

        // 3. Student confirms joining
        $response = $this->actingAs($this->studentUser)
            ->post(route('offers.confirm-joining', $offer->id));
        $response->assertStatus(302);

        $offer->refresh();
        $this->assertEquals('pending_joining', $offer->status); // Still pending since startup hasn't confirmed
        $this->assertEquals('joined', $offer->student_joining_status);

        // 4. Startup confirms joining
        $response = $this->actingAs($this->startupUser)
            ->post(route('offers.confirm-joining', $offer->id));
        $response->assertStatus(302);

        $offer->refresh();
        $this->assertEquals('joined', $offer->status); // Now fully joined!
        $this->assertEquals('joined', $offer->startup_joining_status);

        // Verify platform transaction exists
        $tx = \App\Models\Transaction::where('user_type', 'platform')
            ->where('reference_id', "offer_{$offer->id}")
            ->first();
        $this->assertNotNull($tx);
        $this->assertEquals(1999.00, $tx->amount);
    }

    public function test_hiring_offer_joining_cancellation_by_student()
    {
        $this->startupProfile->update(['wallet_balance' => 3000.00]);

        $offer = HiringOffer::create([
            'startup_profile_id' => $this->startupProfile->id,
            'student_profile_id' => $this->studentProfile->id,
            'offer_type' => 'internship',
            'title' => 'Backend Internship 2',
            'description' => 'Test offer description 2',
            'compensation' => 20000,
            'compensation_period' => 'monthly',
            'start_date' => now()->addDays(2),
            'status' => 'pending_joining',
            'expires_at' => now()->addDays(7),
            'reserved_fee' => 1999.00,
            'student_joining_status' => 'pending',
            'startup_joining_status' => 'pending'
        ]);

        $debitTx = \App\Models\Transaction::create([
            'user_type' => 'startup',
            'user_id' => $this->startupProfile->id,
            'type' => 'debit',
            'amount' => 1999.00,
            'description' => "Reserved success fee",
            'reference_id' => "offer_{$offer->id}"
        ]);

        // Student cancels joining
        $response = $this->actingAs($this->studentUser)
            ->post(route('offers.cancel-joining', $offer->id));
        $response->assertStatus(302);

        $offer->refresh();
        $this->assertEquals('cancelled_by_student', $offer->status);

        // Startup should be refunded
        $this->startupProfile->refresh();
        $this->assertEquals(4999.00, $this->startupProfile->wallet_balance);

        // Original debit should be reversed
        $debitTx->refresh();
        $this->assertEquals('reversed', $debitTx->status);

        // Refund transaction should exist
        $creditTx = \App\Models\Transaction::where('user_type', 'startup')
            ->where('type', 'credit')
            ->where('reference_id', "offer_refund_{$offer->id}")
            ->first();
        $this->assertNotNull($creditTx);
        $this->assertEquals(1999.00, $creditTx->amount);
    }

    public function test_hiring_offer_completion_flow()
    {
        $offer = HiringOffer::create([
            'startup_profile_id' => $this->startupProfile->id,
            'student_profile_id' => $this->studentProfile->id,
            'offer_type' => 'internship',
            'title' => 'Supervised Internship',
            'description' => 'Test internship to complete',
            'compensation' => 20000,
            'compensation_period' => 'monthly',
            'start_date' => now()->subDays(30),
            'end_date' => now(),
            'status' => 'joined',
            'student_joining_status' => 'joined',
            'startup_joining_status' => 'joined',
            'reserved_fee' => 1999.00
        ]);

        $response = $this->actingAs($this->startupUser)
            ->post(route('offers.complete-internship', $offer->id), [
                'rating' => 'excellent',
                'notes' => 'Alice did an incredible job, completed all sprint targets.'
            ]);
        $response->assertStatus(302);

        $offer->refresh();
        $this->assertEquals('completed', $offer->status);
        $this->assertEquals('excellent', $offer->hiring_success_rating);
        $this->assertEquals('Alice did an incredible job, completed all sprint targets.', $offer->completion_notes);

        // Verify Certificate issued
        $cert = \App\Models\Certificate::where('hiring_offer_id', $offer->id)->first();
        $this->assertNotNull($cert);
        $this->assertEquals($this->studentProfile->id, $cert->student_profile_id);

        // Verify Portfolio item added
        $portfolioItem = \App\Models\PortfolioItem::where('hiring_offer_id', $offer->id)->first();
        $this->assertNotNull($portfolioItem);
        $this->assertEquals("Supervised Internship at Funnel Corp", $portfolioItem->project_title);
        $this->assertEquals(5.0, $portfolioItem->rating_received);
    }
}
