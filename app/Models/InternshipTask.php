<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipTask extends Model
{
    protected $fillable = [
        'hiring_offer_id',
        'startup_profile_id',
        'student_profile_id',
        'title',
        'description',
        'priority',
        'milestone_name',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /* ─── Relationships ─── */

    public function hiringOffer(): BelongsTo
    {
        return $this->belongsTo(HiringOffer::class, 'hiring_offer_id');
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class, 'internship_task_id');
    }

    /* ─── Helpers ─── */

    public function latestSubmission()
    {
        return $this->hasOne(TaskSubmission::class, 'internship_task_id')->latestOfMany();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function needsChanges(): bool
    {
        return $this->status === 'needs_changes';
    }

    /* ─── Scopes ─── */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeNeedsChanges($query)
    {
        return $query->where('status', 'needs_changes');
    }
}
