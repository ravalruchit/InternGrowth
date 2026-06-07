<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submission;
use App\Models\Rating;
use App\Services\SkillVerificationService;

class BackfillSkills extends Command
{
    protected $signature = 'skills:backfill';

    protected $description = 'Backfill skill verification entries for all historically accepted submissions';

    public function handle()
    {
        $submissions = Submission::where('status', 'accepted')
            ->with(['application.task.skills', 'application.student'])
            ->get();

        $count = $submissions->count();

        if ($count === 0) {
            $this->info('No accepted submissions found to process skill verifications.');
            return 0;
        }

        $this->info("Found {$count} accepted submission(s). Syncing verified skills...");
        $skillsService = new SkillVerificationService();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($submissions as $submission) {
            $studentId = $submission->application->student_profile_id;
            $task = $submission->application->task;

            if ($task) {
                // Verify all required skills for this task
                foreach ($task->skills as $skill) {
                    $skillsService->verifySkillByTaskCompletion($studentId, $skill->id);
                }

                // If startup has already rated the student for this task, sync the rating score
                $rating = Rating::where('student_profile_id', $studentId)
                    ->where('task_id', $task->id)
                    ->first();

                if ($rating) {
                    $skillsService->syncTaskRatingToSkills($studentId, $task->id, $rating->rating);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully backfilled skill verification records and refreshed reputations!");
        return 0;
    }
}
