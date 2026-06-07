<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Submission;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $submissionId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $submission = Submission::with('application')->findOrFail($submissionId);

        Rating::create([
            'task_id' => $submission->application->task_id,
            'student_profile_id' => $submission->application->student_profile_id,
            'startup_profile_id' => auth()->user()->startupProfile->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        // Update student reliability score and multi-dimensional reputation
        $studentProfile = $submission->application->student;
        
        // Sync rating to dynamic portfolio item
        $portfolioService = new \App\Services\PortfolioAutomationService();
        $portfolioService->updateRatingOnPortfolioItem(
            $studentProfile->id, 
            $submission->application->task_id, 
            $validated['rating']
        );

        // Sync rating to skill verification records
        $skillsService = new \App\Services\SkillVerificationService();
        $skillsService->syncTaskRatingToSkills(
            $studentProfile->id, 
            $submission->application->task_id, 
            $validated['rating']
        );

        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($studentProfile->id);

        return back()->with('success', 'Rating submitted successfully');
    }
}
