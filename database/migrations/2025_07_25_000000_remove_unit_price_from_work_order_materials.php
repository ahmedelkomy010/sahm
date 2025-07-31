<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_order_materials', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }

    public function down()
    {
        Schema::table('work_order_materials', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->after('used_quantity');
        });
    }
};