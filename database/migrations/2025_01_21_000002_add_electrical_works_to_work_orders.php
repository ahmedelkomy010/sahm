<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'electrical_works')) {
                $table->json('electrical_works')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (Schema::hasColumn('work_orders', 'electrical_works')) {
                $table->dropColumn('electrical_works');
            }
        });
    }
}; 