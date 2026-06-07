<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    protected $fillable = ['student_profile_id', 'custom_slug', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PortfolioItem::class);
    }

    /**
     * Compute a hiring summary from portfolio data.
     * Returns an associative array of key stats for the talent profile.
     */
    public function getHiringSummaryAttribute(): array
    {
        $items = $this->items;
        $studentProfileId = $this->student_profile_id;

        $totalProjects = $items->count();

        // Average Startup Rating (renamed for trust)
        $ratedItems = $items->whereNotNull('rating_received');
        $avgStartupRating = $ratedItems->count() > 0
            ? round($ratedItems->avg('rating_received'), 1)
            : null;

        // Verified skills count (unique skills across all verified items)
        $allSkills = $items->pluck('skills_demonstrated')->filter()->flatten()->unique();
        $verifiedSkillsCount = $allSkills->count();

        // Offers received
        $offersCount = HiringOffer::where('student_profile_id', $studentProfileId)->count();

        // Certificates earned
        $certificatesCount = $items->whereNotNull('certificate_number')->count();

        return [
            'total_projects' => $totalProjects,
            'avg_startup_rating' => $avgStartupRating,
            'verified_skills' => $verifiedSkillsCount,
            'offers_received' => $offersCount,
            'certificates' => $certificatesCount,
        ];
    }
}
