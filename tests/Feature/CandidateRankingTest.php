<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\Skill;
use App\Models\ReputationScore;
use App\Models\Portfolio;
use App\Models\PortfolioItem;
use App\Models\Submission;
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
            'stipend' => 1500,
            'status' => 'posted',
            'required_skills' => json_encode(['Laravel', 'PHP']),
        ]);
        $this->task->skills()->attach([$laravel->id, $php->id]);
    }

    /**
     * Test that the ranking service calculates match scores correctly
     * and that the breakdown keys align with the new weights.
     */
    public function test_weighted_score_calculation_details()
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
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
        ]);
        $newStudentProfile->skills()->attach([$laravel->id, $php->id]);

        // Provide Proof of Work (Evidence portfolio project) to pass the gate
        $portfolio = Portfolio::create(['student_profile_id' => $newStudentProfile->id, 'custom_slug' => 'john-new']);
        PortfolioItem::create([
            'portfolio_id' => $portfolio->id,
            'project_title' => 'John Project',
            'skills_demonstrated' => ['Laravel', 'PHP'],
            'github_url' => 'https://github.com/johnnew/project',
            'startup_name' => 'Self-submitted',
        ]);
        
        // Calculate John New score using CandidateRankingService
        $rankingService = new CandidateRankingService();
        $newStudentResult = $rankingService->calculateMatchScore($newStudentProfile, $this->task);

        // Verify the breakdown contains the new components
        $this->assertArrayHasKey('verified_work', $newStudentResult['breakdown']);
        $this->assertArrayHasKey('skills_match', $newStudentResult['breakdown']);
        $this->assertArrayHasKey('domain_alignment', $newStudentResult['breakdown']);
        $this->assertArrayHasKey('role_alignment', $newStudentResult['breakdown']);
        $this->assertArrayHasKey('iprs', $newStudentResult['breakdown']);
        $this->assertArrayHasKey('portfolio', $newStudentResult['breakdown']);

        // Check that scores never exceed 100%
        $this->assertLessThanOrEqual(100, $newStudentResult['match_score']);
        $this->assertGreaterThan(0, $newStudentResult['match_score']);
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

        $portfolioA = Portfolio::create(['student_profile_id' => $studentA->id, 'custom_slug' => 'alice-pedigree']);
        PortfolioItem::create([
            'portfolio_id' => $portfolioA->id,
            'project_title' => 'Alice Project',
            'skills_demonstrated' => ['Laravel', 'PHP'],
            'github_url' => 'https://github.com/alice/project',
            'startup_name' => 'Self-submitted',
        ]);

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

        $portfolioB = Portfolio::create(['student_profile_id' => $studentB->id, 'custom_slug' => 'bob-remote']);
        PortfolioItem::create([
            'portfolio_id' => $portfolioB->id,
            'project_title' => 'Bob Project',
            'skills_demonstrated' => ['Laravel', 'PHP'],
            'github_url' => 'https://github.com/bob/project',
            'startup_name' => 'Self-submitted',
        ]);

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

    /**
     * Test Skill Gate Rule 1: Exclude candidate completely (return null) if skills_match < 20%.
     */
    public function test_skill_gate_rule_1_exclusion()
    {
        $rankingService = new CandidateRankingService();

        // Task requires Laravel and PHP (2 skills)
        // Student A has a completely unrelated skill (e.g. React)
        $react = Skill::create(['name' => 'React']);

        $user = User::create([
            'name' => 'Unrelated Student',
            'email' => 'unrelated@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'Frontend developer',
        ]);
        $student->skills()->attach($react->id);

        $result = $rankingService->calculateMatchScore($student, $this->task);

        // Candidate must be excluded completely (null)
        $this->assertNull($result);
    }

    /**
     * Test Skill Gate Rule 2: Exclude candidate completely if matchedSkills < 2 (when task requires at least 2 skills).
     */
    public function test_skill_gate_rule_2_exclusion()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $rankingService = new CandidateRankingService();

        $user = User::create([
            'name' => 'Laravel Student',
            'email' => 'laravel.only@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'Laravel developer',
        ]);
        $student->skills()->attach($laravel->id);

        // Task requires 2 skills (Laravel, PHP).
        // Candidate has Laravel, which is 1 matched skill.
        // Percentage = (1/2)*100 = 50% >= 20% (passes Rule 1).
        // But matchedSkills = 1 < 2, so Rule 2 excludes them.
        $result = $rankingService->calculateMatchScore($student, $this->task);

        $this->assertNull($result);
    }

    /**
     * Test Skill Gate Rule 2 edge-case: Do not exclude candidate if matchedSkills is 1, but task only requires 1 skill.
     */
    public function test_skill_gate_rule_2_not_triggered_for_single_skill_task()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $rankingService = new CandidateRankingService();

        // Create a task requiring only 1 skill (Laravel)
        $singleSkillTask = Task::create([
            'startup_profile_id' => $this->startupProfile->id,
            'title' => 'Single Skill Task',
            'description' => 'Only Laravel needed',
            'stipend' => 1000,
            'status' => 'posted',
            'required_skills' => json_encode(['Laravel']),
        ]);
        $singleSkillTask->skills()->attach($laravel->id);

        $user = User::create([
            'name' => 'Laravel Student 2',
            'email' => 'laravel.only2@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'Laravel developer',
        ]);
        $student->skills()->attach($laravel->id);

        // Create evidence-backed project
        $portfolio = Portfolio::create(['student_profile_id' => $student->id, 'custom_slug' => 'laravel-student-2']);
        PortfolioItem::create([
            'portfolio_id' => $portfolio->id,
            'project_title' => 'Laravel Project',
            'skills_demonstrated' => ['Laravel'],
            'github_url' => 'https://github.com/laravel-student-2/project',
            'startup_name' => 'Self-submitted',
        ]);

        // Candidate matches 1 out of 1 skill.
        // Percentage = 100% >= 20% (passes Rule 1).
        // Required skills count = 1 (less than 2), so Rule 2 does not apply.
        $result = $rankingService->calculateMatchScore($student, $singleSkillTask);

        $this->assertNotNull($result);
        $this->assertEquals(100, $result['breakdown']['skills_match']);
    }

    /**
     * Test Skill Gate Rule 3 Override: Force Match Label to 'Low Match' if skill score is in range [20%, 30%).
     */
    public function test_skill_gate_rule_3_override_forces_low_match()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $php = Skill::where('name', 'PHP')->first();
        
        // Create 6 more skills
        $vue = Skill::create(['name' => 'Vue']);
        $react = Skill::create(['name' => 'React']);
        $sql = Skill::create(['name' => 'SQL']);
        $css = Skill::create(['name' => 'CSS']);
        $js = Skill::create(['name' => 'JS']);
        $python = Skill::create(['name' => 'Python']);

        $rankingService = new CandidateRankingService();

        // Create a task requiring 8 skills
        $multiSkillTask = Task::create([
            'startup_profile_id' => $this->startupProfile->id,
            'title' => 'Multi Skill Task',
            'description' => 'Requires 8 skills',
            'stipend' => 2000,
            'status' => 'posted',
            'domain' => 'Software Development',
            'role' => 'Backend Developer',
            'required_skills' => json_encode(['Laravel', 'PHP', 'Vue', 'React', 'SQL', 'CSS', 'JS', 'Python']),
        ]);
        $multiSkillTask->skills()->attach([
            $laravel->id, $php->id, $vue->id, $react->id, $sql->id, $css->id, $js->id, $python->id
        ]);

        // Create student matching only 2 skills (Laravel, PHP)
        // This gives skills_match of (2/8) * 100 = 25% (which is in [20%, 30%) range)
        // Passes Rule 2 because matched skills (2) >= 2.
        $user = User::create([
            'name' => 'Multi Skill Student',
            'email' => 'multi@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'Software developer',
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
        ]);
        $student->skills()->attach([$laravel->id, $php->id]);

        // Mock strong secondary profile stats to get high overall score (above 40)
        // Portfolio Quality = Verified (100)
        $portfolio = Portfolio::create(['student_profile_id' => $student->id, 'custom_slug' => 'student-multi-skill']);
        $app = Application::create([
            'student_profile_id' => $student->id,
            'task_id' => $this->task->id,
            'status' => 'accepted',
        ]);
        $sub = Submission::create([
            'application_id' => $app->id,
            'status' => 'accepted',
            'content' => 'Sample submission content showing my work evidence.',
            'github_url' => 'https://github.com/evidence',
        ]);
        PortfolioItem::create([
            'portfolio_id' => $portfolio->id,
            'task_id' => $this->task->id,
            'submission_id' => $sub->id,
            'project_title' => 'E-commerce App',
            'skills_demonstrated' => ['Laravel', 'PHP'],
            'verification_badge' => 'Verified Code',
            'startup_name' => 'Growth Corp',
        ]);

        // Verified Work = 10 completed items (90 score)
        for ($i = 0; $i < 9; $i++) {
            $taskMock = Task::create([
                'startup_profile_id' => $this->startupProfile->id,
                'title' => "Mock Task $i",
                'description' => "Description for mock task $i",
                'stipend' => 1000,
                'status' => 'completed',
                'required_skills' => json_encode(['Laravel']),
            ]);
            $appMock = Application::create([
                'student_profile_id' => $student->id,
                'task_id' => $taskMock->id,
                'status' => 'accepted',
            ]);
            Submission::create([
                'application_id' => $appMock->id,
                'status' => 'accepted',
                'content' => 'Completed task project code submission.',
                'github_url' => 'https://github.com/mock',
            ]);
        }

        // ReputationScore for IPRS = 100
        ReputationScore::create([
            'student_profile_id' => $student->id,
            'overall_score' => 100.00,
        ]);

        $result = $rankingService->calculateMatchScore($student, $multiSkillTask);

        $this->assertNotNull($result);
        $this->assertEquals(25, $result['breakdown']['skills_match']);
        
        // Assert overall score calculation:
        // Skills match: 25 * 0.55 = 13.75
        // Domain match: 100 * 0.15 = 15
        // Verified work: 90 * 0.10 = 9 (9 accepted tasks + 1 verified portfolio item = 10 completed -> 90 score)
        // Role match: 100 * 0.10 = 10
        // IPRS: 100 * 0.05 = 5
        // Portfolio quality: 100 * 0.05 = 5
        // Overall: 13.75 + 15 + 9 + 10 + 5 + 5 = 57.75 -> round to 58.
        $this->assertEquals(58, $result['match_score']);
        
        // Label should be overridden to 'Low Match' (normally 58 would be 'Good Match' or 'Partial Match')
        $this->assertEquals('Low Match', $result['match_label']);
    }

    /**
     * Test CandidateRankingService 55% weighted score calculation math and rounding.
     */
    public function test_overall_score_weighted_calculation()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $php = Skill::where('name', 'PHP')->first();
        $vue = Skill::create(['name' => 'Vue']);

        $rankingService = new CandidateRankingService();

        // Create a task requiring 3 skills
        $task = Task::create([
            'startup_profile_id' => $this->startupProfile->id,
            'title' => 'Three Skill Task',
            'description' => 'Laravel, PHP, Vue',
            'stipend' => 1200,
            'status' => 'posted',
            'domain' => 'Software Development',
            'role' => 'Backend Developer',
            'required_skills' => json_encode(['Laravel', 'PHP', 'Vue']),
        ]);
        $task->skills()->attach([$laravel->id, $php->id, $vue->id]);

        // Create student matching 2 skills: Laravel and PHP
        $user = User::create([
            'name' => 'Jane Dev',
            'email' => 'jane.dev@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'Jane developer',
            'primary_domain' => 'Data & AI', // Related to Software Development (70 score)
            'preferred_role' => 'Backend Developer', // Perfect role match (100 score)
        ]);
        $student->skills()->attach([$laravel->id, $php->id]);

        $portfolio = Portfolio::create(['student_profile_id' => $student->id, 'custom_slug' => 'jane-dev']);
        PortfolioItem::create([
            'portfolio_id' => $portfolio->id,
            'project_title' => 'Jane Project',
            'skills_demonstrated' => ['Laravel', 'PHP'],
            'github_url' => 'https://github.com/janedev/project',
            'startup_name' => 'Self-submitted',
        ]);

        // Mock IPRS score of 80
        ReputationScore::create([
            'student_profile_id' => $student->id,
            'overall_score' => 80.00,
        ]);

        // Verify score math:
        // Skills match: 2/3 = 66.66667% (weight 0.55) -> 36.66667
        // Domain match: Related (70% score, weight 0.15) -> 10.5
        // Role match: Perfect (100% score, weight 0.10) -> 10
        // Verified work: 0 Completed (0 score, weight 0.10) -> 0
        // IPRS: 80% score (weight 0.05) -> 4
        // Portfolio: 0 score (weight 0.05) -> 0
        // Total = 36.66667 + 10.5 + 10 + 0 + 4 + 0 = 61.16667 -> rounds to 61%
        $result = $rankingService->calculateMatchScore($student, $task);

        $this->assertNotNull($result);
        $this->assertEquals(70, $result['match_score']);
        $this->assertEquals('Strong Match', $result['match_label']);
    }

    /**
     * Test ignoreSkillGate parameter override: Return match stats instead of null when candidate fails the gate.
     */
    public function test_ignore_skill_gate_override()
    {
        $rankingService = new CandidateRankingService();

        // Task requires Laravel and PHP (2 skills)
        // Student has a completely unrelated skill (React) -> fails skill gate
        $react = Skill::create(['name' => 'React']);

        $user = User::create([
            'name' => 'Unrelated Student Gate Ignore',
            'email' => 'gateignore@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $student = StudentProfile::create([
            'user_id' => $user->id,
            'bio' => 'Frontend developer',
        ]);
        $student->skills()->attach($react->id);

        // When ignoreSkillGate is false (default) -> should return null
        $resultDefault = $rankingService->calculateMatchScore($student, $this->task);
        $this->assertNull($resultDefault);

        // When ignoreSkillGate is true -> should return match details, but with passes_gate = false and label = 'Not Qualified'
        $resultBypassed = $rankingService->calculateMatchScore($student, $this->task, true);
        $this->assertNotNull($resultBypassed);
        $this->assertFalse($resultBypassed['passes_gate']);
        $this->assertEquals('Not Qualified', $resultBypassed['match_label']);
    }

    /**
     * Test the Proof-of-Work Gate requirements:
     * - Self-declared checkboxes alone are ignored.
     * - Portfolio project with evidence links qualifies.
     * - Skill verification record qualifies.
     */
    public function test_proof_of_work_gate_rules()
    {
        $laravel = Skill::where('name', 'Laravel')->first();
        $php = Skill::where('name', 'PHP')->first();
        $rankingService = new CandidateRankingService();

        // Student A: Self-declared only -> fails gate
        $userA = User::create([
            'name' => 'A Self Declared',
            'email' => 'self@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $studentA = StudentProfile::create([
            'user_id' => $userA->id,
            'bio' => 'Self declared PHP/Laravel enthusiast',
        ]);
        $studentA->skills()->attach([$laravel->id, $php->id]);

        $this->assertNull($rankingService->calculateMatchScore($studentA, $this->task));

        // Student B: Project with github_url -> passes gate
        $userB = User::create([
            'name' => 'B Project Evidence',
            'email' => 'proj@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $studentB = StudentProfile::create([
            'user_id' => $userB->id,
            'bio' => 'Has evidence projects',
        ]);
        $studentB->skills()->attach([$laravel->id, $php->id]);

        $portfolioB = Portfolio::create(['student_profile_id' => $studentB->id, 'custom_slug' => 'b-proj']);
        PortfolioItem::create([
            'portfolio_id' => $portfolioB->id,
            'project_title' => 'Ecom Backend',
            'skills_demonstrated' => ['Laravel', 'PHP'],
            'github_url' => 'https://github.com/test/ecom',
            'startup_name' => 'Self-submitted',
        ]);

        $resultB = $rankingService->calculateMatchScore($studentB, $this->task);
        $this->assertNotNull($resultB);
        $this->assertTrue($resultB['passes_gate']);
        $this->assertEquals(100, $resultB['breakdown']['skills_match']);

        // Student C: Skill verification record -> passes gate
        $userC = User::create([
            'name' => 'C Verification',
            'email' => 'ver@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        $studentC = StudentProfile::create([
            'user_id' => $userC->id,
            'bio' => 'Has verification record',
        ]);
        $studentC->skills()->attach([$laravel->id, $php->id]);

        \App\Models\SkillVerification::create([
            'student_profile_id' => $studentC->id,
            'skill_id' => $laravel->id,
            'verification_method' => 'task_completion',
            'score' => 85,
        ]);
        \App\Models\SkillVerification::create([
            'student_profile_id' => $studentC->id,
            'skill_id' => $php->id,
            'verification_method' => 'ai_assessment',
            'score' => 90,
        ]);

        $resultC = $rankingService->calculateMatchScore($studentC, $this->task);
        $this->assertNotNull($resultC);
        $this->assertTrue($resultC['passes_gate']);
        $this->assertEquals(100, $resultC['breakdown']['skills_match']);
    }
}
