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
        'unit',
        'line',
        'check_in_file',
        'check_out_file',
        'date_gatepass',
        'stock_in',
        'stock_in_file',
        'stock_out',
        'stock_out_file',
        'actual_quantity',
        'difference'
    ];
    
    protected $casts = [
        'date_gatepass' => 'date',
        'planned_quantity' => 'decimal:2',
        'stock_in' => 'decimal:2',
        'stock_out' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
        'difference' => 'decimal:2',
    ];
    
    /**
     * Get the work order that owns the material.
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
