<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignSubmittal extends Model
{
    protected $fillable = [
        'project_id',
        'family',
        'description_code',
        'rev',
        'description',
        'last_status',
        'submitting_date',
        'reply_date',
        'reply_status',
        'notes',
    ];

    protected $casts = [
        'submitting_date' => 'date',
        'reply_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
