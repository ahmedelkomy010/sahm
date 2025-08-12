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
}
