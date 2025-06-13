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
            // التحقق من وجود الأعمدة قبل حذفها
            if (Schema::hasColumn('materials', 'actual_quantity')) {
                $table->dropColumn('actual_quantity');
            }
            
            if (Schema::hasColumn('materials', 'executed_site_quantity')) {
                $table->dropColumn('executed_site_quantity');
            }
            
            if (Schema::hasColumn('materials', 'difference')) {
                $table->dropColumn('difference');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // إعادة إضافة الأعمدة في حالة التراجع
            $table->decimal('actual_quantity', 10, 2)->nullable()->default(0);
            $table->decimal('executed_site_quantity', 10, 2)->nullable()->default(0);
            $table->decimal('difference', 10, 2)->nullable()->default(0);
        });
    }
};
