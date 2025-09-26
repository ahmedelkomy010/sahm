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
        Schema::table('license_extensions', function (Blueprint $table) {
            $table->string('extended_license_number')->nullable()->after('license_id')->comment('رقم الرخصة التي تم التمديد عليها');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_extensions', function (Blueprint $table) {
            $table->dropColumn('extended_license_number');
        });
    }
};