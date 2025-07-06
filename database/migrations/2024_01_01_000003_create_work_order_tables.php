<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Work orders table
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('license_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('status')->default('pending');
            $table->string('priority')->default('normal');
            
            // Project information
            $table->string('project_name')->nullable();
            $table->text('project_description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Location
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Work details
            $table->json('work_details')->nullable();
            $table->boolean('requires_inspection')->default(false);
            $table->json('inspection_results')->nullable();
            
            // Tests and checks
            $table->boolean('pre_operation_tests_completed')->default(false);
            $table->json('pre_operation_test_results')->nullable();
            $table->boolean('post_execution_completed')->default(false);
            $table->json('post_execution_data')->nullable();
            
            // Excavation
            $table->boolean('has_excavation')->default(false);
            $table->json('excavation_details')->nullable();
            $table->boolean('excavation_completed')->default(false);
            
            // Civil works
            $table->boolean('has_civil_works')->default(false);
            $table->json('civil_works_details')->nullable();
            $table->boolean('civil_works_completed')->default(false);
            
            // Electrical works
            $table->boolean('has_electrical_works')->default(false);
            $table->json('electrical_works_details')->nullable();
            $table->boolean('electrical_works_completed')->default(false);
            
            // Financial
            $table->string('purchase_order_number')->nullable();
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->string('payment_status')->default('pending');
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Work items table
        Schema::create('work_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->string('unit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Work order items table
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_item_id')->constrained()->onDelete('restrict');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('executed_quantity', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Materials table
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('unit');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Work order materials table
        Schema::create('work_order_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('restrict');
            $table->decimal('quantity', 10, 2);
            $table->decimal('used_quantity', 10, 2)->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Work order files table
        Schema::create('work_order_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('file_category');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Work order history table
        Schema::create('work_order_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('action');
            $table->string('status_from')->nullable();
            $table->string('status_to')->nullable();
            $table->text('notes')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_order_history');
        Schema::dropIfExists('work_order_files');
        Schema::dropIfExists('work_order_materials');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('work_order_items');
        Schema::dropIfExists('work_items');
        Schema::dropIfExists('work_orders');
    }
}; 