<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderInspectionDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'inspection_date',
        'inspector_name',
        'notes',
        'status'
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