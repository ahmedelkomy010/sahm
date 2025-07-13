<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'restriction_notes',
        'user_id',
        'status',
        'issue_date',
        'description',
        // حقول شهادة التنسيق
        'coordination_certificate_number',
        'coordination_certificate_path',
        'coordination_certificate_notes',
        'letters_commitments_file_path',
        'letters_and_commitments_path',
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
        // حقول مرفقات التمديد
        'extension_attachment_1',
        'extension_attachment_3',
        'extension_attachment_4',
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
        'interlock_test_result',
        // حقول الاختبارات الجديدة
        'lab_tests_data',
        'total_tests_count',
        'successful_tests_count',
        'failed_tests_count',
        'total_tests_amount',
        'successful_tests_amount',
        'failed_tests_amount',
        'issue_date',
        'activation_date',
        'expiry_date',
        'activation_files',
        'payment_receipts',
        'payment_proof_files',
        'status',
        'license_days',
        'extension_start_date',
        'extension_end_date',
        'license_extension_alert_days',
        'invoice_extension_start_date',
        'invoice_extension_end_date',
        'evac_license_value',
        'evac_amount',
        'successful_tests_value',
        'failed_tests_value',
        'extension_alert_days',
        'has_backup_test',
        'lab_tests_data',
        'total_tests_amount',
        'successful_tests_amount',
        'failed_tests_amount',
    ];

    protected $casts = [
        'license_date' => 'datetime',
        'license_start_date' => 'datetime',
        'license_end_date' => 'datetime',
        'extension_start_date' => 'datetime',
        'extension_end_date' => 'datetime',
        'evacuation_date' => 'datetime',
        'lab_test_date' => 'datetime',
        'attachments' => 'array',
        'notes_attachments' => 'array',
        'has_restriction' => 'boolean',
        'is_restricted' => 'boolean',
        'has_depth_test' => 'boolean',
        'has_soil_test' => 'boolean',
        'has_asphalt_test' => 'boolean',
        'has_soil_compaction_test' => 'boolean',
        'has_rc1_mc1_test' => 'boolean',
        'has_interlock_test' => 'boolean',
        'is_evacuated' => 'boolean',
    ];

    public function getIsRestrictedAttribute(): bool
    {
        return $this->has_restriction ?? false;
    }

    public function getRestrictionStatusTextAttribute(): string
    {
        return $this->is_restricted ? 'يوجد حظر' : 'لا يوجد حظر';
    }

    public function getRestrictionStatusColorAttribute(): string
    {
        return $this->is_restricted ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
    }

    public function getFormattedRestrictionAuthorityAttribute(): string
    {
        return $this->restriction_authority ?? 'غير محدد';
    }

    public function getFormattedRestrictionReasonAttribute(): string
    {
        return $this->restriction_reason ?? 'غير محدد';
    }

    protected $dates = [
        'license_start_date',
        'license_end_date',
        'license_date',
        'extension_start_date',
        'extension_end_date',
        'extension_start_date',
        'extension_end_date',
        'evac_date',
        'issue_date',
        'activation_date',
        'expiry_date',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(LicenseExtension::class);
    }

    /**
     * Get the violations for the license.
     */
    public function violations(): HasMany
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

    /**
     * Get the excavation history for the license.
     */
    public function excavationHistory()
    {
        return $this->hasMany(ExcavationDetail::class);
    }

    /**
     * Update lab tests summary
     */
    public function updateLabTestsSummary()
    {
        $tests = json_decode($this->lab_tests_data ?? '[]', true);
        
        $this->total_tests_count = count($tests);
        $this->successful_tests_count = collect($tests)->where('result', 'ناجح')->count();
        $this->failed_tests_count = collect($tests)->where('result', 'راسب')->count();
        
        $this->total_tests_amount = collect($tests)->sum('price');
        $this->successful_tests_amount = collect($tests)
            ->where('result', 'ناجح')
            ->sum('price');
        $this->failed_tests_amount = collect($tests)
            ->where('result', 'راسب')
            ->sum('price');
            
        $this->save();
    }

    public function getIssueDateAttribute()
    {
        return $this->license_date;
    }

    public function getActivationDateAttribute()
    {
        return $this->license_start_date;
    }

    public function getExpiryDateAttribute()
    {
        return $this->license_end_date;
    }

    public function getLicenseDaysAttribute()
    {
        if (!$this->license_start_date || !$this->license_end_date) {
            return 0;
        }

        return $this->license_start_date->diffInDays($this->license_end_date);
    }

    public function getFormattedDimensionsAttribute(): string
    {
        return sprintf(
            '%s × %s × %s م',
            number_format($this->excavation_length ?? 0, 2),
            number_format($this->excavation_width ?? 0, 2),
            number_format($this->excavation_depth ?? 0, 2)
        );
    }

    public function getFormattedValueAttribute(): string
    {
        return number_format($this->license_value ?? 0, 2) . ' ريال';
    }

    public function getStatusTextAttribute(): string
    {
        $today = now();
        
        // Check for active extensions
        $hasActiveExtension = $this->extensions()
            ->where('end_date', '>', $today)
            ->exists();

        // تحقق من تاريخ انتهاء الرخصة
        if (!$this->license_end_date) {
            return 'غير محدد';
        }

        if ($this->license_end_date > $today || $hasActiveExtension) {
            return 'سارية';
        }
        
        return 'منتهية';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status_text) {
            'سارية' => 'bg-green-100 text-green-800',
            'منتهية' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Add the attachments relationship
    public function attachments(): HasMany
    {
        return $this->hasMany(InvoiceAttachment::class, 'license_id');
    }
} 