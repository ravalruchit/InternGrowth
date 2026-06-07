<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('startup_profile_id')->constrained()->cascadeOnDelete();
            $table->integer('rating');
            $table->text('review');
            $table->timestamps();

            $table->unique(['task_id', 'student_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_reviews');
    }
};
