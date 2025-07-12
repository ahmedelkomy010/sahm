<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop the existing unique constraint on code
            $table->dropUnique(['code']);
            
            // Add a new composite unique constraint on code and work_order_id
            $table->unique(['code', 'work_order_id'], 'materials_code_unique');
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('materials_code_unique');
            
            // Restore the original unique constraint on code
            $table->unique(['code']);
        });
    }
}; 