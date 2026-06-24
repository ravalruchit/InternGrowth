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
    /**
     * Compute ranking details for a student applying to a specific task.
     * Note: This engine respects Hidden Bias Protection and ignores college, location, etc.
     *
     * @param StudentProfile $student
     * @param Task $task
     * @return array
     */
    /**
     * Compute Skill Match Score (55% weight component)
     */
    public function calculateSkillMatchScore(StudentProfile $student, Task $task): array
    {
        $taskSkills = $task->skills;
        $taskSkillNames = [];
        if ($taskSkills && $taskSkills->isNotEmpty()) {
            $taskSkillNames = $taskSkills->pluck('name')->toArray();
        } elseif (!empty($task->required_skills)) {
            $taskSkillNames = is_array($task->required_skills) 
                ? $task->required_skills 
                : json_decode($task->required_skills, true);
        }

        $verifiedSkillNames = [];
        if ($student->skillVerifications) {
            $verifiedSkillNames = $student->skillVerifications->map(function($v) {
                return $v->skill ? strtolower(trim($v->skill->name)) : null;
            })->filter()->unique()->toArray();
        }

        $portfolioSkillNames = [];
        if ($student->portfolio && $student->portfolio->items) {
            foreach ($student->portfolio->items as $item) {
                if ($item->hasEvidence() || !empty($item->verification_badge)) {
                    $skills = is_array($item->skills_demonstrated) 
                        ? $item->skills_demonstrated 
                        : json_decode($item->skills_demonstrated ?? '[]', true);
                    if (is_array($skills)) {
                        foreach ($skills as $skill) {
                            $portfolioSkillNames[] = strtolower(trim($skill));
                        }
                    }
                }
            }
        }
        $proofOfWorkSkills = array_unique(array_merge($verifiedSkillNames, $portfolioSkillNames));

        $matchedSkillsCount = 0;
        if (!empty($taskSkillNames)) {
            foreach ($taskSkillNames as $reqSkill) {
                $reqSkillLower = strtolower(trim($reqSkill));
                if (in_array($reqSkillLower, $proofOfWorkSkills)) {
                    $matchedSkillsCount++;
                }
            }
            $percentage = ($matchedSkillsCount / count($taskSkillNames)) * 100;
        } else {
            // Task has no defined skills — we can't verify alignment,
            // so give a neutral baseline instead of a free 100%.
            $percentage = 30;
        }

        $hasProofOfWork = !empty($proofOfWorkSkills);

        return [
            'percentage' => $percentage,
            'matched_count' => $matchedSkillsCount,
            'required_count' => count($taskSkillNames),
            'has_proof_of_work' => $hasProofOfWork,
        ];
    }

    /**
     * Compute Domain Match Score (15% weight component)
     */
    public function calculateDomainMatchScore(StudentProfile $student, Task $task): float
    {
        $domainScore = 30;
        if (!empty($task->domain) && !empty($student->primary_domain)) {
            $taskDom = trim($task->domain);
            $studDom = trim($student->primary_domain);
            if (strtolower($taskDom) === strtolower($studDom)) {
                $domainScore = 100;
            } else {
                $related = [
                    'software development' => ['data & ai', 'ui/ux design'],
                    'data & ai' => ['software development'],
                    'ui/ux design' => ['software development', 'content & business'],
                    'digital marketing' => ['content & business'],
                    'content & business' => ['digital marketing', 'ui/ux design'],
                ];
                $taskDomLower = strtolower($taskDom);
                $studDomLower = strtolower($studDom);
                if (isset($related[$taskDomLower]) && in_array($studDomLower, $related[$taskDomLower])) {
                    $domainScore = 70;
                } else {
                    $domainScore = 30;
                }
            }
        }
        return $domainScore;
    }

    /**
     * Compute Verified Work Score (10% weight component)
     */
    public function calculateVerifiedWorkScore(StudentProfile $student): array
    {
        $completedTasksCount = $student->applications->filter(fn($app) => $app->submission && $app->submission->status === 'accepted')->count();
        $internshipCount = $student->hiringOffers->where('offer_type', 'internship')->where('status', 'accepted')->count();
        $jobCount = $student->hiringOffers->where('offer_type', 'job')->where('status', 'accepted')->count();
        $verifiedPortfolioCount = $student->portfolio ? $student->portfolio->items->whereNotNull('verification_badge')->count() : 0;
        
        $totalCompleted = $completedTasksCount + $internshipCount + $jobCount + $verifiedPortfolioCount;
        if ($totalCompleted === 0) {
            $verifiedWorkScore = 0;
        } elseif ($totalCompleted >= 1 && $totalCompleted <= 3) {
            $verifiedWorkScore = 40;
        } elseif ($totalCompleted >= 4 && $totalCompleted <= 7) {
            $verifiedWorkScore = 70;
        } elseif ($totalCompleted >= 8 && $totalCompleted <= 15) {
            $verifiedWorkScore = 90;
        } else {
            $verifiedWorkScore = 100;
        }
        return [
            'score' => $verifiedWorkScore,
            'completed_tasks_count' => $completedTasksCount,
            'total_completed_items' => $totalCompleted
        ];
    }

    /**
     * Compute Role Match Score (10% weight component)
     */
    public function calculateRoleMatchScore(StudentProfile $student, Task $task): float
    {
        $roleScore = 0;
        if (!empty($task->role) && !empty($student->preferred_role)) {
            if (strtolower(trim($task->role)) === strtolower(trim($student->preferred_role))) {
                $roleScore = 100;
            }
        }
        return $roleScore;
    }

    /**
     * Compute Portfolio Quality Score (5% weight component)
     */
    public function calculatePortfolioQualityScore(StudentProfile $student): float
    {
        return $this->calculatePortfolioStrength($student);
    }

    /**
     * Skill Gate Eligibility Check (Exclusions)
     */
    public function passesSkillGate(int $matchedSkillsCount, float $skillScore, int $requiredSkillsCount): bool
    {
        // Rule 1: Exclude if skillMatch < 20%
        if ($skillScore < 20) {
            return false;
        }
        // Rule 2: Exclude if matchedSkills < 2 (when task requires at least 2 skills)
        if ($requiredSkillsCount >= 2 && $matchedSkillsCount < 2) {
            return false;
        }
        return true;
    }

    /**
     * Compute ranking details for a student applying to a specific task.
     * Note: This engine respects Hidden Bias Protection and ignores college, location, etc.
     *
     * @param StudentProfile $student
     * @param Task $task
     * @return array|null
     */
    public function calculateMatchScore(StudentProfile $student, Task $task, bool $ignoreSkillGate = false): ?array
    {
        // 1. Load relationships if not already loaded (optimized to avoid query duplication)
        $student->loadMissing(['skills', 'reputationScore', 'portfolio.items', 'skillVerifications.skill', 'hiringOffers', 'applications.submission']);

        // 2. Compute Skills Match Score (55%)
        $skillDetails = $this->calculateSkillMatchScore($student, $task);
        $skillScore = $skillDetails['percentage'];
        $matchedCount = $skillDetails['matched_count'];
        $requiredCount = $skillDetails['required_count'];
        $hasProofOfWork = $skillDetails['has_proof_of_work'] ?? false;

        // Enforce Skill Gate Exclusions (Rules 1 & 2)
        $passesGate = $this->passesSkillGate($matchedCount, $skillScore, $requiredCount);
        if (!$ignoreSkillGate && !$passesGate) {
            return null; // Signals complete exclusion
        }

        // 3. Compute Domain Match Score (15%)
        $domainScore = $this->calculateDomainMatchScore($student, $task);

        // 4. Compute Verified Work Score (10%)
        $verifiedWorkDetails = $this->calculateVerifiedWorkScore($student);
        $verifiedWorkScore = $verifiedWorkDetails['score'];

        // 5. Compute Role Match Score (10%)
        $roleScore = $this->calculateRoleMatchScore($student, $task);

        // 6. Compute IPRS Score (5%)
        $iprsValue = $student->iprs_score ?? $student->reputationScore?->overall_score ?? 50.00;
        $iprsScore = min(100.00, floatval($iprsValue));

        // 7. Compute Portfolio Quality Score (5%)
        $portfolioScore = $this->calculatePortfolioQualityScore($student);

        // 8. Calculate Overall Match Score (Total 100%)
        // Skills Match = 55%
        // Domain Match = 15%
        // Verified Work = 10%
        // Role Match = 10%
        // IPRS Score = 5%
        // Portfolio Quality = 5%
        $overallScore = ($skillScore * 0.55) +
                        ($domainScore * 0.15) +
                        ($verifiedWorkScore * 0.10) +
                        ($roleScore * 0.10) +
                        ($iprsScore * 0.05) +
                        ($portfolioScore * 0.05);

        $overallScore = round(max(0, min(100, $overallScore)));

        // Match classification labels (including Rule 3 override)
        $matchLabel = $this->getMatchLabel(intval($overallScore));
        if ($skillScore >= 20 && $skillScore < 30) {
            $matchLabel = 'Low Match'; // Rule 3 Override
        }
        if (!$passesGate) {
            $matchLabel = 'Not Qualified';
        }

        // 9. Generate explanations & insights using helper parameters
        //    Use NEUTRAL defaults for students with no reputation history.
        //    Previously defaulted to 100% which inflated scores for new students.
        $taskSkills = $task->skills;
        $taskCompletion = floatval($student->reputationScore?->completion_rate ?? 70.00);
        $interviewPerformance = floatval($student->reputationScore?->interview_performance_score ?? 60.00);
        $commRating = floatval($student->reputationScore?->communication_rating ?? 3.50);
        $communicationScore = ($commRating <= 0.0 ? 3.50 : $commRating) * 20.0;

        $explanations = $this->generateWhyRecommended($student, $task, $taskSkills, $skillScore, $taskCompletion, $portfolioScore, $interviewPerformance);
        
        // Add domain explanation if it aligns
        if ($domainScore === 100) {
            array_unshift($explanations, "Domain Match: Aligns perfectly with candidate's primary career track");
        }

        $insights = $this->generateInsights($student, $taskSkills, $skillScore, $iprsScore, $taskCompletion, $portfolioScore, $interviewPerformance, $communicationScore);

        // 10. Find Best Evidence Project
        $bestEvidence = $this->findBestEvidence($student);

        return [
            'match_score' => intval($overallScore),
            'match_label' => $matchLabel,
            'passes_gate' => $passesGate,
            'has_proof_of_work' => $hasProofOfWork,
            'ascii_bar' => str_repeat('█', round((intval($overallScore) / 100) * 10)) . str_repeat('░', 10 - round((intval($overallScore) / 100) * 10)),
            'completed_tasks_count' => $verifiedWorkDetails['completed_tasks_count'],
            'top_strength' => $this->determineTopStrength($student),
            'portfolio_rating_label' => $this->getPortfolioLabel($portfolioScore),
            'breakdown' => [
                'skills_match' => round($skillScore),
                'domain_alignment' => round($domainScore),
                'verified_work' => round($verifiedWorkScore),
                'role_alignment' => round($roleScore),
                'iprs' => round($iprsScore),
                'portfolio' => round($portfolioScore),
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
    public function calculatePortfolioStrength(StudentProfile $student): float
    {
        $portfolio = $student->portfolio;
        if (!$portfolio || $portfolio->items->isEmpty()) {
            return 0.00;
        }

        $items = $portfolio->items;

        $hasVerificationBadge = $items->contains(fn($item) => !empty($item->verification_badge));
        $hasTaskId = $items->contains(fn($item) => !empty($item->task_id));

        if ($hasVerificationBadge || $hasTaskId) {
            return 100.00;
        }

        $hasRating = $items->contains(fn($item) => !empty($item->rating_received));
        $hasEvidence = $items->contains(fn($item) => $item->hasEvidence());

        if ($hasRating || $hasEvidence) {
            return 70.00;
        }

        return 40.00;
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
    public function getPortfolioLabel(float $score): string
    {
        if ($score >= 100.0) {
            return 'Verified';
        } elseif ($score >= 70.0) {
            return 'Strong';
        } elseif ($score >= 40.0) {
            return 'Basic';
        } else {
            return 'No Portfolio';
        }
    }

    /**
     * Dynamic match categories mapping
     */
    public function getMatchLabel(int $score): string
    {
        if ($score >= 90) {
            return 'Perfect Match';
        } elseif ($score >= 80) {
            return 'Excellent Match';
        } elseif ($score >= 70) {
            return 'Strong Match';
        } elseif ($score >= 60) {
            return 'Good Match';
        } elseif ($score >= 40) {
            return 'Partial Match';
        } else {
            return 'Low Match';
        }
    }

    /**
     * Determine Top Strength skill
     */
    private function determineTopStrength(StudentProfile $student): string
    {
        $student->loadMissing(['skills', 'skillVerifications.skill']);
        $verifications = $student->skillVerifications;

        if ($verifications->isNotEmpty()) {
            $bestVerification = $verifications->sortByDesc(function($v) {
                return $v->rating ?? ($v->score / 20.0);
            })->first();

            if ($bestVerification && $bestVerification->skill) {
                return $bestVerification->skill->name;
            }
        }

        if ($student->skills->isNotEmpty()) {
            return $student->skills->first()->name;
        }

        return 'General Aptitude';
    }
}
