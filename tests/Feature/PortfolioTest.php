<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\Submission;
use App\Models\Skill;
use App\Models\Portfolio;
use App\Models\PortfolioItem;
use App\Models\SkillVerification;
use App\Services\SkillVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    private $studentUser;
    private $studentProfile;
    private $startupUser;
    private $startupProfile;
    private $task;
    private $application;
    private $submission;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a student user and profile
        $this->studentUser = User::create([
            'name' => 'John Student',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => true,
        ]);

        $this->studentProfile = StudentProfile::create([
            'user_id' => $this->studentUser->id,
            'bio' => 'Student Bio',
            'portfolio_links' => [],
            'reliability_score' => 0.85,
        ]);

        // Create a startup user and profile
        $this->startupUser = User::create([
            'name' => 'Tech Startup',
            'email' => 'startup@example.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);

        $this->startupProfile = StartupProfile::create([
            'user_id' => $this->startupUser->id,
            'company_name' => 'Tech Innovations Inc',
            'description' => 'Building the future',
            'website' => 'https://techinnovations.com',
            'credibility_score' => 0.90,
        ]);

        // Create a skill
        $skill = Skill::create(['name' => 'Laravel']);

        // Create a task
        $this->task = Task::create([
            'startup_profile_id' => $this->startupProfile->id,
            'title' => 'Laravel Task',
            'description' => 'Build Laravel API',
            'stipend' => 1000,
            'status' => 'posted',
            'required_skills' => json_encode(['Laravel']),
        ]);
        $this->task->skills()->attach($skill->id);

        // Create application
        $this->application = Application::create([
            'student_profile_id' => $this->studentProfile->id,
            'task_id' => $this->task->id,
            'status' => 'approved',
        ]);

        // Create submission
        $this->submission = Submission::create([
            'application_id' => $this->application->id,
            'content' => 'Completed work details here',
            'status' => 'submitted',
            'files' => null,
        ]);
    }

    public function test_portfolio_item_and_skills_created_on_submission_accept()
    {
        // Accept the submission
        $this->actingAs($this->startupUser)
            ->post(route('startup.submissions.accept', $this->submission->id))
            ->assertRedirect();

        // 1. Verify portfolio item was created
        $portfolioItem = PortfolioItem::where('task_id', $this->task->id)->first();
        $this->assertNotNull($portfolioItem);
        $this->assertEquals('verified_project', $portfolioItem->verification_badge);
        $this->assertEquals($this->task->title, $portfolioItem->project_title);

        // 2. Verify skill verification was created with startup_profile_id
        $skillVerification = SkillVerification::where('student_profile_id', $this->studentProfile->id)
            ->where('startup_profile_id', $this->startupProfile->id)
            ->first();
        $this->assertNotNull($skillVerification);
        $this->assertEquals('task_completion', $skillVerification->verification_method);
    }

    public function test_badge_upgrade_on_high_rating()
    {
        // First accept
        $this->actingAs($this->startupUser)
            ->post(route('startup.submissions.accept', $this->submission->id));

        // Submit a rating of 5
        $this->actingAs($this->startupUser)
            ->post(route('startup.submissions.rate', $this->submission->id), [
                'rating' => 5,
                'feedback' => 'Incredible work, very satisfied!',
            ])
            ->assertRedirect();

        // Verify badge upgraded to outstanding_performance
        $portfolioItem = PortfolioItem::where('task_id', $this->task->id)->first();
        $this->assertEquals('outstanding_performance', $portfolioItem->verification_badge);
    }

    public function test_startup_skill_verification_count()
    {
        $laravelSkill = Skill::where('name', 'Laravel')->first();

        // Startup 1 verified (done in setUp task)
        $skillsService = new SkillVerificationService();
        $skillsService->verifySkillByTaskCompletion($this->studentProfile->id, $laravelSkill->id, 80, $this->startupProfile->id);

        // Create Startup 2 and verify
        $startup2User = User::create([
            'name' => 'Startup 2',
            'email' => 'startup2@example.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
        ]);
        $startup2Profile = StartupProfile::create([
            'user_id' => $startup2User->id,
            'company_name' => 'Startup 2 Co',
        ]);
        $skillsService->verifySkillByTaskCompletion($this->studentProfile->id, $laravelSkill->id, 90, $startup2Profile->id);

        // Verify count is 2
        $count = $skillsService->getStartupVerificationCount($this->studentProfile->id, $laravelSkill->id);
        $this->assertEquals(2, $count);
    }

    public function test_update_evidence_links()
    {
        // First accept to create item
        $response = $this->actingAs($this->startupUser)
            ->post(route('startup.submissions.accept', $this->submission->id));
        
        if ($response->status() !== 302) {
            dd($response->status(), $response->content());
        }

        $portfolioItem = PortfolioItem::where('task_id', $this->task->id)->first();

        // Update evidence
        $this->actingAs($this->studentUser)
            ->post(route('student.portfolio.evidence', $portfolioItem->id), [
                'github_url' => 'https://github.com/student/my-repo',
                'demo_url' => 'https://my-demo-app.com',
            ])
            ->assertRedirect();

        $portfolioItem->refresh();
        $this->assertEquals('https://github.com/student/my-repo', $portfolioItem->github_url);
        $this->assertEquals('https://my-demo-app.com', $portfolioItem->demo_url);
    }

    public function test_talent_profile_routing_and_visibility()
    {
        // First accept to trigger portfolio creation
        $this->actingAs($this->startupUser)
            ->post(route('startup.submissions.accept', $this->submission->id));

        $portfolio = Portfolio::where('student_profile_id', $this->studentProfile->id)->first();

        // 1. Visit public route when is_public is true
        $response = $this->get(route('talent.profile', $portfolio->custom_slug));
        $response->assertStatus(200);

        // 2. Disable visibility
        $portfolio->update(['is_public' => false]);

        // 3. Visit public route when is_public is false -> should 404
        $response = $this->get(route('talent.profile', $portfolio->custom_slug));
        $response->assertStatus(404);
    }
}
