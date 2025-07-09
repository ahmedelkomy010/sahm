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
            // Add excavation dimensions
            if (!Schema::hasColumn('licenses', 'excavation_length')) {
                $table->decimal('excavation_length', 8, 2)->nullable()->after('coordination_certificate_notes');
            }
            if (!Schema::hasColumn('licenses', 'excavation_width')) {
                $table->decimal('excavation_width', 8, 2)->nullable()->after('excavation_length');
            }
            if (!Schema::hasColumn('licenses', 'excavation_depth')) {
                $table->decimal('excavation_depth', 8, 2)->nullable()->after('excavation_width');
            }
            
            // Add license period dates
            if (!Schema::hasColumn('licenses', 'license_start_date')) {
                $table->date('license_start_date')->nullable()->after('excavation_depth');
            }
            if (!Schema::hasColumn('licenses', 'license_end_date')) {
                $table->date('license_end_date')->nullable()->after('license_start_date');
            }
            if (!Schema::hasColumn('licenses', 'license_alert_days')) {
                $table->integer('license_alert_days')->default(30)->after('license_end_date');
            }
            
            // Add extension dates
            if (!Schema::hasColumn('licenses', 'extension_start_date')) {
                $table->date('extension_start_date')->nullable()->after('license_alert_days');
            }
            if (!Schema::hasColumn('licenses', 'extension_end_date')) {
                $table->date('extension_end_date')->nullable()->after('extension_start_date');
            }
            
            // Add license length field
            if (!Schema::hasColumn('licenses', 'license_length')) {
                $table->decimal('license_length', 8, 2)->nullable()->after('extension_end_date');
            }
            
            // Add test fields
            if (!Schema::hasColumn('licenses', 'has_depth_test')) {
                $table->boolean('has_depth_test')->default(false)->after('license_length');
            }
            if (!Schema::hasColumn('licenses', 'has_soil_compaction_test')) {
                $table->boolean('has_soil_compaction_test')->default(false)->after('has_depth_test');
            }
            if (!Schema::hasColumn('licenses', 'has_rc1_mc1_test')) {
                $table->boolean('has_rc1_mc1_test')->default(false)->after('has_soil_compaction_test');
            }
            if (!Schema::hasColumn('licenses', 'has_asphalt_test')) {
                $table->boolean('has_asphalt_test')->default(false)->after('has_rc1_mc1_test');
            }
            if (!Schema::hasColumn('licenses', 'has_soil_test')) {
                $table->boolean('has_soil_test')->default(false)->after('has_asphalt_test');
            }
            if (!Schema::hasColumn('licenses', 'has_interlock_test')) {
                $table->boolean('has_interlock_test')->default(false)->after('has_soil_test');
            }
            
            // Add evacuation fields
            if (!Schema::hasColumn('licenses', 'is_evacuated')) {
                $table->boolean('is_evacuated')->default(false)->after('has_interlock_test');
            }
            if (!Schema::hasColumn('licenses', 'evac_license_number')) {
                $table->string('evac_license_number')->nullable()->after('is_evacuated');
            }
            if (!Schema::hasColumn('licenses', 'evac_payment_number')) {
                $table->string('evac_payment_number')->nullable()->after('evac_license_number');
            }
            if (!Schema::hasColumn('licenses', 'evac_date')) {
                $table->date('evac_date')->nullable()->after('evac_payment_number');
            }
            
            // Add notes field
            if (!Schema::hasColumn('licenses', 'notes')) {
                $table->text('notes')->nullable()->after('evac_date');
            }
            
            // Add test result fields
            if (!Schema::hasColumn('licenses', 'successful_tests_value')) {
                $table->decimal('successful_tests_value', 10, 2)->nullable()->after('notes');
            }
            if (!Schema::hasColumn('licenses', 'failed_tests_value')) {
                $table->decimal('failed_tests_value', 10, 2)->nullable()->after('successful_tests_value');
            }
            if (!Schema::hasColumn('licenses', 'test_failure_reasons')) {
                $table->text('test_failure_reasons')->nullable()->after('failed_tests_value');
            }
            
            // Add table data fields
            if (!Schema::hasColumn('licenses', 'lab_table1_data')) {
                $table->json('lab_table1_data')->nullable()->after('test_failure_reasons');
            }
            if (!Schema::hasColumn('licenses', 'lab_table2_data')) {
                $table->json('lab_table2_data')->nullable()->after('lab_table1_data');
            }
            if (!Schema::hasColumn('licenses', 'evac_table1_data')) {
                $table->json('evac_table1_data')->nullable()->after('lab_table2_data');
            }
            if (!Schema::hasColumn('licenses', 'evac_table2_data')) {
                $table->json('evac_table2_data')->nullable()->after('evac_table1_data');
            }
            
            // Add file path fields
            if (!Schema::hasColumn('licenses', 'payment_invoices_path')) {
                $table->text('payment_invoices_path')->nullable()->after('evac_table2_data');
            }
            if (!Schema::hasColumn('licenses', 'payment_proof_path')) {
                $table->text('payment_proof_path')->nullable()->after('payment_invoices_path');
            }
            if (!Schema::hasColumn('licenses', 'activation_file_path')) {
                $table->text('activation_file_path')->nullable()->after('payment_proof_path');
            }
            if (!Schema::hasColumn('licenses', 'invoice_extension_file_path')) {
                $table->text('invoice_extension_file_path')->nullable()->after('activation_file_path');
            }
            if (!Schema::hasColumn('licenses', 'soil_test_images_path')) {
                $table->text('soil_test_images_path')->nullable()->after('invoice_extension_file_path');
            }
            if (!Schema::hasColumn('licenses', 'evacuations_file_path')) {
                $table->text('evacuations_file_path')->nullable()->after('soil_test_images_path');
            }
            if (!Schema::hasColumn('licenses', 'violation_files_path')) {
                $table->text('violation_files_path')->nullable()->after('evacuations_file_path');
            }
            if (!Schema::hasColumn('licenses', 'notes_attachments_path')) {
                $table->text('notes_attachments_path')->nullable()->after('violation_files_path');
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
                'excavation_length',
                'excavation_width',
                'excavation_depth',
                'license_start_date',
                'license_end_date',
                'license_alert_days',
                'extension_start_date',
                'extension_end_date',
                'license_length',
                'has_depth_test',
                'has_soil_compaction_test',
                'has_rc1_mc1_test',
                'has_asphalt_test',
                'has_soil_test',
                'has_interlock_test',
                'is_evacuated',
                'evac_license_number',
                'evac_payment_number',
                'evac_date',
                'notes',
                'successful_tests_value',
                'failed_tests_value',
                'test_failure_reasons',
                'lab_table1_data',
                'lab_table2_data',
                'evac_table1_data',
                'evac_table2_data',
                'payment_invoices_path',
                'payment_proof_path',
                'activation_file_path',
                'invoice_extension_file_path',
                'soil_test_images_path',
                'evacuations_file_path',
                'violation_files_path',
                'notes_attachments_path'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 