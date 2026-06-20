<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('student_profiles', 'id_card_path')) {
                $table->string('id_card_path', 500)->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('student_profiles', 'id_card_verification_status')) {
                $table->string('id_card_verification_status', 50)->default('none')->after('id_card_path');
                // Values: none | pending | processing | ai_approved | ai_rejected | manual_review | admin_approved
            }
            if (!Schema::hasColumn('student_profiles', 'id_card_ai_result')) {
                $table->json('id_card_ai_result')->nullable()->after('id_card_verification_status');
            }
            if (!Schema::hasColumn('student_profiles', 'id_card_submitted_at')) {
                $table->timestamp('id_card_submitted_at')->nullable()->after('id_card_ai_result');
            }
            if (!Schema::hasColumn('student_profiles', 'id_card_verified_at')) {
                $table->timestamp('id_card_verified_at')->nullable()->after('id_card_submitted_at');
            }
            if (!Schema::hasColumn('student_profiles', 'verification_method')) {
                $table->string('verification_method', 50)->nullable()->after('id_card_verified_at');
                // Values: college_email | college_id_ai | admin_manual
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'id_card_path',
                'id_card_verification_status',
                'id_card_ai_result',
                'id_card_submitted_at',
                'id_card_verified_at',
                'verification_method',
            ]);
        });
    }
};
