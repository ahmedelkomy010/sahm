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
        // تعديل عمود project_type لإضافة القيم الجديدة
        DB::statement("ALTER TABLE projects MODIFY COLUMN project_type ENUM('civil', 'electrical', 'mixed', 'OH33KV', 'UA33LW', 'SLS33KV', 'UG132KV', 'special') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع العمود للقيم القديمة
        DB::statement("ALTER TABLE projects MODIFY COLUMN project_type ENUM('civil', 'electrical', 'mixed') NOT NULL");
    }
};
