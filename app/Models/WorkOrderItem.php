<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'work_item_id', 
        'quantity',
        'unit_price',
        'unit',
        'executed_quantity',
        'notes',
        'work_date'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'executed_quantity' => 'decimal:2',
        'work_date' => 'date'
    ];

    /**
     * Get the work order that owns this item.
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the work item details.
     */
    public function workItem()
    {
        return $this->belongsTo(WorkItem::class);
    }

    /**
     * Get work items by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('work_date', $date);
    }

    /**
     * Get daily executions for this work order item
     */
    public function dailyExecutions()
    {
        return $this->hasMany(DailyWorkExecution::class);
    }

    /**
     * Get daily execution for a specific date
     */
    public function getDailyExecutionForDate($date)
    {
        return $this->dailyExecutions()->where('work_date', $date)->first();
    }

    /**
     * Get executed quantity for a specific date
     */
    public function getExecutedQuantityForDate($date)
    {
        $dailyExecution = $this->getDailyExecutionForDate($date);
        return $dailyExecution ? $dailyExecution->executed_quantity : 0;
    }
}
