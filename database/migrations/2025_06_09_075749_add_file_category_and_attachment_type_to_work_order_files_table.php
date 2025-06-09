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
        Schema::table('work_order_files', function (Blueprint $table) {
            // إضافة أو تعديل عمود file_category بحجم أكبر
            if (!Schema::hasColumn('work_order_files', 'file_category')) {
                $table->string('file_category', 100)->nullable()->after('file_size');
            } else {
                $table->string('file_category', 100)->nullable()->change();
            }
            
            // إضافة عمود attachment_type إذا لم يكن موجوداً
            if (!Schema::hasColumn('work_order_files', 'attachment_type')) {
                $table->string('attachment_type', 100)->nullable()->after('file_category');
            } else {
                $table->string('attachment_type', 100)->nullable()->change();
            }
            
            // إضافة عمود survey_id إذا لم يكن موجوداً
            if (!Schema::hasColumn('work_order_files', 'survey_id')) {
                $table->unsignedBigInteger('survey_id')->nullable()->after('work_order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_files', function (Blueprint $table) {
            // لا نحذف الأعمدة في الـ rollback لتجنب فقدان البيانات
            // يمكن إعادة تقليل الحجم فقط
        });
    }
};
