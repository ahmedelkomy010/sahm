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
        Schema::create('design_submittals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('family')->nullable();
            $table->string('description_code')->nullable();
            $table->string('rev')->nullable();
            $table->text('description')->nullable();
            $table->string('last_status')->nullable();
            $table->date('submitting_date')->nullable();
            $table->date('reply_date')->nullable();
            $table->enum('reply_status', ['Total Submittals', 'Approved', 'Approved With Note', 'Comments', 'Under Review', 'Cancelled'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_submittals');
    }
};
