<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HiringOffer extends Model
{
    protected $fillable = [
        'startup_profile_id',
        'student_profile_id',
        'source_task_id',
        'offer_type',
        'title',
        'description',
        'compensation',
        'compensation_period',
        'start_date',
        'end_date',
        'status',
        'contract_terms',
        'expires_at',
        'reserved_fee',
        'domain',
        'role'
    ];

    protected $casts = [
        'compensation' => 'decimal:2',
        'reserved_fee' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'expires_at' => 'datetime',
        'contract_terms' => 'array'
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function sourceTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'source_task_id');
    }
}
