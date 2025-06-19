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
            // حقول قيم الاختبارات الناجحة
            $table->decimal('successful_tests_value', 10, 2)->nullable()->after('interlock_test_date')->comment('قيمة الاختبارات الناجحة');
            
            // حقول قيم الاختبارات الراسبة
            $table->decimal('failed_tests_value', 10, 2)->nullable()->after('successful_tests_value')->comment('قيمة الاختبارات الراسبة');
            
            // حقل الأسباب
            $table->text('test_failure_reasons')->nullable()->after('failed_tests_value')->comment('أسباب رسوب الاختبارات');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn([
                'successful_tests_value',
                'failed_tests_value', 
                'test_failure_reasons'
            ]);
        });
    }
};
