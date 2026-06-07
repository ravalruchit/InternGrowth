<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Enhance portfolio_items with verification badges and project evidence
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->string('verification_badge')->default('verified_project')->after('is_featured');
            $table->timestamp('completed_at')->nullable()->after('verification_badge');
            $table->string('github_url')->nullable()->after('completed_at');
            $table->string('demo_url')->nullable()->after('github_url');
            $table->json('screenshots')->nullable()->after('demo_url');
        });

        // Enhance skill_verifications with startup tracking
        Schema::table('skill_verifications', function (Blueprint $table) {
            $table->foreignId('startup_profile_id')->nullable()->after('skill_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn(['verification_badge', 'completed_at', 'github_url', 'demo_url', 'screenshots']);
        });

        Schema::table('skill_verifications', function (Blueprint $table) {
            $table->dropForeign(['startup_profile_id']);
            $table->dropColumn('startup_profile_id');
        });
    }
};
