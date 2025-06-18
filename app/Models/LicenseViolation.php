<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_id',
        'violation_license_number',
        'violation_license_value',
        'violation_license_date',
        'violation_due_date',
        'violation_number',
        'violation_payment_number',
        'violation_cause',
        'violations_file_path',
    ];

    protected $casts = [
        'violation_license_date' => 'date',
        'violation_due_date' => 'date',
        'violation_license_value' => 'decimal:2',
    ];

    /**
     * Get the license that owns the violation.
     */
    public function license()
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Get the violation value as formatted number
     */
    public function getFormattedViolationValueAttribute()
    {
        return number_format($this->violation_license_value ?? 0, 2);
    }

    /**
     * Get the violation status based on due date
     */
    public function getStatusAttribute()
    {
        if (!$this->violation_due_date) {
            return 'unknown';
        }
        
        return now()->isAfter($this->violation_due_date) ? 'overdue' : 'pending';
    }

    /**
     * Scope for overdue violations
     */
    public function scopeOverdue($query)
    {
        return $query->where('violation_due_date', '<', now());
    }

    /**
     * Scope for pending violations
     */
    public function scopePending($query)
    {
        return $query->where('violation_due_date', '>=', now());
    }
} 