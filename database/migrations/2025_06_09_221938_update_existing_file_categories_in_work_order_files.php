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
        // تحديث القيم الموجودة لتقصير أسماء فئات الملفات
        DB::table('work_order_files')
            ->where('file_category', 'civil_works_execution')
            ->update(['file_category' => 'civil_exec']);
            
        DB::table('work_order_files')
            ->where('file_category', 'civil_works_attachments')
            ->update(['file_category' => 'civil_attach']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // العودة للأسماء الطويلة
        DB::table('work_order_files')
            ->where('file_category', 'civil_exec')
            ->update(['file_category' => 'civil_works_execution']);
            
        DB::table('work_order_files')
            ->where('file_category', 'civil_attach')
            ->update(['file_category' => 'civil_works_attachments']);
    }
};
