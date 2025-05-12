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
            $table->string('post_execution_status')->nullable();
            $table->decimal('post_execution_value', 10, 2)->nullable();
            $table->string('post_execution_file')->nullable();
            $table->text('post_execution_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'post_execution_status',
                'post_execution_value',
                'post_execution_file',
                'post_execution_notes'
            ]);
        });
    }
}; 