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
            // إضافة أعمدة لحفظ ملفات السلامة (PDF + صور)
            $table->json('safety_tbt_files')->nullable()->after('safety_tbt_images');
            $table->json('safety_permits_files')->nullable()->after('safety_permits_images');
            $table->json('safety_team_files')->nullable()->after('safety_team_images');
            $table->json('safety_equipment_files')->nullable()->after('safety_equipment_images');
            $table->json('safety_general_files')->nullable()->after('safety_general_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'safety_tbt_files',
                'safety_permits_files',
                'safety_team_files',
                'safety_equipment_files',
                'safety_general_files'
            ]);
        });
    }
};
