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
            // التحقق من وجود حقول شهادة التنسيق
            if (!Schema::hasColumn('licenses', 'coordination_certificate_number')) {
                $table->string('coordination_certificate_number')->nullable()->after('work_order_id');
            }
            
            if (!Schema::hasColumn('licenses', 'coordination_certificate_path')) {
                $table->text('coordination_certificate_path')->nullable()->after('coordination_certificate_number');
            }
            
            if (!Schema::hasColumn('licenses', 'coordination_certificate_notes')) {
                $table->text('coordination_certificate_notes')->nullable()->after('coordination_certificate_path');
            }
            
            if (!Schema::hasColumn('licenses', 'letters_commitments_file_path')) {
                $table->text('letters_commitments_file_path')->nullable()->after('coordination_certificate_notes');
            }
            
            if (!Schema::hasColumn('licenses', 'has_restriction')) {
                $table->boolean('has_restriction')->default(false)->after('letters_commitments_file_path');
            }
            
            // التحقق من وجود حقول رخصة الحفر
            if (!Schema::hasColumn('licenses', 'license_value')) {
                $table->decimal('license_value', 10, 2)->nullable()->after('has_restriction');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_value')) {
                $table->decimal('extension_value', 10, 2)->nullable()->after('license_value');
            }
            
            if (!Schema::hasColumn('licenses', 'excavation_length')) {
                $table->decimal('excavation_length', 8, 2)->nullable()->after('extension_value');
            }
            
            if (!Schema::hasColumn('licenses', 'excavation_width')) {
                $table->decimal('excavation_width', 8, 2)->nullable()->after('excavation_length');
            }
            
            if (!Schema::hasColumn('licenses', 'excavation_depth')) {
                $table->decimal('excavation_depth', 8, 2)->nullable()->after('excavation_width');
            }
            
            if (!Schema::hasColumn('licenses', 'license_start_date')) {
                $table->date('license_start_date')->nullable()->after('excavation_depth');
            }
            
            if (!Schema::hasColumn('licenses', 'license_end_date')) {
                $table->date('license_end_date')->nullable()->after('license_start_date');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_start_date')) {
                $table->date('extension_start_date')->nullable()->after('license_end_date');
            }
            
            if (!Schema::hasColumn('licenses', 'extension_end_date')) {
                $table->date('extension_end_date')->nullable()->after('extension_start_date');
            }
            
            // التحقق من وجود حقول المرفقات
            if (!Schema::hasColumn('licenses', 'payment_invoices_path')) {
                $table->text('payment_invoices_path')->nullable()->after('extension_end_date');
            }
            
            if (!Schema::hasColumn('licenses', 'payment_proof_path')) {
                $table->text('payment_proof_path')->nullable()->after('payment_invoices_path');
            }
            
            if (!Schema::hasColumn('licenses', 'license_activation_path')) {
                $table->text('license_activation_path')->nullable()->after('payment_proof_path');
            }
            
            if (!Schema::hasColumn('licenses', 'notes_attachments_path')) {
                $table->text('notes_attachments_path')->nullable()->after('license_activation_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $columnsToCheck = [
                'coordination_certificate_number',
                'coordination_certificate_path', 
                'coordination_certificate_notes',
                'letters_commitments_file_path',
                'has_restriction',
                'license_value',
                'extension_value',
                'excavation_length',
                'excavation_width',
                'excavation_depth',
                'license_start_date',
                'license_end_date',
                'extension_start_date',
                'extension_end_date',
                'payment_invoices_path',
                'payment_proof_path',
                'license_activation_path',
                'notes_attachments_path',
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('licenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 