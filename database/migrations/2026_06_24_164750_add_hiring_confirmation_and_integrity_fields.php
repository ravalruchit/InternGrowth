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
        // 1. Update transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->default('active')->after('reference_id');
        });

        // 2. Update hiring_offers table
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->string('student_joining_status')->default('pending')->after('reserved_fee');
            $table->string('startup_joining_status')->default('pending')->after('student_joining_status');
            $table->timestamp('joining_confirmed_at')->nullable()->after('startup_joining_status');
            $table->timestamp('completed_at')->nullable()->after('joining_confirmed_at');
            $table->text('completion_notes')->nullable()->after('completed_at');
            $table->string('hiring_success_rating')->nullable()->after('completion_notes');
            $table->timestamp('hiring_success_rated_at')->nullable()->after('hiring_success_rating');
        });

        // 3. Update certificates table
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('task_id')->nullable()->change();
            $table->foreignId('hiring_offer_id')->nullable()->after('task_id')->constrained('hiring_offers')->cascadeOnDelete();
        });

        // 4. Update portfolio_items table
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->foreignId('hiring_offer_id')->nullable()->after('submission_id')->constrained('hiring_offers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropForeign(['hiring_offer_id']);
            $table->dropColumn('hiring_offer_id');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['hiring_offer_id']);
            $table->dropColumn('hiring_offer_id');
            $table->foreignId('task_id')->nullable(false)->change();
        });

        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn([
                'student_joining_status',
                'startup_joining_status',
                'joining_confirmed_at',
                'completed_at',
                'completion_notes',
                'hiring_success_rating',
                'hiring_success_rated_at'
            ]);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
