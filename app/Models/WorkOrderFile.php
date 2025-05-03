<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 