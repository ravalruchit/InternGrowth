<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = [
        'task_id', 'student_profile_id', 'cover_letter', 'status',
        'startup_hiring_outcome', 'hired_via',
        'agreement_accepted', 'agreement_accepted_at', 'agreement_ip',
        'hiring_success_rating', 'hiring_success_rated_at'
    ];

    protected $casts = [
        'agreement_accepted' => 'boolean',
        'agreement_accepted_at' => 'datetime',
        'hiring_success_rated_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function submission(): HasOne
    {
        return $this->hasOne(Submission::class);
    }

    /**
     * Check if contact details are unlocked for this application.
     */
    public function contactDetailsUnlocked(): bool
    {
        return in_array($this->status, ['internship_accepted', 'hired']);
    }
}
