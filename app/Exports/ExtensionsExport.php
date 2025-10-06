<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExtensionsExport implements FromArray, WithHeadings
{
    protected $licenses;
    protected $city;

    public function __construct($licenses, $city)
    {
        $this->licenses = $licenses;
        $this->city = $city;
    }

    public function array(): array
    {
        $data = [];
        $counter = 1;
        
        foreach ($this->licenses as $license) {
            // حساب مدة الرخصة
            $licenseDays = 0;
            if ($license->license_start_date && $license->license_end_date) {
                $licenseDays = $license->license_start_date->diffInDays($license->license_end_date);
            }
            
            // حساب عدد التمديدات
            $extensionsCount = $license->extensions->count();
            $hasExtension = $extensionsCount > 0 ? 'نعم' : 'لا';
            
            // نص عربي لعدد المرات
            $extensionsText = '-';
            if ($extensionsCount > 0) {
                if ($extensionsCount == 1) {
                    $extensionsText = '1 مرة';
                } elseif ($extensionsCount == 2) {
                    $extensionsText = '2 مرتان';
                } else {
                    $extensionsText = $extensionsCount . ' مرات';
                }
            }
            
            $data[] = [
                $counter++,
                $license->workOrder->order_number ?? '-',
                $license->workOrder->work_type ?? '-',
                $license->license_number ?? '-',
                $licenseDays,
                $hasExtension,
                $extensionsText,
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
            'رقم الرخصة',
            'مدة الرخصة (يوم)',
            'تم التمديد',
            'عدد مرات التمديد',
            'المدينة',
        ];
    }
}

