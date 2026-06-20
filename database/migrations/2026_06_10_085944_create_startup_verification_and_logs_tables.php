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
        Schema::table('startup_profiles', function (Blueprint $table) {
            $table->string('ai_verification_status')->default('none')->after('verification_status');
            $table->json('ai_verification_result')->nullable()->after('ai_verification_status');
            $table->timestamp('ai_verified_at')->nullable()->after('ai_verification_result');
            $table->integer('ai_confidence_score')->nullable()->after('ai_verified_at');
            $table->string('verification_documents_hash')->nullable()->after('ai_confidence_score');
            $table->char('verification_level', 1)->nullable()->after('verification_documents_hash');
            $table->timestamp('verification_expires_at')->nullable()->after('verification_level');
            $table->integer('startup_trust_score')->default(0)->after('verification_expires_at');
            $table->json('trust_score_breakdown')->nullable()->after('startup_trust_score');
            $table->boolean('is_suspicious')->default(false)->after('trust_score_breakdown');
        });

        Schema::create('startup_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_profile_id')->constrained('startup_profiles')->cascadeOnDelete();
            $table->string('action'); // 'ai_analysis', 'admin_approved', 'admin_rejected', 'marked_suspicious', etc.
            $table->string('performed_by'); // 'ai', 'admin', 'system'
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startup_verification_logs');

        Schema::table('startup_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'ai_verification_status',
                'ai_verification_result',
                'ai_verified_at',
                'ai_confidence_score',
                'verification_documents_hash',
                'verification_level',
                'verification_expires_at',
                'startup_trust_score',
                'trust_score_breakdown',
                'is_suspicious'
            ]);
        });
    }
};
