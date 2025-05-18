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
        // تحقق من وجود قيد فهرس فريد على حقل work_order_id
        $indexExists = false;
        
        // جلب جميع الفهارس على جدول licenses
        $indexes = DB::select("SHOW INDEXES FROM licenses WHERE Column_name = 'work_order_id'");
        
        // التحقق من وجود فهرس فريد
        foreach ($indexes as $index) {
            if ($index->Key_name === 'licenses_work_order_id_unique' || 
                $index->Non_unique == 0) { // إذا كان Non_unique = 0، فهذا يعني أنه فهرس فريد
                $indexExists = true;
                $indexName = $index->Key_name;
                break;
            }
        }
        
        // إذا وُجد الفهرس الفريد، قم بإزالته
        if ($indexExists) {
            Schema::table('licenses', function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
            
            // إضافة فهرس عادي بدلاً من الفهرس الفريد
            Schema::table('licenses', function (Blueprint $table) {
                $table->index('work_order_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // التحقق من وجود الفهرس العادي
        $indexExists = false;
        $indexes = DB::select("SHOW INDEXES FROM licenses WHERE Column_name = 'work_order_id'");
        
        foreach ($indexes as $index) {
            if ($index->Key_name === 'licenses_work_order_id_index') {
                $indexExists = true;
                break;
            }
        }
        
        // إذا وُجد الفهرس العادي، قم بإزالته
        if ($indexExists) {
            Schema::table('licenses', function (Blueprint $table) {
                $table->dropIndex('licenses_work_order_id_index');
            });
            
            // إعادة إنشاء الفهرس الفريد
            Schema::table('licenses', function (Blueprint $table) {
                $table->unique('work_order_id');
            });
        }
    }
};
