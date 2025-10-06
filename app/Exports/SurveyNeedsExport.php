<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SurveyNeedsExport implements FromArray, WithHeadings
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
            // حساب عدد المسوحات والملفات بشكل آمن
            $surveysCount = 0;
            $totalFiles = 0;
            
            try {
                $surveysCount = $workOrder->surveys->count();
                foreach ($workOrder->surveys as $survey) {
                    $totalFiles += $survey->files->count();
                }
            } catch (\Exception $e) {
                // في حالة حدوث خطأ، استخدم القيم الافتراضية
            }

            // تحديد حالة ملفات المسح
            if ($surveysCount == 0) {
                $status = 'لا يوجد مسح';
            } elseif ($totalFiles == 0) {
                $status = 'بدون ملفات';
            } else {
                $status = 'يوجد ملفات';
            }

            $data[] = [
                $counter++,
                $workOrder->order_number ?? '-',
                $workOrder->work_type ?? '-',
                $workOrder->approval_date ? $workOrder->approval_date->format('Y-m-d') : '-',
                $surveysCount,
                $totalFiles,
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
            'نوع أمر العمل',
            'تاريخ الاعتماد',
            'عدد المسوحات',
            'عدد الملفات',
            'حالة ملفات المسح',
            'المدينة',
        ];
    }
}

