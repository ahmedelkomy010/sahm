<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\License;

class GenerateCoordinationNumbers extends Command
{
    protected $signature = 'licenses:generate-coordination-numbers';
    protected $description = 'Generate coordination certificate numbers for existing licenses';

    public function handle()
    {
        $licenses = License::whereNull('coordination_certificate_number')
                          ->orWhere('coordination_certificate_number', '')
                          ->get();

        $count = 0;

        foreach ($licenses as $license) {
            $year = $license->license_date ? $license->license_date->format('Y') : date('Y');
            $workOrderId = str_pad($license->work_order_id, 4, '0', STR_PAD_LEFT);
            $licenseId = str_pad($license->id, 4, '0', STR_PAD_LEFT);
            
            $coordinationNumber = "TC-{$year}-{$workOrderId}-{$licenseId}";
            
            $license->update([
                'coordination_certificate_number' => $coordinationNumber
            ]);
            
            $count++;
            $this->info("Generated coordination number for License #{$license->id}: {$coordinationNumber}");
        }

        $this->info("Successfully generated coordination numbers for {$count} licenses!");
        
        return Command::SUCCESS;
    }
} 