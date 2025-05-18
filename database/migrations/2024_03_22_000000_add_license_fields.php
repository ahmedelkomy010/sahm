<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            // معلومات الرخصة الأساسية
            if (!Schema::hasColumn('licenses', 'license_number')) {
                $table->string('license_number')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_date')) {
                $table->date('license_date')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_type')) {
                $table->string('license_type')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'notes')) {
                $table->text('notes')->nullable();
            }
            
            // مسارات الملفات الإضافية
            if (!Schema::hasColumn('licenses', 'payment_invoices_path')) {
                $table->string('payment_invoices_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'soil_test_images_path')) {
                $table->string('soil_test_images_path')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $columns = [
                'license_number',
                'license_date',
                'license_type',
                'notes',
                'payment_invoices_path',
                'payment_proof_path',
                'soil_test_images_path'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 