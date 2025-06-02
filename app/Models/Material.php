<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'work_order_id',
        'code',
        'description',
        'planned_quantity',
        'actual_quantity',
        'spent_quantity',
        'executed_site_quantity',
        'unit',
        'line',
        'check_in_file',
        'date_gatepass',
        'gate_pass_file',
        'store_in_file',
        'store_out_file',
        'ddo_file',
        'difference',
    ];
    
    protected $casts = [
        'date_gatepass' => 'date',
        'planned_quantity' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
        'spent_quantity' => 'decimal:2',
        'executed_site_quantity' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    protected $attributes = [
        'planned_quantity' => 0,
        'actual_quantity' => 0,
        'spent_quantity' => 0,
        'executed_site_quantity' => 0,
        'difference' => 0,
    ];
    
    /**
     * Get the work order that owns the material.
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Set the planned quantity attribute.
     */
    public function setPlannedQuantityAttribute($value)
    {
        $this->attributes['planned_quantity'] = $value ?? 0;
    }

    /**
     * Set the actual quantity attribute.
     */
    public function setActualQuantityAttribute($value)
    {
        $this->attributes['actual_quantity'] = $value ?? 0;
    }

    /**
     * Set the spent quantity attribute.
     */
    public function setSpentQuantityAttribute($value)
    {
        $this->attributes['spent_quantity'] = $value ?? 0;
    }

    /**
     * Set the executed site quantity attribute.
     */
    public function setExecutedSiteQuantityAttribute($value)
    {
        $this->attributes['executed_site_quantity'] = $value ?? 0;
    }
}
