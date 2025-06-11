<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\WorkOrderSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ReferenceMaterialsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->call([
            AdminSeeder::class,
            WorkOrderSeeder::class,
        ]);

        // استدعاء Seeder المواد المرجعية
        $this->call(ReferenceMaterialsSeeder::class);
        
        // إنشاء أرقام شهادات التنسيق للرخص الموجودة
        $this->call(CoordinationCertificateNumberSeeder::class);
    }
}
