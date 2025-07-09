<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->json('evacuation_data')->nullable()->after('lab_tests_notes');
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn('evacuation_data');
        });
    }
}; 