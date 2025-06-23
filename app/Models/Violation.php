<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'violation_type',
        'responsible_party',
        'payment_status',
        'description',
        'actions_taken',
        'violation_value',
        'violation_file',
        'payment_proof',
    ];

    protected $casts = [
        'violation_value' => 'float',
    ];
} 