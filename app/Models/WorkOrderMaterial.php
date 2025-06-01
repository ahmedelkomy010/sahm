<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'material_code',
        'material_description',
        'planned_quantity',
        'unit',
        'actual_quantity',
        'notes'
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
    ];

    /**
     * Get the work order that owns this material.
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the reference material details if available.
     */
    public function referenceMaterial()
    {
        return $this->hasOne(ReferenceMaterial::class, 'code', 'material_code');
    }
}
