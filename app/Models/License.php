<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'coordination_certificate_path',
        'has_restriction',
        'restriction_authority',
        'license_start_date',
        'license_end_date',
        'license_alert_days',
        'license_extension_file_path',
        'license_extension_start_date',
        'license_extension_end_date',
        'license_extension_alert_days',
        'license_closure_file_path',
    ];

    protected $casts = [
        'has_restriction' => 'boolean',
        'license_start_date' => 'date',
        'license_end_date' => 'date',
        'license_alert_days' => 'integer',
        'license_extension_start_date' => 'date',
        'license_extension_end_date' => 'date',
        'license_extension_alert_days' => 'integer',
    ];

    protected $dates = [
        'license_start_date',
        'license_end_date',
        'license_extension_start_date',
        'license_extension_end_date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 