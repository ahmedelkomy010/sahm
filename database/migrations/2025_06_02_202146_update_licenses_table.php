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
            // معلومات الرخصة الأساسية
            if (!Schema::hasColumn('licenses', 'work_order_id')) {
                $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('cascade');
            }
            if (!Schema::hasColumn('licenses', 'license_number')) {
                $table->string('license_number')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_date')) {
                $table->date('license_date')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_type')) {
                $table->string('license_type')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_value')) {
                $table->decimal('license_value', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('licenses', 'extension_value')) {
                $table->decimal('extension_value', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('licenses', 'notes')) {
                $table->text('notes')->nullable();
            }

            // معلومات الحظر
            if (!Schema::hasColumn('licenses', 'has_restriction')) {
                $table->boolean('has_restriction')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'restriction_authority')) {
                $table->string('restriction_authority')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'restriction_reason')) {
                $table->string('restriction_reason')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'restriction_notes')) {
                $table->text('restriction_notes')->nullable();
            }

            // معلومات التواريخ
            if (!Schema::hasColumn('licenses', 'license_start_date')) {
                $table->date('license_start_date')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_end_date')) {
                $table->date('license_end_date')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_alert_days')) {
                $table->integer('license_alert_days')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'license_length')) {
                $table->decimal('license_length', 10, 2)->nullable();
            }

            // معلومات الحفر
            if (!Schema::hasColumn('licenses', 'excavation_length')) {
                $table->decimal('excavation_length', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('licenses', 'excavation_width')) {
                $table->decimal('excavation_width', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('licenses', 'excavation_depth')) {
                $table->decimal('excavation_depth', 10, 2)->nullable();
            }

            // معلومات الاختبارات
            if (!Schema::hasColumn('licenses', 'has_depth_test')) {
                $table->boolean('has_depth_test')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'has_soil_compaction_test')) {
                $table->boolean('has_soil_compaction_test')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'has_rc1_mc1_test')) {
                $table->boolean('has_rc1_mc1_test')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'has_asphalt_test')) {
                $table->boolean('has_asphalt_test')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'has_soil_test')) {
                $table->boolean('has_soil_test')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'has_interlock_test')) {
                $table->boolean('has_interlock_test')->default(false);
            }

            // معلومات الإخلاء
            if (!Schema::hasColumn('licenses', 'is_evacuated')) {
                $table->boolean('is_evacuated')->default(false);
            }
            if (!Schema::hasColumn('licenses', 'evac_license_number')) {
                $table->string('evac_license_number')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'evac_license_value')) {
                $table->decimal('evac_license_value', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('licenses', 'evac_payment_number')) {
                $table->string('evac_payment_number')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'evac_date')) {
                $table->date('evac_date')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'evac_amount')) {
                $table->decimal('evac_amount', 10, 2)->nullable();
            }

            // مسارات الملفات
            if (!Schema::hasColumn('licenses', 'coordination_certificate_path')) {
                $table->text('coordination_certificate_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'coordination_certificate_notes')) {
                $table->text('coordination_certificate_notes')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'letters_commitments_file_path')) {
                $table->text('letters_commitments_file_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'payment_invoices_path')) {
                $table->text('payment_invoices_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'payment_proof_path')) {
                $table->text('payment_proof_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'activation_file_path')) {
                $table->text('activation_file_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'invoice_extension_file_path')) {
                $table->text('invoice_extension_file_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'soil_test_images_path')) {
                $table->text('soil_test_images_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'evac_files_path')) {
                $table->text('evac_files_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'violation_files_path')) {
                $table->text('violation_files_path')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'notes_attachments_path')) {
                $table->text('notes_attachments_path')->nullable();
            }

            // بيانات الجداول
            if (!Schema::hasColumn('licenses', 'lab_table1_data')) {
                $table->json('lab_table1_data')->nullable();
            }
            if (!Schema::hasColumn('licenses', 'lab_table2_data')) {
                $table->json('lab_table2_data')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // يمكنك إضافة عمليات التراجع هنا إذا كنت تريد
        });
    }
};
