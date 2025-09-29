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
        Schema::table('revenues', function (Blueprint $table) {
            // Change extract_status from enum to text
            $table->text('extract_status')->nullable()->change();
            
            // Change extract_type to text (if it's not already)
            $table->text('extract_type')->nullable()->change();
            
            // Change payment_type to text (if it's not already)
            $table->text('payment_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revenues', function (Blueprint $table) {
            // Revert back to original types if needed
            $table->string('extract_status')->nullable()->change();
            $table->string('extract_type')->nullable()->change();
            $table->string('payment_type')->nullable()->change();
        });
    }
};