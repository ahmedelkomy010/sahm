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
        Schema::create('daily_work_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_order_item_id')->constrained()->onDelete('cascade');
            $table->date('work_date');
            $table->decimal('executed_quantity', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            // فهرس مركب لضمان عدم تكرار نفس البند في نفس اليوم
            $table->unique(['work_order_item_id', 'work_date'], 'daily_execution_unique');
            
            // فهرس للبحث السريع
            $table->index(['work_order_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_work_executions');
    }
};
