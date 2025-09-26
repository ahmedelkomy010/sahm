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
        Schema::create('work_order_safety_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('safety_officer')->nullable()->comment('مسؤول السلامة');
            $table->string('safety_status')->nullable()->comment('حالة السلامة');
            $table->text('safety_notes')->nullable()->comment('ملاحظات السلامة');
            $table->text('non_compliance_reasons')->nullable()->comment('أسباب عدم المطابقة');
            $table->date('inspection_date')->nullable()->comment('تاريخ التفتيش');
            $table->string('updated_by')->nullable()->comment('المحدث بواسطة');
            $table->timestamps();

            $table->index(['work_order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_safety_history');
    }
};