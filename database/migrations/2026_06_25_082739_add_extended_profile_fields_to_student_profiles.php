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
        Schema::table('student_profiles', function (Blueprint $table) {
            // Social and contact info
            $table->string('phone_number')->nullable()->after('graduation_year');
            $table->string('github_url')->nullable()->after('phone_number');
            $table->string('linkedin_url')->nullable()->after('github_url');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->string('leetcode_url')->nullable()->after('portfolio_url');
            
            // Education info
            $table->string('degree_name')->nullable()->after('leetcode_url');
            $table->decimal('cgpa', 4, 2)->nullable()->after('degree_name'); // e.g. 9.99

            // Professional headline & location
            $table->string('professional_title')->nullable()->after('cgpa');
            $table->string('city')->nullable()->after('professional_title');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');

            // Resume Customization Options
            $table->string('resume_theme')->default('ats')->after('country');
            $table->boolean('show_iprs')->default(true)->after('resume_theme');
            $table->boolean('show_stipends')->default(true)->after('show_iprs');
            $table->boolean('show_ratings')->default(true)->after('show_stipends');
            $table->boolean('show_certificates')->default(true)->after('show_ratings');
            $table->boolean('show_social_links')->default(true)->after('show_certificates');
            $table->boolean('show_profile_photo')->default(false)->after('show_social_links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'github_url',
                'linkedin_url',
                'portfolio_url',
                'leetcode_url',
                'degree_name',
                'cgpa',
                'professional_title',
                'city',
                'state',
                'country',
                'resume_theme',
                'show_iprs',
                'show_stipends',
                'show_ratings',
                'show_certificates',
                'show_social_links',
                'show_profile_photo'
            ]);
        });
    }
};
