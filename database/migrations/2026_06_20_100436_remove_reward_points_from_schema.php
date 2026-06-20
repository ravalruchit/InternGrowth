<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('points_transactions');
        Schema::dropIfExists('points_wallets');

        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'reward_points')) {
                $table->dropColumn('reward_points');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('reward_points')->default(0);
        });

        Schema::create('points_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->integer('balance')->default(0);
            $table->timestamps();
        });

        Schema::create('points_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('points_wallet_id')->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->string('type'); // credit, debit
            $table->string('description');
            $table->timestamps();
        });
    }

};
