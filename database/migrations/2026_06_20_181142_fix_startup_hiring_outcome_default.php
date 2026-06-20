<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('startup_hiring_outcome')->nullable()->default(null)->change();
        });

        // Safe cleanup: Reset 'task_only' to null for all existing applications, since they defaulted to it automatically
        DB::table('applications')
            ->where('startup_hiring_outcome', 'task_only')
            ->update(['startup_hiring_outcome' => null]);
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('startup_hiring_outcome')->default('task_only')->change();
        });
    }
};
