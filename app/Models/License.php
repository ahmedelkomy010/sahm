<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'license_number',
        'license_date',
        'license_type',
        'license_value',
        'extension_value',
        'notes',
        'coordination_certificate_path',
        'coordination_certificate_notes',
        'letters_commitments_file_path',
        'has_restriction',
        'restriction_authority',
        'restriction_reason',
        'restriction_notes',
        'license_start_date',
        'license_end_date',
        'license_alert_days',
        'license_length',
        'license_1_path',
        'letters_and_commitments_path',
        'payment_invoices_path',
        'payment_proof_path',
        'activation_file_path',
        'has_depth_test',
        'has_soil_compaction_test',
        'has_rc1_mc1_test',
        'has_asphalt_test',
        'has_soil_test',
        'has_interlock_test',
        'lab_table1_data',
        'lab_table2_data',
        'soil_test_images_path',
        'test_results_file_path',
        'final_inspection_file_path',
        'license_extension_file_path',
        'license_extension_start_date',
        'license_extension_end_date',
        'license_extension_alert_days',
        'license_closure_file_path',
        'invoice_extension_file_path',
        'invoice_extension_start_date',
        'invoice_extension_end_date',
        'is_evacuated',
        'evac_license_number',
        'evac_license_value',
        'evac_payment_number',
        'evac_date',
        'evac_amount',
        'evac_files_path',
        'violation_license_number',
        'violation_license_value',
        'violation_license_date',
        'violation_due_date',
        'violation_number',
        'violation_payment_number',
        'violation_cause',
        'violation_files_path',
    ];

    protected $casts = [
        'has_restriction' => 'boolean',
        'has_depth_test' => 'boolean',
        'has_soil_compaction_test' => 'boolean',
        'has_rc1_mc1_test' => 'boolean',
        'has_asphalt_test' => 'boolean',
        'has_soil_test' => 'boolean',
        'has_interlock_test' => 'boolean',
        'is_evacuated' => 'boolean',
        'lab_table1_data' => 'array',
        'lab_table2_data' => 'array',
        'license_start_date' => 'date',
        'license_end_date' => 'date',
        'license_date' => 'date',
        'evac_date' => 'date',
        'violation_license_date' => 'date',
        'violation_due_date' => 'date',
        'license_alert_days' => 'integer',
        'license_length' => 'float',
        'license_extension_start_date' => 'date',
        'license_extension_end_date' => 'date',
        'license_extension_alert_days' => 'integer',
        'invoice_extension_start_date' => 'date',
        'invoice_extension_end_date' => 'date',
        'evac_license_value' => 'decimal:2',
        'evac_amount' => 'decimal:2',
        'violation_license_value' => 'decimal:2',
    ];

    protected $dates = [
        'license_start_date',
        'license_end_date',
        'license_extension_start_date',
        'license_extension_end_date',
        'evac_date',
        'violation_license_date',
        'violation_due_date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 