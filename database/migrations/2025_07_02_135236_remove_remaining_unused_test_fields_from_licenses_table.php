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
            // حذف الحقول المتبقية غير المستخدمة في واجهة المستخدم
            
            $unusedFields = [
                // تواريخ الاختبارات - غير مستخدمة في الواجهة
                'depth_test_date',
                'soil_compaction_test_date',
                'rc1_mc1_test_date',
                'asphalt_test_date',
                'soil_test_date',
                'interlock_test_date',
                
                // حقول أخرى غير مستخدمة
                'test_failure_reasons', // غير مستخدم في الواجهة
                'total_tests_count',     // غير مستخدم في الواجهة
                'total_tests_amount',    // غير مستخدم في الواجهة
                'soil_test_images_path', // غير مستخدم في الواجهة
            ];
            
            // الحقول المستخدمة في الواجهة - يجب الاحتفاظ بها:
            // - successful_tests_value (مستخدم في عرض أوامر العمل)
            // - failed_tests_value (مستخدم في عرض أوامر العمل)  
            // - test_results_file_path (مستخدم في الواجهة)
            // - lab_tests_data (مستخدم في الواجهة)
            // - lab_table1_data (مستخدم في الواجهة)
            // - lab_table2_data (مستخدم في الواجهة)
            
            // حذف الحقول غير المستخدمة فقط
            foreach ($unusedFields as $field) {
                if (Schema::hasColumn('licenses', $field)) {
                    $table->dropColumn($field);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // إعادة إضافة الحقول المحذوفة في حالة التراجع
            $table->date('depth_test_date')->nullable();
            $table->date('soil_compaction_test_date')->nullable();
            $table->date('rc1_mc1_test_date')->nullable();
            $table->date('asphalt_test_date')->nullable();
            $table->date('soil_test_date')->nullable();
            $table->date('interlock_test_date')->nullable();
            $table->text('test_failure_reasons')->nullable();
            $table->integer('total_tests_count')->nullable();
            $table->decimal('total_tests_amount', 10, 2)->nullable();
            $table->text('soil_test_images_path')->nullable();
        });
    }
};
