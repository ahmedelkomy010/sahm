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
            // التحقق من وجود العمود قبل إضافته
            if (!Schema::hasColumn('licenses', 'restriction_reason')) {
                $table->text('restriction_reason')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'license_length')) {
                $table->float('license_length')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'license_1_path')) {
                $table->text('license_1_path')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'letters_and_commitments_path')) {
                $table->text('letters_and_commitments_path')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'has_depth_test')) {
                $table->boolean('has_depth_test')->default(false);
            }
            
            if (!Schema::hasColumn('licenses', 'has_soil_compaction_test')) {
                $table->boolean('has_soil_compaction_test')->default(false);
            }
            
            if (!Schema::hasColumn('licenses', 'has_rc1_mc1_test')) {
                $table->boolean('has_rc1_mc1_test')->default(false);
            }
            
            if (!Schema::hasColumn('licenses', 'has_asphalt_test')) {
                $table->boolean('has_asphalt_test')->default(false);
            }
            
            if (!Schema::hasColumn('licenses', 'has_soil_test')) {
                $table->boolean('has_soil_test')->default(false);
            }
            
            if (!Schema::hasColumn('licenses', 'has_interlock_test')) {
                $table->boolean('has_interlock_test')->default(false);
            }
            
            if (!Schema::hasColumn('licenses', 'test_results_file_path')) {
                $table->text('test_results_file_path')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'final_inspection_file_path')) {
                $table->text('final_inspection_file_path')->nullable();
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
                'restriction_reason',
                'license_length',
                'license_1_path',
                'letters_and_commitments_path',
                'has_depth_test',
                'has_soil_compaction_test',
                'has_rc1_mc1_test',
                'has_asphalt_test',
                'has_soil_test',
                'has_interlock_test',
                'test_results_file_path',
                'final_inspection_file_path',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
