<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\StartupReview;
use App\Services\StartupReputationService;
use Illuminate\Http\Request;

class StartupReviewController extends Controller
{
    public function store(Request $request, $taskId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:5|max:1000',
        ]);

        $student = auth()->user()->studentProfile;
        if (!$student) {
            abort(403, 'Only students can review startups.');
        }

        $task = Task::findOrFail($taskId);

        // Find the application for this student
        $application = $task->applications()
            ->where('student_profile_id', $student->id)
            ->where('status', 'approved')
            ->first();

        if (!$application) {
            return back()->with('error', 'You cannot review this startup because you did not work on this task.');
        }

        // Ensure the task submission is accepted or marked as completed
        $isCompleted = ($application->submission && $application->submission->status === 'accepted') 
            || $task->status === 'completed';

        if (!$isCompleted) {
            return back()->with('error', 'You can only review the startup after the task is completed and approved.');
        }

        // Check if already reviewed
        $alreadyReviewed = StartupReview::where('task_id', $task->id)
            ->where('student_profile_id', $student->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already submitted a review for this startup on this task.');
        }

        // Create the review
        StartupReview::create([
            'task_id' => $task->id,
            'student_profile_id' => $student->id,
            'startup_profile_id' => $task->startup_profile_id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

        // Recalculate startup trust score
        $reputationService = new StartupReputationService();
        $reputationService->updateReputation($task->startup_profile_id);

        return back()->with('success', 'Thank you! Your review for the startup has been submitted.');
    }
}
