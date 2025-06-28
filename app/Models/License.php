<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'extension_start_date',
        'extension_end_date',
        'notes',
        'coordination_certificate_path',
        'coordination_certificate_number',
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
        'excavation_length',
        'excavation_width',
        'excavation_depth',
        'license_file_path',
        'payment_invoices_path',
        'payment_proof_path',
        'license_activation_path',
        'has_depth_test',
        'has_soil_compaction_test',
        'has_rc1_mc1_test',
        'has_asphalt_test',
        'has_soil_test',
        'has_interlock_test',
        'depth_test_file_path',
        'soil_compaction_file_path',
        'rc1_mc1_file_path',
        'asphalt_test_file_path',
        'soil_test_file_path',
        'interlock_test_file_path',
        'lab_table1_data',
        'lab_table2_data',
        'evac_table1_data',
        'evac_table2_data',
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
        'evacuations_file_path',
        'successful_tests_value',
        'failed_tests_value',
        'test_failure_reasons',
        'notes_attachments_path',
        
        // حقول مرفقات التمديد
        'extension_attachment_1',
        'extension_attachment_3',
        'extension_attachment_4',
        'extension_alert_days',

        // الحقول الجديدة للاختبارات
        'has_max_dry_density_pro_test',
        'max_dry_density_pro_test_file_path',
        'has_asphalt_ratio_gradation_test',
        'asphalt_ratio_gradation_test_file_path',
        'has_marshall_test',
        'marshall_test_file_path',
        'has_concrete_molds_test',
        'concrete_molds_test_file_path',
        'has_excavation_bottom_test',
        'excavation_bottom_test_file_path',
        'has_protection_depth_test',
        'protection_depth_test_file_path',
        'has_settlement_test',
        'settlement_test_file_path',
        'has_concrete_temperature_test',
        'concrete_temperature_test_file_path',
        'has_field_density_atomic_test',
        'field_density_atomic_test_file_path',
        'has_moisture_content_test',
        'moisture_content_test_file_path',
        'has_soil_layer_flatness_test',
        'soil_layer_flatness_test_file_path',
        'has_concrete_sample_test',
        'concrete_sample_test_file_path',
        'has_asphalt_spray_rate_test',
        'asphalt_spray_rate_test_file_path',
        'has_asphalt_temperature_test',
        'asphalt_temperature_test_file_path',
        'has_concrete_cylinder_compression_test',
        'concrete_cylinder_compression_test_file_path',
        'has_soil_particle_analysis_test',
        'soil_particle_analysis_test_file_path',
        'has_liquid_plastic_limit_test',
        'liquid_plastic_limit_test_file_path',
        'has_proctor_test',
        'proctor_test_file_path',
        'has_asphalt_layer_flatness_test',
        'asphalt_layer_flatness_test_file_path',
        'has_asphalt_compaction_atomic_test',
        'asphalt_compaction_atomic_test_file_path',
        'has_bitumen_ratio_test',
        'bitumen_ratio_test_file_path',
        'has_asphalt_gradation_test',
        'asphalt_gradation_test_file_path',
        'has_asphalt_mix_gmm_test',
        'asphalt_mix_gmm_test_file_path',
        'has_marshall_density_test',
        'marshall_density_test_file_path',
        'has_aggregate_ratio_test',
        'aggregate_ratio_test_file_path',
        'has_stability_deficiency_test',
        'stability_deficiency_test_file_path',
        'has_stability_degree_test',
        'stability_degree_test_file_path',
        'has_backup_test',
        'backup_test_file_path',
        'backup_test_type',
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
        'evac_table1_data' => 'array',
        'evac_table2_data' => 'array',
        'license_start_date' => 'date',
        'license_end_date' => 'date',
        'license_date' => 'date',
        'extension_start_date' => 'date',
        'extension_end_date' => 'date',
        'evac_date' => 'date',

        'license_alert_days' => 'integer',
        'license_length' => 'float',
        'license_value' => 'decimal:2',
        'extension_value' => 'decimal:2',
        'excavation_length' => 'decimal:2',
        'excavation_width' => 'decimal:2',
        'excavation_depth' => 'decimal:2',
        'license_extension_start_date' => 'date',
        'license_extension_end_date' => 'date',
        'license_extension_alert_days' => 'integer',
        'invoice_extension_start_date' => 'date',
        'invoice_extension_end_date' => 'date',
        'evac_license_value' => 'decimal:2',
        'evac_amount' => 'decimal:2',
        'successful_tests_value' => 'decimal:2',
        'failed_tests_value' => 'decimal:2',
        'extension_alert_days' => 'integer',

        // الحقول الجديدة للاختبارات
        'has_max_dry_density_pro_test' => 'boolean',
        'has_asphalt_ratio_gradation_test' => 'boolean',
        'has_marshall_test' => 'boolean',
        'has_concrete_molds_test' => 'boolean',
        'has_excavation_bottom_test' => 'boolean',
        'has_protection_depth_test' => 'boolean',
        'has_settlement_test' => 'boolean',
        'has_concrete_temperature_test' => 'boolean',
        'has_field_density_atomic_test' => 'boolean',
        'has_moisture_content_test' => 'boolean',
        'has_soil_layer_flatness_test' => 'boolean',
        'has_concrete_sample_test' => 'boolean',
        'has_asphalt_spray_rate_test' => 'boolean',
        'has_asphalt_temperature_test' => 'boolean',
        'has_concrete_cylinder_compression_test' => 'boolean',
        'has_soil_particle_analysis_test' => 'boolean',
        'has_liquid_plastic_limit_test' => 'boolean',
        'has_proctor_test' => 'boolean',
        'has_asphalt_layer_flatness_test' => 'boolean',
        'has_asphalt_compaction_atomic_test' => 'boolean',
        'has_bitumen_ratio_test' => 'boolean',
        'has_asphalt_gradation_test' => 'boolean',
        'has_asphalt_mix_gmm_test' => 'boolean',
        'has_marshall_density_test' => 'boolean',
        'has_aggregate_ratio_test' => 'boolean',
        'has_stability_deficiency_test' => 'boolean',
        'has_stability_degree_test' => 'boolean',
        'has_backup_test' => 'boolean',
    ];

    protected $dates = [
        'license_start_date',
        'license_end_date',
        'license_date',
        'extension_start_date',
        'extension_end_date',
        'license_extension_start_date',
        'license_extension_end_date',
        'evac_date',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the violations for the license.
     */
    public function violations()
    {
        return $this->hasMany(LicenseViolation::class);
    }

    /**
     * Update the violations count for this license
     */
    public function updateViolationsCount()
    {
        $this->violations_count = $this->violations()->count();
        $this->save();
    }

    /**
     * Get total violations value
     */
    public function getTotalViolationsValueAttribute()
    {
        return $this->violations()->sum('violation_license_value') ?? 0;
    }

    /**
     * Get violations count
     */
    public function getViolationsCountAttribute()
    {
        return $this->violations()->count();
    }

    /**
     * Get the streets for the license.
     */
    public function streets()
    {
        return $this->hasMany(Street::class);
    }
} 