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
            $table->decimal('spent_quantity', 10, 2)->nullable()->after('actual_quantity')->comment('الكمية المصروفة');
            $table->decimal('executed_site_quantity', 10, 2)->nullable()->after('spent_quantity')->comment('الكمية المنفذة بالموقع');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['spent_quantity', 'executed_site_quantity']);
        });
    }
};
