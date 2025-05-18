<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'actual_execution_value_consultant')) {
                $table->double('actual_execution_value_consultant')->nullable()->after('extract_number');
            }
            if (!Schema::hasColumn('work_orders', 'actual_execution_value_without_consultant')) {
                $table->double('actual_execution_value_without_consultant')->nullable()->after('actual_execution_value_consultant');
            }
            if (!Schema::hasColumn('work_orders', 'first_partial_payment_without_tax')) {
                $table->double('first_partial_payment_without_tax')->nullable()->after('actual_execution_value_without_consultant');
            }
            if (!Schema::hasColumn('work_orders', 'second_partial_payment_with_tax')) {
                $table->double('second_partial_payment_with_tax')->nullable()->after('first_partial_payment_without_tax');
            }
            if (!Schema::hasColumn('work_orders', 'tax_value')) {
                $table->double('tax_value')->nullable()->after('second_partial_payment_with_tax');
            }
            if (!Schema::hasColumn('work_orders', 'procedure_155_delivery_date')) {
                $table->date('procedure_155_delivery_date')->nullable()->after('tax_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'actual_execution_value_consultant',
                'actual_execution_value_without_consultant',
                'first_partial_payment_without_tax',
                'second_partial_payment_with_tax',
                'tax_value',
                'procedure_155_delivery_date',
            ]);
        });
    }
}; 