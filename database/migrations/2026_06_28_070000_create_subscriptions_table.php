<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('plan_type', ['student_pro', 'startup_growth', 'enterprise']);
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->enum('status', ['active', 'expired', 'cancelled', 'trial'])->default('active');
            $table->decimal('amount', 8, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
