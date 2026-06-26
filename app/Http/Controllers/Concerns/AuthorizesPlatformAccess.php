<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Conversation;
use App\Models\Submission;

trait AuthorizesPlatformAccess
{
    protected function authorizeConversationAccess(Conversation $conversation): void
    {
        $user = auth()->user();

        if ($user->isStudent() && $user->studentProfile && $conversation->student_profile_id === $user->studentProfile->id) {
            return;
        }

        if ($user->isStartup() && $user->startupProfile && $conversation->startup_profile_id === $user->startupProfile->id) {
            return;
        }

        abort(403, 'Unauthorized action.');
    }

    protected function authorizeStartupOwnsSubmission(Submission $submission): void
    {
        $startup = auth()->user()->startupProfile;

        if (!$startup || $submission->application->task->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    protected function authorizeStartupOwnsApplication(\App\Models\Application $application): void
    {
        $startup = auth()->user()->startupProfile;

        if (!$startup || $application->task->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
