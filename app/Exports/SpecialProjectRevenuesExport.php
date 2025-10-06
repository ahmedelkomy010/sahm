<?php

namespace App\Exports;

use App\Models\SpecialProjectRevenue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SpecialProjectRevenuesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return SpecialProjectRevenue::where('project_id', $this->projectId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'اسم العميل',
            'المشروع',
            'رقم العقد',
            'رقم المستخلص',
            'المكتب',
            'نوع المستخلص',
            'رقم PO',
            'رقم الفاتورة',
            'إجمالي قيمة المستخلصات غير شامله الضريبه',
            'جهة المستخلص',
            'قيمة الضريبة',
            'الغرامات',
            'ضريبة الدفعة الأولى',
            'صافي قيمة المستخلص',
            'تاريخ إعداد المستخلص',
            'العام',
            'موقف المستخلص عند ...',
            'الرقم المرجعي',
            'تاريخ الصرف',
            'قيمة الصرف',
            'حالة الصرف',
            'الإجراءات',
        ];
    }

    /**
     * @var SpecialProjectRevenue $revenue
     */
    public function map($revenue): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $revenue->client_name ?? '',
            $revenue->project ?? '',
            $revenue->contract_number ?? '',
            $revenue->extract_number ?? '',
            $revenue->office ?? '',
            $revenue->extract_type ?? '',
            $revenue->po_number ?? '',
            $revenue->invoice_number ?? '',
            $revenue->total_value ?? 0,
            $revenue->extract_entity ?? '',
            $revenue->tax_value ?? 0,
            $revenue->penalties ?? 0,
            $revenue->advance_payment_tax ?? 0,
            $revenue->net_value ?? 0,
            $revenue->preparation_date ? $revenue->preparation_date->format('Y-m-d') : '',
            $revenue->year ?? '',
            $revenue->extract_status ?? '',
            $revenue->reference_number ?? '',
            $revenue->payment_date ? $revenue->payment_date->format('Y-m-d') : '',
            $revenue->payment_amount ?? 0,
            $revenue->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع',
            $revenue->procedures ?? '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Apply styles to header row
        $sheet->getStyle('A1:W1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '667eea'],
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

        // Apply borders to all cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:W' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}

    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return SpecialProjectRevenue::where('project_id', $this->projectId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'اسم العميل',
            'المشروع',
            'رقم العقد',
            'رقم المستخلص',
            'المكتب',
            'نوع المستخلص',
            'رقم PO',
            'رقم الفاتورة',
            'إجمالي قيمة المستخلصات غير شامله الضريبه',
            'جهة المستخلص',
            'قيمة الضريبة',
            'الغرامات',
            'ضريبة الدفعة الأولى',
            'صافي قيمة المستخلص',
            'تاريخ إعداد المستخلص',
            'العام',
            'موقف المستخلص عند ...',
            'الرقم المرجعي',
            'تاريخ الصرف',
            'قيمة الصرف',
            'حالة الصرف',
            'الإجراءات',
        ];
    }

    /**
     * @var SpecialProjectRevenue $revenue
     */
    public function map($revenue): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $revenue->client_name ?? '',
            $revenue->project ?? '',
            $revenue->contract_number ?? '',
            $revenue->extract_number ?? '',
            $revenue->office ?? '',
            $revenue->extract_type ?? '',
            $revenue->po_number ?? '',
            $revenue->invoice_number ?? '',
            $revenue->total_value ?? 0,
            $revenue->extract_entity ?? '',
            $revenue->tax_value ?? 0,
            $revenue->penalties ?? 0,
            $revenue->advance_payment_tax ?? 0,
            $revenue->net_value ?? 0,
            $revenue->preparation_date ? $revenue->preparation_date->format('Y-m-d') : '',
            $revenue->year ?? '',
            $revenue->extract_status ?? '',
            $revenue->reference_number ?? '',
            $revenue->payment_date ? $revenue->payment_date->format('Y-m-d') : '',
            $revenue->payment_amount ?? 0,
            $revenue->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع',
            $revenue->procedures ?? '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Apply styles to header row
        $sheet->getStyle('A1:W1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '667eea'],
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

        // Apply borders to all cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:W' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}

    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SpecialProjectRevenue::where('project_id', $this->projectId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'اسم العميل',
            'المشروع',
            'رقم العقد',
            'رقم المستخلص',
            'المكتب',
            'نوع المستخلص',
            'رقم PO',
            'رقم الفاتورة',
            'إجمالي قيمة المستخلصات غير شامله الضريبه',
            'جهة المستخلص',
            'قيمة الضريبة',
            'الغرامات',
            'ضريبة الدفعة الأولى',
            'صافي قيمة المستخلص',
            'تاريخ إعداد المستخلص',
            'العام',
            'موقف المستخلص عند ...',
            'الرقم المرجعي',
            'تاريخ الصرف',
            'قيمة الصرف',
            'حالة الصرف',
            'الإجراءات',
        ];
    }

    /**
     * @var SpecialProjectRevenue $revenue
     */
    public function map($revenue): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $revenue->client_name ?? '',
            $revenue->project ?? '',
            $revenue->contract_number ?? '',
            $revenue->extract_number ?? '',
            $revenue->office ?? '',
            $revenue->extract_type ?? '',
            $revenue->po_number ?? '',
            $revenue->invoice_number ?? '',
            $revenue->total_value ?? 0,
            $revenue->extract_entity ?? '',
            $revenue->tax_value ?? 0,
            $revenue->penalties ?? 0,
            $revenue->advance_payment_tax ?? 0,
            $revenue->net_value ?? 0,
            $revenue->preparation_date ? $revenue->preparation_date->format('Y-m-d') : '',
            $revenue->year ?? '',
            $revenue->extract_status ?? '',
            $revenue->reference_number ?? '',
            $revenue->payment_date ? $revenue->payment_date->format('Y-m-d') : '',
            $revenue->payment_amount ?? 0,
            $revenue->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع',
            $revenue->procedures ?? '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Apply styles to header row
        $sheet->getStyle('A1:W1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '667eea'],
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

        // Apply borders to all cells
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:W' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
