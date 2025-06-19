<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('license_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->string('license_number');
            $table->date('violation_date');
            $table->date('payment_due_date');
            $table->decimal('violation_amount', 10, 2);
            $table->string('violation_number');
            $table->string('responsible_party');
            $table->text('violation_description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('license_violations');
    }
}; 