<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AllProjectsRevenuesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // سنرجع البيانات كـ collection من arrays
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'نوع المشروع',
            'اسم المشروع',
            'رقم العقد',
            'الموقع',
            'عدد المستخلصات',
            'إجمالي قيمة المستخلصات',
            'إجمالي الضريبة',
            'إجمالي الغرامات',
            'صافي قيمة المستخلصات',
            'إجمالي المدفوعات',
            'ضريبة الدفعة الأولى',
            'المبلغ المتبقي عند العميل',
        ];
    }

    public function map($row): array
    {
        return [
            $row['project_type'] ?? '',
            $row['project_name'] ?? '',
            $row['contract_number'] ?? '',
            $row['location'] ?? '',
            $row['count'] ?? 0,
            number_format($row['total_value'] ?? 0, 2),
            number_format($row['total_tax'] ?? 0, 2),
            number_format($row['total_penalties'] ?? 0, 2),
            number_format($row['total_net_value'] ?? 0, 2),
            number_format($row['total_payments'] ?? 0, 2),
            number_format($row['first_payment_tax'] ?? 0, 2),
            number_format($row['unpaid_amount'] ?? 0, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // تنسيق رأس الجدول
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // تنسيق جميع الخلايا
        $sheet->getStyle('A:L')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // ضبط عرض الأعمدة
        $sheet->getColumnDimension('A')->setWidth(20); // نوع المشروع
        $sheet->getColumnDimension('B')->setWidth(30); // اسم المشروع
        $sheet->getColumnDimension('C')->setWidth(20); // رقم العقد
        $sheet->getColumnDimension('D')->setWidth(20); // الموقع
        $sheet->getColumnDimension('E')->setWidth(18); // عدد المستخلصات
        $sheet->getColumnDimension('F')->setWidth(22); // إجمالي قيمة المستخلصات
        $sheet->getColumnDimension('G')->setWidth(18); // إجمالي الضريبة
        $sheet->getColumnDimension('H')->setWidth(18); // إجمالي الغرامات
        $sheet->getColumnDimension('I')->setWidth(22); // صافي قيمة المستخلصات
        $sheet->getColumnDimension('J')->setWidth(20); // إجمالي المدفوعات
        $sheet->getColumnDimension('K')->setWidth(20); // ضريبة الدفعة الأولى
        $sheet->getColumnDimension('L')->setWidth(25); // المبلغ المتبقي

        return [];
    }
}

