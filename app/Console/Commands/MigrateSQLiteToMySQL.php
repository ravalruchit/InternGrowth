<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSQLiteToMySQL extends Command
{
    protected $signature = 'migrate:sqlite-to-mysql';
    protected $description = 'Migrate data from SQLite to MySQL';

    public function handle()
    {
        $this->info('Starting data migration from SQLite to MySQL...');
        
        // Set SQLite database path
        $sqlitePath = database_path('database.sqlite');
        
        if (!file_exists($sqlitePath)) {
            $this->error('SQLite database not found at: ' . $sqlitePath);
            return 1;
        }
        
        $this->info('Reading from: ' . $sqlitePath);
        
        // Temporarily switch to SQLite with absolute path
        config(['database.connections.sqlite.database' => $sqlitePath]);
        config(['database.default' => 'sqlite']);
        
        try {
            // Get all data from SQLite
            $users = DB::table('users')->get();
            $studentProfiles = DB::table('student_profiles')->get();
            $startupProfiles = DB::table('startup_profiles')->get();
            $skills = DB::table('skills')->get();
            $studentSkill = DB::table('student_skill')->get();
            $tasks = DB::table('tasks')->get();
            $skillTask = DB::table('skill_task')->get();
            $applications = DB::table('applications')->get();
            $submissions = DB::table('submissions')->get();
            $pointsWallets = DB::table('points_wallets')->get();
            $pointsTransactions = DB::table('points_transactions')->get();
            $certificates = DB::table('certificates')->get();
            $ratings = DB::table('ratings')->get();
            $notifications = DB::table('notifications')->get();
            
            // Check if conversations and messages exist
            $conversations = [];
            $messages = [];
            try {
                $conversations = DB::table('conversations')->get();
                $messages = DB::table('messages')->get();
            } catch (\Exception $e) {
                $this->warn('Conversations/Messages tables not found in SQLite, skipping...');
            }
            
            $this->info('Data extracted from SQLite successfully!');
            
            // Switch to MySQL
            config(['database.default' => 'mysql']);
            
            // Clear MySQL tables
            $this->info('Clearing existing MySQL data...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            DB::table('messages')->truncate();
            DB::table('conversations')->truncate();
            DB::table('notifications')->truncate();
            DB::table('ratings')->truncate();
            DB::table('certificates')->truncate();
            DB::table('points_transactions')->truncate();
            DB::table('points_wallets')->truncate();
            DB::table('submissions')->truncate();
            DB::table('applications')->truncate();
            DB::table('skill_task')->truncate();
            DB::table('tasks')->truncate();
            DB::table('student_skill')->truncate();
            DB::table('skills')->truncate();
            DB::table('startup_profiles')->truncate();
            DB::table('student_profiles')->truncate();
            DB::table('users')->truncate();
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('MySQL tables cleared!');
            
            // Insert data into MySQL
            $this->info('Inserting users...');
            foreach ($users as $user) {
                DB::table('users')->insert((array) $user);
            }
            
            $this->info('Inserting student profiles...');
            foreach ($studentProfiles as $profile) {
                DB::table('student_profiles')->insert((array) $profile);
            }
            
            $this->info('Inserting startup profiles...');
            foreach ($startupProfiles as $profile) {
                DB::table('startup_profiles')->insert((array) $profile);
            }
            
            $this->info('Inserting skills...');
            foreach ($skills as $skill) {
                DB::table('skills')->insert((array) $skill);
            }
            
            $this->info('Inserting student-skill relationships...');
            foreach ($studentSkill as $relation) {
                DB::table('student_skill')->insert((array) $relation);
            }
            
            $this->info('Inserting tasks...');
            foreach ($tasks as $task) {
                DB::table('tasks')->insert((array) $task);
            }
            
            $this->info('Inserting skill-task relationships...');
            foreach ($skillTask as $relation) {
                DB::table('skill_task')->insert((array) $relation);
            }
            
            $this->info('Inserting applications...');
            foreach ($applications as $application) {
                DB::table('applications')->insert((array) $application);
            }
            
            $this->info('Inserting submissions...');
            foreach ($submissions as $submission) {
                DB::table('submissions')->insert((array) $submission);
            }
            
            $this->info('Inserting points wallets...');
            foreach ($pointsWallets as $wallet) {
                DB::table('points_wallets')->insert((array) $wallet);
            }
            
            $this->info('Inserting points transactions...');
            foreach ($pointsTransactions as $transaction) {
                DB::table('points_transactions')->insert((array) $transaction);
            }
            
            $this->info('Inserting certificates...');
            foreach ($certificates as $certificate) {
                DB::table('certificates')->insert((array) $certificate);
            }
            
            $this->info('Inserting ratings...');
            foreach ($ratings as $rating) {
                DB::table('ratings')->insert((array) $rating);
            }
            
            $this->info('Inserting notifications...');
            foreach ($notifications as $notification) {
                DB::table('notifications')->insert((array) $notification);
            }
            
            if (count($conversations) > 0) {
                $this->info('Inserting conversations...');
                foreach ($conversations as $conversation) {
                    DB::table('conversations')->insert((array) $conversation);
                }
            }
            
            if (count($messages) > 0) {
                $this->info('Inserting messages...');
                foreach ($messages as $message) {
                    DB::table('messages')->insert((array) $message);
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->info('✅ Data migration completed successfully!');
            $this->info('Total records migrated:');
            $this->info("- Users: " . count($users));
            $this->info("- Student Profiles: " . count($studentProfiles));
            $this->info("- Startup Profiles: " . count($startupProfiles));
            $this->info("- Skills: " . count($skills));
            $this->info("- Tasks: " . count($tasks));
            $this->info("- Applications: " . count($applications));
            $this->info("- Submissions: " . count($submissions));
            $this->info("- Points Wallets: " . count($pointsWallets));
            $this->info("- Points Transactions: " . count($pointsTransactions));
            $this->info("- Certificates: " . count($certificates));
            $this->info("- Ratings: " . count($ratings));
            $this->info("- Notifications: " . count($notifications));
            $this->info("- Conversations: " . count($conversations));
            $this->info("- Messages: " . count($messages));
            
        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
