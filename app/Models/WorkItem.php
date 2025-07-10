<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'unit_price',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the work orders that use this work item.
     */
    public function workOrders()
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_items')
                    ->withPivot('quantity', 'unit_price', 'executed_quantity', 'notes')
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
