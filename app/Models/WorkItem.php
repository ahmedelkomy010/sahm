<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'unit',
        'unit_price',
        'notes'
    ];

    /**
     * Get the work orders that use this work item.
     */
    public function workOrders()
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_items')
                    ->withPivot('planned_quantity', 'actual_quantity', 'notes')
                    ->withTimestamps();
    }

    /**
     * Get the work order items for this work item.
     */
    public function workOrderItems()
    {
        return $this->hasMany(WorkOrderItem::class);
    }
}
