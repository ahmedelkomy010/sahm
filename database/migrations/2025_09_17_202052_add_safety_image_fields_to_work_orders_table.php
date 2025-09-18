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
            $table->json('safety_permits_images')->nullable()->comment('صور التصاريح PERMITS/PRG');
            $table->json('safety_team_images')->nullable()->comment('صور فريق العمل والتأهيل للعمالة');
            $table->json('safety_equipment_images')->nullable()->comment('صور المعدات والتأهيل للمعدات');
            $table->json('safety_general_images')->nullable()->comment('صور عامة للسلامة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'safety_permits_images', 
                'safety_team_images', 
                'safety_equipment_images', 
                'safety_general_images'
            ]);
        });
    }
};