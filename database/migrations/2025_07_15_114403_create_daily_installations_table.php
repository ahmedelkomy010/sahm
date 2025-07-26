<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('daily_installations', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
//             $table->date('work_date');
//             $table->json('installation_data'); // بيانات التركيبات كـ JSON
//             $table->decimal('total_amount', 12, 2)->default(0); // الإجمالي اليومي
//             $table->integer('total_items')->default(0); // عدد العناصر المركبة
//             $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم الذي أدخل البيانات
//             $table->text('notes')->nullable(); // ملاحظات
//             $table->timestamps();
            
//             // فهرس مركب للعمل والتاريخ لضمان عدم تكرار البيانات لنفس اليوم
//             $table->unique(['work_order_id', 'work_date']);
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('daily_installations');
//     }
// };
