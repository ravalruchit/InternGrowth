<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = ['name'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(StudentProfile::class, 'student_skill');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'skill_task');
    }
}
