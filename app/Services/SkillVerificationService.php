<?php

namespace App\Services;

use App\Models\SkillVerification;
use App\Models\Task;
use App\Models\StudentProfile;
use App\Services\ReputationEngineService;

class SkillVerificationService
{
    private $reputationService;

    public function __construct()
    {
        $this->reputationService = new ReputationEngineService();
    }

    /**
     * Verify a skill by task completion.
     * Now tracks which startup verified the skill, allowing multiple startup verifications.
     */
    public function verifySkillByTaskCompletion(int $studentProfileId, int $skillId, ?int $score = null, ?int $startupProfileId = null, ?int $taskId = null): SkillVerification
    {
        $verification = SkillVerification::updateOrCreate(
            [
                'student_profile_id' => $studentProfileId,
                'skill_id' => $skillId,
                'verification_method' => 'task_completion',
                'startup_profile_id' => $startupProfileId,
                'task_id' => $taskId,
            ],
            [
                'score' => $score,
                'verification_type' => 'task',
                'verified_at' => now()
            ]
        );

        $this->reputationService->updateReputation($studentProfileId);

        return $verification;
    }

    /**
     * Bulk-create/update skill verifications from a startup's review form.
     * Called when a startup verifies skills after accepting a submission.
     *
     * @param int $studentProfileId
     * @param int $startupProfileId
     * @param int $taskId
     * @param array $skillsData — array of ['skill_id' => int, 'rating' => int(1-5), 'notes' => string|null]
     * @param string $verificationType — 'task', 'internship', 'job'
     */
    public function verifySkillsFromStartupReview(
        int $studentProfileId,
        int $startupProfileId,
        int $taskId,
        array $skillsData,
        string $verificationType = 'task'
    ): array {
        $verifications = [];

        foreach ($skillsData as $skillEntry) {
            $skillId = $skillEntry['skill_id'];
            $rating = $skillEntry['rating'] ?? null;
            $notes = $skillEntry['notes'] ?? null;

            $verification = SkillVerification::updateOrCreate(
                [
                    'student_profile_id' => $studentProfileId,
                    'skill_id' => $skillId,
                    'startup_profile_id' => $startupProfileId,
                    'task_id' => $taskId,
                ],
                [
                    'verification_method' => 'task_completion',
                    'score' => $rating ? ($rating * 20) : null, // scale 1-5 to 20-100
                    'rating' => $rating,
                    'verification_type' => $verificationType,
                    'notes' => $notes,
                    'verified_at' => now(),
                ]
            );

            $verifications[] = $verification;
        }

        $this->reputationService->updateReputation($studentProfileId);

        return $verifications;
    }

    /**
     * Get a structured summary of verified skills for a student.
     * Returns per-skill: name, verification_count, avg_rating, startup_names, badge_level.
     */
    public static function getVerifiedSkillsSummary(int $studentProfileId): array
    {
        $verifications = SkillVerification::where('student_profile_id', $studentProfileId)
            ->whereNotNull('startup_profile_id')
            ->with(['skill', 'startup'])
            ->get();

        $grouped = [];
        foreach ($verifications as $v) {
            $skillId = $v->skill_id;
            if (!isset($grouped[$skillId])) {
                $grouped[$skillId] = [
                    'skill_id' => $skillId,
                    'skill_name' => $v->skill->name ?? 'Unknown',
                    'verifications' => [],
                    'startup_ids' => [],
                ];
            }
            $grouped[$skillId]['verifications'][] = $v;
            if (!in_array($v->startup_profile_id, $grouped[$skillId]['startup_ids'])) {
                $grouped[$skillId]['startup_ids'][] = $v->startup_profile_id;
            }
        }

        $summary = [];
        foreach ($grouped as $skillId => $data) {
            $ratings = collect($data['verifications'])->pluck('rating')->filter()->values();
            $startupNames = collect($data['verifications'])
                ->pluck('startup')
                ->filter()
                ->unique('id')
                ->pluck('company_name')
                ->values()
                ->toArray();

            $verificationCount = count($data['startup_ids']);
            $avgRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : null;

            // Badge level based on verification count
            $badgeLevel = 'verified';
            if ($verificationCount >= 5) {
                $badgeLevel = 'expert';
            } elseif ($verificationCount >= 3) {
                $badgeLevel = 'proficient';
            }

            $summary[] = [
                'skill_id' => $skillId,
                'skill_name' => $data['skill_name'],
                'verification_count' => $verificationCount,
                'avg_rating' => $avgRating,
                'startup_names' => $startupNames,
                'badge_level' => $badgeLevel,
            ];
        }

        // Sort by verification count descending
        usort($summary, fn($a, $b) => $b['verification_count'] <=> $a['verification_count']);

        return $summary;
    }

    public function verifySkillByAIAssessment(int $studentProfileId, int $skillId, int $score): SkillVerification
    {
        $verification = SkillVerification::updateOrCreate(
            [
                'student_profile_id' => $studentProfileId,
                'skill_id' => $skillId,
                'verification_method' => 'ai_assessment'
            ],
            [
                'score' => $score,
                'verified_at' => now()
            ]
        );

        $this->reputationService->updateReputation($studentProfileId);

        return $verification;
    }

    public function verifySkillByAdmin(int $studentProfileId, int $skillId, ?int $score = null): SkillVerification
    {
        $verification = SkillVerification::updateOrCreate(
            [
                'student_profile_id' => $studentProfileId,
                'skill_id' => $skillId,
                'verification_method' => 'admin_approved'
            ],
            [
                'score' => $score,
                'verified_at' => now()
            ]
        );

        $this->reputationService->updateReputation($studentProfileId);

        return $verification;
    }

    public function syncTaskRatingToSkills(int $studentProfileId, int $taskId, int $rating): void
    {
        $task = Task::with('skills')->find($taskId);
        if (!$task) {
            return;
        }

        $score = $rating * 20; // Scale 1-5 to 20-100

        // Update all task_completion verifications for this student's skills (across all startups)
        foreach ($task->skills as $skill) {
            SkillVerification::where('student_profile_id', $studentProfileId)
                ->where('skill_id', $skill->id)
                ->where('verification_method', 'task_completion')
                ->update(['score' => $score]);
        }

        $this->reputationService->updateReputation($studentProfileId);
    }

    /**
     * Get the count of unique startups that verified a specific skill for a student.
     */
    public function getStartupVerificationCount(int $studentProfileId, int $skillId): int
    {
        return SkillVerification::where('student_profile_id', $studentProfileId)
            ->where('skill_id', $skillId)
            ->where('verification_method', 'task_completion')
            ->whereNotNull('startup_profile_id')
            ->distinct('startup_profile_id')
            ->count('startup_profile_id');
    }

    /**
     * Get startup verification counts for all skills of a student.
     * Returns array keyed by skill_id => count.
     */
    public static function getStartupVerificationCounts(int $studentProfileId): array
    {
        return SkillVerification::where('student_profile_id', $studentProfileId)
            ->where('verification_method', 'task_completion')
            ->whereNotNull('startup_profile_id')
            ->selectRaw('skill_id, COUNT(DISTINCT startup_profile_id) as startup_count')
            ->groupBy('skill_id')
            ->pluck('startup_count', 'skill_id')
            ->toArray();
    }
}
