<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SafetyViolationsExport implements FromArray, WithHeadings
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
            // تحديد جهة المخالفة
            $source = '';
            if ($violation->violation_source == 'internal') {
                $source = 'داخلية';
            } elseif ($violation->violation_source == 'external') {
                $source = 'خارجية';
            } else {
                $source = $violation->violation_source ?? '-';
            }

            $data[] = [
                $counter++,
                $violation->workOrder->order_number ?? '-',
                $violation->workOrder->work_type ?? '-',
                $violation->violation_date ? $violation->violation_date->format('Y-m-d') : '-',
                $violation->violator ?? '-',
                $source,
                $violation->violation_amount ?? 0,
                $violation->description ?? '-',
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
            'تاريخ المخالفة',
            'المخالف',
            'جهة المخالفة',
            'قيمة المخالفة (ريال)',
            'الوصف',
            'المدينة',
        ];
    }
}

