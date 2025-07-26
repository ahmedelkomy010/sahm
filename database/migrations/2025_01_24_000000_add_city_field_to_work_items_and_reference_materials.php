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
        // إضافة حقل المدينة لجدول بنود العمل
        Schema::table('work_items', function (Blueprint $table) {
            $table->string('city')->default('الرياض')->after('unit');
            $table->index(['city', 'is_active']); // فهرس للبحث السريع
        });

        // إضافة حقل المدينة لجدول المواد المرجعية
        Schema::table('reference_materials', function (Blueprint $table) {
            $table->string('city')->default('الرياض')->after('unit');
            $table->index(['city', 'is_active']); // فهرس للبحث السريع
        });

        // تحديث القيد الفريد للمواد المرجعية ليشمل المدينة
        Schema::table('reference_materials', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['code', 'city']);
        });

        // تحديث القيد الفريد لبنود العمل ليشمل المدينة
        Schema::table('work_items', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['code', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة القيود الفريدة الأصلية
        Schema::table('work_items', function (Blueprint $table) {
            $table->dropUnique(['code', 'city']);
            $table->unique(['code']);
            $table->dropIndex(['city', 'is_active']);
            $table->dropColumn('city');
        });

        Schema::table('reference_materials', function (Blueprint $table) {
            $table->dropUnique(['code', 'city']);
            $table->unique(['code']);
            $table->dropIndex(['city', 'is_active']);
            $table->dropColumn('city');
        });
    }
}; 