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
        // تحديث مسارات الملفات لتتوافق مع التسميات الجديدة
        
        // تحديث مسارات civil_works إلى civil_exec
        DB::table('work_order_files')
            ->where('file_path', 'like', '%civil_works%')
            ->update([
                'file_path' => DB::raw("REPLACE(file_path, '/civil_works/', '/civil_exec/')")
            ]);
            
        // إنشاء المجلدات الجديدة إذا لم تكن موجودة
        $workOrders = DB::table('work_order_files')
            ->select('work_order_id')
            ->distinct()
            ->get();
            
        foreach ($workOrders as $workOrder) {
            $oldCivilWorksPath = storage_path('app/public/work_orders/' . $workOrder->work_order_id . '/civil_works');
            $newCivilExecPath = storage_path('app/public/work_orders/' . $workOrder->work_order_id . '/civil_exec');
            
            // نقل الملفات من المجلد القديم للجديد
            if (is_dir($oldCivilWorksPath) && !is_dir($newCivilExecPath)) {
                if (!is_dir(dirname($newCivilExecPath))) {
                    mkdir(dirname($newCivilExecPath), 0755, true);
                }
                rename($oldCivilWorksPath, $newCivilExecPath);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // العكس - إرجاع المسارات للأسماء القديمة
        DB::table('work_order_files')
            ->where('file_path', 'like', '%civil_exec%')
            ->update([
                'file_path' => DB::raw("REPLACE(file_path, '/civil_exec/', '/civil_works/')")
            ]);
    }
};
