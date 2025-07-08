<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            
            // معلومات الرخصة الأساسية
            $table->string('license_number')->unique();
            $table->date('license_date')->nullable();
            $table->string('license_type')->nullable();
            $table->decimal('license_value', 10, 2)->default(0);
            $table->decimal('extension_value', 10, 2)->nullable()->default(0);
            $table->string('license_file_path')->nullable();
            
            // أبعاد الحفر
            $table->decimal('excavation_length', 10, 2)->default(0);
            $table->decimal('excavation_width', 10, 2)->default(0);
            $table->decimal('excavation_depth', 10, 2)->default(0);
            
            // معلومات الحظر
            $table->boolean('has_restriction')->default(false);
            $table->string('restriction_authority')->nullable();
            $table->text('restriction_reason')->nullable();
            
            // تواريخ الرخصة
            $table->date('issue_date')->nullable();
            $table->date('activation_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('license_days')->default(0);
            
            // معلومات التمديد
            $table->date('license_extension_start_date')->nullable();
            $table->date('license_extension_end_date')->nullable();
            $table->integer('license_extension_alert_days')->default(30);
            
            // معلومات الفواتير والمدفوعات
            $table->json('activation_files')->nullable();
            $table->json('payment_receipts')->nullable();
            $table->json('payment_proof_files')->nullable();
            
            // معلومات الإخلاء
            $table->decimal('evac_license_value', 10, 2)->nullable();
            $table->decimal('evac_amount', 10, 2)->nullable();
            $table->json('evacuation_data')->nullable();
            
            // معلومات الاختبارات المعملية
            $table->integer('total_tests_count')->default(0);
            $table->integer('successful_tests_count')->default(0);
            $table->integer('failed_tests_count')->default(0);
            $table->decimal('total_tests_amount', 10, 2)->default(0);
            $table->decimal('successful_tests_amount', 10, 2)->default(0);
            $table->decimal('failed_tests_amount', 10, 2)->default(0);
            $table->json('lab_tests_data')->nullable();
            
            // الحالة والملاحظات
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}; 