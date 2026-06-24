<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_type',
        'user_id',
        'type',
        'amount',
        'description',
        'reference_id',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
