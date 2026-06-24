<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = ['student_profile_id', 'task_id', 'hiring_offer_id', 'certificate_number', 'qr_code', 'issued_at'];

    protected $casts = ['issued_at' => 'datetime'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function hiringOffer(): BelongsTo
    {
        return $this->belongsTo(HiringOffer::class, 'hiring_offer_id');
    }
}
