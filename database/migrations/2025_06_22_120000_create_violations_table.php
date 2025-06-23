<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->string('violation_type');
            $table->string('responsible_party');
            $table->string('payment_status');
            $table->text('description');
            $table->text('actions_taken');
            $table->decimal('violation_value', 10, 2);
            $table->string('violation_file')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('violations');
    }
}; 