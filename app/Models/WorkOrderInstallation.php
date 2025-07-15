<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderInstallation extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'installation_type',
        'price',
        'quantity',
        'total'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 