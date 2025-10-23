<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InspectionsReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $licenses;

    public function __construct($licenses)
    {
        $this->licenses = $licenses;
    }

    public function collection()
    {
        return $this->licenses;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم الرخصة',
            'رقم أمر العمل',
            'نوع العمل',
            'المدينة',
            'إجمالي الاختبارات',
            'اختبارات ناجحة',
            'اختبارات راسبة',
            'نسبة النجاح %',
            'تاريخ الإضافة',
        ];
    }

    public function map($license): array
    {
        static $index = 0;
        $index++;

        $successRate = 0;
        if ($license->total_tests_count > 0) {
            $successRate = ($license->successful_tests_count / $license->total_tests_count) * 100;
        }

        return [
            $index,
            $license->license_number ?? '-',
            $license->workOrder->order_number ?? '-',
            $license->workOrder->work_type ?? '-',
            $license->workOrder->city ?? '-',
            $license->total_tests_count ?? 0,
            $license->successful_tests_count ?? 0,
            $license->failed_tests_count ?? 0,
            number_format($successRate, 1) . '%',
            $license->created_at ? $license->created_at->format('Y-m-d') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}

