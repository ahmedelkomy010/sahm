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
            // حقول الحظر الإضافية
            if (!Schema::hasColumn('licenses', 'restriction_notes')) {
                $table->text('restriction_notes')->nullable()->after('restriction_reason');
            }
            
            // حقول شهادة التنسيق
            if (!Schema::hasColumn('licenses', 'coordination_certificate_notes')) {
                $table->text('coordination_certificate_notes')->nullable()->after('coordination_certificate_path');
            }
            
            // مرفق الخطابات والتعهدات
            if (!Schema::hasColumn('licenses', 'letters_commitments_file_path')) {
                $table->text('letters_commitments_file_path')->nullable()->after('coordination_certificate_notes');
            }
            
            // بيانات الجداول للمختبر
            if (!Schema::hasColumn('licenses', 'lab_table1_data')) {
                $table->json('lab_table1_data')->nullable()->after('letters_commitments_file_path');
            }
            
            if (!Schema::hasColumn('licenses', 'lab_table2_data')) {
                $table->json('lab_table2_data')->nullable()->after('lab_table1_data');
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
                'restriction_notes',
                'coordination_certificate_notes',
                'letters_commitments_file_path',
                'lab_table1_data',
                'lab_table2_data'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
