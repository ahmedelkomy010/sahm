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
            $table->json('installations_data')->nullable()->after('electrical_works');
            $table->boolean('installations_locked')->default(false)->after('installations_data');
            $table->timestamp('installations_locked_at')->nullable()->after('installations_locked');
            $table->unsignedBigInteger('installations_locked_by')->nullable()->after('installations_locked_at');
            
            // إضافة foreign key لـ installations_locked_by
            $table->foreign('installations_locked_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['installations_locked_by']);
            $table->dropColumn(['installations_data', 'installations_locked', 'installations_locked_at', 'installations_locked_by']);
        });
    }
};
