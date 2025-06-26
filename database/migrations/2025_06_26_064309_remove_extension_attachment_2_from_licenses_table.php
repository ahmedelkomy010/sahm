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
        Schema::table('licenses', function (Blueprint $table) {
            // حذف حقل مرفق التجديد
            if (Schema::hasColumn('licenses', 'extension_attachment_2')) {
                $table->dropColumn('extension_attachment_2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // إضافة الحقل مرة أخرى في حالة rollback
            if (!Schema::hasColumn('licenses', 'extension_attachment_2')) {
                $table->string('extension_attachment_2')->nullable()->comment('التجديد - محذوف');
            }
        });
    }
};
