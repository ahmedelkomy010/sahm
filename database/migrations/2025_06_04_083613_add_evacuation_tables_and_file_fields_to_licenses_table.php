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
            // جداول الإخلاءات
            if (!Schema::hasColumn('licenses', 'evac_table1_data')) {
                $table->json('evac_table1_data')->nullable()->after('evac_amount');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_table2_data')) {
                $table->json('evac_table2_data')->nullable()->after('evac_table1_data');
            }
            
            // ملفات الإخلاءات (تصحيح الاسم)
            if (!Schema::hasColumn('licenses', 'evacuations_file_path')) {
                $table->text('evacuations_file_path')->nullable()->after('evac_table2_data');
            }
            
            // ملفات المخالفات (تصحيح الاسم)
            if (!Schema::hasColumn('licenses', 'violations_file_path')) {
                $table->text('violations_file_path')->nullable()->after('violation_cause');
            }
            
            // ملفات اختبارات المختبر
            if (!Schema::hasColumn('licenses', 'depth_test_file_path')) {
                $table->string('depth_test_file_path')->nullable()->after('evac_table2_data');
            }
            
            if (!Schema::hasColumn('licenses', 'soil_compaction_file_path')) {
                $table->string('soil_compaction_file_path')->nullable()->after('depth_test_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'rc1_mc1_file_path')) {
                $table->string('rc1_mc1_file_path')->nullable()->after('soil_compaction_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'asphalt_test_file_path')) {
                $table->string('asphalt_test_file_path')->nullable()->after('rc1_mc1_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'soil_test_file_path')) {
                $table->string('soil_test_file_path')->nullable()->after('asphalt_test_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'interlock_test_file_path')) {
                $table->string('interlock_test_file_path')->nullable()->after('soil_test_file_path');
            }
            
            // ملفات رخص الحفر
            if (!Schema::hasColumn('licenses', 'license_file_path')) {
                $table->string('license_file_path')->nullable()->after('interlock_test_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'payment_invoices_path')) {
                $table->text('payment_invoices_path')->nullable()->after('license_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'payment_proof_path')) {
                $table->text('payment_proof_path')->nullable()->after('payment_invoices_path');
            }
            
            if (!Schema::hasColumn('licenses', 'license_activation_path')) {
                $table->text('license_activation_path')->nullable()->after('payment_proof_path');
            }
            
            // مرفقات الملاحظات
            if (!Schema::hasColumn('licenses', 'notes_attachments_path')) {
                $table->text('notes_attachments_path')->nullable()->after('notes');
            }
            
            // بيانات الرخصة الأساسية
            if (!Schema::hasColumn('licenses', 'license_number')) {
                $table->string('license_number')->nullable()->after('work_order_id');
            }
            
            if (!Schema::hasColumn('licenses', 'license_date')) {
                $table->date('license_date')->nullable()->after('license_number');
            }
            
            if (!Schema::hasColumn('licenses', 'license_type')) {
                $table->string('license_type')->nullable()->after('license_date');
            }
            
            if (!Schema::hasColumn('licenses', 'license_value')) {
                $table->decimal('license_value', 10, 2)->nullable()->after('license_type');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_value')) {
                $table->decimal('extension_value', 10, 2)->nullable()->after('license_value');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_start_date')) {
                $table->date('extension_start_date')->nullable()->after('extension_value');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_end_date')) {
                $table->date('extension_end_date')->nullable()->after('extension_start_date');
            }
            
            // اختبارات المختبر
            if (!Schema::hasColumn('licenses', 'has_depth_test')) {
                $table->boolean('has_depth_test')->default(false)->after('extension_end_date');
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
            
            // ملاحظات
            if (!Schema::hasColumn('licenses', 'notes')) {
                $table->text('notes')->nullable()->after('has_interlock_test');
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
                'evac_table1_data',
                'evac_table2_data',
                'evacuations_file_path',
                'violations_file_path',
                'depth_test_file_path',
                'soil_compaction_file_path',
                'rc1_mc1_file_path',
                'asphalt_test_file_path',
                'soil_test_file_path',
                'interlock_test_file_path',
                'license_file_path',
                'payment_invoices_path',
                'payment_proof_path',
                'license_activation_path',
                'notes_attachments_path',
                'license_number',
                'license_date',
                'license_type',
                'license_value',
                'extension_value',
                'extension_start_date',
                'extension_end_date',
                'has_depth_test',
                'has_soil_compaction_test',
                'has_rc1_mc1_test',
                'has_asphalt_test',
                'has_soil_test',
                'has_interlock_test',
                'notes'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
