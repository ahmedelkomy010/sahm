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
        Schema::table('excavation_details', function (Blueprint $table) {
            // Add work_order_id if it doesn't exist
            if (!Schema::hasColumn('excavation_details', 'work_order_id')) {
                $table->foreignId('work_order_id')->after('license_id')->constrained()->onDelete('cascade');
            }

            // Add excavation type and soil type
            if (!Schema::hasColumn('excavation_details', 'excavation_type')) {
                $table->string('excavation_type')->nullable()->after('status_text');
            }
            if (!Schema::hasColumn('excavation_details', 'soil_type')) {
                $table->string('soil_type')->nullable()->after('excavation_type');
            }

            // Add price and total fields
            if (!Schema::hasColumn('excavation_details', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('soil_type');
            }
            if (!Schema::hasColumn('excavation_details', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('price');
            }

            // Add is_open_excavation flag
            if (!Schema::hasColumn('excavation_details', 'is_open_excavation')) {
                $table->boolean('is_open_excavation')->default(false)->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('excavation_details', function (Blueprint $table) {
            $columns = [
                'work_order_id',
                'excavation_type',
                'soil_type',
                'price',
                'total',
                'is_open_excavation'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('excavation_details', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 