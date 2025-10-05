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
        Schema::create('special_project_revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            // معلومات أساسية
            $table->string('client_name')->nullable();
            $table->string('project')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('extract_number')->nullable();
            $table->string('office')->nullable();
            $table->string('extract_type')->nullable();
            $table->string('po_number')->nullable();
            $table->string('invoice_number')->nullable();
            
            // القيم المالية
            $table->decimal('total_value', 15, 2)->default(0);
            $table->enum('extract_entity', ['SAP', 'UDS'])->default('SAP');
            $table->decimal('tax_value', 15, 2)->default(0);
            $table->decimal('penalties', 15, 2)->default(0);
            $table->decimal('advance_payment_tax', 15, 2)->default(0);
            $table->decimal('net_value', 15, 2)->default(0);
            
            // التواريخ والمعلومات الإضافية
            $table->date('preparation_date')->nullable();
            $table->integer('year')->nullable();
            $table->enum('extract_status', ['المقاول', 'ادارة الكهرباء', 'المالية', 'الخزينة'])->default('المقاول');
            $table->string('reference_number')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->text('procedures')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_project_revenues');
    }
};
