<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InspectionsReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $inspections;
    protected $city;

    public function __construct($inspections, $city = 'الرياض')
    {
        $this->inspections = $inspections;
        $this->city = $city;
    }

    public function collection()
    {
        return $this->inspections;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'نوع العمل',
            'رقم الرخصة',
            'إجمالي الاختبارات',
            'ناجحة',
            'راسبة',
            'النسبة المئوية',
            'الإجراءات',
        ];
    }

    public function map($inspection): array
    {
        static $counter = 0;
        $counter++;

        $total = $inspection->total_tests ?? 0;
        $successful = $inspection->successful_tests ?? 0;
        $failed = $inspection->failed_tests ?? 0;
        $percentage = $total > 0 ? number_format(($successful / $total) * 100, 1) . '%' : '0%';

        return [
            $counter,
            $inspection->work_order_number ?? '',
            $inspection->work_type ?? '',
            $inspection->license_number ?? '',
            $total,
            $successful,
            $failed,
            $percentage,
            $inspection->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A5568']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}

