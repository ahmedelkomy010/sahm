<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class WorkOrdersExecutionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $workOrders;

    public function __construct($workOrders)
    {
        $this->workOrders = $workOrders;
    }

    public function collection()
    {
        return $this->workOrders;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'اسم المشترك',
            'المكتب',
            'القيمة المبدئية',
            'السعر الإجمالي المنفذ',
            'نسبة الإنجاز',
            'حالة التنفيذ',
            'تاريخ الإنشاء',
        ];
    }

    public function map($workOrder): array
    {
        static $counter = 0;
        $counter++;

        $executionPercentage = 0;
        if ($workOrder->order_value_without_consultant > 0) {
            $executionPercentage = round(($workOrder->executed_value / $workOrder->order_value_without_consultant) * 100);
        }

        return [
            $counter,
            $workOrder->order_number,
            $workOrder->subscriber_name,
            $workOrder->office ?? '-',
            $workOrder->order_value_without_consultant ? number_format($workOrder->order_value_without_consultant, 2) . ' ريال' : '0.00 ريال',
            $workOrder->executed_value ? number_format($workOrder->executed_value, 2) . ' ريال' : '0.00 ريال',
            $executionPercentage . '%',
            $this->getExecutionStatusText($workOrder->execution_status),
            $workOrder->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'ffc107',
                    ],
                ],
                'font' => [
                    'color' => ['rgb' => '000000'],
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
            ],
            // Style all cells
            'A:I' => [
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
            ],
        ];
    }

    private function getExecutionStatusText($status)
    {
        $statuses = [
            1 => 'جاري العمل',
            2 => 'تم تسليم 155 ولم تصدر شهادة انجاز',
            3 => 'صدرت شهادة ولم تعتمد',
            4 => 'تم اعتماد شهادة الانجاز',
            5 => 'مؤكد ولم تدخل مستخلص',
            6 => 'دخلت مستخلص ولم تصرف',
            7 => 'منتهي تم الصرف',
        ];
        
        return $statuses[$status] ?? 'حالة غير محددة';
    }
}
