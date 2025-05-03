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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique(); // رقم الطلب
            $table->string('work_type'); // نوع العمل
            $table->text('work_description')->nullable(); // وصف العمل
            $table->date('approval_date'); // تاريخ الاعتماد
            $table->string('subscriber_name'); // اسم المشترك
            $table->string('district'); // الحي
            $table->string('station_number')->nullable(); // رقم المحطة
            $table->string('consultant_name')->nullable(); // اسم الاستشاري
            $table->decimal('order_value_with_consultant', 10, 2); // قيمة امر العمل شامل الاستشاري
            $table->decimal('order_value_without_consultant', 10, 2); // قيمة امر العمل بدون استشاري
            $table->string('execution_status')->default('1'); // حالة تنفيذ امر العمل
            $table->decimal('actual_execution_value', 10, 2)->nullable(); // قيمة التنفيذ الفعلي لامر العمل
            $table->date('procedure_155_delivery_date')->nullable(); // تاريخ تسليم اجراء 155
            $table->date('procedure_211_date')->nullable(); // تاريخ اجراء 211
            $table->boolean('partial_deletion')->default(false); // حذف جزئ
            $table->decimal('partial_payment_value', 10, 2)->nullable(); // قيمة الدفعه الجزئية
            $table->string('extract_number')->nullable(); // رقم المستخلص
            $table->string('invoice_number')->nullable(); // رقم الفاتوره
            $table->string('purchase_order_number')->nullable(); // رقم امر الشراء
            $table->decimal('tax_value', 10, 2)->nullable(); // قيمة الضريبة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
