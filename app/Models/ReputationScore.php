<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReputationScore extends Model
{
    protected $fillable = [
        'student_profile_id',
        'overall_score',
        'trust_score',
        'completion_rate',
        'on_time_rate',
        'satisfaction_rating',
        'communication_rating',
        'skill_verification_rating',
        'interview_performance_score',
        'interviews_attended',
        'interview_success_rate',
        'strong_candidate_outcomes',
        'no_shows',
        'total_verified_projects',
        'domain_scores'
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'trust_score' => 'decimal:2',
        'completion_rate' => 'decimal:2',
        'on_time_rate' => 'decimal:2',
        'satisfaction_rating' => 'decimal:2',
        'communication_rating' => 'decimal:2',
        'skill_verification_rating' => 'decimal:2',
        'interview_performance_score' => 'decimal:2',
        'interviews_attended' => 'integer',
        'interview_success_rate' => 'decimal:2',
        'strong_candidate_outcomes' => 'integer',
        'no_shows' => 'integer',
        'total_verified_projects' => 'integer',
        'domain_scores' => 'array'
    ];

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
