<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
    echo "MySQL users (" . count($users) . "):\n";
    foreach ($users as $user) {
        echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
    }
} catch (\Exception $e) {
    echo "Error checking MySQL: " . $e->getMessage() . "\n";
}
