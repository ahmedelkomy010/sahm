<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Only add extract_date if it doesn't exist
            if (!Schema::hasColumn('work_orders', 'extract_date')) {
                $table->date('extract_date')->nullable()->after('extract_number');
            }
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (Schema::hasColumn('work_orders', 'extract_date')) {
                $table->dropColumn('extract_date');
            }
        });
    }
};
