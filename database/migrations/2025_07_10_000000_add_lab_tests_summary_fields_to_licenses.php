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
            if (!Schema::hasColumn('licenses', 'total_tests_count')) {
                $table->integer('total_tests_count')->default(0);
            }
            if (!Schema::hasColumn('licenses', 'successful_tests_count')) {
                $table->integer('successful_tests_count')->default(0);
            }
            if (!Schema::hasColumn('licenses', 'failed_tests_count')) {
                $table->integer('failed_tests_count')->default(0);
            }
            if (!Schema::hasColumn('licenses', 'total_tests_amount')) {
                $table->decimal('total_tests_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('licenses', 'successful_tests_amount')) {
                $table->decimal('successful_tests_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('licenses', 'failed_tests_amount')) {
                $table->decimal('failed_tests_amount', 10, 2)->default(0);
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
                'failed_tests_amount'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 