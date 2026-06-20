<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->string('startup_hiring_outcome')->nullable();
            $table->string('hired_via')->nullable();
            $table->boolean('agreement_accepted')->default(false);
            $table->timestamp('agreement_accepted_at')->nullable();
            $table->string('agreement_ip')->nullable();
            $table->string('hiring_success_rating')->nullable();
            $table->timestamp('hiring_success_rated_at')->nullable();
        });

        // Update hiring_offers table
        // Change status to string type to easily support 'countered' and 'expired' without enum restrictions
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->decimal('counter_compensation', 12, 2)->nullable();
            $table->text('counter_note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'startup_hiring_outcome',
                'hired_via',
                'agreement_accepted',
                'agreement_accepted_at',
                'agreement_ip',
                'hiring_success_rating',
                'hiring_success_rated_at'
            ]);
        });

        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn([
                'counter_compensation',
                'counter_note'
            ]);
            // Optional: Revert column type if needed, but keeping string is safer.
        });
    }
};
