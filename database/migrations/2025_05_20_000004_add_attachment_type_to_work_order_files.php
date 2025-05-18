<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('work_order_files', function (Blueprint $table) {
            $table->string('attachment_type')->nullable()->after('file_category');
        });
    }

    public function down()
    {
        Schema::table('work_order_files', function (Blueprint $table) {
            $table->dropColumn('attachment_type');
        });
    }
}; 