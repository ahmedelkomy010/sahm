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
            // إضافة أعمدة المرفقات لإجراءات ما بعد التنفيذ
            $table->string('quantities_statement_file')->nullable()->comment('ملف بيان كميات التنفيذ');
            $table->string('final_materials_file')->nullable()->comment('ملف كميات المواد النهائية');
            $table->string('final_measurement_file')->nullable()->comment('ملف ورقة القياس النهائية');
            $table->string('soil_tests_file')->nullable()->comment('ملف اختبارات التربة');
            $table->string('site_drawing_file')->nullable()->comment('ملف الرسم الهندسي للموقع');
            $table->string('modified_estimate_file')->nullable()->comment('ملف تعديل المقايسة');
            $table->string('completion_certificate_file')->nullable()->comment('ملف شهادة الانجاز');
            $table->string('form_200_file')->nullable()->comment('ملف نموذج 200');
            $table->string('form_190_file')->nullable()->comment('ملف نموذج 190');
            $table->string('pre_operation_tests_file')->nullable()->comment('ملف اختبارات ما قبل التشغيل 211');
            $table->string('extract_number_file')->nullable()->comment('ملف رقم المستخلص');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // حذف الأعمدة عند التراجع عن الهجرة
            $table->dropColumn([
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
                'extract_number_file'
            ]);
        });
    }
};
