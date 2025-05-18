<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'work_type',
        'work_description',
        'approval_date',
        'subscriber_name',
        'district',
        'station_number',
        'consultant_name',
        'order_value_with_consultant',
        'order_value_without_consultant',
        'final_total_value',
        'execution_status',
        'actual_execution_value',
        'procedure_155_delivery_date',
        'procedure_211_date',
        'partial_deletion',
        'partial_payment_value',
        'office',
        'extract_number',
        'extract_date',
        'extract_value',
        'invoice_number',
        'purchase_order_number',
        'tax_value',
        'notes',
        'project',
        'user_id',
        'execution_file',
        'execution_notes',
        'single_meter_installation',
        'double_meter_installation',
        'excavation_unsurfaced_soil',
        'excavation_surfaced_soil',
        'excavation_unsurfaced_rock',
        'excavation_surfaced_rock',
        'excavation_precise',
        'entry_sheet',
        'actual_execution_value_consultant',
        'actual_execution_value_without_consultant',
        'first_partial_payment_without_tax',
        'second_partial_payment_with_tax',
        'municipality',
    ];

    protected $casts = [
        'approval_date' => 'date',
        'procedure_155_delivery_date' => 'date',
        'procedure_211_date' => 'date',
        'extract_date' => 'date',
        'partial_deletion' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'single_meter_installation' => 'string',
        'double_meter_installation' => 'string',
        'excavation_unsurfaced_soil' => 'array',
        'excavation_surfaced_soil' => 'array',
        'excavation_unsurfaced_rock' => 'array',
        'excavation_surfaced_rock' => 'array',
        'excavation_precise' => 'array',
        'invoice_images' => 'array',
    ];

    // Relationships
    public function files()
    {
        return $this->hasMany(WorkOrderFile::class);
    }

    public function civilWorksFiles()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'civil_works');
    }

    public function installationsFiles()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'installations');
    }

    public function electricalWorksFiles()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'electrical_works');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function survey()
    {
        return $this->hasOne(Survey::class);
    }

    public function license()
    {
        return $this->hasOne(License::class)->withDefault();
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    /**
     * Get the materials for the work order.
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function invoiceAttachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ][$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute()
    {
        return [
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'عالي',
            'urgent' => 'عاجل',
        ][$this->priority] ?? $this->priority;
    }
}