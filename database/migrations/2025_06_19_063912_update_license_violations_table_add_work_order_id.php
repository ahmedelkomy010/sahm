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
        Schema::table('license_violations', function (Blueprint $table) {
            // إضافة عمود work_order_id
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('cascade');
            
            // جعل license_id قابل للقيم الفارغة
            $table->foreignId('license_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            // حذف عمود work_order_id
            $table->dropForeign(['work_order_id']);
            $table->dropColumn('work_order_id');
            
            // إعادة license_id إلى الحالة الأصلية
            $table->foreignId('license_id')->nullable(false)->change();
        });
    }
};
