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
        Schema::table('work_order_items', function (Blueprint $table) {
            // التحقق من وجود الأعمدة قبل إضافتها
            if (!Schema::hasColumn('work_order_items', 'work_order_id')) {
                $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            }
            if (!Schema::hasColumn('work_order_items', 'work_item_id')) {
                $table->foreignId('work_item_id')->constrained('work_items')->onDelete('cascade');
            }
            if (!Schema::hasColumn('work_order_items', 'planned_quantity')) {
                $table->decimal('planned_quantity', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('work_order_items', 'actual_quantity')) {
                $table->decimal('actual_quantity', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('work_order_items', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_items', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
            $table->dropForeign(['work_item_id']);
            $table->dropColumn(['work_order_id', 'work_item_id', 'planned_quantity', 'actual_quantity', 'notes']);
        });
    }
};
