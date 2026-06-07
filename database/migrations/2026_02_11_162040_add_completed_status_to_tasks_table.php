<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the enum to include 'completed' status
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('posted', 'closed', 'moderated', 'completed') DEFAULT 'posted'");
        }
    }

    public function down(): void
    {
        // Revert back to original enum values
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('posted', 'closed', 'moderated') DEFAULT 'posted'");
        }
    }
};
