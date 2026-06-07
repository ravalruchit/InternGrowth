<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StartupProfile extends Model
{
    protected $fillable = [
        'user_id', 
        'company_name', 
        'description', 
        'website', 
        'credibility_score', 
        'is_verified', 
        'wallet_balance',
        'company_registration_number',
        'gst_number',
        'company_address',
        'contact_phone',
        'verification_documents',
        'verification_status',
        'verification_notes',
        'verification_submitted_at',
        'verification_reviewed_at'
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
        'is_verified' => 'boolean',
        'credibility_score' => 'float',
        'verification_documents' => 'array',
        'verification_submitted_at' => 'datetime',
        'verification_reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function topupRequests(): HasMany
    {
        return $this->hasMany(WalletTopupRequest::class);
    }

    public function hiringOffers(): HasMany
    {
        return $this->hasMany(HiringOffer::class);
    }

    public function savedCandidates(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(StudentProfile::class, 'saved_candidates', 'startup_profile_id', 'student_profile_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(StartupReview::class, 'startup_profile_id');
    }

    public function trustScore(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StartupTrustScore::class, 'startup_profile_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'startup_profile_id');
    }
}
