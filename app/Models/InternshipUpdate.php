<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipUpdate extends Model
{
    protected $fillable = [
        'hiring_offer_id',
        'student_profile_id',
        'title',
        'description',
        'github_url',
        'demo_url',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'json'
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
