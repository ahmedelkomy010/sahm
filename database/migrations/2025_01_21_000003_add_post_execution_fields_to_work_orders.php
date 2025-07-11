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
        Schema::table('work_orders', function (Blueprint $table) {
            // حقول القيم الفعلية للتنفيذ
            $table->decimal('actual_execution_value_consultant', 12, 2)->nullable()->after('extract_number');
            $table->decimal('actual_execution_value_without_consultant', 12, 2)->nullable()->after('actual_execution_value_consultant');
            
            // حقول الدفعات
            $table->decimal('first_partial_payment_without_tax', 12, 2)->nullable()->after('actual_execution_value_without_consultant');
            $table->decimal('second_partial_payment_with_tax', 12, 2)->nullable()->after('first_partial_payment_without_tax');
            $table->decimal('tax_value', 12, 2)->nullable()->after('second_partial_payment_with_tax');
            
            // تاريخ تسليم إجراء 155
            $table->date('procedure_155_delivery_date')->nullable()->after('tax_value');
            
            // القيمة الكلية النهائية
            $table->decimal('final_total_value', 12, 2)->nullable()->after('procedure_155_delivery_date');
            
            // اختبارات ما قبل التشغيل
            $table->string('pre_operation_tests')->nullable()->after('final_total_value');
            
            // مسارات ملفات ما بعد التنفيذ
            $table->string('quantities_statement_file')->nullable()->after('pre_operation_tests');
            $table->string('final_materials_file')->nullable()->after('quantities_statement_file');
            $table->string('final_measurement_file')->nullable()->after('final_materials_file');
            $table->string('soil_tests_file')->nullable()->after('final_measurement_file');
            $table->string('site_drawing_file')->nullable()->after('soil_tests_file');
            $table->string('modified_estimate_file')->nullable()->after('site_drawing_file');
            $table->string('completion_certificate_file')->nullable()->after('modified_estimate_file');
            $table->string('form_200_file')->nullable()->after('completion_certificate_file');
            $table->string('form_190_file')->nullable()->after('form_200_file');
            $table->string('pre_operation_tests_file')->nullable()->after('form_190_file');
            $table->string('first_payment_extract_file')->nullable()->after('pre_operation_tests_file');
            $table->string('second_payment_extract_file')->nullable()->after('first_payment_extract_file');
            $table->string('total_extract_file')->nullable()->after('second_payment_extract_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'actual_execution_value_consultant',
                'actual_execution_value_without_consultant',
                'first_partial_payment_without_tax',
                'second_partial_payment_with_tax',
                'tax_value',
                'procedure_155_delivery_date',
                'final_total_value',
                'pre_operation_tests',
                'quantities_statement_file',
                'final_materials_file',
                'final_measurement_file',
                'soil_tests_file',
                'site_drawing_file',
                'modified_estimate_file',
                'completion_certificate_file',
                'form_200_file',
                'form_190_file',
                'pre_operation_tests_file',
                'first_payment_extract_file',
                'second_payment_extract_file',
                'total_extract_file',
            ]);
        });
    }
}; 