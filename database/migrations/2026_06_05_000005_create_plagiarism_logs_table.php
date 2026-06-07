<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plagiarism_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->decimal('similarity_score', 5, 2)->default(0.00);
            $table->json('matched_sources')->nullable();
            $table->enum('status', ['clean', 'flagged', 'cleared_by_admin'])->default('clean');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plagiarism_logs');
    }
};
