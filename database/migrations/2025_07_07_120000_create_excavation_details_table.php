<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('excavation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->string('excavation_type'); // نوع الحفرية
            $table->decimal('length', 10, 2); // الطول
            $table->decimal('width', 10, 2)->nullable(); // العرض (للحفر المفتوح)
            $table->decimal('depth', 10, 2)->nullable(); // العمق (للحفر المفتوح)
            $table->decimal('price', 10, 2); // السعر
            $table->decimal('total', 10, 2); // الإجمالي
            $table->boolean('is_open_excavation')->default(false); // هل هو حفر مفتوح
            $table->string('soil_type'); // نوع التربة (مسفلتة/غير مسفلتة)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('excavation_details');
    }
}; 