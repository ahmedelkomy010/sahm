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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->text('non_compliance_reasons')->nullable()->comment('أسباب عدم المطابقة');
            $table->json('non_compliance_attachments')->nullable()->comment('مرفقات عدم المطابقة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['non_compliance_reasons', 'non_compliance_attachments']);
        });
    }
};