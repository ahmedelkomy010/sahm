<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contract_number')->unique();
            $table->enum('project_type', ['civil', 'electrical', 'mixed']);
            $table->string('location');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // إضافة حذف لين للمشاريع
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}; 