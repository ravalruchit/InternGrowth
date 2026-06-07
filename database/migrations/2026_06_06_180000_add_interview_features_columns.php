<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add reminder flags to interviews table
        Schema::table('interviews', function (Blueprint $table) {
            $table->boolean('reminder_24h_sent')->default(false)->after('feedback_notes');
            $table->boolean('reminder_1h_sent')->default(false)->after('reminder_24h_sent');
        });

        // 2. Add Interview Performance Score columns to reputation_scores table
        Schema::table('reputation_scores', function (Blueprint $table) {
            $table->integer('interviews_attended')->default(0)->after('total_verified_projects');
            $table->decimal('interview_success_rate', 5, 2)->default(100.00)->after('interviews_attended');
            $table->integer('strong_candidate_outcomes')->default(0)->after('interview_success_rate');
            $table->integer('no_shows')->default(0)->after('strong_candidate_outcomes');
            $table->decimal('interview_performance_score', 5, 2)->default(100.00)->after('no_shows');
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['reminder_24h_sent', 'reminder_1h_sent']);
        });

        Schema::table('reputation_scores', function (Blueprint $table) {
            $table->dropColumn([
                'interviews_attended',
                'interview_success_rate',
                'strong_candidate_outcomes',
                'no_shows',
                'interview_performance_score'
            ]);
        });
    }
};
