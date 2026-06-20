<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'type', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTargetUrlAttribute()
    {
        $user = auth()->user();
        if (!$user) {
            return '#';
        }

        // Wallet notifications for both roles
        if (str_contains($this->title, 'Wallet') || str_contains($this->title, 'Payment') || str_contains($this->title, 'Top-up')) {
            return route('wallet.index');
        }

        if ($user->isStartup() && $user->startupProfile) {
            // Notifications for Startup
            if ($this->title === 'New Application Received' || $this->title === 'Work Submitted' || $this->title === 'Submission Revised') {
                if (preg_match('/task "([^"]+)"/', $this->message, $matches)) {
                    $taskTitle = $matches[1];
                    $task = \App\Models\Task::where('title', $taskTitle)
                        ->where('startup_profile_id', $user->startupProfile->id)
                        ->first();
                    if ($task) {
                        return route('tasks.show', $task->id);
                    }
                }
                return route('startup.dashboard');
            }
            
            return route('startup.dashboard');
        } elseif ($user->isStudent() && $user->studentProfile) {
            // Notifications for Student
            $studentProfileId = $user->studentProfile->id;
            
            // 1. Task/Application Status updates
            if (in_array($this->title, ['Application Approved', 'Application Rejected', 'Application Status Updated'])) {
                if (preg_match('/task "([^"]+)"/', $this->message, $matches)) {
                    $taskTitle = $matches[1];
                    $task = \App\Models\Task::where('title', $taskTitle)->first();
                    if ($task) {
                        return route('tasks.show', $task->id);
                    }
                }
                return route('student.dashboard');
            }
            
            // 2. Work Submissions updates (accepted, rejected, revision requested)
            if (in_array($this->title, ['Submission Accepted', 'Submission Rejected', 'Revision Requested'])) {
                $statusMap = [
                    'Submission Accepted' => 'accepted',
                    'Submission Rejected' => 'rejected',
                    'Revision Requested' => 'revision_requested',
                ];
                $status = $statusMap[$this->title] ?? null;
                
                if ($status) {
                    $submission = \App\Models\Submission::whereHas('application', function($q) use ($studentProfileId) {
                        $q->where('student_profile_id', $studentProfileId);
                    })
                    ->where('status', $status)
                    ->latest()
                    ->first();
                    
                    if ($submission && $submission->application) {
                        return route('tasks.show', $submission->application->task_id);
                    }
                }
                return route('student.dashboard');
            }
            
            return route('student.dashboard');
        }

        return '#';
    }
}
