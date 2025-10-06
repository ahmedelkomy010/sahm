<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompletionFilesExport implements FromArray, WithHeadings
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
            // تحديد حالة الرفع
            $status = $workOrder->completion_files_count > 0 ? 'تم الرفع' : 'لم يتم الرفع';

            $data[] = [
                $counter++,
                $workOrder->order_number ?? '-',
                $workOrder->work_type ?? '-',
                $workOrder->approval_date ? $workOrder->approval_date->format('Y-m-d') : '-',
                $status,
                $workOrder->city ?? '-',
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
            'تاريخ الاعتماد',
            'حالة الرفع',
            'المدينة',
        ];
    }
}

