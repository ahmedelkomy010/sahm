<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyWorkExecution extends Model
{
    protected $fillable = [
        'work_order_id',
        'work_order_item_id', 
        'work_date',
        'executed_quantity',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'work_date' => 'date',
        'executed_quantity' => 'decimal:2'
    ];

    /**
     * Get the work order that owns the daily execution
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the work order item that owns the daily execution
     */
    public function workOrderItem(): BelongsTo
    {
        return $this->belongsTo(WorkOrderItem::class);
    }

    /**
     * Get the user who created the daily execution
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
