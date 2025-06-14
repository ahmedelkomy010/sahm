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
            $table->decimal('executed_quantity', 10, 2)->default(0)->after('spent_quantity')->comment('الكمية المنفذة');
            $table->decimal('quantity_difference', 10, 2)->default(0)->after('executed_quantity')->comment('الفرق بين الكمية المخططة والمنفذة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['executed_quantity', 'quantity_difference']);
        });
    }
};
