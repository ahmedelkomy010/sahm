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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('first_payment_collection_file')->nullable();
            $table->string('second_payment_collection_file')->nullable();
            $table->string('first_payment_extract_file')->nullable();
            $table->string('second_payment_extract_file')->nullable();
            $table->string('total_extract_file')->nullable();
            $table->string('entry_sheet')->nullable();
            $table->json('invoice_images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'first_payment_collection_file',
                'second_payment_collection_file',
                'first_payment_extract_file',
                'second_payment_extract_file',
                'total_extract_file',
                'entry_sheet',
                'invoice_images',
            ]);
        });
    }
};
