<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LicenseViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_id',
        'work_order_id',
        'license_number',
        'violation_number',
        'violation_date',
        'violation_type',
        'responsible_party',
        'payment_status',
        'violation_amount',
        'payment_due_date',
        'violation_description',
        'payment_invoice_number',
        'attachment_path',
        'notes'
    ];

    protected $casts = [
        'violation_date' => 'date',
        'payment_due_date' => 'date',
        'violation_amount' => 'decimal:2'
    ];

    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the license that owns the violation.
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Get the violation value as formatted number
     */
    public function getFormattedViolationValueAttribute()
    {
        return number_format($this->violation_amount ?? 0, 2);
    }

    /**
     * Get the violation status based on due date
     */
    public function getStatusAttribute()
    {
        if (!$this->payment_due_date) {
            return 'unknown';
        }
        
        return now()->isAfter($this->payment_due_date) ? 'overdue' : 'pending';
    }

    /**
     * Scope for overdue violations
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_due_date', '<', now());
    }

    /**
     * Scope for pending violations
     */
    public function scopePending($query)
    {
        return $query->where('payment_due_date', '>=', now());
    }

    /**
     * Get the full URL for the attachment file
     */
    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_path ? Storage::url($this->attachment_path) : null;
    }

    /**
     * Get payment status as text
     */
    public function getPaymentStatusTextAttribute()
    {
        switch($this->payment_status) {
            case 1:
                return 'paid';
            case 2:
                return 'pending';
            case 3:
                return 'overdue';
            default:
                return 'unknown';
        }
    }
} 