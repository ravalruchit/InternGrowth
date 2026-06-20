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
        'is_suspicious'
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
        $verificationVal = $this->isVerifiedAndActive() ? 50 : 0;

        // Completed tasks (tasks that have at least one accepted application)
        $completedTasksCount = $this->tasks()
            ->whereHas('applications.submission', function($q) {
                $q->where('status', 'accepted');
            })->count();
        $completedTasksVal = $completedTasksCount * 5;

        // Successful internships (accepted HiringOffers with type 'internship')
        $successfulInternshipsCount = $this->hiringOffers()
            ->where('offer_type', 'internship')
            ->where('status', 'accepted')->count();
        $successfulInternshipsVal = $successfulInternshipsCount * 10;

        // Successful hires (accepted HiringOffers with type 'job')
        $successfulHiresCount = $this->hiringOffers()
            ->where('offer_type', 'job')
            ->where('status', 'accepted')->count();
        $successfulHiresVal = $successfulHiresCount * 15;

        // Reviews & Payouts (student reviews, payment completions, and complaints)
        $positiveReviewsCount = $this->reviews()->where('rating', '>=', 4)->count();
        $negativeReviewsCount = $this->reviews()->where('rating', '<=', 2)->count();
        
        // We count 1-star reviews as complaints (-20)
        $complaintsCount = $this->reviews()->where('rating', 1)->count();
        
        // Count verified payment completions: completed tasks that had stipend/escrow payout
        $verifiedPaymentsCount = $this->tasks()
            ->where('escrow_locked', false)
            ->where('escrow_amount', '>', 0)
            ->whereHas('applications.submission', function($q) {
                $q->where('status', 'accepted');
            })->count();

        $studentReviewsVal = ($positiveReviewsCount * 2) 
            + ($negativeReviewsCount * -5) 
            + ($complaintsCount * -20) 
            + ($verifiedPaymentsCount * 3);

        $totalScore = $verificationVal + $completedTasksVal + $successfulInternshipsVal + $successfulHiresVal + $studentReviewsVal;
        $totalScore = max(0, min(100, $totalScore));

        $breakdown = [
            'verification'           => $verificationVal,
            'completed_tasks'        => $completedTasksVal,
            'successful_internships' => $successfulInternshipsVal,
            'successful_hires'       => $successfulHiresVal,
            'student_reviews'        => $studentReviewsVal,
        ];

        $this->update([
            'startup_trust_score'   => $totalScore,
            'trust_score_breakdown' => $breakdown,
        ]);

        return $totalScore;
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
