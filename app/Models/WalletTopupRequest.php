<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTopupRequest extends Model
{
    protected $fillable = [
        'startup_profile_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'notes',
        'status',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
