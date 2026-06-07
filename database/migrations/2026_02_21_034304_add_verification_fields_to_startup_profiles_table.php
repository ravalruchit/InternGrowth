<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('startup_profiles', function (Blueprint $table) {
            $table->string('company_registration_number')->nullable()->after('website');
            $table->string('gst_number')->nullable()->after('company_registration_number');
            $table->string('company_address')->nullable()->after('gst_number');
            $table->string('contact_phone')->nullable()->after('company_address');
            $table->json('verification_documents')->nullable()->after('contact_phone');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_verified');
            $table->text('verification_notes')->nullable()->after('verification_status');
            $table->timestamp('verification_submitted_at')->nullable()->after('verification_notes');
            $table->timestamp('verification_reviewed_at')->nullable()->after('verification_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('startup_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'company_registration_number',
                'gst_number',
                'company_address',
                'contact_phone',
                'verification_documents',
                'verification_status',
                'verification_notes',
                'verification_submitted_at',
                'verification_reviewed_at'
            ]);
        });
    }
};
