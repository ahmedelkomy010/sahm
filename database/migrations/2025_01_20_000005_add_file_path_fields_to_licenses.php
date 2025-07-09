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
            // Add missing file path fields
            if (!Schema::hasColumn('licenses', 'license_file_path')) {
                $table->string('license_file_path')->nullable()->after('notes_attachments_path');
            }
            if (!Schema::hasColumn('licenses', 'license_activation_path')) {
                $table->text('license_activation_path')->nullable()->after('license_file_path');
            }
            
            // Add lab test file paths
            if (!Schema::hasColumn('licenses', 'depth_test_file_path')) {
                $table->string('depth_test_file_path')->nullable()->after('license_activation_path');
            }
            if (!Schema::hasColumn('licenses', 'soil_compaction_file_path')) {
                $table->string('soil_compaction_file_path')->nullable()->after('depth_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'rc1_mc1_file_path')) {
                $table->string('rc1_mc1_file_path')->nullable()->after('soil_compaction_file_path');
            }
            if (!Schema::hasColumn('licenses', 'asphalt_test_file_path')) {
                $table->string('asphalt_test_file_path')->nullable()->after('rc1_mc1_file_path');
            }
            if (!Schema::hasColumn('licenses', 'soil_test_file_path')) {
                $table->string('soil_test_file_path')->nullable()->after('asphalt_test_file_path');
            }
            if (!Schema::hasColumn('licenses', 'interlock_test_file_path')) {
                $table->string('interlock_test_file_path')->nullable()->after('soil_test_file_path');
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
                'license_file_path',
                'license_activation_path',
                'depth_test_file_path',
                'soil_compaction_file_path',
                'rc1_mc1_file_path',
                'asphalt_test_file_path',
                'soil_test_file_path',
                'interlock_test_file_path'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 