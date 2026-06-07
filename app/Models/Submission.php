<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = ['application_id', 'content', 'files', 'status', 'feedback', 'is_plagiarized'];

    protected $casts = [
        'files' => 'array',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function plagiarismLog(): HasOne
    {
        return $this->hasOne(PlagiarismLog::class);
    }
}
