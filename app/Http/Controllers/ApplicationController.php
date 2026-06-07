<?php

namespace App\Http\Controllers;

use App\Repositories\ApplicationRepository;
use Illuminate\Http\Request;
use App\Models\Notification;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationRepository $repository) {}

    public function store(Request $request, $taskId)
    {
        $validated = $request->validate([
            'cover_letter' => 'nullable|string',
        ]);

        $validated['task_id'] = $taskId;
        $validated['student_profile_id'] = auth()->user()->studentProfile->id;

        $application = $this->repository->create($validated);

        // Notify the startup that a student applied
        $task = \App\Models\Task::with('startup.user')->find($taskId);
        if ($task && $task->startup && $task->startup->user) {
            \App\Models\Notification::create([
                'user_id' => $task->startup->user_id,
                'title'   => 'New Application Received',
                'message' => auth()->user()->name . ' applied for your task "' . $task->title . '".',
                'type'    => 'info',
            ]);
        }

        return redirect()->route('tasks.show', $taskId)->with('success', 'Application submitted');
    }

    public function approve($id)
    {
        $application = $this->repository->updateStatus($id, 'approved');
        
        Notification::create([
            'user_id' => $application->student->user_id,
            'title' => 'Application Approved',
            'message' => 'Your application has been approved',
            'type' => 'success'
        ]);

        return back()->with('success', 'Application approved');
    }

    public function reject($id)
    {
        $application = $this->repository->updateStatus($id, 'rejected');
        
        Notification::create([
            'user_id' => $application->student->user_id,
            'title' => 'Application Rejected',
            'message' => 'Your application has been rejected',
            'type' => 'info'
        ]);

        return back()->with('success', 'Application rejected');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:applied,shortlisted,approved,interview,rejected,internship_offered,internship_accepted,hired'
        ]);

        $application = \App\Models\Application::with(['student.user', 'task'])->findOrFail($id);

        // Authorization check: Ensure task belongs to startup
        if ($application->task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        $application->update(['status' => $validated['status']]);

        // Notify student of status change
        $statusLabels = [
            'applied' => 'Task Applicant',
            'shortlisted' => 'Shortlisted',
            'approved' => 'Approved to Start Task',
            'interview' => 'Interview Scheduled',
            'rejected' => 'Rejected',
            'internship_offered' => 'Internship Offered',
            'internship_accepted' => 'Internship Accepted',
            'hired' => 'Hired'
        ];

        $statusLabel = $statusLabels[$validated['status']] ?? $validated['status'];

        Notification::create([
            'user_id' => $application->student->user_id,
            'title' => 'Application Status Updated',
            'message' => "Your application status for task \"{$application->task->title}\" was updated to: {$statusLabel}",
            'type' => in_array($validated['status'], ['approved', 'internship_offered', 'hired']) ? 'success' : 'info'
        ]);

        return back()->with('success', "Application status updated to {$statusLabel}");
    }
}
