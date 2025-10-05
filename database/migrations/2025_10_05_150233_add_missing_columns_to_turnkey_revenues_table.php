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
        Schema::table('turnkey_revenues', function (Blueprint $table) {
            if (!Schema::hasColumn('turnkey_revenues', 'client_name')) {
                $table->string('client_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'extract_number')) {
                $table->string('extract_number')->nullable()->after('contract_number');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'office')) {
                $table->string('office')->nullable()->after('extract_number');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'extract_type')) {
                $table->string('extract_type')->nullable()->after('office');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'po_number')) {
                $table->string('po_number')->nullable()->after('extract_type');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('po_number');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'extract_entity')) {
                $table->string('extract_entity')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'year')) {
                $table->integer('year')->nullable()->after('extract_date');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'extract_status')) {
                $table->string('extract_status')->nullable()->after('year');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('extract_status');
            }
            if (!Schema::hasColumn('turnkey_revenues', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnkey_revenues', function (Blueprint $table) {
            $columns = [
                'client_name',
                'extract_number',
                'office',
                'extract_type',
                'po_number',
                'invoice_number',
                'extract_entity',
                'year',
                'extract_status',
                'reference_number',
                'payment_status',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('turnkey_revenues', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
