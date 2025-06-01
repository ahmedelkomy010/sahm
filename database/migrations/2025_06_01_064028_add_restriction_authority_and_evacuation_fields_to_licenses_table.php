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
            // التحقق من وجود الحقول قبل إضافتها
            if (!Schema::hasColumn('licenses', 'restriction_notes')) {
                $table->text('restriction_notes')->nullable()->after('restriction_reason');
            }
            
            if (!Schema::hasColumn('licenses', 'coordination_certificate_notes')) {
                $table->text('coordination_certificate_notes')->nullable()->after('coordination_certificate_path');
            }
            
            if (!Schema::hasColumn('licenses', 'letters_commitments_file_path')) {
                $table->text('letters_commitments_file_path')->nullable()->after('coordination_certificate_notes');
            }
            
            if (!Schema::hasColumn('licenses', 'lab_table1_data')) {
                $table->json('lab_table1_data')->nullable()->after('letters_commitments_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'lab_table2_data')) {
                $table->json('lab_table2_data')->nullable()->after('lab_table1_data');
            }
            
            if (!Schema::hasColumn('licenses', 'is_evacuated')) {
                $table->boolean('is_evacuated')->default(false)->after('lab_table2_data');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_license_number')) {
                $table->string('evac_license_number')->nullable()->after('is_evacuated');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_license_value')) {
                $table->decimal('evac_license_value', 10, 2)->nullable()->after('evac_license_number');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_payment_number')) {
                $table->string('evac_payment_number')->nullable()->after('evac_license_value');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_date')) {
                $table->date('evac_date')->nullable()->after('evac_payment_number');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_amount')) {
                $table->decimal('evac_amount', 10, 2)->nullable()->after('evac_date');
            }
            
            if (!Schema::hasColumn('licenses', 'evac_files_path')) {
                $table->text('evac_files_path')->nullable()->after('evac_amount');
            }
            
            // حقول المخالفات المحدثة
            if (!Schema::hasColumn('licenses', 'violation_license_number')) {
                $table->string('violation_license_number')->nullable()->after('evac_files_path');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_license_value')) {
                $table->decimal('violation_license_value', 10, 2)->nullable()->after('violation_license_number');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_license_date')) {
                $table->date('violation_license_date')->nullable()->after('violation_license_value');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_due_date')) {
                $table->date('violation_due_date')->nullable()->after('violation_license_date');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_number')) {
                $table->string('violation_number')->nullable()->after('violation_due_date');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_payment_number')) {
                $table->string('violation_payment_number')->nullable()->after('violation_number');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_cause')) {
                $table->string('violation_cause')->nullable()->after('violation_payment_number');
            }
            
            if (!Schema::hasColumn('licenses', 'violation_files_path')) {
                $table->text('violation_files_path')->nullable()->after('violation_cause');
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
                'restriction_notes',
                'coordination_certificate_notes',
                'letters_commitments_file_path',
                'lab_table1_data',
                'lab_table2_data',
                'is_evacuated',
                'evac_license_number',
                'evac_license_value',
                'evac_payment_number',
                'evac_date',
                'evac_amount',
                'evac_files_path',
                'violation_license_number',
                'violation_license_value',
                'violation_license_date',
                'violation_due_date',
                'violation_number',
                'violation_payment_number',
                'violation_cause',
                'violation_files_path'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
