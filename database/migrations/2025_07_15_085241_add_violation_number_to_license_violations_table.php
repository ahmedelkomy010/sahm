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
        Schema::table('license_violations', function (Blueprint $table) {
            // إضافة حقل رقم المخالفة
            if (!Schema::hasColumn('license_violations', 'violation_number')) {
                $table->string('violation_number')->nullable()->after('description');
            }
            
            // إضافة حقل رقم الرخصة
            if (!Schema::hasColumn('license_violations', 'license_number')) {
                $table->string('license_number')->nullable()->after('license_id');
            }
            
            // إضافة حقل رقم أمر العمل
            if (!Schema::hasColumn('license_violations', 'work_order_id')) {
                $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('cascade')->after('license_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            if (Schema::hasColumn('license_violations', 'violation_number')) {
                $table->dropColumn('violation_number');
            }
            
            if (Schema::hasColumn('license_violations', 'license_number')) {
                $table->dropColumn('license_number');
            }
            
            if (Schema::hasColumn('license_violations', 'work_order_id')) {
                $table->dropForeign(['work_order_id']);
                $table->dropColumn('work_order_id');
            }
        });
    }
};
