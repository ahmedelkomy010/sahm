<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyWorkProgram extends Model
{
    protected $fillable = [
        'work_order_id',
        'program_date',
        'work_type',
        'location',
        'google_coordinates',
        'consultant_name',
        'site_engineer',
        'supervisor',
        'issuer',
        'receiver',
        'safety_officer',
        'quality_monitor',
        'notes',
    ];

    protected $casts = [
        'program_date' => 'date',
    ];

    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
