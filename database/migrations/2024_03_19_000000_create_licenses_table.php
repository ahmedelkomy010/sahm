<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('licenses');
        
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id');
            
            // شهادات التنسيق
            $table->string('coordination_certificate_path')->nullable();
            
            // معلومات الحظر
            $table->boolean('has_restriction')->default(false);
            $table->string('restriction_authority')->nullable();
            
            // تفعيل الرخصة
            $table->date('license_start_date')->nullable();
            $table->date('license_end_date')->nullable();
            $table->integer('license_alert_days')->default(30);
            
            // تمديد الرخصة
            $table->string('license_extension_file_path')->nullable();
            $table->date('license_extension_start_date')->nullable();
            $table->date('license_extension_end_date')->nullable();
            $table->integer('license_extension_alert_days')->default(30);
            
            // إخلاء وإغلاق الرخصة
            $table->string('license_closure_file_path')->nullable();
            
            // الطوابع الزمنية
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}; 