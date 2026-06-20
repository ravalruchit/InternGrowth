<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class DbJsonSync extends Command
{
    protected $signature = 'db:sync-json {action : export or import}';
    protected $description = 'Export local database to JSON, or import JSON database to Neon';

    // List of tables in order of migration
    protected $tables = [
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

    public function handle()
    {
        $action = $this->argument('action');

        if ($action === 'export') {
            return $this->exportToJson();
        } elseif ($action === 'import') {
            return $this->importFromJson();
        } else {
            $this->error('Invalid action. Use "export" or "import".');
            return 1;
        }
    }

    private function exportToJson()
    {
        $this->info('Starting database export to JSON...');
        $data = [];

        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("Exporting table: {$table}...");
                $data[$table] = DB::table($table)->get()->map(function ($row) {
                    return (array) $row;
                })->toArray();
            } else {
                $this->warn("Table {$table} does not exist. Skipping.");
            }
        }

        $filePath = database_path('data_dump.json');
        File::put($filePath, json_encode($data, JSON_PRETTY_PRINT));

        $this->info("Database exported successfully to: {$filePath}");
        return 0;
    }

    private function importFromJson()
    {
        $this->info('Starting database import from JSON...');
        $filePath = database_path('data_dump.json');

        if (!File::exists($filePath)) {
            $this->error("Data dump file not found at: {$filePath}");
            return 1;
        }

        $data = json_decode(File::get($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Failed to parse JSON: ' . json_last_error_msg());
            return 1;
        }

        // 1. Temporarily disable foreign key constraints / triggers in PostgreSQL
        $this->info('Disabling triggers to bypass foreign key constraints...');
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::statement("ALTER TABLE \"{$table}\" DISABLE TRIGGER ALL;");
                } catch (\Exception $e) {
                    // Ignore if it fails
                }
            }
        }

        // 2. Clear tables and insert data
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                $this->warn("Table {$table} does not exist in target database. Skipping.");
                continue;
            }

            $this->info("Importing table: {$table}...");

            // Clear target table
            DB::table($table)->delete();

            if (empty($data[$table])) {
                $this->info("No records for {$table}.");
                continue;
            }

            $rows = $data[$table];

            // Convert boolean or JSON values if necessary for Postgres driver
            foreach ($rows as &$row) {
                foreach ($row as $key => $val) {
                    if (is_array($val)) {
                        $row[$key] = json_encode($val);
                    }
                }
            }

            // Insert in chunks
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table($table)->insert($chunk);
            }

            $this->info("Imported " . count($rows) . " rows into {$table}.");

            // 3. Reset PostgreSQL serial sequences
            if (Schema::hasColumn($table, 'id')) {
                $maxId = DB::table($table)->max('id') ?: 1;
                $sequenceName = "{$table}_id_seq";
                try {
                    DB::statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), :max_id)", ['max_id' => $maxId]);
                } catch (\Exception $e) {
                    try {
                        DB::statement("ALTER SEQUENCE \"{$sequenceName}\" RESTART WITH " . ($maxId + 1));
                    } catch (\Exception $ex) {
                        // Skip if table doesn't use sequence
                    }
                }
            }
        }

        // 4. Re-enable triggers
        $this->info('Re-enabling triggers...');
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::statement("ALTER TABLE \"{$table}\" ENABLE TRIGGER ALL;");
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        }

        $this->info('Database import completed successfully!');
        return 0;
    }
}
