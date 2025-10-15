<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the work order that owns the note
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the user who created the note
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
