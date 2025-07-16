<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contract_number',
        'project_type',
        'location',
        'description',
    ];

    public function attachments()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    public function getProjectTypeText()
    {
        $types = [
            'civil' => 'أعمال مدنية',
            'electrical' => 'أعمال كهربائية',
            'mixed' => 'أعمال مختلطة',
        ];

        return $types[$this->project_type] ?? $this->project_type;
    }
} 