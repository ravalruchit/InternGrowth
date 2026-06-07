<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->boolean('files_downloaded')->default(false)->after('status');
            $table->timestamp('downloaded_at')->nullable()->after('files_downloaded');
            $table->boolean('payment_locked')->default(false)->after('downloaded_at');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['files_downloaded', 'downloaded_at', 'payment_locked']);
        });
    }
};
