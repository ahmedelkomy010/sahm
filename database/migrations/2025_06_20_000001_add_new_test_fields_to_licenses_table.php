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
            // إضافة حقول نتائج الاختبارات فقط (الحقول الأخرى موجودة بالفعل)
            if (!Schema::hasColumn('licenses', 'max_dry_density_pro_test_result')) {
                $table->text('max_dry_density_pro_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_ratio_gradation_test_result')) {
                $table->text('asphalt_ratio_gradation_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'marshall_test_result')) {
                $table->text('marshall_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_molds_test_result')) {
                $table->text('concrete_molds_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'excavation_bottom_test_result')) {
                $table->text('excavation_bottom_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'protection_depth_test_result')) {
                $table->text('protection_depth_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'settlement_test_result')) {
                $table->text('settlement_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_temperature_test_result')) {
                $table->text('concrete_temperature_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'field_density_atomic_test_result')) {
                $table->text('field_density_atomic_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'moisture_content_test_result')) {
                $table->text('moisture_content_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'soil_layer_flatness_test_result')) {
                $table->text('soil_layer_flatness_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_sample_test_result')) {
                $table->text('concrete_sample_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_spray_rate_test_result')) {
                $table->text('asphalt_spray_rate_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_temperature_test_result')) {
                $table->text('asphalt_temperature_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_cylinder_compression_test_result')) {
                $table->text('concrete_cylinder_compression_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'soil_particle_analysis_test_result')) {
                $table->text('soil_particle_analysis_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'liquid_plastic_limit_test_result')) {
                $table->text('liquid_plastic_limit_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'proctor_test_result')) {
                $table->text('proctor_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_layer_flatness_test_result')) {
                $table->text('asphalt_layer_flatness_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_compaction_atomic_test_result')) {
                $table->text('asphalt_compaction_atomic_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'bitumen_ratio_test_result')) {
                $table->text('bitumen_ratio_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_gradation_test_result')) {
                $table->text('asphalt_gradation_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_mix_gmm_test_result')) {
                $table->text('asphalt_mix_gmm_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'marshall_density_test_result')) {
                $table->text('marshall_density_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'aggregate_ratio_test_result')) {
                $table->text('aggregate_ratio_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'stability_deficiency_test_result')) {
                $table->text('stability_deficiency_test_result')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'stability_degree_test_result')) {
                $table->text('stability_degree_test_result')->nullable();
            }

            // إضافة حقول حالة الاختبارات
            if (!Schema::hasColumn('licenses', 'max_dry_density_pro_test_status')) {
                $table->enum('max_dry_density_pro_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_ratio_gradation_test_status')) {
                $table->enum('asphalt_ratio_gradation_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'marshall_test_status')) {
                $table->enum('marshall_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_molds_test_status')) {
                $table->enum('concrete_molds_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'excavation_bottom_test_status')) {
                $table->enum('excavation_bottom_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'protection_depth_test_status')) {
                $table->enum('protection_depth_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'settlement_test_status')) {
                $table->enum('settlement_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_temperature_test_status')) {
                $table->enum('concrete_temperature_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'field_density_atomic_test_status')) {
                $table->enum('field_density_atomic_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'moisture_content_test_status')) {
                $table->enum('moisture_content_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'soil_layer_flatness_test_status')) {
                $table->enum('soil_layer_flatness_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_sample_test_status')) {
                $table->enum('concrete_sample_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_spray_rate_test_status')) {
                $table->enum('asphalt_spray_rate_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_temperature_test_status')) {
                $table->enum('asphalt_temperature_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'concrete_cylinder_compression_test_status')) {
                $table->enum('concrete_cylinder_compression_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'soil_particle_analysis_test_status')) {
                $table->enum('soil_particle_analysis_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'liquid_plastic_limit_test_status')) {
                $table->enum('liquid_plastic_limit_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'proctor_test_status')) {
                $table->enum('proctor_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_layer_flatness_test_status')) {
                $table->enum('asphalt_layer_flatness_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_compaction_atomic_test_status')) {
                $table->enum('asphalt_compaction_atomic_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'bitumen_ratio_test_status')) {
                $table->enum('bitumen_ratio_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_gradation_test_status')) {
                $table->enum('asphalt_gradation_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'asphalt_mix_gmm_test_status')) {
                $table->enum('asphalt_mix_gmm_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'marshall_density_test_status')) {
                $table->enum('marshall_density_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'aggregate_ratio_test_status')) {
                $table->enum('aggregate_ratio_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'stability_deficiency_test_status')) {
                $table->enum('stability_deficiency_test_status', ['pass', 'fail'])->nullable();
            }
            if (!Schema::hasColumn('licenses', 'stability_degree_test_status')) {
                $table->enum('stability_degree_test_status', ['pass', 'fail'])->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // حذف حقول نتائج الاختبارات
            $table->dropColumn([
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
                
                // حذف حقول حالة الاختبارات
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
                'stability_degree_test_status'
            ]);
        });
    }
}; 