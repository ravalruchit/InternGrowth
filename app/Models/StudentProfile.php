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
    ];

    protected $casts = [
        'portfolio_links'          => 'array',
        'wallet_balance'           => 'decimal:2',
        'is_verified'              => 'boolean',
        'email_verified_at'        => 'datetime',
        'id_card_ai_result'        => 'array',
        'id_card_submitted_at'     => 'datetime',
        'id_card_verified_at'      => 'datetime',
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

    public function wallet(): HasOne
    {
        return $this->hasOne(PointsWallet::class);
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
}
