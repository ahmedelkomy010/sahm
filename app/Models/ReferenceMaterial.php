<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceMaterial extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];
}
