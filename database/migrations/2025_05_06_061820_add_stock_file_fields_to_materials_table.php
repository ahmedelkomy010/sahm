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
            // إضافة حقول لتخزين ملفات STOCK IN و STOCK OUT
            $table->string('stock_in_file')->nullable()->comment('STOCK IN file path')->after('stock_in');
            $table->string('stock_out_file')->nullable()->comment('STOCK OUT file path')->after('stock_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // إزالة الحقول عند التراجع
            $table->dropColumn(['stock_in_file', 'stock_out_file']);
        });
    }
};
