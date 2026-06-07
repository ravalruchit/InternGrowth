<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlagiarismLog extends Model
{
    protected $fillable = ['submission_id', 'similarity_score', 'matched_sources', 'status'];

    protected $casts = [
        'similarity_score' => 'decimal:2',
        'matched_sources' => 'array'
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
