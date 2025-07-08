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
        Schema::table('surveys', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('surveys', 'start_coordinates')) {
                $table->string('start_coordinates')->nullable();
            }
            if (!Schema::hasColumn('surveys', 'end_coordinates')) {
                $table->string('end_coordinates')->nullable();
            }
            if (!Schema::hasColumn('surveys', 'has_obstacles')) {
                $table->boolean('has_obstacles')->default(false);
            }
            if (!Schema::hasColumn('surveys', 'obstacles_notes')) {
                $table->text('obstacles_notes')->nullable();
            }
            
            // Modify fields if they exist but need different defaults
            if (Schema::hasColumn('surveys', 'survey_type') && !Schema::hasColumn('surveys', 'survey_type_default_set')) {
                $table->string('survey_type')->default('general')->change();
            }
            if (Schema::hasColumn('surveys', 'survey_date') && !Schema::hasColumn('surveys', 'survey_date_nullable_set')) {
                $table->date('survey_date')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'start_coordinates',
                'end_coordinates', 
                'has_obstacles',
                'obstacles_notes'
            ]);
        });
    }
};
