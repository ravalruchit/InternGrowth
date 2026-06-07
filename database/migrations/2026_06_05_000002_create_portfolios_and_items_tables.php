<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('custom_slug')->unique();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained()->restrictOnDelete();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->string('project_title');
            $table->text('auto_summary')->nullable();
            $table->json('skills_demonstrated')->nullable();
            $table->decimal('rating_received', 3, 2)->nullable();
            $table->string('startup_name');
            $table->string('certificate_number')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('portfolios');
    }
};
