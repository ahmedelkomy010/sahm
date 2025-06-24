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
        Schema::table('license_violations', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('license_violations', 'payment_invoice_number')) {
                $table->string('payment_invoice_number')->nullable()->after('violation_amount');
            }
            
            if (!Schema::hasColumn('license_violations', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('payment_invoice_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            if (Schema::hasColumn('license_violations', 'payment_invoice_number')) {
                $table->dropColumn('payment_invoice_number');
            }
            
            if (Schema::hasColumn('license_violations', 'attachment_path')) {
                $table->dropColumn('attachment_path');
            }
        });
    }
};
