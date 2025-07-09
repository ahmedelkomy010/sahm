<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseAttachment extends Model
{
    protected $fillable = [
        'license_id',
        'attachment_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'description'
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
} 