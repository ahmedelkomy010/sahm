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
            // حقول تفاصيل كل قسم من الحفريات منفصل
            $table->json('excavation_unsurfaced_soil_details')->nullable()->comment('تفاصيل حفريات التربة الترابية غير المسفلتة');
            $table->json('excavation_surfaced_soil_details')->nullable()->comment('تفاصيل حفريات التربة الترابية المسفلتة');
            $table->json('excavation_unsurfaced_rock_details')->nullable()->comment('تفاصيل حفريات التربة الصخرية غير المسفلتة');
            $table->json('excavation_surfaced_rock_details')->nullable()->comment('تفاصيل حفريات التربة الصخرية المسفلتة');
            $table->json('excavation_open_details')->nullable()->comment('تفاصيل الحفر المفتوح');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'excavation_unsurfaced_soil_details',
                'excavation_surfaced_soil_details',
                'excavation_unsurfaced_rock_details',
                'excavation_surfaced_rock_details',
                'excavation_open_details'
            ]);
        });
    }
};
