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
        Schema::table('safety_violations', function (Blueprint $table) {
            $table->enum('violation_source', ['internal', 'external'])->default('internal')->after('violator')->comment('جهة المخالفة - داخلية أم خارجية');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safety_violations', function (Blueprint $table) {
            $table->dropColumn('violation_source');
        });
    }
};
