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
        Schema::create('turnkey_revenues', function (Blueprint $table) {
            $table->id();
            $table->string('project')->nullable(); // riyadh or madinah
            $table->string('contract_number')->nullable();
            $table->string('location')->nullable();
            $table->string('project_type')->nullable();
            $table->decimal('extract_value', 15, 2)->nullable();
            $table->decimal('tax_value', 15, 2)->nullable();
            $table->decimal('penalties', 15, 2)->nullable();
            $table->decimal('net_extract_value', 15, 2)->nullable();
            $table->decimal('payment_value', 15, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->decimal('first_payment_tax', 15, 2)->nullable();
            $table->date('extract_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('project');
            $table->index('contract_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnkey_revenues');
    }
};
