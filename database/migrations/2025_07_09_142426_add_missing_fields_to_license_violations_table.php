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
            // إضافة الأعمدة الناقصة فقط
            $table->string('responsible_party')->nullable()->after('violation_type');
            $table->date('payment_due_date')->nullable()->after('violation_amount');
            $table->text('violation_description')->nullable()->after('description');
            $table->string('payment_invoice_number')->nullable()->after('payment_status');
            $table->string('attachment_path')->nullable()->after('attachment');
            $table->text('notes')->nullable()->after('attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            // إزالة الأعمدة
            $table->dropColumn([
                'responsible_party',
                'payment_due_date',
                'violation_description',
                'payment_invoice_number',
                'attachment_path',
                'notes'
            ]);
        });
    }
};
