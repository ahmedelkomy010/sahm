<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lab_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('consultant')->nullable();
            $table->string('urs')->nullable();
            $table->string('contractor')->nullable();
            $table->date('date')->nullable();
            $table->string('permit_no')->nullable();
            $table->date('permit_date')->nullable();
            $table->boolean('street_type_terabi')->default(false);
            $table->boolean('street_type_asphalt')->default(false);
            $table->boolean('street_type_blat')->default(false);
            $table->string('lab_check')->nullable();
            $table->integer('year')->nullable();
            $table->string('work_type')->nullable();
            $table->string('depth')->nullable();
            $table->string('soil_compaction')->nullable();
            $table->string('mc1rc2')->nullable();
            $table->string('max_density')->nullable();
            $table->string('asphalt_percent')->nullable();
            $table->string('gradation')->nullable();
            $table->string('marshall')->nullable();
            $table->string('tile_eval')->nullable();
            $table->string('soil_class')->nullable();
            $table->string('proctor')->nullable();
            $table->string('concrete')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lab_licenses');
    }
}; 