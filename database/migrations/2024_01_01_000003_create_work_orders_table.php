<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create work orders table
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('project')->nullable();
            $table->string('status')->default('pending');
            
            // Pre-operation tests
            $table->boolean('pre_operation_tests_completed')->default(false);
            $table->json('pre_operation_test_results')->nullable();
            
            // Post-execution fields
            $table->boolean('post_execution_completed')->default(false);
            $table->json('post_execution_data')->nullable();
            
            // Excavation details
            $table->boolean('has_open_excavation')->default(false);
            $table->json('excavation_details')->nullable();
            $table->boolean('excavation_table_completed')->default(false);
            
            // Section details
            $table->json('section_details')->nullable();
            
            // Civil works
            $table->boolean('civil_works_images_locked')->default(false);
            $table->json('civil_works_data')->nullable();
            
            // Electrical works
            $table->boolean('electrical_works_locked')->default(false);
            $table->json('electrical_works_data')->nullable();
            
            // Installations
            $table->json('installations_data')->nullable();
            $table->boolean('meter_installation_completed')->default(false);
            
            // Purchase orders
            $table->string('purchase_order_number')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Create work items table
        Schema::create('work_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->string('unit');
            $table->timestamps();
        });

        // Create work order items table
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_item_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('executed_quantity', 10, 2)->default(0);
            $table->timestamps();
        });

        // Create materials table
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('executed_quantity', 10, 2)->default(0);
            $table->decimal('difference', 10, 2)->default(0);
            $table->string('ddo_file')->nullable();
            $table->json('stock_data')->nullable();
            $table->timestamps();
        });

        // Create work order files table
        Schema::create('work_order_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_category');
            $table->string('attachment_type')->nullable();
            $table->timestamps();
        });

        // Create work order logs table
        Schema::create('work_order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->json('details')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_order_logs');
        Schema::dropIfExists('work_order_files');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('work_order_items');
        Schema::dropIfExists('work_items');
        Schema::dropIfExists('work_orders');
    }
}; 