<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // تعديل حقل name ليكون nullable
            $table->string('name')->nullable()->change();
            
            // تعديل حقل code ليكون nullable أيضاً لأنه قد يكون مرتبطاً
            $table->string('code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('code')->unique()->nullable(false)->change();
        });
    }
}; 