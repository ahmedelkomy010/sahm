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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('code')->comment('كد المادة');
            $table->text('description')->comment('وصف المادة');
            $table->decimal('planned_quantity', 10, 2)->default(0)->comment('الكميه المخططه');
            $table->string('unit')->comment('الوحدة');
            $table->string('line')->nullable()->comment('السطر');
            $table->boolean('check_in')->default(false)->comment('CHECK IN');
            $table->boolean('check_out')->default(false)->comment('CHECK OUT');
            $table->date('date_gatepass')->nullable()->comment('DATE GATEPASS');
            $table->decimal('stock_in', 10, 2)->default(0)->comment('STOCK IN');
            $table->decimal('stock_out', 10, 2)->default(0)->comment('STOCK OUT');
            $table->decimal('actual_quantity', 10, 2)->default(0)->comment('الكمية المنفذه الفعلية');
            $table->decimal('difference', 10, 2)->default(0)->comment('الفرق');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
