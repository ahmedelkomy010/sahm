<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // No need to modify the schema since we already have file_category column
        // We'll just add a comment to document the new category
        DB::statement("ALTER TABLE work_order_files MODIFY COLUMN file_category ENUM('survey', 'execution', 'post_execution', 'civil_works') NULL COMMENT 'Categories: survey, execution, post_execution, civil_works'");
    }

    public function down(): void
    {
        // Revert the ENUM to its previous state
        DB::statement("ALTER TABLE work_order_files MODIFY COLUMN file_category ENUM('survey', 'execution', 'post_execution') NULL");
    }
}; 