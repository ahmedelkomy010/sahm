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
            // اختبار الكثافة الجافة القصوى (طريقة برو)
            if (!Schema::hasColumn('licenses', 'has_max_dry_density_pro_test')) {
                $table->boolean('has_max_dry_density_pro_test')->default(false);
                $table->string('max_dry_density_pro_test_file_path')->nullable();
            }

            // اختبار تعيين نسبة الاسفلت والتدرج الحبيبي  
            if (!Schema::hasColumn('licenses', 'has_asphalt_ratio_gradation_test')) {
                $table->boolean('has_asphalt_ratio_gradation_test')->default(false);
                $table->string('asphalt_ratio_gradation_test_file_path')->nullable();
            }

            // اختبار تجربة مارشال
            if (!Schema::hasColumn('licenses', 'has_marshall_test')) {
                $table->boolean('has_marshall_test')->default(false);
                $table->string('marshall_test_file_path')->nullable();
            }

            // اختبار اعداد القوالب الخرسانية وصبها مع الكسر
            if (!Schema::hasColumn('licenses', 'has_concrete_molds_test')) {
                $table->boolean('has_concrete_molds_test')->default(false);
                $table->string('concrete_molds_test_file_path')->nullable();
            }

            // اختبار قاع الحفر
            if (!Schema::hasColumn('licenses', 'has_excavation_bottom_test')) {
                $table->boolean('has_excavation_bottom_test')->default(false);
                $table->string('excavation_bottom_test_file_path')->nullable();
            }

            // اختبار تحديد الاعماق لمواد الحماية
            if (!Schema::hasColumn('licenses', 'has_protection_depth_test')) {
                $table->boolean('has_protection_depth_test')->default(false);
                $table->string('protection_depth_test_file_path')->nullable();
            }

            // اختبار الهبوط
            if (!Schema::hasColumn('licenses', 'has_settlement_test')) {
                $table->boolean('has_settlement_test')->default(false);
                $table->string('settlement_test_file_path')->nullable();
            }

            // اختبار درجة حرارة الخرسانة
            if (!Schema::hasColumn('licenses', 'has_concrete_temperature_test')) {
                $table->boolean('has_concrete_temperature_test')->default(false);
                $table->string('concrete_temperature_test_file_path')->nullable();
            }

            // اختبار الكثافة الحقلية باستخدام الجهاز الذري
            if (!Schema::hasColumn('licenses', 'has_field_density_atomic_test')) {
                $table->boolean('has_field_density_atomic_test')->default(false);
                $table->string('field_density_atomic_test_file_path')->nullable();
            }

            // اختبار محتوي الرطوبة
            if (!Schema::hasColumn('licenses', 'has_moisture_content_test')) {
                $table->boolean('has_moisture_content_test')->default(false);
                $table->string('moisture_content_test_file_path')->nullable();
            }

            // اختبار استواء طبقة التربة
            if (!Schema::hasColumn('licenses', 'has_soil_layer_flatness_test')) {
                $table->boolean('has_soil_layer_flatness_test')->default(false);
                $table->string('soil_layer_flatness_test_file_path')->nullable();
            }

            // اختبار اخذ عينة من الخرسانة بالموقع
            if (!Schema::hasColumn('licenses', 'has_concrete_sample_test')) {
                $table->boolean('has_concrete_sample_test')->default(false);
                $table->string('concrete_sample_test_file_path')->nullable();
            }

            // اختبار معدل الرش الاسفلتي MC1/RC2
            if (!Schema::hasColumn('licenses', 'has_asphalt_spray_rate_test')) {
                $table->boolean('has_asphalt_spray_rate_test')->default(false);
                $table->string('asphalt_spray_rate_test_file_path')->nullable();
            }

            // اختبار قياس درجة حرارة الاسفلت
            if (!Schema::hasColumn('licenses', 'has_asphalt_temperature_test')) {
                $table->boolean('has_asphalt_temperature_test')->default(false);
                $table->string('asphalt_temperature_test_file_path')->nullable();
            }

            // اختبار تعيين مقاومة الضغط لخرسانة الاسطوانات
            if (!Schema::hasColumn('licenses', 'has_concrete_cylinder_compression_test')) {
                $table->boolean('has_concrete_cylinder_compression_test')->default(false);
                $table->string('concrete_cylinder_compression_test_file_path')->nullable();
            }

            // اختبار التحليل الحبيبي للتربة
            if (!Schema::hasColumn('licenses', 'has_soil_particle_analysis_test')) {
                $table->boolean('has_soil_particle_analysis_test')->default(false);
                $table->string('soil_particle_analysis_test_file_path')->nullable();
            }

            // اختبار تحديد حد السيولة واللدونة ومؤشر اللدونة
            if (!Schema::hasColumn('licenses', 'has_liquid_plastic_limit_test')) {
                $table->boolean('has_liquid_plastic_limit_test')->default(false);
                $table->string('liquid_plastic_limit_test_file_path')->nullable();
            }

            // اختبار البروكتور
            if (!Schema::hasColumn('licenses', 'has_proctor_test')) {
                $table->boolean('has_proctor_test')->default(false);
                $table->string('proctor_test_file_path')->nullable();
            }

            // اختبار استواء طبقة الاسفلت
            if (!Schema::hasColumn('licenses', 'has_asphalt_layer_flatness_test')) {
                $table->boolean('has_asphalt_layer_flatness_test')->default(false);
                $table->string('asphalt_layer_flatness_test_file_path')->nullable();
            }

            // اختبار قياس قوة دمك الاسفلت بالجهاز الذري
            if (!Schema::hasColumn('licenses', 'has_asphalt_compaction_atomic_test')) {
                $table->boolean('has_asphalt_compaction_atomic_test')->default(false);
                $table->string('asphalt_compaction_atomic_test_file_path')->nullable();
            }

            // اختبار تعيين نسبة البترومين بالخلطة الاسفلتية
            if (!Schema::hasColumn('licenses', 'has_bitumen_ratio_test')) {
                $table->boolean('has_bitumen_ratio_test')->default(false);
                $table->string('bitumen_ratio_test_file_path')->nullable();
            }

            // اختبار التدرج الحبيبي للاسفلت
            if (!Schema::hasColumn('licenses', 'has_asphalt_gradation_test')) {
                $table->boolean('has_asphalt_gradation_test')->default(false);
                $table->string('asphalt_gradation_test_file_path')->nullable();
            }

            // اختبار الوزن النوعي لخليط الاسفلت GMM
            if (!Schema::hasColumn('licenses', 'has_asphalt_mix_gmm_test')) {
                $table->boolean('has_asphalt_mix_gmm_test')->default(false);
                $table->string('asphalt_mix_gmm_test_file_path')->nullable();
            }

            // اختبار كثافة مارشال
            if (!Schema::hasColumn('licenses', 'has_marshall_density_test')) {
                $table->boolean('has_marshall_density_test')->default(false);
                $table->string('marshall_density_test_file_path')->nullable();
            }

            // اختبار تحديد نسبة الحصمة الاجمالية بالخلطة
            if (!Schema::hasColumn('licenses', 'has_aggregate_ratio_test')) {
                $table->boolean('has_aggregate_ratio_test')->default(false);
                $table->string('aggregate_ratio_test_file_path')->nullable();
            }

            // اختبار تحديد النقص في درجة الثبات
            if (!Schema::hasColumn('licenses', 'has_stability_deficiency_test')) {
                $table->boolean('has_stability_deficiency_test')->default(false);
                $table->string('stability_deficiency_test_file_path')->nullable();
            }

            // اختبار قياس درجة الثبات
            if (!Schema::hasColumn('licenses', 'has_stability_degree_test')) {
                $table->boolean('has_stability_degree_test')->default(false);
                $table->string('stability_degree_test_file_path')->nullable();
            }

            // اختبار احتياطي
            if (!Schema::hasColumn('licenses', 'has_backup_test')) {
                $table->boolean('has_backup_test')->default(false);
                $table->string('backup_test_file_path')->nullable();
                $table->string('backup_test_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $columns = [
                'has_max_dry_density_pro_test', 'max_dry_density_pro_test_file_path',
                'has_asphalt_ratio_gradation_test', 'asphalt_ratio_gradation_test_file_path',
                'has_marshall_test', 'marshall_test_file_path',
                'has_concrete_molds_test', 'concrete_molds_test_file_path',
                'has_excavation_bottom_test', 'excavation_bottom_test_file_path',
                'has_protection_depth_test', 'protection_depth_test_file_path',
                'has_settlement_test', 'settlement_test_file_path',
                'has_concrete_temperature_test', 'concrete_temperature_test_file_path',
                'has_field_density_atomic_test', 'field_density_atomic_test_file_path',
                'has_moisture_content_test', 'moisture_content_test_file_path',
                'has_soil_layer_flatness_test', 'soil_layer_flatness_test_file_path',
                'has_concrete_sample_test', 'concrete_sample_test_file_path',
                'has_asphalt_spray_rate_test', 'asphalt_spray_rate_test_file_path',
                'has_asphalt_temperature_test', 'asphalt_temperature_test_file_path',
                'has_concrete_cylinder_compression_test', 'concrete_cylinder_compression_test_file_path',
                'has_soil_particle_analysis_test', 'soil_particle_analysis_test_file_path',
                'has_liquid_plastic_limit_test', 'liquid_plastic_limit_test_file_path',
                'has_proctor_test', 'proctor_test_file_path',
                'has_asphalt_layer_flatness_test', 'asphalt_layer_flatness_test_file_path',
                'has_asphalt_compaction_atomic_test', 'asphalt_compaction_atomic_test_file_path',
                'has_bitumen_ratio_test', 'bitumen_ratio_test_file_path',
                'has_asphalt_gradation_test', 'asphalt_gradation_test_file_path',
                'has_asphalt_mix_gmm_test', 'asphalt_mix_gmm_test_file_path',
                'has_marshall_density_test', 'marshall_density_test_file_path',
                'has_aggregate_ratio_test', 'aggregate_ratio_test_file_path',
                'has_stability_deficiency_test', 'stability_deficiency_test_file_path',
                'has_stability_degree_test', 'stability_degree_test_file_path',
                'has_backup_test', 'backup_test_file_path', 'backup_test_type',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 