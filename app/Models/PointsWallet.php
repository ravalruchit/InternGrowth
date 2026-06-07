<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointsWallet extends Model
{
    protected $fillable = ['student_profile_id', 'balance'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PointsTransaction::class);
    }
}
