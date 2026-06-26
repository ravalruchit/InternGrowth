<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->dropForeign(['task_id']);
            });
        } catch (\Exception $e) {
            // Already dropped
        }

        DB::table('portfolio_items')
            ->whereNotNull('task_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tasks')
                    ->whereColumn('tasks.id', 'portfolio_items.task_id');
            })
            ->update(['task_id' => null]);

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id')->nullable()->change();
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->nullOnDelete();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->dropForeign(['task_id']);
            });
        } catch (\Exception $e) {
            // Already dropped
        }

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id')->nullable(false)->change();
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->restrictOnDelete();
        });
    }
};

