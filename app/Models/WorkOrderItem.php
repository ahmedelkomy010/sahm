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
        'executed_quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'executed_quantity' => 'decimal:2',
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
}
