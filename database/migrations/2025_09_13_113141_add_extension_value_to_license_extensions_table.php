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
            $table->decimal('extension_value', 15, 2)->nullable()->after('license_id')->comment('قيمة التمديد بالريال السعودي');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_extensions', function (Blueprint $table) {
            $table->dropColumn('extension_value');
        });
    }
};
