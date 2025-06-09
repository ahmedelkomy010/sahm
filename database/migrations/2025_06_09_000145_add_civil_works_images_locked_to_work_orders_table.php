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
            $table->boolean('civil_works_images_locked')->default(false)->comment('حالة تثبيت صور الأعمال المدنية');
            $table->timestamp('civil_works_images_locked_at')->nullable()->comment('تاريخ تثبيت صور الأعمال المدنية');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['civil_works_images_locked', 'civil_works_images_locked_at']);
        });
    }
};
