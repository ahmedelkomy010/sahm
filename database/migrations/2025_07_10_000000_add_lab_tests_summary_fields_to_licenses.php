<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            // حقول الاختبارات الجديدة
            $table->json('lab_tests_data')->nullable();
            $table->integer('total_tests_count')->default(0);
            $table->integer('successful_tests_count')->default(0);
            $table->integer('failed_tests_count')->default(0);
            $table->decimal('total_tests_amount', 10, 2)->default(0);
            $table->decimal('successful_tests_amount', 10, 2)->default(0);
            $table->decimal('failed_tests_amount', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn([
                'lab_tests_data',
                'total_tests_count',
                'successful_tests_count',
                'failed_tests_count',
                'total_tests_amount',
                'successful_tests_amount',
                'failed_tests_amount',
            ]);
        });
    }
}; 