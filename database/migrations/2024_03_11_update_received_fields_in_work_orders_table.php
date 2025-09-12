<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث الأوامر التي لها رقم أمر عمل كمستلمة
        DB::table('work_orders')
            ->whereNotNull('order_number')
            ->update([
                'is_received' => true,
                'received_at' => DB::raw('approval_date')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا نحتاج لعمل شيء في حالة الـ rollback
    }
};
