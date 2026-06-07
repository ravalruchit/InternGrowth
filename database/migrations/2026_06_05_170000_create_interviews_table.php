<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('startup_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(30);
            $table->string('type'); // 'online', 'phone', 'in_person'
            $table->string('location'); // URL link, phone number, or physical address
            $table->text('agenda')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'accepted', 'completed', 'rejected', 'cancelled', 'no_show'
            
            // Outcome Tracking & Skill Evaluation Ratings
            $table->string('outcome')->nullable(); // 'strong_candidate', 'proceed_to_offer', 'keep_in_pipeline', 'rejected'
            $table->integer('technical_rating')->nullable(); // 1-10
            $table->integer('communication_rating')->nullable(); // 1-10
            $table->integer('problem_solving_rating')->nullable(); // 1-10
            $table->text('feedback_notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
