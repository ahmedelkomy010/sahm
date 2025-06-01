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
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_item_id')->constrained()->onDelete('cascade');
            $table->decimal('planned_quantity', 10, 2)->default(0); // الكمية المخططة
            $table->decimal('actual_quantity', 10, 2)->nullable(); // الكمية الفعلية
            $table->text('notes')->nullable(); // ملاحظات خاصة بهذا البند في أمر العمل
            $table->timestamps();
            
            // منع التكرار - بند واحد لكل أمر عمل
            $table->unique(['work_order_id', 'work_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_items');
    }
};
