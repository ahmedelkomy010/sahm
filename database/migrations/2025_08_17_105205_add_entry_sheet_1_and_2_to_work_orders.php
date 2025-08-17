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
            // إضافة الحقلين الجديدين بعد entry_sheet
            $table->string('entry_sheet_1')->nullable()->after('entry_sheet');
            $table->string('entry_sheet_2')->nullable()->after('entry_sheet_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['entry_sheet_1', 'entry_sheet_2']);
        });
    }
};