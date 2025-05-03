<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->date('license_start_date')->nullable()->after('license_3_path');
            $table->date('license_end_date')->nullable()->after('license_start_date');
            $table->integer('license_alert_days')->default(30)->after('license_end_date');
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['license_start_date', 'license_end_date', 'license_alert_days']);
        });
    }
}; 