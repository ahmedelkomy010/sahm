<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'violation_amount',
        'violator',
        'violation_date',
        'description',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'violation_date' => 'date',
        'violation_amount' => 'decimal:2',
        'attachments' => 'array',
    ];

    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}