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
            // حقل لحفظ بيانات الاختبارات الجديدة كـ JSON
            $table->json('lab_tests_data')->nullable()->after('notes');
            
            // حقول الإحصائيات
            $table->integer('total_tests_count')->default(0)->after('lab_tests_data');
            $table->decimal('total_tests_amount', 10, 2)->default(0)->after('total_tests_count');
            
            // فهرس للبحث السريع
            $table->index('total_tests_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropIndex(['total_tests_count']);
            $table->dropColumn([
                'lab_tests_data',
                'total_tests_count',
                'total_tests_amount'
            ]);
        });
    }
};
