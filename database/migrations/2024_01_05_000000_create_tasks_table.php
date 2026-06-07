<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->json('required_skills');
            $table->integer('reward_points');
            $table->decimal('stipend', 10, 2)->nullable();
            $table->enum('status', ['posted', 'closed', 'moderated'])->default('posted');
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();
        });

        Schema::create('skill_task', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->primary(['task_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_task');
        Schema::dropIfExists('tasks');
    }
};
