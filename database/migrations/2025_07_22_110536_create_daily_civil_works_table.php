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
        Schema::create('daily_civil_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            
            // تاريخ العمل
            $table->date('work_date');
            
            // نوع العمل والكابل
            $table->string('work_type'); // نوع الحفرية (تربة ترابية غير مسفلتة، إلخ)
            $table->string('cable_type'); // نوع الكابل (1 كابل منخفض، إلخ)
            
            // القياسات
            $table->decimal('length', 10, 2)->default(0); // الطول بالمتر
            $table->decimal('width', 10, 2)->nullable(); // العرض بالمتر (للحفريات المفتوحة)
            $table->decimal('depth', 10, 2)->nullable(); // العمق بالمتر (للحفريات المفتوحة)
            $table->decimal('volume', 10, 2)->nullable(); // الحجم المحسوب (للحفريات الحجمية)
            
            // الأسعار والتكلفة
            $table->decimal('unit_price', 10, 2)->default(0); // سعر الوحدة
            $table->decimal('total_cost', 10, 2)->default(0); // إجمالي التكلفة
            
            // وحدة القياس
            $table->string('unit')->default('متر'); // متر، متر مكعب، إلخ
            
            // ملاحظات إضافية
            $table->text('notes')->nullable();
            
            // وقت الإنشاء والتحديث
            $table->timestamps();
            
            // الفهارس للبحث السريع
            $table->index(['work_order_id', 'work_date']);
            $table->index('work_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_civil_works');
    }
};
