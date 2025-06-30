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
        'license_status',
        'license_file_path',
        'notes',
        'attachments',
        'restriction_reason',
        'restriction_authority',
        // حقول شهادة التنسيق
        'coordination_certificate_number',
        'coordination_certificate_path',
        'coordination_certificate_notes',
        'letters_commitments_file_path',
        'has_restriction',
        // حقول رخصة الحفر
        'license_value',
        'extension_value',
        'excavation_length',
        'excavation_width',
        'excavation_depth',
        'license_start_date',
        'license_end_date',
        'extension_start_date',
        'extension_end_date',
        'payment_invoices_path',
        'payment_proof_path',
        'license_activation_path',
        'notes_attachments_path',
        'evacuation_date',
        'evacuation_time',
        'evacuation_duration',
        'evacuation_tables',
        'evacuation_file_path',
        'lab_test_date',
        'lab_test_time',
        'lab_test_duration',
        'lab_test_tables',
        'lab_test_file_path',
        'lab_test_result',
        'lab_test_status',
        'lab_test_notes',
        'lab_test_attachments',
        // حقول تفعيل الاختبارات
        'has_max_dry_density_pro_test',
        'has_asphalt_ratio_gradation_test',
        'has_marshall_test',
        'has_concrete_molds_test',
        'has_excavation_bottom_test',
        'has_protection_depth_test',
        'has_settlement_test',
        'has_concrete_temperature_test',
        'has_field_density_atomic_test',
        'has_moisture_content_test',
        'has_soil_layer_flatness_test',
        'has_concrete_sample_test',
        'has_asphalt_spray_rate_test',
        'has_asphalt_temperature_test',
        'has_concrete_cylinder_compression_test',
        'has_soil_particle_analysis_test',
        'has_liquid_plastic_limit_test',
        'has_proctor_test',
        'has_asphalt_layer_flatness_test',
        'has_asphalt_compaction_atomic_test',
        'has_bitumen_ratio_test',
        'has_asphalt_gradation_test',
        'has_asphalt_mix_gmm_test',
        'has_marshall_density_test',
        'has_aggregate_ratio_test',
        'has_stability_deficiency_test',
        'has_stability_degree_test',
        // حقول مرفقات الاختبارات
        'max_dry_density_pro_test_file_path',
        'asphalt_ratio_gradation_test_file_path',
        'marshall_test_file_path',
        'concrete_molds_test_file_path',
        'excavation_bottom_test_file_path',
        'protection_depth_test_file_path',
        'settlement_test_file_path',
        'concrete_temperature_test_file_path',
        'field_density_atomic_test_file_path',
        'moisture_content_test_file_path',
        'soil_layer_flatness_test_file_path',
        'concrete_sample_test_file_path',
        'asphalt_spray_rate_test_file_path',
        'asphalt_temperature_test_file_path',
        'concrete_cylinder_compression_test_file_path',
        'soil_particle_analysis_test_file_path',
        'liquid_plastic_limit_test_file_path',
        'proctor_test_file_path',
        'asphalt_layer_flatness_test_file_path',
        'asphalt_compaction_atomic_test_file_path',
        'bitumen_ratio_test_file_path',
        'asphalt_gradation_test_file_path',
        'asphalt_mix_gmm_test_file_path',
        'marshall_density_test_file_path',
        'aggregate_ratio_test_file_path',
        'stability_deficiency_test_file_path',
        'stability_degree_test_file_path',
        // حقول نتائج الاختبارات
        'max_dry_density_pro_test_result',
        'asphalt_ratio_gradation_test_result',
        'marshall_test_result',
        'concrete_molds_test_result',
        'excavation_bottom_test_result',
        'protection_depth_test_result',
        'settlement_test_result',
        'concrete_temperature_test_result',
        'field_density_atomic_test_result',
        'moisture_content_test_result',
        'soil_layer_flatness_test_result',
        'concrete_sample_test_result',
        'asphalt_spray_rate_test_result',
        'asphalt_temperature_test_result',
        'concrete_cylinder_compression_test_result',
        'soil_particle_analysis_test_result',
        'liquid_plastic_limit_test_result',
        'proctor_test_result',
        'asphalt_layer_flatness_test_result',
        'asphalt_compaction_atomic_test_result',
        'bitumen_ratio_test_result',
        'asphalt_gradation_test_result',
        'asphalt_mix_gmm_test_result',
        'marshall_density_test_result',
        'aggregate_ratio_test_result',
        'stability_deficiency_test_result',
        'stability_degree_test_result',
        // حقول حالة الاختبارات
        'max_dry_density_pro_test_status',
        'asphalt_ratio_gradation_test_status',
        'marshall_test_status',
        'concrete_molds_test_status',
        'excavation_bottom_test_status',
        'protection_depth_test_status',
        'settlement_test_status',
        'concrete_temperature_test_status',
        'field_density_atomic_test_status',
        'moisture_content_test_status',
        'soil_layer_flatness_test_status',
        'concrete_sample_test_status',
        'asphalt_spray_rate_test_status',
        'asphalt_temperature_test_status',
        'concrete_cylinder_compression_test_status',
        'soil_particle_analysis_test_status',
        'liquid_plastic_limit_test_status',
        'proctor_test_status',
        'asphalt_layer_flatness_test_status',
        'asphalt_compaction_atomic_test_status',
        'bitumen_ratio_test_status',
        'asphalt_gradation_test_status',
        'asphalt_mix_gmm_test_status',
        'marshall_density_test_status',
        'aggregate_ratio_test_status',
        'stability_deficiency_test_status',
        'stability_degree_test_status',
        // حقول جداول الإخلاءات والمختبر
        'evac_table1_data',
        'evac_table2_data',
        'lab_table1_data',
        'lab_table2_data',
        'evacuation_data',
        // الحقول الأساسية للاختبارات
        'has_depth_test',
        'has_soil_compaction_test',
        'has_rc1_mc1_test',
        'has_asphalt_test',
        'has_soil_test',
        'has_interlock_test',
        'depth_test_value',
        'soil_compaction_test_value',
        'rc1_mc1_test_value',
        'asphalt_test_value',
        'soil_test_value',
        'interlock_test_value',
        'depth_test_file_path',
        'soil_compaction_test_file_path',
        'rc1_mc1_test_file_path',
        'asphalt_test_file_path',
        'soil_test_file_path',
        'interlock_test_file_path',
        'depth_test_result',
        'soil_compaction_test_result',
        'rc1_mc1_test_result',
        'asphalt_test_result',
        'soil_test_result',
        'interlock_test_result'
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
        'evacuation_data' => 'array',
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

    /**
     * دالة مساعدة للحصول على ملفات متعددة من JSON path
     */
    public function getMultipleFiles($fieldName)
    {
        $files = [];
        
        if ($this->{$fieldName}) {
            try {
                $decoded = json_decode($this->{$fieldName}, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $files = array_filter($decoded, function($path) {
                        return !empty($path) && is_string($path);
                    });
                } elseif (is_string($this->{$fieldName})) {
                    $files = [$this->{$fieldName}];
                }
            } catch (\Exception $e) {
                \Log::error("Error parsing {$fieldName}: " . $e->getMessage());
            }
        }
        
        return $files;
    }

    /**
     * التحقق من وجود ملف واحد
     */
    public function hasFile($fieldName)
    {
        return !empty($this->{$fieldName}) && \Storage::disk('public')->exists($this->{$fieldName});
    }

    /**
     * الحصول على رابط ملف واحد
     */
    public function getFileUrl($fieldName)
    {
        if ($this->hasFile($fieldName)) {
            return \Storage::disk('public')->url($this->{$fieldName});
        }
        return null;
    }

    /**
     * الحصول على روابط ملفات متعددة
     */
    public function getMultipleFileUrls($fieldName)
    {
        $files = $this->getMultipleFiles($fieldName);
        $urls = [];
        
        foreach ($files as $file) {
            if (!empty($file) && \Storage::disk('public')->exists($file)) {
                $urls[] = [
                    'path' => $file,
                    'url' => \Storage::disk('public')->url($file),
                    'exists' => true
                ];
            } else {
                $urls[] = [
                    'path' => $file,
                    'url' => null,
                    'exists' => false
                ];
            }
        }
        
        return $urls;
    }
} 