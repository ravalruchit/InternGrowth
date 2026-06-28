<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->integer('highest_streak')->default(0)->after('current_streak');
            $table->timestamp('last_submission_at')->nullable()->after('highest_streak');
            $table->timestamp('streak_freeze_used_at')->nullable()->after('last_submission_at');
        });
    }

    public function down(): void
    {
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn(['highest_streak', 'last_submission_at', 'streak_freeze_used_at']);
        });
    }
};
