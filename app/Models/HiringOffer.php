<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HiringOffer extends Model
{
    protected $fillable = [
        'startup_profile_id',
        'student_profile_id',
        'source_task_id',
        'offer_type',
        'title',
        'description',
        'compensation',
        'compensation_period',
        'start_date',
        'end_date',
        'status',
        'contract_terms',
        'expires_at',
        'reserved_fee',
        'domain',
        'role',
        'counter_compensation',
        'counter_note',
        'student_joining_status',
        'startup_joining_status',
        'joining_confirmed_at',
        'completed_at',
        'completion_notes',
        'hiring_success_rating',
        'hiring_success_rated_at',
        'internship_score',
        'converted_to_full_time',
        'last_checked_in_at',
        'current_streak',
        'highest_streak',
        'last_submission_at',
        'streak_freeze_used_at',
    ];

    /**
     * Dynamically expire pending offers in the status accessor.
     */
    public function getStatusAttribute($value)
    {
        if ($value === 'pending' && $this->expires_at && $this->expires_at->isPast()) {
            return 'expired';
        }
        return $value;
    }

    protected $casts = [
        'compensation'             => 'decimal:2',
        'reserved_fee'             => 'decimal:2',
        'start_date'               => 'date',
        'end_date'                 => 'date',
        'expires_at'               => 'datetime',
        'joining_confirmed_at'     => 'datetime',
        'completed_at'             => 'datetime',
        'hiring_success_rated_at'  => 'datetime',
        'last_checked_in_at'       => 'datetime',
        'last_submission_at'       => 'datetime',
        'streak_freeze_used_at'    => 'datetime',
    ];

    /* ─── Core Relationships ─── */

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function sourceTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'source_task_id');
    }

    /* ─── Legacy Relationships (kept for backward compat) ─── */

    public function updates(): HasMany
    {
        return $this->hasMany(InternshipUpdate::class, 'hiring_offer_id');
    }

    public function weeklyReports(): HasMany
    {
        return $this->hasMany(WeeklyReport::class, 'hiring_offer_id');
    }

    /* ─── NEW: Verified Task System Relationships ─── */

    public function internshipTasks(): HasMany
    {
        return $this->hasMany(InternshipTask::class, 'hiring_offer_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(InternshipResource::class, 'hiring_offer_id');
    }

    /* ─── Computed Accessors ─── */

    /**
     * Contract progress based on date range.
     */
    public function getProgressPctAttribute(): int
    {
        if (!$this->start_date) {
            return 0;
        }
        $start = $this->start_date;
        $end   = $this->end_date ?? $start->copy()->addDays(90);
        $total = max($start->diffInDays($end), 1);
        $elapsed = max($start->diffInDays(now()), 0);
        return min(100, (int) round(($elapsed / $total) * 100));
    }

    /**
     * Count of approved internship tasks.
     */
    public function getApprovedTasksCountAttribute(): int
    {
        return $this->internshipTasks()->where('status', 'approved')->count();
    }

    /**
     * Count of tasks awaiting startup review.
     */
    public function getPendingReviewCountAttribute(): int
    {
        return $this->internshipTasks()->where('status', 'submitted')->count();
    }

    /**
     * Weighted performance score.
     *
     * 50% Approved task completion rate
     * 20% Average startup weekly-report rating (out of 5)
     * 15% Streak consistency (current_streak / max(highest_streak, 1))
     * 15% Weekly report submission rate
     */
    public function getPerformanceScoreAttribute(): float
    {
        $totalTasks    = $this->internshipTasks()->count();
        $approvedTasks = $totalTasks > 0 ? $this->approved_tasks_count : 0;

        // 50% — Task completion rate
        $taskScore = $totalTasks > 0
            ? ($approvedTasks / $totalTasks)
            : 0;

        // 20% — Average startup rating from weekly reports (scale 1-5)
        $avgRating = $this->weeklyReports()
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;
        $ratingScore = $avgRating / 5;

        // 15% — Streak consistency
        $highestStreak = max($this->highest_streak ?? 0, 1);
        $streakScore   = ($this->current_streak ?? 0) / $highestStreak;

        // 15% — Weekly report submission rate
        if ($this->start_date) {
            $weeksSinceStart = max(1, (int) ceil($this->start_date->diffInDays(now()) / 7));
            $reportCount     = $this->weeklyReports()->count();
            $reportScore     = min(1, $reportCount / $weeksSinceStart);
        } else {
            $reportScore = 0;
        }

        $score = ($taskScore * 50)
               + ($ratingScore * 20)
               + ($streakScore * 15)
               + ($reportScore * 15);

        return round(min(100, max(0, $score)), 1);
    }
}
