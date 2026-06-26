<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItem extends Model
{
    protected $fillable = [
        'portfolio_id',
        'task_id',
        'submission_id',
        'hiring_offer_id',
        'project_title',
        'auto_summary',
        'skills_demonstrated',
        'rating_received',
        'startup_name',
        'certificate_number',
        'is_featured',
        'verification_badge',
        'completed_at',
        'github_url',
        'demo_url',
        'screenshots',
        'domain',
        'role'
    ];

    protected $casts = [
        'skills_demonstrated' => 'array',
        'rating_received' => 'decimal:2',
        'is_featured' => 'boolean',
        'completed_at' => 'datetime',
        'screenshots' => 'array'
    ];

    /**
     * Get the human-readable badge label with emoji.
     */
    public function badgeLabel(): array
    {
        if ($this->verification_badge === 'outstanding_performance') {
            return ['emoji' => '🏆', 'label' => 'Outstanding Performance', 'color' => 'amber'];
        }

        if ($this->verification_badge === 'certified') {
            return ['emoji' => '🎓', 'label' => 'Certified', 'color' => 'accent'];
        }

        if ($this->verification_badge === 'featured') {
            return ['emoji' => '⭐', 'label' => 'Featured', 'color' => 'lime'];
        }

        return ['emoji' => '✅', 'label' => 'Verified Project', 'color' => 'emerald'];
    }

    /**
     * Check if this portfolio item has any evidence links.
     */
    public function hasEvidence(): bool
    {
        return !empty($this->github_url)
            || !empty($this->demo_url)
            || (!empty($this->screenshots) && count($this->screenshots) > 0);
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function hiringOffer(): BelongsTo
    {
        return $this->belongsTo(HiringOffer::class, 'hiring_offer_id');
    }
}
