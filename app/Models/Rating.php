<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = ['task_id', 'student_profile_id', 'startup_profile_id', 'rating', 'comment'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class, 'startup_profile_id');
    }
}
