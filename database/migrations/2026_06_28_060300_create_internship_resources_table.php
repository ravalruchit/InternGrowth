<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_offer_id')->constrained('hiring_offers')->cascadeOnDelete();
            $table->foreignId('uploaded_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('url');
            $table->enum('type', ['link', 'document', 'figma', 'github', 'api'])->default('link');
            $table->timestamps();

            $table->index('hiring_offer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_resources');
    }
};
