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
        Schema::create('work_order_inspection_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->date('inspection_date')->comment('تاريخ التفتيش');
            $table->string('inspector_name')->nullable()->comment('اسم المفتش');
            $table->text('notes')->nullable()->comment('ملاحظات التفتيش');
            $table->string('status')->default('completed')->comment('حالة التفتيش');
            $table->timestamps();

            $table->index(['work_order_id', 'inspection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_inspection_dates');
    }
};