<?php

namespace App\Services;

use App\Models\StartupProfile;
use App\Models\StartupTrustScore;

class StartupReputationService
{
    public function updateReputation(int $startupProfileId): StartupTrustScore
    {
        $startup = StartupProfile::with(['reviews', 'tasks.applications.submission', 'hiringOffers'])->findOrFail($startupProfileId);

        $verificationScore = $this->calculateVerificationScore($startup);
        $paymentScore = $this->calculatePaymentScore($startup);
        $studentRatingScore = $this->calculateStudentRatingScore($startup);
        $hiringScore = $this->calculateHiringScore($startup);
        $taskCompletionScore = $this->calculateTaskCompletionScore($startup);

        $overallScore = ($verificationScore * 0.25) +
                         ($paymentScore * 0.25) +
                         ($studentRatingScore * 0.20) +
                         ($hiringScore * 0.15) +
                         ($taskCompletionScore * 0.15);

        // Deduct 5 points per startup no-show
        $noShows = \App\Models\Interview::where('startup_profile_id', $startup->id)
            ->where('status', 'no_show')
            ->where('no_show_by', 'startup')
            ->count();
        $overallScore -= ($noShows * 5.00);

        // Adjust for hiring success ratings (30-day post-hire feedback)
        $hiringSuccessRatings = \App\Models\Application::whereHas('task', function($q) use ($startup) {
            $q->where('startup_profile_id', $startup->id);
        })->whereNotNull('hiring_success_rating')->get();

        foreach ($hiringSuccessRatings as $app) {
            if ($app->hiring_success_rating === 'excellent') {
                $overallScore += 5.00;
            } elseif ($app->hiring_success_rating === 'average') {
                $overallScore -= 5.00;
            } elseif ($app->hiring_success_rating === 'poor') {
                $overallScore -= 15.00;
            } elseif ($app->hiring_success_rating === 'terminated') {
                $overallScore -= 30.00;
            }
        }

        $overallScore = max(0.00, min(100.00, $overallScore));

        // Sync credibility_score to startup_profiles
        $startup->update(['credibility_score' => round($overallScore / 100, 2)]);

        return StartupTrustScore::updateOrCreate(
            ['startup_profile_id' => $startup->id],
            [
                'overall_score' => round($overallScore, 2),
                'payment_score' => round($paymentScore, 2),
                'verification_score' => round($verificationScore, 2),
                'student_rating_score' => round($studentRatingScore, 2),
                'hiring_score' => round($hiringScore, 2),
            ]
        );
    }

    private function calculateVerificationScore($startup): float
    {
        return $startup->is_verified ? 100.00 : 0.00;
    }

    private function calculatePaymentScore($startup): float
    {
        // 100 base score. Decays if negative wallet balance.
        if ($startup->wallet_balance < 0) {
            return 50.00;
        }
        return 100.00;
    }

    private function calculateStudentRatingScore($startup): float
    {
        $avgRating = $startup->reviews()->avg('rating');
        if ($avgRating === null) {
            return 70.00; // Neutral score if no reviews exist
        }
        return $avgRating * 20.00; // Scale from 1-5 to 20-100
    }

    private function calculateHiringScore($startup): float
    {
        // Hiring offer acceptance rate.
        $totalRespondedOffers = $startup->hiringOffers()
            ->whereIn('status', ['accepted', 'rejected'])
            ->count();

        if ($totalRespondedOffers === 0) {
            return 100.00; // default full points
        }

        $acceptedOffersCount = $startup->hiringOffers()
            ->where('status', 'accepted')
            ->count();

        return ($acceptedOffersCount / $totalRespondedOffers) * 100;
    }

    private function calculateTaskCompletionScore($startup): float
    {
        $totalTasks = $startup->tasks()->count();
        if ($totalTasks === 0) {
            return 100.00; // default full points
        }

        $completedTasksCount = $startup->tasks()->where(function($q) {
            $q->where('status', 'completed')
              ->orWhereHas('applications.submission', function($subQ) {
                  $subQ->where('status', 'accepted');
              });
        })->count();

        return ($completedTasksCount / $totalTasks) * 100;
    }
}
