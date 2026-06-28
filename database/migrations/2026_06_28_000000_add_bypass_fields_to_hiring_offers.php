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
            $table->boolean('flagged_for_bypass')->default(false);
            $table->decimal('bypass_penalty_charged', 10, 2)->default(0.00);
            $table->text('bypass_notes')->nullable();
            $table->timestamp('audited_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hiring_offers', function (Blueprint $table) {
            $table->dropColumn(['flagged_for_bypass', 'bypass_penalty_charged', 'bypass_notes', 'audited_at']);
        });
    }
};
