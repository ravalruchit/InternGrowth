<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, StudentProfile, ReputationScore, Skill, PointsWallet, Portfolio, PortfolioItem, HiringOffer, Task, Application, Submission};

class CandidatesTestSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up any existing test users
        User::whereIn('email', [
            'ruchit@example.com',
            'alice@example.com',
            'bob@example.com',
            'carol@example.com',
            'startup_test@example.com'
        ])->delete();

        // Fetch or create skills
        $php = Skill::firstOrCreate(['name' => 'PHP']);
        $laravel = Skill::firstOrCreate(['name' => 'Laravel']);
        $js = Skill::firstOrCreate(['name' => 'JavaScript']);
        $react = Skill::firstOrCreate(['name' => 'React']);
        $python = Skill::firstOrCreate(['name' => 'Python']);
        $uiux = Skill::firstOrCreate(['name' => 'UI/UX Design']);
        $writing = Skill::firstOrCreate(['name' => 'Content Writing']);

        // Find a startup profile to associate with some portfolio items
        $startup = \App\Models\StartupProfile::first();
        if (!$startup) {
            // Create a default startup if none exists
            $startupUser = User::create([
                'name' => 'Tech Startup',
                'email' => 'startup_test@example.com',
                'password' => bcrypt('password'),
                'role' => 'startup',
                'is_verified' => true,
            ]);
            $startup = \App\Models\StartupProfile::create([
                'user_id' => $startupUser->id,
                'company_name' => 'Tech Innovations Inc',
                'description' => 'Building the future of technology',
                'website' => 'https://techinnovations.com',
                'credibility_score' => 0.90,
            ]);
        }

        // --- candidate 1: Ruchit ---
        $u1 = User::create([
            'name' => 'Ruchit',
            'email' => 'ruchit@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => true,
        ]);

        $p1 = StudentProfile::create([
            'user_id' => $u1->id,
            'bio' => 'Full-stack Laravel developer with a love for clean code and elegant APIs.',
            'college_name' => 'Stanford University',
            'is_verified' => true,
            'availability' => 'looking_for_internship',
            'reliability_score' => 0.92,
        ]);

        PointsWallet::create(['student_profile_id' => $p1->id, 'balance' => 12500]);
        $p1->skills()->sync([$php->id, $laravel->id, $js->id]);

        // Verifications
        \App\Models\SkillVerification::create(['student_profile_id' => $p1->id, 'skill_id' => $php->id, 'verification_method' => 'task_completion', 'score' => 91, 'verified_at' => now()]);
        \App\Models\SkillVerification::create(['student_profile_id' => $p1->id, 'skill_id' => $laravel->id, 'verification_method' => 'task_completion', 'score' => 94, 'verified_at' => now()]);

        // Reputation Score
        ReputationScore::create([
            'student_profile_id' => $p1->id,
            'overall_score' => 92.00,
            'trust_score' => 95.00,
            'completion_rate' => 98.00,
            'on_time_rate' => 94.00,
            'satisfaction_rating' => 4.80,
            'communication_rating' => 4.40, // 4.4 * 20 = 88% Match Comm
            'skill_verification_rating' => 90.00,
            'total_verified_projects' => 18,
        ]);

        // Portfolio & Ledger items
        $port1 = Portfolio::create(['student_profile_id' => $p1->id, 'custom_slug' => 'ruchit', 'is_public' => true]);
        
        // Create dummy task/app/sub for constraints
        $t1 = Task::create([
            'startup_profile_id' => $startup->id,
            'title' => 'Laravel Optimized Setup',
            'description' => 'Development verification task',
            'required_skills' => ['PHP', 'Laravel'],
            'reward_points' => 200,
            'status' => 'completed'
        ]);
        $a1 = Application::create(['task_id' => $t1->id, 'student_profile_id' => $p1->id, 'status' => 'approved']);
        $s1 = Submission::create(['application_id' => $a1->id, 'content' => 'https://github.com/ruchit/test', 'status' => 'accepted']);

        // Add 18 mock project/task completions to match "Projects Completed: 18"
        for ($i = 1; $i <= 18; $i++) {
            PortfolioItem::create([
                'portfolio_id' => $port1->id,
                'task_id' => $t1->id,
                'submission_id' => $s1->id,
                'project_title' => "Laravel Optimization Task #{$i}",
                'startup_name' => $startup->company_name,
                'rating_received' => 4.8,
                'skills_demonstrated' => ['PHP', 'Laravel'],
                'certificate_number' => 'CERT-RUCHIT-' . rand(1000, 9999),
                'is_featured' => $i <= 3
            ]);
        }

        // Add 2 accepted internship offers to match "Internships Completed: 2"
        for ($i = 1; $i <= 2; $i++) {
            HiringOffer::create([
                'startup_profile_id' => $startup->id,
                'student_profile_id' => $p1->id,
                'offer_type' => 'internship',
                'title' => "Laravel Backend Intern Role {$i}",
                'description' => 'Worked on microservices and migrations.',
                'compensation' => 15000.00,
                'compensation_period' => 'monthly',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->subMonths(3),
                'status' => 'accepted'
            ]);
        }

        // Add 4 job offers to match "Job Offers Received: 4"
        for ($i = 1; $i <= 4; $i++) {
            HiringOffer::create([
                'startup_profile_id' => $startup->id,
                'student_profile_id' => $p1->id,
                'offer_type' => 'job',
                'title' => "Full-Stack Engineer Offer {$i}",
                'description' => 'Build customer-facing products with Laravel and React.',
                'compensation' => 700000.00,
                'compensation_period' => 'annual',
                'start_date' => now()->addMonth(),
                'status' => 'pending'
            ]);
        }


        // --- candidate 2: Alice ---
        $u2 = User::create([
            'name' => 'Alice Dev',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => true,
        ]);

        $p2 = StudentProfile::create([
            'user_id' => $u2->id,
            'bio' => 'Python backend developer and machine learning enthusiast.',
            'college_name' => 'MIT',
            'is_verified' => true,
            'availability' => 'open_to_work',
            'reliability_score' => 0.88,
        ]);

        PointsWallet::create(['student_profile_id' => $p2->id, 'balance' => 8000]);
        $p2->skills()->sync([$python->id, $js->id]);

        \App\Models\SkillVerification::create(['student_profile_id' => $p2->id, 'skill_id' => $python->id, 'verification_method' => 'task_completion', 'score' => 88, 'verified_at' => now()]);

        ReputationScore::create([
            'student_profile_id' => $p2->id,
            'overall_score' => 88.00,
            'trust_score' => 90.00,
            'completion_rate' => 92.00,
            'on_time_rate' => 90.00,
            'satisfaction_rating' => 4.60,
            'communication_rating' => 4.50,
            'skill_verification_rating' => 85.00,
            'total_verified_projects' => 10,
        ]);

        $port2 = Portfolio::create(['student_profile_id' => $p2->id, 'custom_slug' => 'alice', 'is_public' => true]);
        
        $t2 = Task::create([
            'startup_profile_id' => $startup->id,
            'title' => 'Python Data Cleaning',
            'description' => 'Development verification task',
            'required_skills' => ['Python'],
            'reward_points' => 150,
            'status' => 'completed'
        ]);
        $a2 = Application::create(['task_id' => $t2->id, 'student_profile_id' => $p2->id, 'status' => 'approved']);
        $s2 = Submission::create(['application_id' => $a2->id, 'content' => 'https://github.com/alice/test', 'status' => 'accepted']);

        for ($i = 1; $i <= 10; $i++) {
            PortfolioItem::create([
                'portfolio_id' => $port2->id,
                'task_id' => $t2->id,
                'submission_id' => $s2->id,
                'project_title' => "Data Engineering Script #{$i}",
                'startup_name' => $startup->company_name,
                'rating_received' => 4.6,
                'skills_demonstrated' => ['Python'],
                'certificate_number' => 'CERT-ALICE-' . rand(1000, 9999)
            ]);
        }

        // 1 internship completed
        HiringOffer::create([
            'startup_profile_id' => $startup->id,
            'student_profile_id' => $p2->id,
            'offer_type' => 'internship',
            'title' => "Python Developer Intern",
            'description' => 'Optimizing database queries and data scraping scripts.',
            'compensation' => 12000.00,
            'compensation_period' => 'monthly',
            'start_date' => now()->subMonths(4),
            'end_date' => now()->subMonths(1),
            'status' => 'accepted'
        ]);

        // 2 job offers received
        for ($i = 1; $i <= 2; $i++) {
            HiringOffer::create([
                'startup_profile_id' => $startup->id,
                'student_profile_id' => $p2->id,
                'offer_type' => 'job',
                'title' => "Data Scientist Role {$i}",
                'description' => 'Machine learning models pipeline developer.',
                'compensation' => 850000.00,
                'compensation_period' => 'annual',
                'start_date' => now()->addMonth(),
                'status' => 'pending'
            ]);
        }


        // --- candidate 3: Bob ---
        $u3 = User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => true,
        ]);

        $p3 = StudentProfile::create([
            'user_id' => $u3->id,
            'bio' => 'Frontend developer specializing in React and responsive UI design.',
            'college_name' => 'UC Berkeley',
            'is_verified' => false,
            'availability' => 'looking_for_job',
            'reliability_score' => 0.81,
        ]);

        PointsWallet::create(['student_profile_id' => $p3->id, 'balance' => 4500]);
        $p3->skills()->sync([$js->id, $react->id, $uiux->id]);

        ReputationScore::create([
            'student_profile_id' => $p3->id,
            'overall_score' => 81.00,
            'trust_score' => 75.00,
            'completion_rate' => 85.00,
            'on_time_rate' => 88.00,
            'satisfaction_rating' => 4.20,
            'communication_rating' => 4.30,
            'skill_verification_rating' => 60.00,
            'total_verified_projects' => 8,
        ]);

        $port3 = Portfolio::create(['student_profile_id' => $p3->id, 'custom_slug' => 'bob', 'is_public' => true]);
        
        $t3 = Task::create([
            'startup_profile_id' => $startup->id,
            'title' => 'React Component Development',
            'description' => 'Development verification task',
            'required_skills' => ['React', 'JavaScript'],
            'reward_points' => 120,
            'status' => 'completed'
        ]);
        $a3 = Application::create(['task_id' => $t3->id, 'student_profile_id' => $p3->id, 'status' => 'approved']);
        $s3 = Submission::create(['application_id' => $a3->id, 'content' => 'https://github.com/bob/test', 'status' => 'accepted']);

        for ($i = 1; $i <= 8; $i++) {
            PortfolioItem::create([
                'portfolio_id' => $port3->id,
                'task_id' => $t3->id,
                'submission_id' => $s3->id,
                'project_title' => "React SPA Interface #{$i}",
                'startup_name' => $startup->company_name,
                'rating_received' => 4.2,
                'skills_demonstrated' => ['React', 'JavaScript'],
                'certificate_number' => 'CERT-BOB-' . rand(1000, 9999)
            ]);
        }

        // 3 job offers received
        for ($i = 1; $i <= 3; $i++) {
            HiringOffer::create([
                'startup_profile_id' => $startup->id,
                'student_profile_id' => $p3->id,
                'offer_type' => 'job',
                'title' => "Frontend React Developer {$i}",
                'description' => 'Build rich user interfaces.',
                'compensation' => 550000.00,
                'compensation_period' => 'annual',
                'start_date' => now()->addMonth(),
                'status' => 'pending'
            ]);
        }


        // --- candidate 4: Carol ---
        $u4 = User::create([
            'name' => 'Carol Writer',
            'email' => 'carol@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => true,
        ]);

        $p4 = StudentProfile::create([
            'user_id' => $u4->id,
            'bio' => 'Professional content writer, copywriter, and digital marketing specialist.',
            'college_name' => 'Yale University',
            'is_verified' => true,
            'availability' => 'freelance_available',
            'reliability_score' => 0.95,
        ]);

        PointsWallet::create(['student_profile_id' => $p4->id, 'balance' => 9500]);
        $p4->skills()->sync([$writing->id]);

        \App\Models\SkillVerification::create(['student_profile_id' => $p4->id, 'skill_id' => $writing->id, 'verification_method' => 'task_completion', 'score' => 95, 'verified_at' => now()]);

        ReputationScore::create([
            'student_profile_id' => $p4->id,
            'overall_score' => 95.00,
            'trust_score' => 98.00,
            'completion_rate' => 100.00,
            'on_time_rate' => 100.00,
            'satisfaction_rating' => 4.90,
            'communication_rating' => 4.90,
            'skill_verification_rating' => 95.00,
            'total_verified_projects' => 5,
        ]);

        $port4 = Portfolio::create(['student_profile_id' => $p4->id, 'custom_slug' => 'carol', 'is_public' => true]);
        
        $t4 = Task::create([
            'startup_profile_id' => $startup->id,
            'title' => 'Blog Content Creation',
            'description' => 'Development verification task',
            'required_skills' => ['Content Writing'],
            'reward_points' => 80,
            'status' => 'completed'
        ]);
        $a4 = Application::create(['task_id' => $t4->id, 'student_profile_id' => $p4->id, 'status' => 'approved']);
        $s4 = Submission::create(['application_id' => $a4->id, 'content' => 'https://github.com/carol/test', 'status' => 'accepted']);

        for ($i = 1; $i <= 5; $i++) {
            PortfolioItem::create([
                'portfolio_id' => $port4->id,
                'task_id' => $t4->id,
                'submission_id' => $s4->id,
                'project_title' => "Marketing Strategy Document #{$i}",
                'startup_name' => $startup->company_name,
                'rating_received' => 4.9,
                'skills_demonstrated' => ['Content Writing'],
                'certificate_number' => 'CERT-CAROL-' . rand(1000, 9999)
            ]);
        }

        // 1 job offer received
        HiringOffer::create([
            'startup_profile_id' => $startup->id,
            'student_profile_id' => $p4->id,
            'offer_type' => 'job',
            'title' => "Technical Copywriter",
            'description' => 'Write blogs, API docs, and documentation.',
            'compensation' => 450000.00,
            'compensation_period' => 'annual',
            'start_date' => now()->addMonth(),
            'status' => 'pending'
        ]);
    }
}
