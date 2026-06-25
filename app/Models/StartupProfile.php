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
        'verification_reviewed_at',
        // AI Verification & Trust columns
        'ai_verification_status',
        'ai_verification_result',
        'ai_verified_at',
        'ai_confidence_score',
        'verification_documents_hash',
        'verification_level',
        'verification_expires_at',
        'startup_trust_score',
        'trust_score_breakdown',
        'is_suspicious',
        'industry'
    ];
 
    protected $casts = [
        'wallet_balance' => 'decimal:2',
        'is_verified' => 'boolean',
        'credibility_score' => 'float',
        'verification_documents' => 'array',
        'verification_submitted_at' => 'datetime',
        'verification_reviewed_at' => 'datetime',
        'ai_verification_result' => 'array',
        'ai_verified_at' => 'datetime',
        'verification_expires_at' => 'datetime',
        'trust_score_breakdown' => 'array',
        'is_suspicious' => 'boolean',
    ];
 
    /**
     * Determine if the startup is verified, not suspicious, and verification is active.
     */
    public function isVerifiedAndActive(): bool
    {
        if (!$this->is_verified || $this->is_suspicious) {
            return false;
        }
 
        if ($this->verification_expires_at && now()->gt($this->verification_expires_at)) {
            return false;
        }
 
        return true;
    }
 
    /**
     * Recalculate and cache the startup's trust score and breakdown.
     */
    public function recalculateTrustScore(): int
    {
        $reputationService = new \App\Services\StartupReputationService();
        $trustScoreObj = $reputationService->updateReputation($this->id);

        $breakdown = [
            'verification'   => (int) $trustScoreObj->verification_score,
            'payment'        => (int) $trustScoreObj->payment_score,
            'student_rating' => (int) $trustScoreObj->student_rating_score,
            'hiring'         => (int) $trustScoreObj->hiring_score,
        ];

        $this->update([
            'startup_trust_score'   => (int) $trustScoreObj->overall_score,
            'trust_score_breakdown' => $breakdown,
        ]);

        return (int) $trustScoreObj->overall_score;
    }

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

    public function verificationLogs(): HasMany
    {
        return $this->hasMany(StartupVerificationLog::class, 'startup_profile_id');
    }
}
