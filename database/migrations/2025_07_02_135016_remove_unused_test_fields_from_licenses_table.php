<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // حذف جميع حقول الاختبارات غير المستخدمة في واجهة المستخدم
            
            // حقول الاختبارات المتقدمة من migration 2025_06_20_000001
            $advancedTestFields = [
                // نتائج الاختبارات المتقدمة
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
                
                // حالات الاختبارات المتقدمة
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
            ];
            
            // حقول الاختبارات الأساسية من migration 2025_06_29_161147
            $basicTestFields = [
                // الحقول البوليانية للتفعيل
                'has_depth_test',
                'has_soil_compaction_test',
                'has_rc1_mc1_test',
                'has_asphalt_test',
                'has_soil_test',
                'has_interlock_test',
                
                // قيم الاختبارات
                'depth_test_value',
                'soil_compaction_test_value',
                'rc1_mc1_test_value',
                'asphalt_test_value',
                'soil_test_value',
                'interlock_test_value',
                
                // مسارات ملفات الاختبارات
                'depth_test_file_path',
                'soil_compaction_test_file_path',
                'rc1_mc1_test_file_path',
                'asphalt_test_file_path',
                'soil_test_file_path',
                'interlock_test_file_path',
                
                // نتائج الاختبارات الأساسية
                'depth_test_result',
                'soil_compaction_test_result',
                'rc1_mc1_test_result',
                'asphalt_test_result',
                'soil_test_result',
                'interlock_test_result',
            ];
            
            // دمج جميع الحقول
            $allFieldsToRemove = array_merge($advancedTestFields, $basicTestFields);
            
            // حذف الحقول إذا كانت موجودة
            foreach ($allFieldsToRemove as $field) {
                if (Schema::hasColumn('licenses', $field)) {
                    $table->dropColumn($field);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // في حالة الحاجة للتراجع، يمكن إعادة تشغيل migrations الأصلية
            // هذا الـ migration يركز على التنظيف فقط
            // إذا كنت تحتاج لاستعادة الحقول، استخدم:
            // php artisan migrate:rollback --step=1
            // ثم أعد تشغيل migrations الأصلية المطلوبة
        });
    }
};
