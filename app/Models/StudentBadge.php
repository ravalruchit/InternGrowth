<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBadge extends Model
{
    protected $fillable = [
        'student_profile_id',
        'badge_slug',
        'badge_name',
        'hiring_offer_id',
        'earned_at',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
    ];

    /* ─── Badge Definitions ─── */

    public const BADGES = [
        'consistent_coder'  => ['name' => '🔥 Consistent Coder',  'threshold' => 7,  'metric' => 'approved_tasks'],
        'startup_warrior'   => ['name' => '⚡ Startup Warrior',   'threshold' => 15, 'metric' => 'streak'],
        'product_builder'   => ['name' => '🏆 Product Builder',   'threshold' => 30, 'metric' => 'approved_tasks'],
        'growth_champion'   => ['name' => '🚀 Growth Champion',   'threshold' => 100,'metric' => 'iprs'],
        'future_founder'    => ['name' => '💼 Future Founder',    'threshold' => 1,  'metric' => 'full_time_conversion'],
    ];

    /* ─── Relationships ─── */

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function hiringOffer(): BelongsTo
    {
        return $this->belongsTo(HiringOffer::class, 'hiring_offer_id');
    }
}
