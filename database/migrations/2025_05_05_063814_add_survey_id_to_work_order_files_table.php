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
            $table->unsignedBigInteger('survey_id')->nullable()->after('work_order_id');
            $table->index('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_files', function (Blueprint $table) {
            $table->dropForeign(['survey_id']);
            $table->dropIndex(['survey_id']);
            $table->dropColumn('survey_id');
        });
    }
};
