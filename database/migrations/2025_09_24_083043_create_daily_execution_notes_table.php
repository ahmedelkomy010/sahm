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
        Schema::create('daily_execution_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->date('execution_date');
            $table->text('notes');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            // فهرس مركب لضمان عدم تكرار الملاحظات لنفس أمر العمل في نفس اليوم
            $table->unique(['work_order_id', 'execution_date'], 'daily_notes_unique');
            
            // فهرس للبحث السريع
            $table->index(['work_order_id', 'execution_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_execution_notes');
    }
};
