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
        Schema::create('daily_work_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->date('program_date')->default(now());
            $table->string('work_type')->nullable();
            $table->string('location')->nullable();
            $table->string('google_coordinates', 500)->nullable();
            $table->string('consultant_name')->nullable();
            $table->string('site_engineer')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('issuer')->nullable();
            $table->string('receiver')->nullable();
            $table->string('safety_officer')->nullable();
            $table->string('quality_monitor')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_work_programs');
    }
};
