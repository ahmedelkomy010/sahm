<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceAttachment extends Model
{
    protected $fillable = [
        'work_order_id',
        'file_path',
        'original_filename',
        'file_type',
        'description'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 