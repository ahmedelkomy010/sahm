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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->string('client_name')->nullable();
            $table->string('project_area')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('extract_number')->nullable();
            $table->string('office')->nullable();
            $table->string('extract_type')->nullable();
            $table->string('po_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->decimal('extract_value', 15, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->decimal('tax_value', 15, 2)->nullable();
            $table->decimal('penalties', 15, 2)->nullable();
            $table->decimal('first_payment_tax', 15, 2)->nullable();
            $table->decimal('net_extract_value', 15, 2)->nullable();
            $table->date('extract_date')->nullable();
            $table->string('year')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('payment_value', 15, 2)->nullable();
            $table->string('extract_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};