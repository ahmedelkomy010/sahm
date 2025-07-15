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
            if (!Schema::hasColumn('license_violations', 'responsible_party')) {
                $table->string('responsible_party')->nullable()->after('violation_type');
            }
            if (!Schema::hasColumn('license_violations', 'payment_due_date')) {
                $table->date('payment_due_date')->nullable()->after('violation_amount');
            }
            if (!Schema::hasColumn('license_violations', 'violation_description')) {
                $table->text('violation_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('license_violations', 'payment_invoice_number')) {
                $table->string('payment_invoice_number')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('license_violations', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('attachment');
            }
            if (!Schema::hasColumn('license_violations', 'notes')) {
                $table->text('notes')->nullable()->after('attachment_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            // إزالة الأعمدة
            $fieldsToRemove = ['responsible_party', 'payment_due_date', 'violation_description', 'payment_invoice_number', 'attachment_path', 'notes'];
            
            foreach ($fieldsToRemove as $field) {
                if (Schema::hasColumn('license_violations', $field)) {
                    $table->dropColumn($field);
                }
            }
        });
    }
};
