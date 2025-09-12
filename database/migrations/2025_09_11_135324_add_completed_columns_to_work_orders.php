<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->decimal('final_value', 10, 2)->nullable()->after('actual_execution_value_without_consultant');
            $table->timestamp('payment_date')->nullable()->after('final_value');
        });
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['final_value', 'payment_date']);
        });
    }
};
