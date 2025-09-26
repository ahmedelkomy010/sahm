<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderSafetyHistory extends Model
{
    use HasFactory;

    protected $table = 'work_order_safety_history';

    protected $fillable = [
        'work_order_id',
        'safety_officer',
        'safety_status',
        'safety_notes',
        'non_compliance_reasons',
        'inspection_date',
        'updated_by'
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}