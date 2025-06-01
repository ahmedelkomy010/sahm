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
        Schema::create('work_order_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('material_code'); // كود المادة
            $table->string('material_description'); // وصف المادة
            $table->decimal('planned_quantity', 10, 2)->default(0); // الكمية المخططة
            $table->string('unit')->default('عدد'); // وحدة القياس
            $table->decimal('actual_quantity', 10, 2)->nullable(); // الكمية الفعلية المستخدمة
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
            
            // فهرس للبحث السريع
            $table->index(['work_order_id', 'material_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_materials');
    }
};
