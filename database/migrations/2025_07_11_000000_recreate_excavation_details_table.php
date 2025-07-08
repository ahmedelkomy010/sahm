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
        // Drop the table if it exists
        Schema::dropIfExists('excavation_details');

        // Create the table with the correct structure
        Schema::create('excavation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('licenses')->onDelete('cascade');
            $table->string('title');
            $table->string('location');
            $table->string('contractor');
            $table->integer('duration');
            $table->decimal('length', 8, 2);
            $table->decimal('width', 8, 2);
            $table->decimal('depth', 8, 2);
            $table->enum('status', ['active', 'expired', 'completed'])->default('active');
            $table->string('status_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excavation_details');
    }
}; 