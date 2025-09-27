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
        Schema::table('revenues', function (Blueprint $table) {
            $table->string('project')->default('riyadh')->after('id')->comment('المشروع: riyadh أو madinah');
            $table->string('city')->nullable()->after('project')->comment('اسم المدينة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revenues', function (Blueprint $table) {
            $table->dropColumn(['project', 'city']);
        });
    }
};
