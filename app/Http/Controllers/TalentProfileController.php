<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioItem;
use App\Services\SkillVerificationService;
use Illuminate\Http\Request;

class TalentProfileController extends Controller
{
    /**
     * Display the public talent profile.
     */
    public function show(string $slug)
    {
        $portfolio = Portfolio::where('custom_slug', $slug)
            ->where('is_public', true)
            ->with([
                'studentProfile.user',
                'studentProfile.skills',
                'studentProfile.reputationScore',
                'studentProfile.skillVerifications.skill',
                'studentProfile.skillVerifications.startup',
                'studentProfile.hiringOffers.startup',
                'studentProfile.certificates',
                'items' => function ($query) {
                    $query->orderByDesc('completed_at')->orderByDesc('created_at');
                },
                'items.task',
                'items.submission',
            ])
            ->firstOrFail();

        $profile = $portfolio->studentProfile;

        // Compute hiring summary
        $hiringSummary = $portfolio->hiring_summary;

        // Compute startup verification counts per skill
        $startupVerificationCounts = SkillVerificationService::getStartupVerificationCounts($profile->id);

        // Get verified skill IDs and scores
        $verifiedSkills = $profile->skillVerifications->pluck('skill_id')->unique()->toArray();
        $skillScores = $profile->skillVerifications
            ->groupBy('skill_id')
            ->map(function ($verifications) {
                // Use the highest score across all verifications for this skill
                return $verifications->max('score');
            })
            ->toArray();

        // IPRS score
        $score = $profile->reputationScore;

        return view('student.talent-profile', compact(
            'portfolio',
            'profile',
            'hiringSummary',
            'startupVerificationCounts',
            'verifiedSkills',
            'skillScores',
            'score'
        ));
    }

    /**
     * Update project evidence for a portfolio item (student self-service).
     */
    public function updateEvidence(Request $request, int $itemId)
    {
        $validated = $request->validate([
            'github_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
        ]);

        $item = PortfolioItem::with('portfolio')->findOrFail($itemId);

        // Verify ownership
        $portfolio = $item->portfolio;
        if ($portfolio->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        $portfolioService = new \App\Services\PortfolioAutomationService();
        $portfolioService->updateProjectEvidence(
            $itemId,
            $validated['github_url'] ?? null,
            $validated['demo_url'] ?? null
        );

        return back()->with('success', 'Project evidence updated successfully!');
    }
}
