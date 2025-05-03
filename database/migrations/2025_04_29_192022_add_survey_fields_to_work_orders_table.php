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
            $table->string('start_coordinates')->nullable()->after('tax_value');
            $table->string('end_coordinates')->nullable()->after('start_coordinates');
            $table->boolean('has_obstacles')->default(false)->after('end_coordinates');
            $table->text('obstacles_notes')->nullable()->after('has_obstacles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['start_coordinates', 'end_coordinates', 'has_obstacles', 'obstacles_notes']);
        });
    }
};
