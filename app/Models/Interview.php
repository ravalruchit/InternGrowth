<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'conversation_id',
        'startup_profile_id',
        'student_profile_id',
        'task_id',
        'title',
        'scheduled_at',
        'duration_minutes',
        'type',
        'location',
        'agenda',
        'status',
        'outcome',
        'technical_rating',
        'communication_rating',
        'problem_solving_rating',
        'feedback_notes',
        'reminder_24h_sent',
        'reminder_1h_sent',
        'domain',
        'role'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'technical_rating' => 'integer',
        'communication_rating' => 'integer',
        'problem_solving_rating' => 'integer',
        'reminder_24h_sent' => 'boolean',
        'reminder_1h_sent' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
