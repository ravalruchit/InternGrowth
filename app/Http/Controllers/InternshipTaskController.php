<?php

namespace App\Http\Controllers;

use App\Models\HiringOffer;
use App\Models\InternshipTask;
use App\Models\InternshipResource;
use App\Models\TaskSubmission;
use App\Services\StreakService;
use Illuminate\Http\Request;

class InternshipTaskController extends Controller
{
    protected StreakService $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    /* ════════════════════════════════════════════════
     *  STARTUP-FACING: Task Management
     * ════════════════════════════════════════════════ */

    /**
     * Create a new task for an intern.
     */
    public function store(Request $request, $offerId)
    {
        $startup = auth()->user()->startupProfile;
        $offer   = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'priority'       => 'required|in:low,medium,high',
            'milestone_name' => 'nullable|string|max:255',
            'due_date'       => 'nullable|date|after_or_equal:today',
        ]);

        InternshipTask::create([
            'hiring_offer_id'    => $offer->id,
            'startup_profile_id' => $startup->id,
            'student_profile_id' => $offer->student_profile_id,
            'title'              => $validated['title'],
            'description'        => $validated['description'] ?? null,
            'priority'           => $validated['priority'],
            'milestone_name'     => $validated['milestone_name'] ?? null,
            'due_date'           => $validated['due_date'] ?? null,
            'status'             => 'pending',
        ]);

        // Notify student
        \App\Models\Notification::create([
            'user_id'    => $offer->student->user_id ?? null,
            'title'      => '🚨 New Task Assigned',
            'message'    => "New task: \"{$validated['title']}\" assigned by {$startup->company_name}.",
            'type'       => 'task_assigned',
            'action_url' => route('student.internships.workspace', $offer->id),
            'is_read'    => false,
        ]);

        return back()->with('success', 'Task assigned successfully.');
    }

    /**
     * Update an existing task.
     */
    public function update(Request $request, $offerId, $taskId)
    {
        $startup = auth()->user()->startupProfile;
        $offer   = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);
        $task    = InternshipTask::where('hiring_offer_id', $offer->id)->findOrFail($taskId);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'priority'       => 'required|in:low,medium,high',
            'milestone_name' => 'nullable|string|max:255',
            'due_date'       => 'nullable|date',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated.');
    }

    /**
     * Delete a task (only if not yet submitted).
     */
    public function destroy($offerId, $taskId)
    {
        $startup = auth()->user()->startupProfile;
        $offer   = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);
        $task    = InternshipTask::where('hiring_offer_id', $offer->id)->findOrFail($taskId);

        if ($task->status !== 'pending') {
            return back()->with('error', 'Cannot delete a task that has already been submitted.');
        }

        $task->delete();
        return back()->with('success', 'Task deleted.');
    }

    /**
     * Approve a task submission.
     * This triggers: streak +1, IPRS award, badge check, notification.
     */
    public function approve(Request $request, $offerId, $taskId)
    {
        $startup = auth()->user()->startupProfile;
        $offer   = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);
        $task    = InternshipTask::where('hiring_offer_id', $offer->id)->findOrFail($taskId);

        if ($task->status !== 'submitted') {
            return back()->with('error', 'Only submitted tasks can be approved.');
        }

        // Mark task as approved
        $task->update(['status' => 'approved']);

        // Mark latest submission as approved
        $submission = $task->latestSubmission;
        if ($submission) {
            $submission->update(['approved_at' => now()]);
        }

        // Trigger all streak/IPRS/badge/notification logic
        $this->streakService->onTaskApproved($task);

        return back()->with('success', "Task \"{$task->title}\" approved! Student streak updated.");
    }

    /**
     * Request changes on a submitted task.
     */
    public function requestChanges(Request $request, $offerId, $taskId)
    {
        $startup = auth()->user()->startupProfile;
        $offer   = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);
        $task    = InternshipTask::where('hiring_offer_id', $offer->id)->findOrFail($taskId);

        $validated = $request->validate([
            'feedback' => 'required|string|max:2000',
        ]);

        if ($task->status !== 'submitted') {
            return back()->with('error', 'Only submitted tasks can have changes requested.');
        }

        // Update task status
        $task->update(['status' => 'needs_changes']);

        // Store feedback on the latest submission
        $submission = $task->latestSubmission;
        if ($submission) {
            $submission->update(['startup_feedback' => $validated['feedback']]);
        }

        // Trigger notification
        $this->streakService->onChangesRequested($task, $validated['feedback']);

        return back()->with('success', "Changes requested for \"{$task->title}\".");
    }

    /* ════════════════════════════════════════════════
     *  STUDENT-FACING: Task Submission
     * ════════════════════════════════════════════════ */

    /**
     * Student submits work for a task.
     */
    public function submit(Request $request, $offerId, $taskId)
    {
        $profile = auth()->user()->studentProfile;
        $offer   = HiringOffer::where('student_profile_id', $profile->id)->findOrFail($offerId);
        $task    = InternshipTask::where('hiring_offer_id', $offer->id)
            ->where('student_profile_id', $profile->id)
            ->findOrFail($taskId);

        if (!in_array($task->status, ['pending', 'needs_changes'])) {
            return back()->with('error', 'This task is not open for submission.');
        }

        $validated = $request->validate([
            'description'      => 'required|string',
            'github_url'       => 'nullable|url',
            'pull_request_url' => 'nullable|url',
            'demo_url'         => 'nullable|url',
        ]);

        TaskSubmission::create([
            'internship_task_id' => $task->id,
            'student_profile_id' => $profile->id,
            'description'        => $validated['description'],
            'github_url'         => $validated['github_url'] ?? null,
            'pull_request_url'   => $validated['pull_request_url'] ?? null,
            'demo_url'           => $validated['demo_url'] ?? null,
            'submitted_at'       => now(),
        ]);

        // Update task status
        $task->update(['status' => 'submitted']);

        // Trigger notification to startup
        $this->streakService->onWorkSubmitted($task);

        return back()->with('success', 'Work submitted! Waiting for startup approval.');
    }

    /* ════════════════════════════════════════════════
     *  STARTUP-FACING: Resource Management
     * ════════════════════════════════════════════════ */

    /**
     * Add a resource link to the internship.
     */
    public function storeResource(Request $request, $offerId)
    {
        $startup = auth()->user()->startupProfile;
        $offer   = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url',
            'type'  => 'required|in:link,document,figma,github,api',
        ]);

        InternshipResource::create([
            'hiring_offer_id'    => $offer->id,
            'uploaded_by_user_id' => auth()->id(),
            'title'              => $validated['title'],
            'url'                => $validated['url'],
            'type'               => $validated['type'],
        ]);

        return back()->with('success', 'Resource added.');
    }

    /**
     * Delete a resource.
     */
    public function destroyResource($offerId, $resourceId)
    {
        $startup  = auth()->user()->startupProfile;
        $offer    = HiringOffer::where('startup_profile_id', $startup->id)->findOrFail($offerId);
        $resource = InternshipResource::where('hiring_offer_id', $offer->id)->findOrFail($resourceId);

        $resource->delete();
        return back()->with('success', 'Resource removed.');
    }
}
