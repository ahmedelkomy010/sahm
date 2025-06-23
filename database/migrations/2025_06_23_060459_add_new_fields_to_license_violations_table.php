<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            if (!Schema::hasColumn('license_violations', 'violation_type')) {
                $table->string('violation_type')->nullable()->after('violation_date');
            }
            if (!Schema::hasColumn('license_violations', 'payment_status')) {
                $table->integer('payment_status')->nullable()->after('responsible_party');
            }
            if (!Schema::hasColumn('license_violations', 'work_order_id')) {
                $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('cascade')->after('license_id');
            }
        });
        
        DB::statement('ALTER TABLE license_violations MODIFY license_number VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_violations', function (Blueprint $table) {
            $table->dropColumn(['violation_type', 'payment_status']);
            if (Schema::hasColumn('license_violations', 'work_order_id')) {
                $table->dropForeign(['work_order_id']);
                $table->dropColumn('work_order_id');
            }
        });
        
        DB::statement('ALTER TABLE license_violations MODIFY license_number VARCHAR(255) NOT NULL');
    }
};
