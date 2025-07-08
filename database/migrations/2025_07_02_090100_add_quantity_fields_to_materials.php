<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // إضافة الحقول المتعلقة بالكميات
            $table->decimal('planned_quantity', 10, 2)->default(0)->after('description');
            $table->decimal('spent_quantity', 10, 2)->default(0)->after('planned_quantity');
            $table->decimal('executed_quantity', 10, 2)->default(0)->after('spent_quantity');
            $table->decimal('quantity_difference', 10, 2)->default(0)->after('executed_quantity');
            $table->decimal('planned_spent_difference', 10, 2)->default(0)->after('quantity_difference');
            $table->decimal('executed_spent_difference', 10, 2)->default(0)->after('planned_spent_difference');
            
            // إضافة حقول إضافية
            $table->string('work_order_number')->nullable()->after('work_order_id');
            $table->string('subscriber_name')->nullable()->after('work_order_number');
            $table->string('line')->nullable()->after('unit');
            
            // إضافة حقول الملفات
            $table->string('check_in_file')->nullable();
            $table->string('check_out_file')->nullable();
            $table->string('stock_in_file')->nullable();
            $table->string('stock_out_file')->nullable();
            $table->string('gate_pass_file')->nullable();
            $table->string('store_in_file')->nullable();
            $table->string('store_out_file')->nullable();
            $table->string('ddo_file')->nullable();
            $table->date('date_gatepass')->nullable();
            
            // إضافة حقول المخزون
            $table->integer('stock_in')->default(0);
            $table->integer('stock_out')->default(0);
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn([
                'planned_quantity',
                'spent_quantity',
                'executed_quantity',
                'quantity_difference',
                'planned_spent_difference',
                'executed_spent_difference',
                'work_order_number',
                'subscriber_name',
                'line',
                'check_in_file',
                'check_out_file',
                'stock_in_file',
                'stock_out_file',
                'gate_pass_file',
                'store_in_file',
                'store_out_file',
                'ddo_file',
                'date_gatepass',
                'stock_in',
                'stock_out'
            ]);
        });
    }
}; 