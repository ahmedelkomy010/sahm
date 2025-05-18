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
        Schema::table('licenses', function (Blueprint $table) {
            // التحقق من وجود العمود قبل إضافته
            if (!Schema::hasColumn('licenses', 'invoice_extension_file_path')) {
                $table->string('invoice_extension_file_path')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'invoice_extension_start_date')) {
                $table->date('invoice_extension_start_date')->nullable();
            }
            
            if (!Schema::hasColumn('licenses', 'invoice_extension_end_date')) {
                $table->date('invoice_extension_end_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $columns = [
                'invoice_extension_file_path',
                'invoice_extension_start_date',
                'invoice_extension_end_date'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
