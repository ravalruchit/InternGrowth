<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_onboarded')->default(false)->after('role');
        });

        // Set existing verified users to onboarded
        \App\Models\User::whereNotNull('email_verified_at')->update(['is_onboarded' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_onboarded');
        });
    }
};
