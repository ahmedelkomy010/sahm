<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds to create an admin user.
     */
    public function run(): void
    {
        // المستخدم المسؤول الأول
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@sahm.com',
            'password' => Hash::make('Admin@123'),
            'is_admin' => true,
        ]);
        
        // إضافة مستخدم أحمد الكومي كمسؤول ثاني
        User::create([
            'name' => 'احمد الكومي',
            'email' => 'ahmedelkomy@example.com',
            'password' => Hash::make('123456'),
            'is_admin' => true,
        ]);
    }
} 