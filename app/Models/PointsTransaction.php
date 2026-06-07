<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsTransaction extends Model
{
    protected $fillable = ['points_wallet_id', 'task_id', 'amount', 'type', 'description'];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(PointsWallet::class, 'points_wallet_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
