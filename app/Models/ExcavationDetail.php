<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcavationDetail extends Model
{
    protected $fillable = [
        'work_order_id',
        'excavation_type',
        'length',
        'width',
        'depth',
        'price',
        'total',
        'is_open_excavation',
        'soil_type'
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'is_open_excavation' => 'boolean'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 