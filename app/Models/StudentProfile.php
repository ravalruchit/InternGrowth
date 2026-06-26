<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentProfile extends Model
{
    public static $domains = [
        'Software Development' => [
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'Mobile App Developer',
            'QA Tester',
        ],
        'UI/UX Design' => [
            'UI Designer',
            'UX Designer',
            'Graphic Designer',
            'Product Designer',
            'Figma Designer',
        ],
        'Digital Marketing' => [
            'SEO Specialist',
            'Social Media Manager',
            'Performance Marketer',
            'Content Marketer',
            'Email Marketing Executive',
        ],
        'Data & AI' => [
            'Data Analyst',
            'Data Scientist',
            'AI Engineer',
            'Machine Learning Engineer',
            'Business Intelligence Analyst',
        ],
        'Content & Business' => [
            'Content Writer',
            'Copywriter',
            'Business Analyst',
            'Market Research Analyst',
            'Operations Associate',
        ],
    ];

    protected $fillable = [
        'user_id',
        'bio',
        'portfolio_links',
        'reliability_score',
        'wallet_balance',
        'college_email',
        'college_name',
        'verification_token',
        'verification_token_expires_at',
        'is_verified',
        'email_verified_at',
        'availability',
        'graduation_year',
        // AI ID Card Verification
        'id_card_path',
        'id_card_verification_status',
        'id_card_ai_result',
        'id_card_submitted_at',
        'id_card_verified_at',
        'verification_method',
        'primary_domain',
        'preferred_role',
        // Social, location, education and customizations
        'phone_number',
        'github_url',
        'linkedin_url',
        'portfolio_url',
        'leetcode_url',
        'degree_name',
        'cgpa',
        'professional_title',
        'city',
        'state',
        'country',
        'resume_theme',
        'show_iprs',
        'show_stipends',
        'show_ratings',
        'show_certificates',
        'show_social_links',
        'show_profile_photo',
    ];

    protected $casts = [
        'portfolio_links'          => 'array',
        'wallet_balance'           => 'decimal:2',
        'is_verified'                    => 'boolean',
        'verification_token_expires_at'  => 'datetime',
        'email_verified_at'              => 'datetime',
        'id_card_ai_result'        => 'array',
        'id_card_submitted_at'     => 'datetime',
        'id_card_verified_at'      => 'datetime',
        'cgpa'                     => 'decimal:2',
        'show_iprs'                => 'boolean',
        'show_stipends'            => 'boolean',
        'show_ratings'             => 'boolean',
        'show_certificates'        => 'boolean',
        'show_social_links'        => 'boolean',
        'show_profile_photo'       => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'student_skill');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }


    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function reputationScore(): HasOne
    {
        return $this->hasOne(ReputationScore::class);
    }

    public function portfolio(): HasOne
    {
        return $this->hasOne(Portfolio::class);
    }

    public function hiringOffers(): HasMany
    {
        return $this->hasMany(HiringOffer::class);
    }

    public function skillVerifications(): HasMany
    {
        return $this->hasMany(SkillVerification::class);
    }

    public function savedByStartups(): BelongsToMany
    {
        return $this->belongsToMany(StartupProfile::class, 'saved_candidates', 'student_profile_id', 'startup_profile_id');
    }

    public function startupReviews(): HasMany
    {
        return $this->hasMany(StartupReview::class, 'student_profile_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'student_profile_id');
    }

    /**
     * Check if contact details are unlocked for a specific user.
     */
    public function contactDetailsUnlockedFor($user): bool
    {
        if (!$user) {
            return false;
        }

        // Student can see their own info
        if ($user->studentProfile && $user->studentProfile->id === $this->id) {
            return true;
        }

        // Admin can see it
        if ($user->isAdmin()) {
            return true;
        }

        // Startup can see it if they have an accepted offer or application
        if ($user->startupProfile) {
            $startupId = $user->startupProfile->id;

            // Check if there is an accepted hiring offer
            $hasAcceptedOffer = \App\Models\HiringOffer::where('student_profile_id', $this->id)
                ->where('startup_profile_id', $startupId)
                ->whereIn('status', ['pending_joining', 'joined', 'completed'])
                ->exists();

            if ($hasAcceptedOffer) {
                return true;
            }

            // Check if there is an approved/accepted/hired application
            $hasAcceptedApp = \App\Models\Application::where('student_profile_id', $this->id)
                ->whereHas('task', function($q) use ($startupId) {
                    $q->where('startup_profile_id', $startupId);
                })
                ->whereIn('status', ['approved', 'internship_accepted', 'hired'])
                ->exists();

            if ($hasAcceptedApp) {
                return true;
            }
        }

        return false;
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class, 'student_profile_id');
    }
}
