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
            $table->decimal('planned_spent_difference', 10, 2)->default(0)->after('quantity_difference')->comment('الفرق بين الكمية المخططة والمصروفة');
            $table->decimal('executed_spent_difference', 10, 2)->default(0)->after('planned_spent_difference')->comment('الفرق بين الكمية المنفذة والمصروفة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['planned_spent_difference', 'executed_spent_difference']);
        });
    }
};
