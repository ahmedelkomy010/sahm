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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->text('work_type');
            $table->text('work_description');
            $table->date('approval_date');
            $table->string('subscriber_name');
            $table->string('district');
            $table->decimal('order_value_with_consultant', 10, 2);
            $table->decimal('order_value_without_consultant', 10, 2);
            $table->string('municipality')->nullable();
            $table->string('office')->nullable();
            $table->string('station_number')->nullable();
            $table->string('consultant_name')->nullable();
            $table->integer('execution_status')->default(1);
            $table->string('purchase_order_number')->nullable();
            $table->string('entry_sheet')->nullable();
            $table->string('extract_number')->nullable();
            $table->decimal('actual_execution_value_consultant', 10, 2)->nullable();
            $table->decimal('actual_execution_value_without_consultant', 10, 2)->nullable();
            $table->decimal('first_partial_payment_without_tax', 10, 2)->nullable();
            $table->decimal('second_partial_payment_with_tax', 10, 2)->nullable();
            $table->decimal('tax_value', 10, 2)->nullable();
            $table->date('procedure_155_delivery_date')->nullable();
            $table->decimal('final_total_value', 10, 2)->nullable();
            $table->json('electrical_works')->nullable();
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
