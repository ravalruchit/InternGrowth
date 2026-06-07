<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_trust_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_profile_id')->constrained()->cascadeOnDelete();
            $table->decimal('overall_score', 5, 2)->default(100.00);
            $table->decimal('payment_score', 5, 2)->default(100.00);
            $table->decimal('verification_score', 5, 2)->default(100.00);
            $table->decimal('student_rating_score', 5, 2)->default(100.00);
            $table->decimal('hiring_score', 5, 2)->default(100.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_trust_scores');
    }
};
