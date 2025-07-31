<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'material_id',
        'quantity',
        'used_quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'used_quantity' => 'decimal:2',
    ];

    /**
     * Get the work order that owns this material.
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the material details.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
