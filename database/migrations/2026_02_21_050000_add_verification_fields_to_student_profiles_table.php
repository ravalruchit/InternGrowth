<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('student_profiles', 'college_email')) {
                $table->string('college_email')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('student_profiles', 'college_name')) {
                $table->string('college_name')->nullable()->after('college_email');
            }
            if (!Schema::hasColumn('student_profiles', 'verification_token')) {
                $table->string('verification_token')->nullable()->after('college_name');
            }
            if (!Schema::hasColumn('student_profiles', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('verification_token');
            }
            if (!Schema::hasColumn('student_profiles', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('is_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['college_email', 'college_name', 'verification_token', 'is_verified', 'email_verified_at']);
        });
    }
};
