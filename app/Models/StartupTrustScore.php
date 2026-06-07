<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupTrustScore extends Model
{
    protected $fillable = [
        'startup_profile_id',
        'overall_score',
        'payment_score',
        'verification_score',
        'student_rating_score',
        'hiring_score'
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }
}
