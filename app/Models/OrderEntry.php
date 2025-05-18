<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'type',
        'value',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 