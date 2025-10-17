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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('status_note_updated_by')->nullable()->after('work_order_status_note');
            $table->timestamp('status_note_updated_at')->nullable()->after('status_note_updated_by');
            
            $table->foreign('status_note_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['status_note_updated_by']);
            $table->dropColumn(['status_note_updated_by', 'status_note_updated_at']);
        });
    }
};
