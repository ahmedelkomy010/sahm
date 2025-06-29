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
            // التحقق من وجود الحقول قبل إضافتها
            
            // حقول تفعيل الاختبارات الأساسية
            if (!Schema::hasColumn('licenses', 'has_depth_test')) {
                $table->boolean('has_depth_test')->default(false)->after('lab_table2_data');
            }
            if (!Schema::hasColumn('licenses', 'has_soil_compaction_test')) {
                $table->boolean('has_soil_compaction_test')->default(false)->after('has_depth_test');
            }
            if (!Schema::hasColumn('licenses', 'has_rc1_mc1_test')) {
                $table->boolean('has_rc1_mc1_test')->default(false)->after('has_soil_compaction_test');
            }
            if (!Schema::hasColumn('licenses', 'has_asphalt_test')) {
                $table->boolean('has_asphalt_test')->default(false)->after('has_rc1_mc1_test');
            }
            if (!Schema::hasColumn('licenses', 'has_soil_test')) {
                $table->boolean('has_soil_test')->default(false)->after('has_asphalt_test');
            }
            if (!Schema::hasColumn('licenses', 'has_interlock_test')) {
                $table->boolean('has_interlock_test')->default(false)->after('has_soil_test');
            }
            
            // حقول قيم الاختبارات
            if (!Schema::hasColumn('licenses', 'depth_test_value')) {
                $table->decimal('depth_test_value', 10, 2)->default(0)->after('has_interlock_test');
            }
            if (!Schema::hasColumn('licenses', 'soil_compaction_test_value')) {
                $table->decimal('soil_compaction_test_value', 10, 2)->default(0)->after('depth_test_value');
            }
            if (!Schema::hasColumn('licenses', 'rc1_mc1_test_value')) {
                $table->decimal('rc1_mc1_test_value', 10, 2)->default(0)->after('soil_compaction_test_value');
            }
            if (!Schema::hasColumn('licenses', 'asphalt_test_value')) {
                $table->decimal('asphalt_test_value', 10, 2)->default(0)->after('rc1_mc1_test_value');
            }
            if (!Schema::hasColumn('licenses', 'soil_test_value')) {
                $table->decimal('soil_test_value', 10, 2)->default(0)->after('asphalt_test_value');
            }
            if (!Schema::hasColumn('licenses', 'interlock_test_value')) {
                $table->decimal('interlock_test_value', 10, 2)->default(0)->after('soil_test_value');
            }
            
            // حقول مرفقات الاختبارات
            if (!Schema::hasColumn('licenses', 'depth_test_file_path')) {
                $table->string('depth_test_file_path')->nullable()->after('interlock_test_value');
            }
            if (!Schema::hasColumn('licenses', 'soil_compaction_test_file_path')) {
                $table->string('soil_compaction_test_file_path')->nullable()->after('depth_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'rc1_mc1_test_file_path')) {
                $table->string('rc1_mc1_test_file_path')->nullable()->after('soil_compaction_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'asphalt_test_file_path')) {
                $table->string('asphalt_test_file_path')->nullable()->after('rc1_mc1_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'soil_test_file_path')) {
                $table->string('soil_test_file_path')->nullable()->after('asphalt_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'interlock_test_file_path')) {
                $table->string('interlock_test_file_path')->nullable()->after('soil_test_file_path');
            }
            
            // حقول نتائج الاختبارات
            if (!Schema::hasColumn('licenses', 'depth_test_result')) {
                $table->text('depth_test_result')->nullable()->after('interlock_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'soil_compaction_test_result')) {
                $table->text('soil_compaction_test_result')->nullable()->after('depth_test_result');
            }
            if (!Schema::hasColumn('licenses', 'rc1_mc1_test_result')) {
                $table->text('rc1_mc1_test_result')->nullable()->after('soil_compaction_test_result');
            }
            if (!Schema::hasColumn('licenses', 'asphalt_test_result')) {
                $table->text('asphalt_test_result')->nullable()->after('rc1_mc1_test_result');
            }
            if (!Schema::hasColumn('licenses', 'soil_test_result')) {
                $table->text('soil_test_result')->nullable()->after('asphalt_test_result');
            }
            if (!Schema::hasColumn('licenses', 'interlock_test_result')) {
                $table->text('interlock_test_result')->nullable()->after('soil_test_result');
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
                'has_depth_test',
                'has_soil_compaction_test',
                'has_rc1_mc1_test',
                'has_asphalt_test',
                'has_soil_test',
                'has_interlock_test',
                'depth_test_value',
                'soil_compaction_test_value',
                'rc1_mc1_test_value',
                'asphalt_test_value',
                'soil_test_value',
                'interlock_test_value',
                'depth_test_file_path',
                'soil_compaction_test_file_path',
                'rc1_mc1_test_file_path',
                'asphalt_test_file_path',
                'soil_test_file_path',
                'interlock_test_file_path',
                'depth_test_result',
                'soil_compaction_test_result',
                'rc1_mc1_test_result',
                'asphalt_test_result',
                'soil_test_result',
                'interlock_test_result'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
