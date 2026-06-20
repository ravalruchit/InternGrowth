<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Skill;

class SampleTasksSeeder extends Seeder
{
    public function run()
    {
        // Get or create a verified startup
        $startup = User::where('role', 'startup')->first();
        
        if (!$startup) {
            // Create a sample startup
            $startup = User::create([
                'name' => 'TechCorp Startup',
                'email' => 'startup@techcorp.com',
                'password' => bcrypt('password'),
                'role' => 'startup',
                'email_verified_at' => now(),
            ]);
            
            StartupProfile::create([
                'user_id' => $startup->id,
                'company_name' => 'TechCorp',
                'description' => 'Innovative tech startup',
                'industry' => 'Technology',
                'website' => 'https://techcorp.com',
                'location' => 'San Francisco',
                'team_size' => '10-50',
                'founded_year' => 2020,
                'credibility_score' => 1.0,
                'is_verified' => true,
            ]);
        } else {
            // Make sure startup is verified
            if ($startup->startupProfile) {
                $startup->startupProfile->update(['is_verified' => true]);
            }
        }
        
        $startupProfile = $startup->startupProfile;
        
        // Get skills
        $skills = Skill::all();
        if ($skills->isEmpty()) {
            echo "No skills found. Please run skills seeder first.\n";
            return;
        }
        
        // Sample tasks
        $tasks = [
            [
                'title' => 'Build a React Dashboard',
                'description' => 'We need a modern dashboard built with React and Tailwind CSS. The dashboard should display analytics, charts, and user data. Experience with Chart.js is a plus.',
                'stipend' => 200,
                'required_skills' => ['React', 'JavaScript', 'Tailwind CSS'],
                'domain' => 'Software Development',
                'role' => 'Frontend Developer',
            ],
            [
                'title' => 'Design Mobile App UI/UX',
                'description' => 'Create a beautiful and intuitive mobile app design for our fitness tracking application. Deliverables include wireframes, mockups, and a clickable prototype.',
                'stipend' => 150,
                'required_skills' => ['UI/UX Design', 'Figma', 'Mobile Design'],
                'domain' => 'UI/UX Design',
                'role' => 'UI Designer',
            ],
            [
                'title' => 'Python Data Analysis Script',
                'description' => 'Develop a Python script to analyze sales data and generate insights. Should include data visualization using matplotlib or seaborn.',
                'stipend' => 100,
                'required_skills' => ['Python', 'Data Analysis', 'Pandas'],
                'domain' => 'Data & AI',
                'role' => 'Data Analyst',
            ],
            [
                'title' => 'WordPress Blog Setup',
                'description' => 'Set up a professional WordPress blog with custom theme, plugins, and SEO optimization. Content migration from existing site required.',
                'stipend' => 80,
                'required_skills' => ['WordPress', 'PHP', 'SEO'],
                'domain' => 'Software Development',
                'role' => 'Full Stack Developer',
            ],
            [
                'title' => 'Social Media Content Creation',
                'description' => 'Create engaging social media content for our brand including graphics, captions, and posting schedule for Instagram, Twitter, and LinkedIn.',
                'stipend' => 75,
                'required_skills' => ['Content Writing', 'Graphic Design', 'Social Media'],
                'domain' => 'Digital Marketing',
                'role' => 'Social Media Manager',
            ],
            [
                'title' => 'Node.js REST API Development',
                'description' => 'Build a RESTful API using Node.js and Express. Should include authentication, CRUD operations, and MongoDB integration.',
                'stipend' => 250,
                'required_skills' => ['Node.js', 'JavaScript', 'MongoDB', 'API Development'],
                'domain' => 'Software Development',
                'role' => 'Backend Developer',
            ],
            [
                'title' => 'SEO Blog Copywriting',
                'description' => 'Write 3 high-quality SEO-optimized articles about SaaS marketing trends. Each blog should be 1200+ words with keyword integration.',
                'stipend' => 120,
                'required_skills' => ['Writing', 'Research', 'Blogging'],
                'domain' => 'Content & Business',
                'role' => 'Content Writer',
            ],
            [
                'title' => 'Flutter Mobile App Development',
                'description' => 'Develop a cross-platform mobile app using Flutter. App should work on both iOS and Android with clean UI and smooth animations.',
                'stipend' => 300,
                'required_skills' => ['Flutter', 'Dart', 'Mobile Development'],
                'domain' => 'Software Development',
                'role' => 'Mobile App Developer',
            ],
        ];
        
        foreach ($tasks as $taskData) {
            // Find matching skill IDs
            $skillNames = $taskData['required_skills'];
            $skillIds = $skills->whereIn('name', $skillNames)->pluck('id')->toArray();
            
            // Create task
            $task = Task::create([
                'startup_profile_id' => $startupProfile->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'stipend' => $taskData['stipend'],
                'required_skills' => json_encode($skillNames),
                'status' => 'posted',
                'domain' => $taskData['domain'],
                'role' => $taskData['role'],
            ]);
            
            // Attach skills
            if (!empty($skillIds)) {
                $task->skills()->attach($skillIds);
            }
            
            echo "Created task: {$task->title}\n";
        }
        
        echo "\n✅ Successfully created " . count($tasks) . " sample tasks!\n";
        echo "Login as a student to see AI-powered recommendations!\n";
    }
}
