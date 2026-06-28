<?php

namespace App\Services;

use App\Models\HiringOffer;
use App\Models\InternshipTask;
use App\Models\Notification;
use App\Models\ReputationScore;
use App\Models\StudentBadge;

class StreakService
{
    /**
     * IPRS point values for each event type.
     */
    private const IPRS = [
        'task_approved'          => 10,
        'task_high_priority'     => 20,
        'milestone_complete'     => 50,
        'streak_30_day'          => 100,
        'internship_complete'    => 250,
        'full_time_conversion'   => 500,
    ];

    /**
     * Called when a startup approves a task submission.
     */
    public function onTaskApproved(InternshipTask $task): void
    {
        $offer   = $task->hiringOffer;
        $student = $task->student;

        // ── Update streak ──
        $offer->increment('current_streak');
        if ($offer->current_streak > ($offer->highest_streak ?? 0)) {
            $offer->highest_streak = $offer->current_streak;
        }
        $offer->last_submission_at = now();
        $offer->save();

        // ── Award IPRS ──
        $points = $task->priority === 'high'
            ? self::IPRS['task_high_priority']
            : self::IPRS['task_approved'];
        $this->awardIPRS($offer->student_profile_id, $points);

        // ── Check milestone bonus ──
        $this->checkMilestoneBonus($task);

        // ── Check 30-day streak bonus ──
        if ($offer->current_streak === 30) {
            $this->awardIPRS($offer->student_profile_id, self::IPRS['streak_30_day']);
            $this->notify(
                $offer->student->user_id ?? null,
                '🔥 30-Day Streak!',
                'Incredible! You hit a 30-day approved task streak. +100 IPRS earned.',
                'achievement',
                route('student.internships.workspace', $offer->id)
            );
        }

        // ── Check badge eligibility ──
        $this->checkBadges($offer);

        // ── Notify student ──
        $this->notify(
            $offer->student->user_id ?? null,
            '🎉 Task Approved',
            "Your task \"{$task->title}\" was approved! Streak is now {$offer->current_streak} days. +{$points} IPRS.",
            'task_approved',
            route('student.internships.workspace', $offer->id)
        );
    }

    /**
     * Called when a startup requests changes on a submission.
     */
    public function onChangesRequested(InternshipTask $task, string $feedback): void
    {
        $offer = $task->hiringOffer;

        $this->notify(
            $offer->student->user_id ?? null,
            '❌ Changes Requested',
            "Your task \"{$task->title}\" needs changes: {$feedback}",
            'changes_requested',
            route('student.internships.workspace', $offer->id)
        );
    }

    /**
     * Called when a student submits work.
     */
    public function onWorkSubmitted(InternshipTask $task): void
    {
        $offer = $task->hiringOffer;

        // Update last submission timestamp (for streak reset logic)
        $offer->last_submission_at = now();
        $offer->save();

        // Notify startup
        $this->notify(
            $offer->startup->user_id ?? null,
            '📩 New Task Submission',
            "{$offer->student->user->name} submitted work for \"{$task->title}\". Review it now.",
            'submission_received',
            route('startup.team.tasks.approve', ['offer' => $offer->id, 'task' => $task->id])
        );
    }

    /**
     * Check if 3+ days have passed since last submission → reset streak
     * (with streak-freeze protection: 1 free pass per month).
     */
    public function checkStreakReset(HiringOffer $offer): void
    {
        if (!$offer->last_submission_at) {
            return;
        }

        $daysSinceSubmission = $offer->last_submission_at->diffInDays(now());

        if ($daysSinceSubmission < 3) {
            return; // Still within the 3-day window
        }

        // Check if streak freeze is available this month
        if (!$offer->streak_freeze_used_at || !$offer->streak_freeze_used_at->isCurrentMonth()) {
            // Use the freeze — streak survives
            $offer->streak_freeze_used_at = now();
            $offer->save();

            $this->notify(
                $offer->student->user_id ?? null,
                '🎁 Streak Freeze Used',
                'Your monthly streak freeze was auto-applied. Submit work soon to keep your streak alive!',
                'streak_freeze',
                route('student.internships.workspace', $offer->id)
            );
            return;
        }

        // No freeze available — reset streak
        if ($offer->current_streak > 0) {
            $offer->current_streak = 0;
            $offer->save();

            $this->notify(
                $offer->student->user_id ?? null,
                '💔 Streak Reset',
                'Your streak was reset to 0. No approved submissions in 3+ days and your monthly freeze was already used.',
                'streak_reset',
                route('student.internships.workspace', $offer->id)
            );
        }
    }

    /**
     * Called when an internship is marked as completed.
     */
    public function onInternshipCompleted(HiringOffer $offer): void
    {
        $this->awardIPRS($offer->student_profile_id, self::IPRS['internship_complete']);

        $this->notify(
            $offer->student->user_id ?? null,
            '🏆 Internship Completed!',
            "Congratulations! Your internship with {$offer->startup->company_name} is complete. +250 IPRS earned.",
            'internship_complete',
            route('student.internships.workspace', $offer->id)
        );
    }

    /**
     * Called when a student is converted to full-time.
     */
    public function onFullTimeConversion(HiringOffer $offer): void
    {
        $this->awardIPRS($offer->student_profile_id, self::IPRS['full_time_conversion']);

        // Award the Future Founder badge
        StudentBadge::firstOrCreate(
            ['student_profile_id' => $offer->student_profile_id, 'badge_slug' => 'future_founder', 'hiring_offer_id' => $offer->id],
            ['badge_name' => '💼 Future Founder', 'earned_at' => now()]
        );

        $this->notify(
            $offer->student->user_id ?? null,
            '💼 Full-Time Offer!',
            "You've been converted to a full-time role at {$offer->startup->company_name}! +500 IPRS earned.",
            'full_time',
            route('student.internships.workspace', $offer->id)
        );
    }

    /* ─── Private Helpers ─── */

    /**
     * Check if all tasks in a milestone are approved → bonus IPRS.
     */
    private function checkMilestoneBonus(InternshipTask $task): void
    {
        if (!$task->milestone_name) {
            return;
        }

        $offer          = $task->hiringOffer;
        $milestoneTasks = $offer->internshipTasks()->where('milestone_name', $task->milestone_name)->get();
        $allApproved    = $milestoneTasks->every(fn($t) => $t->status === 'approved');

        if ($allApproved && $milestoneTasks->count() > 1) {
            $this->awardIPRS($offer->student_profile_id, self::IPRS['milestone_complete']);

            $this->notify(
                $offer->student->user_id ?? null,
                '⭐ Milestone Complete!',
                "All tasks in \"{$task->milestone_name}\" are approved! +50 IPRS bonus earned.",
                'milestone',
                route('student.internships.workspace', $offer->id)
            );
        }
    }

    /**
     * Check if student qualifies for any new badges.
     */
    private function checkBadges(HiringOffer $offer): void
    {
        $approvedCount = $offer->approved_tasks_count;
        $streak        = $offer->current_streak;

        $badgesToCheck = [
            'consistent_coder' => $approvedCount >= 7,
            'startup_warrior'  => $streak >= 15,
            'product_builder'  => $approvedCount >= 30,
        ];

        foreach ($badgesToCheck as $slug => $earned) {
            if (!$earned) {
                continue;
            }

            $exists = StudentBadge::where('student_profile_id', $offer->student_profile_id)
                ->where('badge_slug', $slug)
                ->where('hiring_offer_id', $offer->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $badge = StudentBadge::BADGES[$slug] ?? null;
            if (!$badge) {
                continue;
            }

            StudentBadge::create([
                'student_profile_id' => $offer->student_profile_id,
                'badge_slug'         => $slug,
                'badge_name'         => $badge['name'],
                'hiring_offer_id'    => $offer->id,
                'earned_at'          => now(),
            ]);

            $this->notify(
                $offer->student->user_id ?? null,
                '🏆 Achievement Unlocked!',
                "You earned the {$badge['name']} badge!",
                'badge',
                route('student.internships.workspace', $offer->id)
            );
        }
    }

    /**
     * Add IPRS points to student's reputation score.
     */
    private function awardIPRS(int $studentProfileId, int $points): void
    {
        $rep = ReputationScore::firstOrCreate(
            ['student_profile_id' => $studentProfileId],
            ['overall_score' => 0, 'trust_score' => 0, 'completion_rate' => 0, 'on_time_rate' => 0]
        );

        $rep->increment('overall_score', $points);
    }

    /**
     * Create a notification record.
     */
    private function notify(?int $userId, string $title, string $message, string $type, string $actionUrl = ''): void
    {
        if (!$userId) {
            return;
        }

        Notification::create([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'action_url' => $actionUrl,
            'is_read'    => false,
        ]);
    }
}
