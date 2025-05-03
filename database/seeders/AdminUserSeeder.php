<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود مستخدم مشرف واحد على الأقل
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            User::create([
                'name' => 'مشرف النظام',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_admin' => 1,
            ]);
            
            $this->command->info('تم إنشاء مستخدم مشرف افتراضي:');
            $this->command->info('البريد الإلكتروني: admin@example.com');
            $this->command->info('كلمة المرور: password');
        } else {
            // تأكد من أن المستخدم مشرف
            if ($admin->is_admin != 1) {
                $admin->is_admin = 1;
                $admin->save();
                $this->command->info('تم تحديث حالة المستخدم الموجود ليكون مشرفاً');
            } else {
                $this->command->info('المستخدم المشرف موجود بالفعل');
            }
        }
        
        // تأكد من أن المستخدم الحالي (إذا كان موجوداً) هو مشرف
        $currentUser = User::first();
        if ($currentUser && $currentUser->is_admin != 1) {
            $currentUser->is_admin = 1;
            $currentUser->save();
            $this->command->info("تم تحديث المستخدم {$currentUser->name} ليكون مشرفاً");
        }
    }
}
