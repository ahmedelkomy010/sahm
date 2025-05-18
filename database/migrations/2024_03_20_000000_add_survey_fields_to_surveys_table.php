<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->date('survey_date')->nullable()->after('work_order_id');
            $table->string('survey_type')->nullable()->after('survey_date');
            $table->text('notes')->nullable()->after('survey_type');
        });
    }

    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['survey_date', 'survey_type', 'notes']);
        });
    }
}; 