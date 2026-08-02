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
        Schema::table('reputation_scores', function (Blueprint $table) {
            $table->decimal('bonus_points', 5, 2)->default(0.00)->after('overall_score');
        });

        // Backfill data
        try {
            $scores = Illuminate\Support\Facades\DB::table('reputation_scores')->get();
            $engine = resolve(\App\Services\ReputationEngineService::class);
            
            foreach ($scores as $score) {
                $originalScore = (float) $score->overall_score;
                
                // Recalculate base score
                $engine->updateReputation($score->student_profile_id);
                
                // Get the updated base score
                $baseScore = (float) Illuminate\Support\Facades\DB::table('reputation_scores')
                    ->where('id', $score->id)
                    ->value('overall_score');
                
                $bonus = max(0.0, $originalScore - $baseScore);
                $bonus = round($bonus, 2);
                $newOverall = min(100.00, $baseScore + $bonus);
                
                Illuminate\Support\Facades\DB::table('reputation_scores')
                    ->where('id', $score->id)
                    ->update([
                        'bonus_points' => $bonus,
                        'overall_score' => $newOverall
                    ]);
                    
                // Also update the student profile reliability score
                Illuminate\Support\Facades\DB::table('student_profiles')
                    ->where('id', $score->student_profile_id)
                    ->update([
                        'reliability_score' => round($newOverall / 100, 2)
                    ]);
            }
        } catch (\Exception $e) {
            Illuminate\Support\Facades\Log::warning("Could not backfill reputation score bonuses: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reputation_scores', function (Blueprint $table) {
            $table->dropColumn('bonus_points');
        });
    }
};
