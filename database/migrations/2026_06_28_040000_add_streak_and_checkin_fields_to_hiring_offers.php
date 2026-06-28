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
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->timestamp('last_checked_in_at')->nullable()->after('internship_score');
            $table->integer('current_streak')->default(0)->after('last_checked_in_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn(['last_checked_in_at', 'current_streak']);
        });
    }
};
