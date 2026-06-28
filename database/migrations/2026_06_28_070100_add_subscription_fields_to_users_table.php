<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('subscription_status')->default('inactive')->after('is_verified');
            $table->string('subscription_plan')->default('free')->after('subscription_status');
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_plan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_status', 'subscription_plan', 'trial_ends_at']);
        });
    }
};
