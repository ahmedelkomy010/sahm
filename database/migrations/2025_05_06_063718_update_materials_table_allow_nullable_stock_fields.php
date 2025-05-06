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
        Schema::table('materials', function (Blueprint $table) {
            $table->decimal('stock_in', 8, 2)->nullable()->change();
            $table->decimal('stock_out', 8, 2)->nullable()->change();
            $table->decimal('actual_quantity', 8, 2)->nullable()->change();
            $table->decimal('difference', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->decimal('stock_in', 8, 2)->nullable(false)->change();
            $table->decimal('stock_out', 8, 2)->nullable(false)->change();
            $table->decimal('actual_quantity', 8, 2)->nullable(false)->change();
            $table->decimal('difference', 8, 2)->nullable(false)->change();
        });
    }
};
