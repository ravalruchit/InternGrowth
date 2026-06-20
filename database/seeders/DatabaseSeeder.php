<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, StudentProfile, StartupProfile, Skill};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Skills
        $this->call(MultiDomainCareerSeeder::class);

        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@interngrowth.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_verified' => true,
        ]);

        // Create Student
        $student = User::create([
            'name' => 'John Student',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'is_verified' => true,
        ]);

        $studentProfile = StudentProfile::create([
            'user_id' => $student->id,
            'bio' => 'Passionate developer looking for opportunities',
            'portfolio_links' => ['https://github.com/johndoe'],
            'reliability_score' => 0.85,
        ]);

        // Create Startup
        $startup = User::create([
            'name' => 'Tech Startup',
            'email' => 'startup@example.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
            'is_verified' => true,
        ]);

        StartupProfile::create([
            'user_id' => $startup->id,
            'company_name' => 'Tech Innovations Inc',
            'description' => 'Building the future of technology',
            'website' => 'https://techinnovations.com',
            'credibility_score' => 0.90,
        ]);
    }
}
