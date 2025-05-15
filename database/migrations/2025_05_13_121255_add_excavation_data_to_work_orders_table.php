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
            // حفريات تربة ترابية غير مسفلتة
            $table->json('excavation_unsurfaced_soil')->nullable();
            
            // حفريات تربة ترابية مسفلتة
            $table->json('excavation_surfaced_soil')->nullable();
            
            // حفريات تربة صخرية غير مسفلتة
            $table->json('excavation_unsurfaced_rock')->nullable();
            
            // حفريات تربة صخرية مسفلتة
            $table->json('excavation_surfaced_rock')->nullable();
            
            // حفريات دقيقة
            $table->json('excavation_precise')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'excavation_unsurfaced_soil',
                'excavation_surfaced_soil',
                'excavation_unsurfaced_rock',
                'excavation_surfaced_rock',
                'excavation_precise'
            ]);
        });
    }
};
