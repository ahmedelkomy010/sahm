<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            // إضافة المفتاح الخارجي بعد إنشاء كل الجداول
            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
        });
        
        Schema::table('surveys', function (Blueprint $table) {
            // إضافة المفتاح الخارجي للاستبيانات
            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
        });
        
        Schema::table('work_order_files', function (Blueprint $table) {
            // إضافة المفتاح الخارجي لملفات أوامر العمل
            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
        });
        
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
        });
        
        Schema::table('work_order_files', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
        });
    }
}; 