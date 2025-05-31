<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->decimal('license_value', 10, 2)->nullable()->after('license_type');
            $table->decimal('extension_value', 10, 2)->nullable()->after('license_value');
        });
    }

    public function down()
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['license_value', 'extension_value']);
        });
    }
}; 