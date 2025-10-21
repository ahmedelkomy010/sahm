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
        Schema::table('daily_work_programs', function (Blueprint $table) {
            $table->text('survey_notes')->nullable()->after('notes');
            $table->text('materials_notes')->nullable()->after('survey_notes');
            $table->text('quality_notes')->nullable()->after('materials_notes');
            $table->text('safety_notes')->nullable()->after('quality_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_work_programs', function (Blueprint $table) {
            $table->dropColumn(['survey_notes', 'materials_notes', 'quality_notes', 'safety_notes']);
        });
    }
};
