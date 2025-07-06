<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Licenses table
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('status')->default('pending');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Location information
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // License details
            $table->string('license_type');
            $table->text('description')->nullable();
            $table->json('additional_details')->nullable();
            
            // Attachments
            $table->string('activation_file')->nullable();
            $table->string('notes_attachment')->nullable();
            $table->string('extension_attachment')->nullable();
            
            // Lab Tests
            $table->boolean('requires_lab_test')->default(false);
            $table->json('lab_test_results')->nullable();
            $table->boolean('lab_tests_completed')->default(false);
            $table->text('lab_tests_notes')->nullable();
            
            // Restrictions
            $table->boolean('is_restricted')->default(false);
            $table->string('restriction_reason')->nullable();
            $table->string('restriction_authority')->nullable();
            
            // Financial
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('invoice_attachment')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // License violations table
        Schema::create('license_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('restrict');
            $table->string('violation_type');
            $table->text('description');
            $table->date('violation_date');
            $table->string('status')->default('pending');
            $table->string('attachment')->nullable();
            $table->json('violation_details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // License history table
        Schema::create('license_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('action');
            $table->string('status_from')->nullable();
            $table->string('status_to')->nullable();
            $table->text('notes')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();
        });

        // License attachments table
        Schema::create('license_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->string('attachment_type');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('license_attachments');
        Schema::dropIfExists('license_history');
        Schema::dropIfExists('license_violations');
        Schema::dropIfExists('licenses');
    }
}; 