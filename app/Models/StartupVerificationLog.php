<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupVerificationLog extends Model
{
    // Disable default laravel timestamps since migration only uses created_at
    public $timestamps = false;

    protected $fillable = [
        'startup_profile_id',
        'action',
        'performed_by',
        'old_status',
        'new_status',
        'reason',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function startupProfile(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class);
    }
}
