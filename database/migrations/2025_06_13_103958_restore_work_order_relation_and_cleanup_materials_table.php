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
        Schema::table('materials', function (Blueprint $table) {
            // إضافة العلاقة مع أمر العمل
            $table->unsignedBigInteger('work_order_id')->nullable()->after('id');
            $table->string('work_order_number')->nullable()->after('work_order_id');
            $table->string('subscriber_name')->nullable()->after('work_order_number');
            
            // إضافة المفتاح الأجنبي
            $table->foreign('work_order_id')->references('id')->on('work_orders')->nullOnDelete();
            
            // حذف الحقول غير المطلوبة
            $table->dropColumn([
                'actual_quantity',
                'executed_site_quantity', 
                'difference'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // حذف المفتاح الأجنبي
            $table->dropForeign(['work_order_id']);
            
            // حذف أعمدة العلاقة
            $table->dropColumn(['work_order_id', 'work_order_number', 'subscriber_name']);
            
            // إعادة إضافة الحقول المحذوفة
            $table->decimal('actual_quantity', 10, 2)->nullable()->default(0);
            $table->decimal('executed_site_quantity', 10, 2)->nullable()->default(0);
            $table->decimal('difference', 10, 2)->nullable()->default(0);
        });
    }
};
