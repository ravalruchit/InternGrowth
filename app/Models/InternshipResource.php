<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipResource extends Model
{
    protected $fillable = [
        'hiring_offer_id',
        'uploaded_by_user_id',
        'title',
        'url',
        'type',
    ];

    /* ─── Relationships ─── */

    public function hiringOffer(): BelongsTo
    {
        return $this->belongsTo(HiringOffer::class, 'hiring_offer_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
