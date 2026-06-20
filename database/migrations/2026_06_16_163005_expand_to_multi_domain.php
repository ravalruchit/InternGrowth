<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('domain')->nullable();
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('primary_domain')->nullable();
            $table->string('preferred_role')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('domain')->nullable();
            $table->string('role')->nullable();
        });

        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->string('domain')->nullable();
            $table->string('role')->nullable();
        });

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->string('domain')->nullable();
            $table->string('role')->nullable();
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->string('domain')->nullable();
            $table->string('role')->nullable();
        });

        Schema::table('reputation_scores', function (Blueprint $table) {
            $table->json('domain_scores')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('domain');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['primary_domain', 'preferred_role']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['domain', 'role']);
        });

        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn(['domain', 'role']);
        });

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn(['domain', 'role']);
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['domain', 'role']);
        });

        Schema::table('reputation_scores', function (Blueprint $table) {
            $table->dropColumn('domain_scores');
        });
    }
};
