<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReferenceMaterial;

class ReferenceMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['code' => '1', 'description' => 'احمد'],
            ['code' => '2', 'description' => 'محمد'],
            ['code' => '3', 'description' => 'ساره'],
            ['code' => '1001', 'description' => 'أسمنت بورتلاندي'],
            ['code' => '1002', 'description' => 'حديد تسليح 12مم'],
            ['code' => '1003', 'description' => 'رمل ناعم'],
            ['code' => '1004', 'description' => 'حصمة 3/4'],
            ['code' => '1005', 'description' => 'طوب أسمنتي 20سم'],
            // ... أضف باقي المواد من الصورة هنا بنفس النمط
        ];
        foreach ($materials as $material) {
            ReferenceMaterial::updateOrCreate(['code' => $material['code']], ['description' => $material['description']]);
        }
    }
}
