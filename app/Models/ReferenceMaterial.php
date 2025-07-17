<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceMaterial extends Model
{
    protected $fillable = [
        'code',
        'description',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'unit' => 'قطعة',
        'is_active' => true
    ];
}
