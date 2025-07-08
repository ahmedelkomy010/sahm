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
        Schema::table('work_order_files', function (Blueprint $table) {
            // إضافة عمود survey_id إذا لم يكن موجود
            if (!Schema::hasColumn('work_order_files', 'survey_id')) {
                $table->foreignId('survey_id')->nullable()->after('work_order_id')->constrained('surveys')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_files', function (Blueprint $table) {
            if (Schema::hasColumn('work_order_files', 'survey_id')) {
                $table->dropForeign(['survey_id']);
                $table->dropColumn('survey_id');
            }
        });
    }
};
