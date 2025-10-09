<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LicensesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'قيمة الرخصة',
        ];
    }

    public function map($license): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $license->license_number ?? 'غير محدد',
            $license->workOrder->order_entry_number ?? 'غير محدد',
            $license->workOrder->work_type ?? 'غير محدد',
            $license->license_value ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '495057']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'e8eef7']
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'الرخص';
    }
}

