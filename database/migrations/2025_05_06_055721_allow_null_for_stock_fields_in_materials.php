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
            $table->decimal('stock_in', 10, 2)->nullable()->default(0)->change();
            $table->decimal('stock_out', 10, 2)->nullable()->default(0)->change();
            $table->decimal('actual_quantity', 10, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->decimal('stock_in', 10, 2)->nullable(false)->default(0)->change();
            $table->decimal('stock_out', 10, 2)->nullable(false)->default(0)->change();
            $table->decimal('actual_quantity', 10, 2)->nullable(false)->default(0)->change();
        });
    }
};
