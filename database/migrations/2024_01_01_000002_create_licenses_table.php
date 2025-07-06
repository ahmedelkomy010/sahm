<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_number')->unique();
            $table->string('status')->default('pending');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Attachments
            $table->string('activation_file_path')->nullable();
            $table->string('notes_attachment')->nullable();
            $table->string('extension_attachment')->nullable();
            $table->string('coordination_certificate_number')->nullable();
            
            // Lab Tests
            $table->json('lab_test_results')->nullable();
            $table->boolean('lab_tests_completed')->default(false);
            $table->text('lab_tests_notes')->nullable();
            
            // Evacuation Data
            $table->boolean('has_evacuation_data')->default(false);
            $table->json('evacuation_details')->nullable();
            $table->string('evacuation_attachment')->nullable();
            
            // Restriction & Authority
            $table->string('restriction_reason')->nullable();
            $table->string('restriction_authority')->nullable();
            $table->boolean('is_restricted')->default(false);
            
            // Invoice
            $table->string('invoice_number')->nullable();
            $table->decimal('invoice_amount', 10, 2)->nullable();
            $table->string('invoice_attachment')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });

        // Create license violations table
        Schema::create('license_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->string('violation_type');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('license_violations');
        Schema::dropIfExists('licenses');
    }
}; 