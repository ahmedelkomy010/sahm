<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'first_payment_collection_file')) {
                $table->string('first_payment_collection_file')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'second_payment_collection_file')) {
                $table->string('second_payment_collection_file')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'first_payment_extract_file')) {
                $table->string('first_payment_extract_file')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'second_payment_extract_file')) {
                $table->string('second_payment_extract_file')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'total_extract_file')) {
                $table->string('total_extract_file')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'entry_sheet')) {
                $table->string('entry_sheet')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'invoice_images')) {
                $table->json('invoice_images')->nullable();
            }
        });
    }

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