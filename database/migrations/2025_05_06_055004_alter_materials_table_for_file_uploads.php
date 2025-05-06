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
            // تغيير حقول check_in و check_out إلى مسارات ملفات
            $table->dropColumn(['check_in', 'check_out']);
            $table->string('check_in_file')->nullable()->comment('CHECK IN file path')->after('line');
            $table->string('check_out_file')->nullable()->comment('CHECK OUT file path')->after('check_in_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // استعادة الحقول القديمة
            $table->dropColumn(['check_in_file', 'check_out_file']);
            $table->boolean('check_in')->default(false)->comment('CHECK IN')->after('line');
            $table->boolean('check_out')->default(false)->comment('CHECK OUT')->after('check_in');
        });
    }
};
