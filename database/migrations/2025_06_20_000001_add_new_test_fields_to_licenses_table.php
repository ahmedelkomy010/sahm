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
            // حقول تفعيل الاختبارات الجديدة
            $table->boolean('has_max_dry_density_pro_test')->default(false);
            $table->boolean('has_asphalt_ratio_gradation_test')->default(false);
            $table->boolean('has_marshall_test')->default(false);
            $table->boolean('has_concrete_molds_test')->default(false);
            $table->boolean('has_excavation_bottom_test')->default(false);
            $table->boolean('has_protection_depth_test')->default(false);
            $table->boolean('has_settlement_test')->default(false);
            $table->boolean('has_concrete_temperature_test')->default(false);
            $table->boolean('has_field_density_atomic_test')->default(false);
            $table->boolean('has_moisture_content_test')->default(false);
            $table->boolean('has_soil_layer_flatness_test')->default(false);
            $table->boolean('has_concrete_sample_test')->default(false);
            $table->boolean('has_asphalt_spray_rate_test')->default(false);
            $table->boolean('has_asphalt_temperature_test')->default(false);
            $table->boolean('has_concrete_cylinder_compression_test')->default(false);
            $table->boolean('has_soil_particle_analysis_test')->default(false);
            $table->boolean('has_liquid_plastic_limit_test')->default(false);
            $table->boolean('has_proctor_test')->default(false);
            $table->boolean('has_asphalt_layer_flatness_test')->default(false);
            $table->boolean('has_asphalt_compaction_atomic_test')->default(false);
            $table->boolean('has_bitumen_ratio_test')->default(false);
            $table->boolean('has_asphalt_gradation_test')->default(false);
            $table->boolean('has_asphalt_mix_gmm_test')->default(false);
            $table->boolean('has_marshall_density_test')->default(false);
            $table->boolean('has_aggregate_ratio_test')->default(false);
            $table->boolean('has_stability_deficiency_test')->default(false);
            $table->boolean('has_stability_degree_test')->default(false);

            // حقول مرفقات الاختبارات الجديدة
            $table->string('max_dry_density_pro_test_file_path')->nullable();
            $table->string('asphalt_ratio_gradation_test_file_path')->nullable();
            $table->string('marshall_test_file_path')->nullable();
            $table->string('concrete_molds_test_file_path')->nullable();
            $table->string('excavation_bottom_test_file_path')->nullable();
            $table->string('protection_depth_test_file_path')->nullable();
            $table->string('settlement_test_file_path')->nullable();
            $table->string('concrete_temperature_test_file_path')->nullable();
            $table->string('field_density_atomic_test_file_path')->nullable();
            $table->string('moisture_content_test_file_path')->nullable();
            $table->string('soil_layer_flatness_test_file_path')->nullable();
            $table->string('concrete_sample_test_file_path')->nullable();
            $table->string('asphalt_spray_rate_test_file_path')->nullable();
            $table->string('asphalt_temperature_test_file_path')->nullable();
            $table->string('concrete_cylinder_compression_test_file_path')->nullable();
            $table->string('soil_particle_analysis_test_file_path')->nullable();
            $table->string('liquid_plastic_limit_test_file_path')->nullable();
            $table->string('proctor_test_file_path')->nullable();
            $table->string('asphalt_layer_flatness_test_file_path')->nullable();
            $table->string('asphalt_compaction_atomic_test_file_path')->nullable();
            $table->string('bitumen_ratio_test_file_path')->nullable();
            $table->string('asphalt_gradation_test_file_path')->nullable();
            $table->string('asphalt_mix_gmm_test_file_path')->nullable();
            $table->string('marshall_density_test_file_path')->nullable();
            $table->string('aggregate_ratio_test_file_path')->nullable();
            $table->string('stability_deficiency_test_file_path')->nullable();
            $table->string('stability_degree_test_file_path')->nullable();

            // حقول نتائج الاختبارات الجديدة
            $table->string('max_dry_density_pro_test_result')->nullable();
            $table->string('asphalt_ratio_gradation_test_result')->nullable();
            $table->string('marshall_test_result')->nullable();
            $table->string('concrete_molds_test_result')->nullable();
            $table->string('excavation_bottom_test_result')->nullable();
            $table->string('protection_depth_test_result')->nullable();
            $table->string('settlement_test_result')->nullable();
            $table->string('concrete_temperature_test_result')->nullable();
            $table->string('field_density_atomic_test_result')->nullable();
            $table->string('moisture_content_test_result')->nullable();
            $table->string('soil_layer_flatness_test_result')->nullable();
            $table->string('concrete_sample_test_result')->nullable();
            $table->string('asphalt_spray_rate_test_result')->nullable();
            $table->string('asphalt_temperature_test_result')->nullable();
            $table->string('concrete_cylinder_compression_test_result')->nullable();
            $table->string('soil_particle_analysis_test_result')->nullable();
            $table->string('liquid_plastic_limit_test_result')->nullable();
            $table->string('proctor_test_result')->nullable();
            $table->string('asphalt_layer_flatness_test_result')->nullable();
            $table->string('asphalt_compaction_atomic_test_result')->nullable();
            $table->string('bitumen_ratio_test_result')->nullable();
            $table->string('asphalt_gradation_test_result')->nullable();
            $table->string('asphalt_mix_gmm_test_result')->nullable();
            $table->string('marshall_density_test_result')->nullable();
            $table->string('aggregate_ratio_test_result')->nullable();
            $table->string('stability_deficiency_test_result')->nullable();
            $table->string('stability_degree_test_result')->nullable();

            // حقول حالة الاختبارات الجديدة
            $table->enum('max_dry_density_pro_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_ratio_gradation_test_status', ['pass', 'fail'])->nullable();
            $table->enum('marshall_test_status', ['pass', 'fail'])->nullable();
            $table->enum('concrete_molds_test_status', ['pass', 'fail'])->nullable();
            $table->enum('excavation_bottom_test_status', ['pass', 'fail'])->nullable();
            $table->enum('protection_depth_test_status', ['pass', 'fail'])->nullable();
            $table->enum('settlement_test_status', ['pass', 'fail'])->nullable();
            $table->enum('concrete_temperature_test_status', ['pass', 'fail'])->nullable();
            $table->enum('field_density_atomic_test_status', ['pass', 'fail'])->nullable();
            $table->enum('moisture_content_test_status', ['pass', 'fail'])->nullable();
            $table->enum('soil_layer_flatness_test_status', ['pass', 'fail'])->nullable();
            $table->enum('concrete_sample_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_spray_rate_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_temperature_test_status', ['pass', 'fail'])->nullable();
            $table->enum('concrete_cylinder_compression_test_status', ['pass', 'fail'])->nullable();
            $table->enum('soil_particle_analysis_test_status', ['pass', 'fail'])->nullable();
            $table->enum('liquid_plastic_limit_test_status', ['pass', 'fail'])->nullable();
            $table->enum('proctor_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_layer_flatness_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_compaction_atomic_test_status', ['pass', 'fail'])->nullable();
            $table->enum('bitumen_ratio_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_gradation_test_status', ['pass', 'fail'])->nullable();
            $table->enum('asphalt_mix_gmm_test_status', ['pass', 'fail'])->nullable();
            $table->enum('marshall_density_test_status', ['pass', 'fail'])->nullable();
            $table->enum('aggregate_ratio_test_status', ['pass', 'fail'])->nullable();
            $table->enum('stability_deficiency_test_status', ['pass', 'fail'])->nullable();
            $table->enum('stability_degree_test_status', ['pass', 'fail'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // حذف حقول تفعيل الاختبارات الجديدة
            $table->dropColumn([
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
                'has_stability_degree_test'
            ]);

            // حذف حقول مرفقات الاختبارات الجديدة
            $table->dropColumn([
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
                'stability_degree_test_file_path'
            ]);

            // حذف حقول نتائج الاختبارات الجديدة
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
                'stability_degree_test_result'
            ]);

            // حذف حقول حالة الاختبارات الجديدة
            $table->dropColumn([
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