<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->string('gate_pass_file')->nullable()->comment('GATE PASS file path');
            $table->string('store_in_file')->nullable()->comment('STORE IN file path');
            $table->string('store_out_file')->nullable()->comment('STORE OUT file path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['gate_pass_file', 'store_in_file', 'store_out_file']);
        });
    }
};
