<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->timestamp('extract_date')->nullable()->after('extract_number');
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('extract_date');
        });
    }
};
