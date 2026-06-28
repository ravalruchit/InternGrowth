<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('internship_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('github_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->integer('week_number');
            $table->text('tasks_completed');
            $table->text('challenges');
            $table->text('next_week_goals');
            $table->text('startup_feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5
            $table->string('github_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->timestamps();
        });

        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->decimal('internship_score', 5, 2)->default(0.00);
            $table->boolean('converted_to_full_time')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn(['internship_score', 'converted_to_full_time']);
        });

        Schema::dropIfExists('weekly_reports');
        Schema::dropIfExists('internship_updates');
    }
};
