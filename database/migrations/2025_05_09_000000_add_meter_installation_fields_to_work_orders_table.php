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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->enum('single_meter_installation', ['yes', 'no', 'na'])->nullable()->after('execution_notes');
            $table->enum('double_meter_installation', ['yes', 'no', 'na'])->nullable()->after('single_meter_installation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['single_meter_installation', 'double_meter_installation']);
        });
    }
}; 