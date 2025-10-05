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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم المستلم
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null'); // المرسل
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('cascade'); // أمر العمل
            $table->string('type')->default('note'); // نوع الإشعار
            $table->string('title')->nullable(); // عنوان الإشعار
            $table->text('message'); // نص الرسالة
            $table->boolean('is_read')->default(false); // هل تم القراءة
            $table->timestamp('read_at')->nullable(); // وقت القراءة
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('user_id');
            $table->index('is_read');
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
