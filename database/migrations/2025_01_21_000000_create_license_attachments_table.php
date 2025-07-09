<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('license_attachments')) {
            Schema::create('license_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('license_id')->constrained()->onDelete('cascade');
                $table->string('attachment_type');
                $table->string('file_path');
                $table->string('file_name');
                $table->string('mime_type')->nullable();
                $table->integer('file_size')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('license_attachments');
    }
}; 