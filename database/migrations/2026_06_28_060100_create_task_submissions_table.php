<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_task_id')->constrained('internship_tasks')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->string('github_url')->nullable();
            $table->string('pull_request_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->text('description');
            $table->json('attachments')->nullable();
            $table->text('startup_feedback')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('internship_task_id');
            $table->index('student_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
