<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_type',
        'billing_cycle',
        'status',
        'amount',
        'starts_at',
        'expires_at',
        'payment_reference',
        'description',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /* ─── Relationships ─── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ─── Helpers ─── */

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trial']) && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }
        return false;
    }

    public function daysRemaining(): int
    {
        if (!$this->expires_at || $this->isExpired()) {
            return 0;
        }
        return (int) max(0, now()->diffInDays($this->expires_at));
    }

    public function isStudentPro(): bool
    {
        return $this->plan_type === 'student_pro';
    }

    public function isStartupGrowth(): bool
    {
        return $this->plan_type === 'startup_growth';
    }
}
