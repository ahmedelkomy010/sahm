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
            // حفر مفتوح للحفريات التربة الترابية غير مسفلتة
            $table->json('excavation_unsurfaced_soil_open')->nullable();
            
            // حفر مفتوح للحفريات التربة الترابية مسفلتة
            $table->json('excavation_surfaced_soil_open')->nullable();
            
            // حفر مفتوح للحفريات التربة الصخرية غير مسفلتة  
            $table->json('excavation_unsurfaced_rock_open')->nullable();
            
            // حفر مفتوح للحفريات التربة الصخرية مسفلتة
            $table->json('excavation_surfaced_rock_open')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'excavation_unsurfaced_soil_open',
                'excavation_surfaced_soil_open',
                'excavation_unsurfaced_rock_open',
                'excavation_surfaced_rock_open'
            ]);
        });
    }
};
