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
            if (!Schema::hasColumn('daily_work_programs', 'execution_notes')) {
                $table->text('execution_notes')->nullable()->after('safety_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_work_programs', function (Blueprint $table) {
            if (Schema::hasColumn('daily_work_programs', 'execution_notes')) {
                $table->dropColumn('execution_notes');
            }
        });
    }
};

