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

        \App\Helpers\ContactDetector::validate($validated['cover_letter'] ?? '', 'cover_letter');

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

    public function closeTask($id)
    {
        $application = \App\Models\Application::with(['student.user', 'task'])->findOrFail($id);

        // Authorization check: Ensure task belongs to startup
        if ($application->task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        $application->update([
            'startup_hiring_outcome' => 'task_only'
        ]);

        // Recalculate scores for both sides
        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($application->student_profile_id);

        $startupReputationService = new \App\Services\StartupReputationService();
        $startupReputationService->updateReputation($application->task->startup_profile_id);

        // Notify student
        \App\Models\Notification::create([
            'user_id' => $application->student->user_id,
            'title' => 'Project Work Completed',
            'message' => "Your project work for \"{$application->task->title}\" has been completed and finalized.",
            'type' => 'success'
        ]);

        return back()->with('success', 'Task successfully closed. The relationship is resolved.');
    }

    public function rejectHiring($id)
    {
        $application = \App\Models\Application::with(['student.user', 'task'])->findOrFail($id);

        // Authorization check
        if ($application->task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized.');
        }

        $application->update([
            'startup_hiring_outcome' => 'task_completed_rejected',
            'status' => 'rejected'
        ]);

        // Recalculate scores
        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($application->student_profile_id);

        $startupReputationService = new \App\Services\StartupReputationService();
        $startupReputationService->updateReputation($application->task->startup_profile_id);

        // Notify student
        \App\Models\Notification::create([
            'user_id' => $application->student->user_id,
            'title' => 'Not Selected for Hiring',
            'message' => "You successfully completed the project for \"{$application->task->title}\" but were not selected for a placement position.",
            'type' => 'info'
        ]);

        return back()->with('success', 'Candidate marked as not selected for hiring.');
    }

    public function rateHiringSuccess(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|string|in:excellent,good,average,poor,terminated'
        ]);

        $application = \App\Models\Application::with(['student.user', 'task'])->findOrFail($id);

        // Authorization check
        if ($application->task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized.');
        }

        $application->update([
            'hiring_success_rating' => $validated['rating'],
            'hiring_success_rated_at' => now()
        ]);

        // Recalculate scores
        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($application->student_profile_id);

        $startupReputationService = new \App\Services\StartupReputationService();
        $startupReputationService->updateReputation($application->task->startup_profile_id);

        return back()->with('success', 'Hiring feedback submitted. Reputation scores updated.');
    }
}
