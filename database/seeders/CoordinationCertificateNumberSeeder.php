<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\License;

class CoordinationCertificateNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء أرقام شهادات التنسيق للرخص الموجودة التي لا تحتوي على رقم
        $licenses = License::whereNull('coordination_certificate_number')
                          ->orWhere('coordination_certificate_number', '')
                          ->get();

        foreach ($licenses as $license) {
            // إنشاء رقم شهادة التنسيق بناءً على تاريخ الرخصة ومعرف أمر العمل
            $year = $license->license_date ? $license->license_date->format('Y') : date('Y');
            $workOrderId = str_pad($license->work_order_id, 4, '0', STR_PAD_LEFT);
            $licenseId = str_pad($license->id, 4, '0', STR_PAD_LEFT);
            
            // تنسيق: TC-YYYY-WORKORDER-LICENSE (TC = Coordination Certificate)
            $coordinationNumber = "TC-{$year}-{$workOrderId}-{$licenseId}";
            
            $license->update([
                'coordination_certificate_number' => $coordinationNumber
            ]);
        }

        $this->command->info("تم إنشاء أرقام شهادات التنسيق لـ {$licenses->count()} رخصة.");
    }
} 