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
            // حذف المفتاح الأجنبي أولاً إذا كان موجودًا
            try {
                $table->dropForeign(['work_order_id']);
            } catch (\Exception $e) {}
            $table->unsignedBigInteger('work_order_id')->nullable()->change();
            // إعادة المفتاح الأجنبي مع nullOnDelete
            $table->foreign('work_order_id')->references('id')->on('work_orders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
            $table->unsignedBigInteger('work_order_id')->nullable(false)->change();
            $table->foreign('work_order_id')->references('id')->on('work_orders')->restrictOnDelete();
        });
    }
};
