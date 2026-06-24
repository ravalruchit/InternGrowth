<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Include Laravel's autoloader and bootstrap the application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Neon connection credentials
$host = 'ep-purple-meadow-adr5e2fa.c-2.us-east-1.aws.neon.tech';
$database = 'neondb';
$username = 'neondb_owner';
$password = 'npg_hsV6Jqdc8HnR';

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

// Tables to sync (excluding points tables since they are deleted)
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

echo "Starting data restore from Neon to local MySQL...\n";

try {
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($tables as $table) {
        if (!Schema::connection('mysql')->hasTable($table)) {
            echo "Table {$table} does not exist in local MySQL. Skipping.\n";
            continue;
        }

        echo "Syncing table: {$table}... ";

        // Clear local table
        DB::connection('mysql')->table($table)->truncate();

        // Fetch from Neon
        try {
            $rows = DB::connection('neon')->table($table)->get()->map(function ($row) {
                return (array) $row;
            })->toArray();
        } catch (\Exception $e) {
            echo "Failed to fetch from Neon: " . $e->getMessage() . "\n";
            continue;
        }

        if (empty($rows)) {
            echo "0 rows.\n";
            continue;
        }

        // Clean values (JSON field mapping)
        foreach ($rows as &$row) {
            foreach ($row as $key => $val) {
                // If it is array or object, json_encode it for mysql insertion
                if (is_array($val) || is_object($val)) {
                    $row[$key] = json_encode($val);
                }
            }
        }

        // Insert in chunks
        foreach (array_chunk($rows, 100) as $chunk) {
            DB::connection('mysql')->table($table)->insert($chunk);
        }

        echo count($rows) . " rows restored.\n";
    }

    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "\n✅ Successfully restored database from Neon to local MySQL!\n";
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}
