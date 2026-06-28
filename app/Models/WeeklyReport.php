<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyReport extends Model
{
    protected $fillable = [
        'hiring_offer_id',
        'student_profile_id',
        'week_number',
        'tasks_completed',
        'challenges',
        'next_week_goals',
        'startup_feedback',
        'rating',
        'github_url',
        'demo_url'
    ];

    public function hiringOffer(): BelongsTo
    {
        return $this->belongsTo(HiringOffer::class, 'hiring_offer_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }
}
