<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\Skill;
use App\Models\PointsWallet;
use App\Models\ReputationScore;
use App\Services\CandidateRankingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateRankingTest extends TestCase
{
    use RefreshDatabase;

    private $startupUser;
    private $startupProfile;
    private $task;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a startup user and profile
        $this->startupUser = User::create([
            'name' => 'Founder Ruchit',
            'email' => 'founder@example.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);

        $this->startupProfile = StartupProfile::create([
            'user_id' => $this->startupUser->id,
            'company_name' => 'Growth Corp',
            'website' => 'https://growthcorp.com',
            'credibility_score' => 0.90,
            'is_verified' => true,
        ]);

        // Create skills
        $laravel = Skill::create(['name' => 'Laravel']);
        $php = Skill::create(['name' => 'PHP']);

        // Create a task
        $this->task = Task::create([
            'startup_profile_id' => $this->startupProfile->id,
            'title' => 'Backend Development Task',
            'description' => 'Build Laravel applications and PHP scripts',
            'reward_points' => 120,
            'stipend' => 1500,
            'status' => 'posted',
            'required_skills' => json_encode(['Laravel', 'PHP']),
        ]);
        $this->task->skills()->attach([$laravel->id, $php->id]);
    }

    /**
     * Test that the ranking service calculates match scores correctly
     * and that the newcomer Potential Score boost gives them visibility.
     */
    public function test_new_talent_potential_score_boost()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $php = Skill::where('name', 'PHP')->first();

        // 1. Create a brand new student (John New)
        $newStudentUser = User::create([
            'name' => 'John New',
            'email' => 'john.new@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => false,
        ]);
        $newStudentProfile = StudentProfile::create([
            'user_id' => $newStudentUser->id,
            'bio' => 'Aspiring backend dev eager to learn Laravel.',
            'college_name' => 'State Tech College',
            'availability' => 'part_time',
            'portfolio_links' => ['github' => 'https://github.com/johnnew'],
        ]);
        $newStudentProfile->skills()->attach([$laravel->id, $php->id]);
        PointsWallet::create(['student_profile_id' => $newStudentProfile->id, 'balance' => 0]);

        // Calculate John New score using CandidateRankingService
        $rankingService = new CandidateRankingService();
        $newStudentResult = $rankingService->calculateMatchScore($newStudentProfile, $this->task);

        // Verify Potential Score is calculated and John has a reasonable score (not 0) due to completion factors
        $this->assertGreaterThan(0, $newStudentResult['breakdown']['potential']);
        $this->assertGreaterThan(20, $newStudentResult['match_score']); // John should get basic points from potential & declarations
    }

    /**
     * Test that pedigree/college and locations do not affect the candidate score
     * to protect against bias.
     */
    public function test_hidden_bias_protection()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $php = Skill::where('name', 'PHP')->first();

        // Student A: High Pedigree College, Prime Location
        $userA = User::create([
            'name' => 'Alice Pedigree',
            'email' => 'alice@pedigree.edu',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $studentA = StudentProfile::create([
            'user_id' => $userA->id,
            'bio' => 'Backend enthusiast',
            'college_name' => 'Stanford University', // Elite College
            'availability' => 'full_time',
        ]);
        $studentA->skills()->attach([$laravel->id, $php->id]);
        PointsWallet::create(['student_profile_id' => $studentA->id, 'balance' => 0]);

        // Student B: Low Pedigree/State College, Remote Location
        $userB = User::create([
            'name' => 'Bob Remote',
            'email' => 'bob@remote.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $studentB = StudentProfile::create([
            'user_id' => $userB->id,
            'bio' => 'Backend enthusiast',
            'college_name' => 'Local Community College', // Normal College
            'availability' => 'full_time',
        ]);
        $studentB->skills()->attach([$laravel->id, $php->id]);
        PointsWallet::create(['student_profile_id' => $studentB->id, 'balance' => 0]);

        // Compute scores
        $rankingService = new CandidateRankingService();
        $resultA = $rankingService->calculateMatchScore($studentA, $this->task);
        $resultB = $rankingService->calculateMatchScore($studentB, $this->task);

        // Pedigree differences should have 0 weight, scores must be equal as they have identical profiles otherwise!
        $this->assertEquals($resultA['match_score'], $resultB['match_score']);
    }

    /**
     * Test TaskController show method computes match scores for task applications
     */
    public function test_task_show_action_returns_ranked_applications()
    {
        $laravel = Skill::where('name', 'Laravel')->first();

        // Create student and application
        $user = User::create([
            'name' => 'Applicant One',
            'email' => 'applicant1@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'PHP Programmer',
        ]);
        PointsWallet::create(['student_profile_id' => $student->id, 'balance' => 500]);
        
        $application = Application::create([
            'student_profile_id' => $student->id,
            'task_id' => $this->task->id,
            'status' => 'applied',
        ]);

        // View as startup
        $response = $this->actingAs($this->startupUser)
            ->get(route('tasks.show', $this->task->id));

        $response->assertStatus(200);
        $response->assertViewHas('task');

        // Check task applications loaded has match score populated
        $loadedTask = $response->viewData('task');
        $this->assertNotEmpty($loadedTask->applications);
        $this->assertNotNull($loadedTask->applications->first()->match_score);
    }
}
