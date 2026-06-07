<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skill_verifications', function (Blueprint $table) {
            $table->foreignId('task_id')->nullable()->after('startup_profile_id')->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating')->nullable()->after('score'); // 1-5 proficiency
            $table->string('verification_type')->default('task')->after('rating'); // task, internship, job
            $table->text('notes')->nullable()->after('verification_type');
        });
    }

    public function down(): void
    {
        Schema::table('skill_verifications', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn(['task_id', 'rating', 'verification_type', 'notes']);
        });
    }
};
