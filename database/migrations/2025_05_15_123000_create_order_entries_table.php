<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id');
            $table->enum('type', ['purchase_order_number', 'entry_sheet']);
            $table->string('value');
            $table->timestamps();

            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_entries');
    }
}; 