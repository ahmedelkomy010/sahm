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
            $table->boolean('electrical_works_locked')->default(false);
            $table->timestamp('electrical_works_locked_at')->nullable();
            $table->unsignedBigInteger('electrical_works_locked_by')->nullable();
            
            $table->foreign('electrical_works_locked_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['electrical_works_locked_by']);
            $table->dropColumn(['electrical_works_locked', 'electrical_works_locked_at', 'electrical_works_locked_by']);
        });
    }
};
