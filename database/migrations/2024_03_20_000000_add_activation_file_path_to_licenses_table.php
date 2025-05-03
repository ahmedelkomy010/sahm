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
            $table->string('activation_file_path')->nullable()->after('payment_proof_path');
            $table->boolean('has_depth_test')->default(false)->after('activation_file_path');
            $table->date('depth_test_date')->nullable()->after('has_depth_test');
            $table->boolean('has_soil_compaction_test')->default(false)->after('depth_test_date');
            $table->date('soil_compaction_test_date')->nullable()->after('has_soil_compaction_test');
            $table->string('license_extension_file_path')->nullable()->after('soil_compaction_test_date');
            $table->date('license_extension_date')->nullable()->after('license_extension_file_path');
            $table->integer('license_extension_duration')->nullable()->after('license_extension_date');
            $table->string('invoice_extension_file_path')->nullable()->after('license_extension_duration');
            $table->date('invoice_extension_date')->nullable()->after('invoice_extension_file_path');
            $table->integer('invoice_extension_duration')->nullable()->after('invoice_extension_date');
            $table->boolean('has_rc1_mc1_test')->default(false)->after('invoice_extension_duration');
            $table->date('rc1_mc1_test_date')->nullable()->after('has_rc1_mc1_test');
            $table->boolean('has_asphalt_test')->default(false)->after('rc1_mc1_test_date');
            $table->date('asphalt_test_date')->nullable()->after('has_asphalt_test');
            $table->boolean('has_soil_test')->default(false)->after('asphalt_test_date');
            $table->date('soil_test_date')->nullable()->after('has_soil_test');
            $table->boolean('has_interlock_test')->default(false)->after('soil_test_date');
            $table->date('interlock_test_date')->nullable()->after('has_interlock_test');
            $table->string('test_results_file_path')->nullable()->after('interlock_test_date');
            $table->string('final_inspection_file_path')->nullable()->after('test_results_file_path');
            $table->string('license_closure_file_path')->nullable()->after('final_inspection_file_path');
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
                'license_extension_file_path',
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
                'final_inspection_file_path',
                'license_closure_file_path'
            ]);
        });
    }
}; 