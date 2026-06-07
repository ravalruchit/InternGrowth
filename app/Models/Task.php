<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'startup_profile_id', 'title', 'description', 'requirements', 'required_skills',
        'reward_points', 'stipend', 'escrow_amount', 'escrow_locked', 'status', 'is_flagged'
    ];

    protected $casts = [
        'required_skills' => 'array',
        'stipend' => 'decimal:2',
        'escrow_amount' => 'decimal:2',
        'escrow_locked' => 'boolean'
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_task');
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

    public function escrow()
    {
        return $this->hasOne(Escrow::class);
    }

    public function hiringOffers(): HasMany
    {
        return $this->hasMany(HiringOffer::class, 'source_task_id');
    }
}
