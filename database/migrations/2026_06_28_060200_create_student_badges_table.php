<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->string('badge_slug');      // e.g. 'consistent_coder'
            $table->string('badge_name');      // e.g. '🔥 Consistent Coder'
            $table->foreignId('hiring_offer_id')->nullable()->constrained('hiring_offers')->nullOnDelete();
            $table->timestamp('earned_at');
            $table->timestamps();

            $table->unique(['student_profile_id', 'badge_slug', 'hiring_offer_id'], 'sb_profile_slug_offer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_badges');
    }
};
