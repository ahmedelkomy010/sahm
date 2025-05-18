<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // حذف الملف من التخزين قبل حذف العمود
            $workOrders = DB::table('work_orders')
                ->whereNotNull('extract_number_file')
                ->get();

            foreach ($workOrders as $workOrder) {
                if ($workOrder->extract_number_file) {
                    Storage::disk('public')->delete($workOrder->extract_number_file);
                }
            }

            // حذف العمود من الجدول
            $table->dropColumn('extract_number_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('extract_number_file')->nullable()->comment('ملف رقم المستخلص');
        });
    }
}; 