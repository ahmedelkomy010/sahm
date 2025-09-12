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
        // التحقق من وجود العمود وإضافته إذا لم يكن موجوداً
        if (!Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('user_type')->default(0)->after('is_admin');
            });
            
            // تحديث القيم الافتراضية
            DB::table('users')
                ->whereNull('user_type')
                ->update([
                    'user_type' => DB::raw('CASE WHEN is_admin = 1 THEN 1 ELSE 0 END')
                ]);
        }
        
        // التحقق من وجود العمود city وإضافته إذا لم يكن موجوداً
        if (!Schema::hasColumn('users', 'city')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('city')->nullable()->after('user_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};