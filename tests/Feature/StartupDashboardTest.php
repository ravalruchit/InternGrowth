<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\HiringOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StartupDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_startup_dashboard_displays_domain_analytics()
    {
        // Create a startup user and profile
        $startupUser = User::create([
            'name' => 'Founder Ruchit',
            'email' => 'founder@example.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);

        $startupProfile = StartupProfile::create([
            'user_id' => $startupUser->id,
            'company_name' => 'Growth Corp',
            'website' => 'https://growthcorp.com',
            'credibility_score' => 0.90,
            'is_verified' => true,
        ]);

        // Create tasks in different domains
        $task1 = Task::create([
            'startup_profile_id' => $startupProfile->id,
            'title' => 'Software Task',
            'description' => 'Build a backend module',
            'status' => 'posted',
            'domain' => 'Software Development',
            'role' => 'Backend Developer',
            'required_skills' => []
        ]);

        $task2 = Task::create([
            'startup_profile_id' => $startupProfile->id,
            'title' => 'UI/UX Task',
            'description' => 'Design the landing page',
            'status' => 'posted',
            'domain' => 'UI/UX Design',
            'role' => 'UI Designer',
            'required_skills' => []
        ]);

        // Create a student user and profile
        $studentUser = User::create([
            'name' => 'Student Alice',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer'
        ]);

        // Create applications to these tasks
        $app1 = Application::create([
            'student_profile_id' => $studentProfile->id,
            'task_id' => $task1->id,
            'status' => 'applied',
        ]);

        $app2 = Application::create([
            'student_profile_id' => $studentProfile->id,
            'task_id' => $task2->id,
            'status' => 'applied',
        ]);

        // Create a direct hiring offer accepted in Data & AI
        HiringOffer::create([
            'startup_profile_id' => $startupProfile->id,
            'student_profile_id' => $studentProfile->id,
            'offer_type' => 'internship',
            'title' => 'AI Intern',
            'description' => 'Build ML models',
            'compensation' => 5000,
            'compensation_period' => 'monthly',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'status' => 'accepted',
            'domain' => 'Data & AI',
            'role' => 'AI Engineer'
        ]);

        // Access the startup dashboard
        $response = $this->actingAs($startupUser)
            ->get(route('startup.dashboard'));

        $response->assertStatus(200);

        // Verify the view has the calculated domain analytics variables
        $response->assertViewHas('applicationsByDomain');
        $response->assertViewHas('hiringSuccessByDomain');
        $response->assertViewHas('topPerformingDomains');

        $applicationsByDomain = $response->viewData('applicationsByDomain');
        $hiringSuccessByDomain = $response->viewData('hiringSuccessByDomain');
        $topPerformingDomains = $response->viewData('topPerformingDomains');

        // Check correct application counts
        $this->assertEquals(1, $applicationsByDomain['Software Development']);
        $this->assertEquals(1, $applicationsByDomain['UI/UX Design']);
        $this->assertEquals(0, $applicationsByDomain['Digital Marketing']);

        // Check correct hiring success counts (accepted hiring offer is in Data & AI)
        $this->assertEquals(1, $hiringSuccessByDomain['Data & AI']);
        $this->assertEquals(0, $hiringSuccessByDomain['Software Development']);

        // Check top performing domains includes Data & AI first because it has a hire
        $this->assertEquals('Data & AI', $topPerformingDomains[0]);

        // Verify that the view renders the text and sections
        $response->assertSee('Multi-Domain Ecosystem Analytics');
        $response->assertSee('Software Development');
        $response->assertSee('UI/UX Design');
        $response->assertSee('Digital Marketing');
        $response->assertSee('Data & AI');
        $response->assertSee('Content & Business');
    }
}
