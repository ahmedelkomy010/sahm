<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            // حذف عمود المدة
            $table->dropColumn(['license_extension_duration', 'invoice_extension_duration']);
            
            // حذف أعمدة التواريخ القديمة
            $table->dropColumn(['license_extension_date', 'invoice_extension_date']);
            
            // إضافة أعمدة التواريخ الجديدة
            $table->date('license_extension_start_date')->nullable()->after('license_extension_file_path');
            $table->date('license_extension_end_date')->nullable()->after('license_extension_start_date');
            $table->date('invoice_extension_start_date')->nullable()->after('invoice_extension_file_path');
            $table->date('invoice_extension_end_date')->nullable()->after('invoice_extension_start_date');
            
            // إضافة أعمدة التنبيه
            $table->integer('license_extension_alert_days')->default(30)->after('license_extension_end_date');
            $table->integer('invoice_extension_alert_days')->default(30)->after('invoice_extension_end_date');
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            // إعادة أعمدة المدة
            $table->integer('license_extension_duration')->nullable()->after('license_extension_file_path');
            $table->integer('invoice_extension_duration')->nullable()->after('invoice_extension_file_path');
            
            // إعادة أعمدة التواريخ القديمة
            $table->date('license_extension_date')->nullable()->after('license_extension_file_path');
            $table->date('invoice_extension_date')->nullable()->after('invoice_extension_file_path');
            
            // حذف الأعمدة الجديدة
            $table->dropColumn([
                'license_extension_start_date',
                'license_extension_end_date',
                'license_extension_alert_days',
                'invoice_extension_start_date',
                'invoice_extension_end_date',
                'invoice_extension_alert_days'
            ]);
        });
    }
}; 