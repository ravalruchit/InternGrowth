<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reputation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('overall_score', 5, 2)->default(50.00);
            $table->decimal('trust_score', 5, 2)->default(50.00);
            $table->decimal('completion_rate', 5, 2)->default(100.00);
            $table->decimal('on_time_rate', 5, 2)->default(100.00);
            $table->decimal('satisfaction_rating', 3, 2)->default(0.00);
            $table->decimal('communication_rating', 3, 2)->default(0.00);
            $table->decimal('skill_verification_rating', 5, 2)->default(0.00);
            $table->integer('total_verified_projects')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reputation_scores');
    }
};
