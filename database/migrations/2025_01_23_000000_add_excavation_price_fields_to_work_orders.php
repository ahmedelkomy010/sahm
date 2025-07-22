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
            // إضافة حقول الحفريات الأساسية
            $table->json('excavation_unsurfaced_soil')->nullable()->after('civil_works_completed');
            $table->json('excavation_surfaced_soil')->nullable()->after('excavation_unsurfaced_soil');
            $table->json('excavation_unsurfaced_rock')->nullable()->after('excavation_surfaced_soil');
            $table->json('excavation_surfaced_rock')->nullable()->after('excavation_unsurfaced_rock');
            $table->json('excavation_precise')->nullable()->after('excavation_surfaced_rock');
            
            // إضافة حقول الحفريات المفتوحة
            $table->json('excavation_unsurfaced_soil_open')->nullable()->after('excavation_precise');
            $table->json('excavation_surfaced_soil_open')->nullable()->after('excavation_unsurfaced_soil_open');
            $table->json('excavation_unsurfaced_rock_open')->nullable()->after('excavation_surfaced_soil_open');
            $table->json('excavation_surfaced_rock_open')->nullable()->after('excavation_unsurfaced_rock_open');
            $table->json('open_excavation')->nullable()->after('excavation_surfaced_rock_open');
            
            // إضافة حقول أسعار الحفريات
            $table->json('excavation_unsurfaced_soil_price')->nullable()->after('open_excavation');
            $table->json('excavation_surfaced_soil_price')->nullable()->after('excavation_unsurfaced_soil_price');
            $table->json('excavation_unsurfaced_rock_price')->nullable()->after('excavation_surfaced_soil_price');
            $table->json('excavation_surfaced_rock_price')->nullable()->after('excavation_unsurfaced_rock_price');
            
            // إضافة حقول أسعار الحفريات المفتوحة
            $table->decimal('excavation_unsurfaced_soil_open_price', 10, 2)->nullable()->after('excavation_surfaced_rock_price');
            $table->decimal('excavation_surfaced_soil_open_price', 10, 2)->nullable()->after('excavation_unsurfaced_soil_open_price');
            $table->decimal('excavation_unsurfaced_rock_open_price', 10, 2)->nullable()->after('excavation_surfaced_soil_open_price');
            $table->decimal('excavation_surfaced_rock_open_price', 10, 2)->nullable()->after('excavation_unsurfaced_rock_open_price');
            
            // إضافة حقول إضافية
            $table->json('excavation_details_table')->nullable()->after('excavation_surfaced_rock_open_price');
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
                'excavation_precise',
                'excavation_unsurfaced_soil_open',
                'excavation_surfaced_soil_open',
                'excavation_unsurfaced_rock_open',
                'excavation_surfaced_rock_open',
                'open_excavation',
                'excavation_unsurfaced_soil_price',
                'excavation_surfaced_soil_price',
                'excavation_unsurfaced_rock_price',
                'excavation_surfaced_rock_price',
                'excavation_unsurfaced_soil_open_price',
                'excavation_surfaced_soil_open_price',
                'excavation_unsurfaced_rock_open_price',
                'excavation_surfaced_rock_open_price',
                'excavation_details_table',
            ]);
        });
    }
}; 