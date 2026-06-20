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
            ],
            [
                'title' => 'Design Mobile App UI/UX',
                'description' => 'Create a beautiful and intuitive mobile app design for our fitness tracking application. Deliverables include wireframes, mockups, and a clickable prototype.',
                'stipend' => 150,
                'required_skills' => ['UI/UX Design', 'Figma', 'Mobile Design'],
            ],
            [
                'title' => 'Python Data Analysis Script',
                'description' => 'Develop a Python script to analyze sales data and generate insights. Should include data visualization using matplotlib or seaborn.',
                'stipend' => 100,
                'required_skills' => ['Python', 'Data Analysis', 'Pandas'],
            ],
            [
                'title' => 'WordPress Blog Setup',
                'description' => 'Set up a professional WordPress blog with custom theme, plugins, and SEO optimization. Content migration from existing site required.',
                'stipend' => 80,
                'required_skills' => ['WordPress', 'PHP', 'SEO'],
            ],
            [
                'title' => 'Social Media Content Creation',
                'description' => 'Create engaging social media content for our brand including graphics, captions, and posting schedule for Instagram, Twitter, and LinkedIn.',
                'stipend' => 75,
                'required_skills' => ['Content Writing', 'Graphic Design', 'Social Media'],
            ],
            [
                'title' => 'Node.js REST API Development',
                'description' => 'Build a RESTful API using Node.js and Express. Should include authentication, CRUD operations, and MongoDB integration.',
                'stipend' => 250,
                'required_skills' => ['Node.js', 'JavaScript', 'MongoDB', 'API Development'],
            ],
            [
                'title' => 'Video Editing for YouTube',
                'description' => 'Edit 5 YouTube videos including intro/outro, transitions, background music, and color grading. Experience with Adobe Premiere or Final Cut Pro required.',
                'stipend' => 120,
                'required_skills' => ['Video Editing', 'Adobe Premiere', 'Motion Graphics'],
            ],
            [
                'title' => 'Flutter Mobile App Development',
                'description' => 'Develop a cross-platform mobile app using Flutter. App should work on both iOS and Android with clean UI and smooth animations.',
                'stipend' => 300,
                'required_skills' => ['Flutter', 'Dart', 'Mobile Development'],
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
