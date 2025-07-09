<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_attachments', function (Blueprint $table) {
            $table->foreignId('license_id')->nullable()->constrained('licenses')->onDelete('cascade');
            $table->string('attachment_type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('invoice_attachments', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropColumn(['license_id', 'attachment_type']);
        });
    }
}; 