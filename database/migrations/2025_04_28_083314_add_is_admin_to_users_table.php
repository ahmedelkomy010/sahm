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
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود is_admin إذا لم يكن موجوداً
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->unsignedTinyInteger('is_admin')->default(0);
            } else {
                // إذا كان العمود موجوداً، قم بتحويله إلى unsignedTinyInteger
                $table->unsignedTinyInteger('is_admin')->default(0)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا تقم بإزالة العمود في حالة التراجع
        // لأنه قد يكون موجوداً في الأصل
    }
};
