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
        if (!Schema::hasTable('materials')) {
            return;
        }

        Schema::table('materials', function (Blueprint $table) {
            // حذف المفتاح الأجنبي أولاً
            try {
                $table->dropForeign(['work_order_id']);
            } catch (\Exception $e) {
                // تجاهل الخطأ إذا لم يكن المفتاح موجوداً
            }
            
            // حذف الأعمدة المتعلقة بأمر العمل
            $table->dropColumn(['work_order_id', 'work_order_number', 'subscriber_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('materials')) {
            return;
        }

        Schema::table('materials', function (Blueprint $table) {
            // إعادة إضافة الأعمدة
            $table->unsignedBigInteger('work_order_id')->nullable();
            $table->string('work_order_number')->nullable();
            $table->string('subscriber_name')->nullable();
            
            // إعادة إضافة المفتاح الأجنبي
            $table->foreign('work_order_id')->references('id')->on('work_orders')->nullOnDelete();
        });
    }
}; 