<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillVerification extends Model
{
    protected $fillable = [
        'student_profile_id',
        'skill_id',
        'startup_profile_id',
        'task_id',
        'verification_method',
        'score',
        'rating',
        'verification_type',
        'notes',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'rating' => 'integer',
        'score' => 'integer',
    ];

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
