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
            // Añadir la columna extract_date si no existe
            if (!Schema::hasColumn('work_orders', 'extract_date')) {
                $table->date('extract_date')->nullable();
            }
            
            // Añadir la columna extract_value si no existe
            if (!Schema::hasColumn('work_orders', 'extract_value')) {
                $table->decimal('extract_value', 12, 2)->nullable();
            }
            
            // Añadir la columna notes si no existe
            if (!Schema::hasColumn('work_orders', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['extract_date', 'extract_value', 'notes']);
        });
    }
};
