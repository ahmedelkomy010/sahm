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
            // تغيير نوع العمود من enum إلى string لتجنب مشاكل التوافق
            $table->string('execution_status')->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // إعادة العمود إلى enum
            $table->enum('execution_status', ['1', '2', '3', '4', '5', '6', '7'])->default('1')->change();
        });
    }
};
