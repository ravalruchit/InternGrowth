<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        return $user->isStartup() && $task->startup_profile_id === $user->startupProfile->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->isStartup() && $task->startup_profile_id === $user->startupProfile->id;
    }

    public function apply(User $user, Task $task): bool
    {
        return $user->isStudent();
    }
}
