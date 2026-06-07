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
        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('ai_score')->nullable()->after('status');
            $table->text('ai_feedback')->nullable()->after('ai_score');
            $table->json('ai_requirements_met')->nullable()->after('ai_feedback');
            $table->timestamp('ai_checked_at')->nullable()->after('ai_requirements_met');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['ai_score', 'ai_feedback', 'ai_requirements_met', 'ai_checked_at']);
        });
    }
};
