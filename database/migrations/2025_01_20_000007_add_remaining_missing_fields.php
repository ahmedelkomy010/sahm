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
        Schema::table('licenses', function (Blueprint $table) {
            // Test summary fields - ensure they exist
            if (!Schema::hasColumn('licenses', 'total_tests_count')) {
                $table->integer('total_tests_count')->default(0)->after('lab_tests_data');
            }
            if (!Schema::hasColumn('licenses', 'successful_tests_count')) {
                $table->integer('successful_tests_count')->default(0)->after('total_tests_count');
            }
            if (!Schema::hasColumn('licenses', 'failed_tests_count')) {
                $table->integer('failed_tests_count')->default(0)->after('successful_tests_count');
            }
            if (!Schema::hasColumn('licenses', 'total_tests_amount')) {
                $table->decimal('total_tests_amount', 10, 2)->default(0)->after('failed_tests_count');
            }
            if (!Schema::hasColumn('licenses', 'successful_tests_amount')) {
                $table->decimal('successful_tests_amount', 10, 2)->default(0)->after('total_tests_amount');
            }
            if (!Schema::hasColumn('licenses', 'failed_tests_amount')) {
                $table->decimal('failed_tests_amount', 10, 2)->default(0)->after('successful_tests_amount');
            }
            
            // Additional fields that might be missing
            if (!Schema::hasColumn('licenses', 'license_file_url')) {
                $table->string('license_file_url')->nullable()->after('failed_tests_amount');
            }
            if (!Schema::hasColumn('licenses', 'payment_proof_url')) {
                $table->string('payment_proof_url')->nullable()->after('license_file_url');
            }
            if (!Schema::hasColumn('licenses', 'payment_proof_urls')) {
                $table->json('payment_proof_urls')->nullable()->after('payment_proof_url');
            }
            if (!Schema::hasColumn('licenses', 'payment_invoices_urls')) {
                $table->json('payment_invoices_urls')->nullable()->after('payment_proof_urls');
            }
            if (!Schema::hasColumn('licenses', 'license_activation_urls')) {
                $table->json('license_activation_urls')->nullable()->after('payment_invoices_urls');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $columns = [
                'total_tests_count',
                'successful_tests_count', 
                'failed_tests_count',
                'total_tests_amount',
                'successful_tests_amount',
                'failed_tests_amount',
                'license_file_url',
                'payment_proof_url',
                'payment_proof_urls',
                'payment_invoices_urls',
                'license_activation_urls'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 