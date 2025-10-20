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
        // تعديل enum لإضافة "تم الصرف"
        DB::statement("ALTER TABLE special_project_revenues MODIFY COLUMN extract_status ENUM('المقاول', 'ادارة الكهرباء', 'المالية', 'الخزينة', 'تم الصرف') DEFAULT 'المقاول'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum للحالة السابقة
        DB::statement("ALTER TABLE special_project_revenues MODIFY COLUMN extract_status ENUM('المقاول', 'ادارة الكهرباء', 'المالية', 'الخزينة') DEFAULT 'المقاول'");
    }
};
