<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // تحديث قيم ENUM لتشمل جميع الفئات المطلوبة
        DB::statement("ALTER TABLE work_order_files MODIFY COLUMN file_category ENUM(
            'survey',
            'execution',
            'post_execution',
            'civil_works',
            'installations',
            'electrical_works'
        ) NULL COMMENT 'Categories: survey, execution, post_execution, civil_works, installations, electrical_works'");
    }

    public function down(): void
    {
        // إعادة القيم إلى الحالة السابقة
        DB::statement("ALTER TABLE work_order_files MODIFY COLUMN file_category ENUM(
            'survey',
            'execution',
            'post_execution',
            'civil_works'
        ) NULL");
    }
}; 