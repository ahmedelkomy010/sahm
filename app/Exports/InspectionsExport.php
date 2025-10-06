<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InspectionsExport implements FromArray, WithHeadings
{
    protected $workOrders;
    protected $city;

    public function __construct($workOrders, $city)
    {
        $this->workOrders = $workOrders;
        $this->city = $city;
    }

    public function array(): array
    {
        $data = [];
        $counter = 1;
        
        foreach ($this->workOrders as $workOrder) {
            // فحص وجود اختبارات معملية
            $hasTests = false;
            foreach($workOrder->licenses as $license) {
                if ($license->total_tests_count > 0 || !empty($license->lab_tests_data)) {
                    $hasTests = true;
                    break;
                }
            }
            
            $data[] = [
                $counter++,
                $workOrder->order_number ?? '-',
                $workOrder->work_type ?? '-',
                $hasTests ? 'نعم' : 'لا',
                $this->city,
            ];
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'نوع العمل',
            'يوجد اختبارات',
            'المدينة',
        ];
    }
}

