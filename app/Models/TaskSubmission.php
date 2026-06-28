<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubmission extends Model
{
    protected $fillable = [
        'internship_task_id',
        'student_profile_id',
        'github_url',
        'pull_request_url',
        'demo_url',
        'description',
        'attachments',
        'startup_feedback',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'attachments'  => 'array',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    /* ─── Relationships ─── */

    public function task(): BelongsTo
    {
        return $this->belongsTo(InternshipTask::class, 'internship_task_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }
}
