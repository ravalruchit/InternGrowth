<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hiring_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('source_task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->enum('offer_type', ['internship', 'job']);
            $table->string('title');
            $table->text('description');
            $table->decimal('compensation', 12, 2);
            $table->string('compensation_period')->default('monthly'); // monthly, annual, project-based
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired', 'withdrawn'])->default('pending');
            $table->text('contract_terms')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiring_offers');
    }
};
