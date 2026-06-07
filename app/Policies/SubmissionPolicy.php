<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function review(User $user, Submission $submission): bool
    {
        return $user->isStartup() && 
               $submission->application->task->startup_profile_id === $user->startupProfile->id;
    }

    public function submit(User $user): bool
    {
        return $user->isStudent();
    }
}
