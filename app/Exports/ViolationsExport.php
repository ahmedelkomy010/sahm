<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ViolationsExport implements FromArray, WithHeadings
{
    protected $violations;
    protected $city;

    public function __construct($violations, $city)
    {
        $this->violations = $violations;
        $this->city = $city;
    }

    public function array(): array
    {
        $data = [];
        $counter = 1;
        
        foreach ($this->violations as $violation) {
            // تحديد الحالة
            $status = '';
            if ($violation->status == 'نشط' || $violation->status == 'active') {
                $status = 'نشط';
            } elseif ($violation->status == 'قيد المعالجة' || $violation->status == 'in_progress') {
                $status = 'قيد المعالجة';
            } elseif ($violation->status == 'تم الحل' || $violation->status == 'resolved') {
                $status = 'تم الحل';
            } else {
                $status = $violation->status ?? 'نشط';
            }

            $data[] = [
                $counter++,
                $violation->workOrder->order_number ?? '-',
                $violation->workOrder->work_type ?? '-',
                $violation->violation_type ?? '-',
                $violation->violation_date ? $violation->violation_date->format('Y-m-d') : '-',
                $violation->violation_amount ?? 0,
                $violation->description ?? $violation->violation_description ?? '-',
                $status,
                $violation->workOrder->city ?? '-',
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
            'نوع المخالفة',
            'تاريخ المخالفة',
            'قيمة المخالفة (ريال)',
            'الوصف',
            'الحالة',
            'المدينة',
        ];
    }
}

