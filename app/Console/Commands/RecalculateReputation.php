<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentProfile;
use App\Services\ReputationEngineService;

class RecalculateReputation extends Command
{
    protected $signature = 'reputation:recalculate {student_id?}';

    protected $description = 'Recalculate reputation scores for all students or a specific student profile';

    public function handle()
    {
        $studentId = $this->argument('student_id');
        $reputationService = new ReputationEngineService();

        if ($studentId) {
            $student = StudentProfile::find($studentId);
            if (!$student) {
                $this->error("Student profile ID {$studentId} not found.");
                return 1;
            }

            $this->info("Recalculating reputation for student profile ID: {$student->id}...");
            $score = $reputationService->updateReputation($student->id);
            $this->info("Success! New Overall Score: {$score->overall_score}/100");
        } else {
            $students = StudentProfile::all();
            $count = $students->count();

            if ($count === 0) {
                $this->info("No student profiles found in database.");
                return 0;
            }

            $this->info("Recalculating reputation scores for {$count} student(s)...");
            
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            foreach ($students as $student) {
                $reputationService->updateReputation($student->id);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Successfully recalculated reputation scores for all {$count} student(s)!");
        }

        return 0;
    }
}
