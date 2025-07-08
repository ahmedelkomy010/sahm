<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExcavationDetail extends Model
{
    protected $fillable = [
        'license_id',
        'title',
        'location',
        'contractor',
        'duration',
        'length',
        'width',
        'depth',
        'status',
        'status_text'
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'duration' => 'integer',
    ];

    /**
     * Get the license that owns the excavation detail.
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function getFormattedDimensionsAttribute(): string
    {
        return sprintf(
            '%s × %s × %s م',
            number_format($this->length, 2),
            number_format($this->width, 2),
            number_format($this->depth, 2)
        );
    }

    /**
     * Get the formatted status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'expired' => 'منتهي',
            'completed' => 'مكتمل',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-blue-100 text-blue-800',
            'expired' => 'bg-red-100 text-red-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
} 