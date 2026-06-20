<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\Task;
use App\Models\SkillVerification;
use App\Models\PlagiarismLog;

class CandidateRankingService
{
    /**
     * Compute ranking details for a student applying to a specific task.
     * Note: This engine respects Hidden Bias Protection and ignores college, location, etc.
     *
     * @param StudentProfile $student
     * @param Task $task
     * @return array
     */
    public function calculateMatchScore(StudentProfile $student, Task $task): array
    {
        // 1. Load relationships if not already loaded (optimized to avoid query duplication)
        $student->loadMissing(['skills', 'reputationScore', 'portfolio.items', 'skillVerifications.skill']);

        // 2. Compute Verified Skills Match (25%)
        $verifiedSkillsMatch = $this->calculateVerifiedSkillsMatch($student, $task, $taskSkills);

        // 2.5 Compute Domain Alignment Score (15%)
        $domainAlignmentScore = 0.00;
        if (!empty($task->domain) && !empty($student->primary_domain)) {
            if ($task->domain === $student->primary_domain) {
                $domainAlignmentScore += 60.00;
            }
        }
        if (!empty($task->role) && !empty($student->preferred_role)) {
            if ($task->role === $student->preferred_role) {
                $domainAlignmentScore += 40.00;
            }
        }

        // 3. Compute IPRS Score (15%)
        $iprsScore = floatval($student->reputationScore?->overall_score ?? 50.00);

        // 4. Compute Task Completion (15%)
        $taskCompletion = floatval($student->reputationScore?->completion_rate ?? 100.00);

        // 5. Compute Portfolio Strength (15%)
        $portfolioStrength = $this->calculatePortfolioStrength($student);

        // 6. Compute Interview Performance (5%)
        $interviewPerformance = floatval($student->reputationScore?->interview_performance_score ?? 100.00);

        // 7. Compute Communication (5%)
        $commRating = floatval($student->reputationScore?->communication_rating ?? 4.80);
        if ($commRating <= 0.0) {
            $commRating = 4.80;
        }
        $communicationScore = $commRating * 20.0;

        // 8. Compute Potential Score (5%)
        $potentialScore = $this->calculatePotentialScore($student);

        // 9. Calculate Overall Match Score (Total 100%)
        $overallScore = ($verifiedSkillsMatch * 0.25) +
                        ($domainAlignmentScore * 0.15) +
                        ($iprsScore * 0.15) +
                        ($taskCompletion * 0.15) +
                        ($portfolioStrength * 0.15) +
                        ($interviewPerformance * 0.05) +
                        ($communicationScore * 0.05) +
                        ($potentialScore * 0.05);

        $overallScore = round(max(0, min(100, $overallScore)));

        // 10. Generate "Why Recommended" Explanations
        $explanations = $this->generateWhyRecommended($student, $task, $taskSkills, $verifiedSkillsMatch, $taskCompletion, $portfolioStrength, $interviewPerformance);
        
        // Add domain explanation if it aligns
        if ($domainAlignmentScore > 0) {
            if ($domainAlignmentScore === 100) {
                array_unshift($explanations, "Perfect fit: Both Domain and Role align with this candidate's career track");
            } elseif ($domainAlignmentScore === 60) {
                array_unshift($explanations, "Domain Match: Aligns with candidate's primary career domain");
            }
        }

        // 11. Generate Candidate Insights (Strengths, Potential Risks, Suggested Interview Questions)
        $insights = $this->generateInsights($student, $taskSkills, $verifiedSkillsMatch, $iprsScore, $taskCompletion, $portfolioStrength, $interviewPerformance, $communicationScore);

        // 12. Find Best Evidence Project
        $bestEvidence = $this->findBestEvidence($student);

        return [
            'match_score' => intval($overallScore),
            'portfolio_rating_label' => $this->getPortfolioLabel($portfolioStrength),
            'breakdown' => [
                'skills_match' => round($verifiedSkillsMatch),
                'domain_alignment' => round($domainAlignmentScore),
                'iprs' => round($iprsScore),
                'reliability' => round($taskCompletion),
                'portfolio' => round($portfolioStrength),
                'interview' => round($interviewPerformance),
                'communication' => round($communicationScore),
                'potential' => round($potentialScore),
            ],
            'explanations' => $explanations,
            'insights' => $insights,
            'best_evidence' => $bestEvidence,
        ];
    }

    /**
     * Compute Verified Skills Match score (0-100)
     */
    private function calculateVerifiedSkillsMatch(StudentProfile $student, Task $task, &$taskSkills): float
    {
        $taskSkills = $task->skills;
        if ($taskSkills->isEmpty()) {
            return 100.00;
        }

        $studentVerifications = $student->skillVerifications;
        $studentSkills = $student->skills;
        $scores = [];

        foreach ($taskSkills as $requiredSkill) {
            $verificationsForSkill = $studentVerifications->where('skill_id', $requiredSkill->id);

            if ($verificationsForSkill->isNotEmpty()) {
                // Average scores
                $ratings = $verificationsForSkill->pluck('rating')->filter()->values();
                if ($ratings->isNotEmpty()) {
                    $scores[] = $ratings->avg() * 20.0; // scale 1-5 to 20-100
                } else {
                    $scoresForSkill = $verificationsForSkill->pluck('score')->filter()->values();
                    if ($scoresForSkill->isNotEmpty()) {
                        $scores[] = floatval($scoresForSkill->avg());
                    } else {
                        $scores[] = 80.00; // default for verified skill with no explicit score/rating
                    }
                }
            } else {
                // If not verified, but student lists it in their profile
                $hasDeclared = $studentSkills->contains('id', $requiredSkill->id);
                $scores[] = $hasDeclared ? 20.00 : 0.00;
            }
        }

        return count($scores) > 0 ? floatval(array_sum($scores) / count($scores)) : 100.00;
    }

    /**
     * Compute Portfolio Strength score (0-100)
     */
    private function calculatePortfolioStrength(StudentProfile $student): float
    {
        $portfolio = $student->portfolio;
        if (!$portfolio || $portfolio->items->isEmpty()) {
            return 0.00;
        }

        $items = $portfolio->items;
        $itemScores = [];

        foreach ($items as $item) {
            $itemScore = 50.00; // Base points for having a project

            // Rating points (max 50)
            if ($item->rating_received !== null) {
                $itemScore += floatval($item->rating_received) * 10.0;
            } else {
                $itemScore += 40.00; // Default 4 stars if not rated
            }

            // Evidence bonus (+10)
            if ($item->hasEvidence()) {
                $itemScore += 10.00;
            }

            // Badge bonus (+10)
            if (!empty($item->verification_badge)) {
                $itemScore += 10.00;
            }

            $itemScores[] = min(100.00, $itemScore);
        }

        $avgItemScore = count($itemScores) > 0 ? (array_sum($itemScores) / count($itemScores)) : 0.00;
        $quantityFactor = min(1.0, count($items) / 3.0);

        return $avgItemScore * (0.7 + 0.3 * $quantityFactor);
    }

    /**
     * Compute Potential Score (0-100) to boost new talent
     */
    private function calculatePotentialScore(StudentProfile $student): float
    {
        // 1. Profile Completion (25%)
        $completionPoints = 0;
        if (!empty($student->bio)) $completionPoints += 20;
        if ($student->portfolio_links && count($student->portfolio_links) > 0) $completionPoints += 20;
        if (!empty($student->college_name) || !empty($student->college_email)) $completionPoints += 20;
        if (!empty($student->availability)) $completionPoints += 20;
        if ($student->skills->isNotEmpty()) $completionPoints += 20;

        // 2. Verified Skills Count (25%)
        $verifiedCount = $student->skillVerifications->unique('skill_id')->count();
        $verifiedSkillsScore = min(100.00, $verifiedCount * 25.00);

        // 3. College/Personal Projects (25%)
        $collegeProjectsCount = $student->portfolio ? $student->portfolio->items->whereNull('task_id')->count() : 0;
        $collegeProjectsScore = min(100.00, $collegeProjectsCount * 33.33);

        // 4. Assessment Arena (25%)
        $assessments = $student->skillVerifications->where('verification_method', 'ai_assessment');
        $assessmentScore = $assessments->isNotEmpty() ? floatval($assessments->avg('score')) : 75.00;

        return ($completionPoints * 0.25) +
               ($verifiedSkillsScore * 0.25) +
               ($collegeProjectsScore * 0.25) +
               ($assessmentScore * 0.25);
    }

    /**
     * Generate "Why Recommended" bullet list
     */
    private function generateWhyRecommended(
        StudentProfile $student,
        Task $task,
        $taskSkills,
        float $verifiedSkillsMatch,
        float $taskCompletion,
        float $portfolioStrength,
        float $interviewPerformance
    ): array {
        $explanations = [];

        // Check required skills
        if ($taskSkills && $taskSkills->isNotEmpty()) {
            foreach ($taskSkills as $skill) {
                $verifications = $student->skillVerifications
                    ->where('skill_id', $skill->id)
                    ->where('verification_method', 'task_completion')
                    ->filter(fn($v) => !empty($v->startup_profile_id));
                $verCount = $verifications->unique('startup_profile_id')->count();

                if ($verCount > 0) {
                    $explanations[] = "{$skill->name} verified by {$verCount} startup" . ($verCount > 1 ? "s" : "");
                }
            }
        }

        // Completion rate
        if ($taskCompletion >= 90.0) {
            $explanations[] = "Excellent reliability with a " . round($taskCompletion) . "% completion rate";
        }

        // Portfolio Strength
        if ($portfolioStrength >= 70.0) {
            $projCount = $student->portfolio ? $student->portfolio->items->count() : 0;
            $explanations[] = "Strong portfolio evidence with {$projCount} project" . ($projCount > 1 ? "s" : "");
        }

        // Interview Performance
        if ($interviewPerformance >= 80.0 && $student->reputationScore && $student->reputationScore->interviews_attended > 0) {
            $explanations[] = "Outstanding interview performance (" . round($interviewPerformance) . "% score)";
        }

        // If no explanations gathered, provide basic recommendation statements
        if (empty($explanations)) {
            if ($student->is_verified) {
                $explanations[] = "Verified student profile with completed verification check";
            }
            if ($student->skills->isNotEmpty()) {
                $explanations[] = "Has relevant technical skills: " . implode(', ', $student->skills->take(3)->pluck('name')->toArray());
            }
        }

        return $explanations;
    }

    /**
     * Generate Strengths, Potential Risks, and Suggested Interview Questions
     */
    private function generateInsights(
        StudentProfile $student,
        $taskSkills,
        float $verifiedSkillsMatch,
        float $iprsScore,
        float $taskCompletion,
        float $portfolioStrength,
        float $interviewPerformance,
        float $communicationScore
    ): array {
        $strengths = [];
        $risks = [];
        $questions = [];

        // Strengths
        if ($verifiedSkillsMatch >= 80.0) {
            $strengths[] = "Strong match for required skills (average score of " . round($verifiedSkillsMatch) . "%).";
        }
        if ($iprsScore >= 80.0) {
            $strengths[] = "Excellent overall partner reputation (IPRS: " . round($iprsScore) . "%).";
        }
        if ($taskCompletion >= 90.0) {
            $strengths[] = "High reliability in completing tasks (" . round($taskCompletion) . "% completion rate).";
        }
        if ($portfolioStrength >= 70.0) {
            $strengths[] = "Robust portfolio with multiple project entries and verified evidence.";
        }
        if ($interviewPerformance >= 85.0) {
            $strengths[] = "Outstanding interview history (" . round($interviewPerformance) . "% score).";
        }
        if ($communicationScore >= 90.0) {
            $strengths[] = "Highly rated communication skills.";
        }
        if (empty($strengths)) {
            $strengths[] = "Solid profile layout with self-declared skills.";
        }

        // Risks
        if ($verifiedSkillsMatch < 40.0) {
            $risks[] = "Missing verified evidence for some of the required skills.";
        }
        if ($iprsScore < 60.0) {
            $risks[] = "Overall reputation score is below average (IPRS: " . round($iprsScore) . "%).";
        }
        if ($taskCompletion < 75.0) {
            $risks[] = "Relatively low task completion rate (" . round($taskCompletion) . "%).";
        }
        if ($portfolioStrength < 30.0) {
            $risks[] = "Limited project portfolio with few verified details.";
        }
        if ($student->reputationScore && $student->reputationScore->no_shows > 0) {
            $risks[] = "Candidate has recorded no-show(s) for scheduled interviews.";
        }

        // Check for plagiarism logs
        $plagiarismCount = PlagiarismLog::whereHas('submission.application', function($q) use ($student) {
            $q->where('student_profile_id', $student->id);
        })->where('status', 'flagged')->count();

        if ($plagiarismCount > 0) {
            $risks[] = "Warning: Past plagiarism flags detected ({$plagiarismCount} time(s)).";
        }

        if (empty($risks)) {
            $risks[] = "No critical risk indicators detected.";
        }

        // Suggested Interview Questions
        $requiredSkillNames = $taskSkills ? $taskSkills->pluck('name')->toArray() : [];
        if ($verifiedSkillsMatch < 70.0 && !empty($requiredSkillNames)) {
            $questions[] = "Can you walk us through a project where you applied " . implode(', ', array_slice($requiredSkillNames, 0, 3)) . "?";
            $questions[] = "How do you keep your technical skills updated in these areas?";
        }
        if ($taskCompletion < 80.0) {
            $questions[] = "Tell us about a time when you faced challenges completing a task on time. How did you handle it?";
            $questions[] = "What strategies do you use to manage your time and meet deadlines?";
        }
        if ($portfolioStrength < 40.0) {
            $questions[] = "Do you have any personal projects or side work not shown on your profile that you're proud of?";
            $questions[] = "Can you describe a complex technical challenge you solved recently?";
        }
        if ($interviewPerformance < 75.0) {
            $questions[] = "What is your approach to preparing for technical interviews?";
        }

        // Add default/fallback questions if list is thin
        if (count($questions) < 2) {
            $questions[] = "What motivated you to apply for this specific task?";
            $questions[] = "How do you plan to approach the key requirements of this role?";
        }

        return [
            'strengths' => $strengths,
            'risks' => $risks,
            'interview_questions' => $questions,
        ];
    }

    /**
     * Find Best Evidence project
     */
    private function findBestEvidence(StudentProfile $student): ?array
    {
        $portfolio = $student->portfolio;
        if (!$portfolio || $portfolio->items->isEmpty()) {
            return null;
        }

        // Sort items by rating received (desc), then is_featured (desc), then completed_at (desc)
        $bestItem = $portfolio->items->sortByDesc(function($item) {
            return [
                $item->rating_received ?? 0,
                $item->is_featured ? 1 : 0,
                $item->completed_at ? $item->completed_at->timestamp : 0
            ];
        })->first();

        if (!$bestItem) {
            return null;
        }

        return [
            'project_title' => $bestItem->project_title,
            'rating_received' => $bestItem->rating_received ? number_format($bestItem->rating_received, 1) : null,
            'skills_demonstrated' => is_array($bestItem->skills_demonstrated) 
                ? $bestItem->skills_demonstrated 
                : json_decode($bestItem->skills_demonstrated ?? '[]', true),
        ];
    }

    /**
     * Qualitative portfolio strength mapping
     */
    private function getPortfolioLabel(float $score): string
    {
        if ($score >= 75.0) {
            return 'Strong';
        } elseif ($score >= 45.0) {
            return 'Moderate';
        } else {
            return 'Basic';
        }
    }
}
