<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث المستخدمين الموجودين اللي مالهمش قيمة في user_type
        DB::table('users')
            ->whereNull('user_type')
            ->update([
                'user_type' => DB::raw('CASE WHEN is_admin = 1 THEN 1 ELSE 0 END')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // مش محتاجين نعمل rollback لأن دي بيانات
    }
};