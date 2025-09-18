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
            $table->text('safety_notes')->nullable()->comment('ملاحظات السلامة');
            $table->string('safety_status')->nullable()->comment('حالة السلامة');
            $table->string('safety_officer')->nullable()->comment('مسؤول السلامة');
            $table->date('inspection_date')->nullable()->comment('تاريخ التفتيش');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['safety_notes', 'safety_status', 'safety_officer', 'inspection_date']);
        });
    }
};