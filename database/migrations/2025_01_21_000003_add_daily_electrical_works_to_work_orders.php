<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'daily_electrical_works_data')) {
                $table->json('daily_electrical_works_data')->nullable()->after('electrical_works');
            }
            if (!Schema::hasColumn('work_orders', 'daily_electrical_works_last_update')) {
                $table->timestamp('daily_electrical_works_last_update')->nullable()->after('daily_electrical_works_data');
            }
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (Schema::hasColumn('work_orders', 'daily_electrical_works_data')) {
                $table->dropColumn('daily_electrical_works_data');
            }
            if (Schema::hasColumn('work_orders', 'daily_electrical_works_last_update')) {
                $table->dropColumn('daily_electrical_works_last_update');
            }
        });
    }
}; 