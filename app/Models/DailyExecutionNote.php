<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyExecutionNote extends Model
{
    protected $fillable = [
        'work_order_id',
        'execution_date',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'execution_date' => 'date'
    ];

    /**
     * Get the work order that owns the note
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the user who created the note
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get note for specific work order and date
     */
    public static function getForWorkOrderAndDate($workOrderId, $date)
    {
        return static::where('work_order_id', $workOrderId)
                    ->where('execution_date', $date)
                    ->first();
    }
}
