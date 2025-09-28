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
        Schema::table('projects', function (Blueprint $table) {
            // تحديث أنواع المشاريع
            $table->dropColumn('project_type');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            // إضافة أنواع المشاريع الجديدة
            $table->enum('project_type', ['OH33KV', 'UA33LW', 'SLS33KV', 'UG132KV'])->after('contract_number');
            
            // إضافة حقول التواريخ
            $table->date('srgn_date')->nullable()->after('location');
            $table->date('tcc_date')->nullable()->after('srgn_date');
            $table->date('pac_date')->nullable()->after('tcc_date');
            $table->date('fat_date')->nullable()->after('pac_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // حذف الحقول الجديدة
            $table->dropColumn(['srgn_date', 'tcc_date', 'pac_date', 'fat_date']);
            $table->dropColumn('project_type');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            // إرجاع أنواع المشاريع القديمة
            $table->enum('project_type', ['civil', 'electrical', 'mixed'])->after('contract_number');
        });
    }
};
