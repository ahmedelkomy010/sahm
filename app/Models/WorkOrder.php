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
        'execution_status',
        'actual_execution_value',
        'procedure_155_delivery_date',
        'procedure_211_date',
        'office',
        'extract_number',
        'extract_date',
        'extract_value',
        'notes',
        'project',
        'user_id'
    ];

    protected $casts = [
        'approval_date' => 'date',
        'procedure_155_delivery_date' => 'date',
        'procedure_211_date' => 'date',
        'extract_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function files()
    {
        return $this->hasMany(WorkOrderFile::class);
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