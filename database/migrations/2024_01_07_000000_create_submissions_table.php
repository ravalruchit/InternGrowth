<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->json('files')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'revision_requested', 'accepted', 'rejected'])->default('submitted');
            $table->text('feedback')->nullable();
            $table->boolean('is_plagiarized')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
