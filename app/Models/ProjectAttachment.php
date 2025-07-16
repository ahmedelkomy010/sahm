<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'file_path',
        'original_filename',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
} 