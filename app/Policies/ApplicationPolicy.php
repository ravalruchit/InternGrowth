<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function approve(User $user, Application $application): bool
    {
        return $user->isStartup() && 
               $application->task->startup_profile_id === $user->startupProfile->id;
    }

    public function view(User $user, Application $application): bool
    {
        return $user->isStudent() && $application->student_profile_id === $user->studentProfile->id ||
               $user->isStartup() && $application->task->startup_profile_id === $user->startupProfile->id;
    }
}
