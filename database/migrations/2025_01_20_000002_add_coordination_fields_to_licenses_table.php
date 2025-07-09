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
            // Add coordination certificate fields
            if (!Schema::hasColumn('licenses', 'coordination_certificate_number')) {
                $table->string('coordination_certificate_number')->nullable()->after('license_number');
            }
            if (!Schema::hasColumn('licenses', 'coordination_certificate_path')) {
                $table->string('coordination_certificate_path')->nullable()->after('coordination_certificate_number');
            }
            if (!Schema::hasColumn('licenses', 'coordination_certificate_notes')) {
                $table->text('coordination_certificate_notes')->nullable()->after('coordination_certificate_path');
            }
            
            // Add has_restriction field (rename from is_restricted if needed)
            if (!Schema::hasColumn('licenses', 'has_restriction')) {
                $table->boolean('has_restriction')->default(false)->after('coordination_certificate_notes');
            }
            
            // Add restriction_notes field if missing
            if (!Schema::hasColumn('licenses', 'restriction_notes')) {
                $table->text('restriction_notes')->nullable()->after('restriction_authority');
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
                'coordination_certificate_number',
                'coordination_certificate_path',
                'coordination_certificate_notes',
                'has_restriction',
                'restriction_notes'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 