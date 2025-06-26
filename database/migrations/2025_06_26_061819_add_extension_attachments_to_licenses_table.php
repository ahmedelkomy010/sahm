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
        Schema::table('licenses', function (Blueprint $table) {
            // إضافة حقول مرفقات التمديد الأربعة
            if (!Schema::hasColumn('licenses', 'extension_attachment_1')) {
                $table->string('extension_attachment_1')->nullable()->comment(' ملف الرخصة ');
            }
            
            // extension_attachment_2 تم حذفه - لم يعد مطلوباً
            
            if (!Schema::hasColumn('licenses', 'extension_attachment_3')) {
                $table->string('extension_attachment_3')->nullable()->comment('إثبات سداد البنك ');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_attachment_4')) {
                $table->string('extension_attachment_4')->nullable()->comment('اثبات السداد');
            }
            
            // إضافة حقول التمديد الناقصة إذا لم تكن موجودة
            if (!Schema::hasColumn('licenses', 'extension_start_date')) {
                $table->date('extension_start_date')->nullable()->comment('تاريخ بداية التمديد');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_end_date')) {
                $table->date('extension_end_date')->nullable()->comment('تاريخ نهاية التمديد');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_alert_days')) {
                $table->integer('extension_alert_days')->nullable()->comment('عدد أيام التمديد');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $columns = [
                'extension_attachment_1',
                'extension_attachment_2', 
                'extension_attachment_3',
                'extension_attachment_4',
                'extension_start_date',
                'extension_end_date',
                'extension_alert_days'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
