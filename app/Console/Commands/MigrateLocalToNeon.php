<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateLocalToNeon extends Command
{
    protected $signature = 'db:migrate-to-neon';
    protected $description = 'Migrate local MySQL database data to Neon PostgreSQL';

    public function handle()
    {
        $this->info('Starting database migration from local MySQL to Neon PostgreSQL...');

        // Neon connection credentials
        $host = 'ep-purple-meadow-adr5e2fa.c-2.us-east-1.aws.neon.tech';
        $database = 'neondb';
        $username = 'neondb_owner';
        $password = 'npg_hsV6Jqdc8HnR';

        // Add the Neon connection configuration dynamically
        config(['database.connections.neon' => [
            'driver' => 'pgsql',
            'host' => $host,
            'port' => '5432',
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'require',
        ]]);

        // List of all tables to migrate
        $tables = [
            'users',
            'student_profiles',
            'startup_profiles',
            'skills',
            'student_skill',
            'tasks',
            'skill_task',
            'conversations',
            'messages',
            'applications',
            'submissions',
            'points_wallets',
            'points_transactions',
            'certificates',
            'ratings',
            'notifications',
            'transactions',
            'escrows',
            'wallet_topup_requests',
            'reputation_scores',
            'portfolios',
            'portfolio_items',
            'hiring_offers',
            'skill_verifications',
            'plagiarism_logs',
            'saved_candidates',
            'startup_reviews',
            'startup_trust_scores',
            'interviews',
            'startup_verification_logs',
        ];

        // 1. Temporarily disable all foreign key triggers on Neon
        $this->info('Disabling triggers to bypass foreign key constraints...');
        foreach ($tables as $table) {
            if (Schema::connection('neon')->hasTable($table)) {
                try {
                    DB::connection('neon')->statement("ALTER TABLE \"{$table}\" DISABLE TRIGGER ALL;");
                } catch (\Exception $e) {
                    $this->warn("Could not disable triggers for {$table}: " . $e->getMessage());
                }
            }
        }

        // 2. Clear tables and migrate data
        foreach ($tables as $table) {
            if (!Schema::connection('mysql')->hasTable($table)) {
                $this->warn("Table {$table} does not exist in local MySQL. Skipping.");
                continue;
            }

            if (!Schema::connection('neon')->hasTable($table)) {
                $this->warn("Table {$table} does not exist in Neon PostgreSQL. Skipping.");
                continue;
            }

            $this->info("Migrating table: {$table}...");

            // Fetch data from local MySQL
            $rows = DB::connection('mysql')->table($table)->get()->map(function ($row) {
                return (array) $row;
            })->toArray();

            // Clear target table in Neon
            DB::connection('neon')->table($table)->delete();

            if (empty($rows)) {
                $this->info("Table {$table} is empty locally. Cleared Neon target and skipped insert.");
                continue;
            }

            // Clean boolean and array fields for Postgres compatibility if needed
            foreach ($rows as &$row) {
                foreach ($row as $key => $val) {
                    // Check if column is stored as JSON or requires array mapping
                    if (is_string($val) && (str_starts_with($val, '[') || str_starts_with($val, '{'))) {
                        // Keep JSON strings as they are, Postgres driver will parse them
                    }
                }
            }

            // Insert data into Neon in chunks
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::connection('neon')->table($table)->insert($chunk);
            }

            $this->info("Successfully migrated " . count($rows) . " rows for {$table}.");

            // 3. Reset PostgreSQL sequence for auto-incrementing ID
            if (Schema::connection('neon')->hasColumn($table, 'id')) {
                $maxId = DB::connection('neon')->table($table)->max('id') ?: 1;
                $sequenceName = "{$table}_id_seq";
                try {
                    DB::connection('neon')->statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), :max_id)", ['max_id' => $maxId]);
                } catch (\Exception $e) {
                    try {
                        DB::connection('neon')->statement("ALTER SEQUENCE \"{$sequenceName}\" RESTART WITH " . ($maxId + 1));
                    } catch (\Exception $ex) {
                        // Some pivot tables or config tables might not have sequences, which is fine
                    }
                }
            }
        }

        // 4. Re-enable triggers on Neon
        $this->info('Re-enabling triggers and constraints...');
        foreach ($tables as $table) {
            if (Schema::connection('neon')->hasTable($table)) {
                try {
                    DB::connection('neon')->statement("ALTER TABLE \"{$table}\" ENABLE TRIGGER ALL;");
                } catch (\Exception $e) {
                    $this->warn("Could not enable triggers for {$table}: " . $e->getMessage());
                }
            }
        }

        $this->info('Database migration from local MySQL to Neon completed successfully!');
        return 0;
    }
}
