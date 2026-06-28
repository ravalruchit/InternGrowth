<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_offer_id')->constrained('hiring_offers')->cascadeOnDelete();
            $table->foreignId('startup_profile_id')->constrained('startup_profiles')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('milestone_name')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'needs_changes'])->default('pending');
            $table->timestamps();

            $table->index(['hiring_offer_id', 'status']);
            $table->index('student_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_tasks');
    }
};
