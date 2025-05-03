<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'start_coordinates',
        'end_coordinates',
        'has_obstacles',
        'obstacles_notes'
    ];

    protected $casts = [
        'has_obstacles' => 'boolean',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function files()
    {
        return $this->hasMany(WorkOrderFile::class, 'work_order_id', 'work_order_id')
            ->where('file_type', 'like', 'image/%');
    }
} 