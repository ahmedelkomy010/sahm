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
            $table->string('activation_file_path')->nullable();
            $table->boolean('has_depth_test')->default(false);
            $table->date('depth_test_date')->nullable();
            $table->boolean('has_soil_compaction_test')->default(false);
            $table->date('soil_compaction_test_date')->nullable();
            $table->date('license_extension_date')->nullable();
            $table->integer('license_extension_duration')->nullable();
            $table->string('invoice_extension_file_path')->nullable();
            $table->date('invoice_extension_date')->nullable();
            $table->integer('invoice_extension_duration')->nullable();
            $table->boolean('has_rc1_mc1_test')->default(false);
            $table->date('rc1_mc1_test_date')->nullable();
            $table->boolean('has_asphalt_test')->default(false);
            $table->date('asphalt_test_date')->nullable();
            $table->boolean('has_soil_test')->default(false);
            $table->date('soil_test_date')->nullable();
            $table->boolean('has_interlock_test')->default(false);
            $table->date('interlock_test_date')->nullable();
            $table->string('test_results_file_path')->nullable();
            $table->string('final_inspection_file_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn([
                'activation_file_path',
                'has_depth_test',
                'depth_test_date',
                'has_soil_compaction_test',
                'soil_compaction_test_date',
                'license_extension_date',
                'license_extension_duration',
                'invoice_extension_file_path',
                'invoice_extension_date',
                'invoice_extension_duration',
                'has_rc1_mc1_test',
                'rc1_mc1_test_date',
                'has_asphalt_test',
                'asphalt_test_date',
                'has_soil_test',
                'soil_test_date',
                'has_interlock_test',
                'interlock_test_date',
                'test_results_file_path',
                'final_inspection_file_path'
            ]);
        });
    }
}; 