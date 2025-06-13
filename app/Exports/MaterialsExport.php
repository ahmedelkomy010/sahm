<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MaterialsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $workOrderId;

    public function __construct($workOrderId = null)
    {
        $this->workOrderId = $workOrderId;
    }

    public function collection()
    {
        if ($this->workOrderId) {
            return Material::where('work_order_id', $this->workOrderId)
                          ->with('workOrder')
                          ->orderBy('created_at', 'desc')
                          ->get();
        }
        
        return Material::with('workOrder')
                      ->orderBy('created_at', 'desc')
                      ->get();
    }

    public function headings(): array
    {
        return [
            'رقم أمر العمل',
            'اسم المشترك',
            'كود المادة',
            'وصف المادة',
            'الكمية المخططة',
            'الكمية المستهلكة',
            'الوحدة',
            'الخط',
            'تاريخ تصريح المرور',
            'تاريخ الإنشاء'
        ];
    }

    public function map($material): array
    {
        return [
            $material->work_order_number ?? '',
            $material->subscriber_name ?? '',
            $material->code,
            $material->description,
            $material->planned_quantity,
            $material->spent_quantity,
            $material->unit,
            $material->line,
            $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '',
            $material->created_at->format('Y-m-d H:i:s')
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // رقم أمر العمل
            'B' => 40, // اسم المشترك
            'C' => 15, // كود المادة
            'D' => 40, // وصف المادة
            'E' => 15, // الكمية المخططة
            'F' => 15, // الكمية المستهلكة
            'G' => 10, // الوحدة
            'H' => 10, // الخط
            'I' => 15, // تاريخ تصريح المرور
            'J' => 20, // تاريخ الإنشاء
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // تنسيق العنوان
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2196F3'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // تنسيق البيانات
            'A2:J' . ($sheet->getHighestRow()) => [
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A2:J' . $event->sheet->getHighestRow())->getFont()->setSize(10);
                
                // تنسيق الأرقام
                $event->sheet->getDelegate()->getStyle('E2:F' . $event->sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0.00');
                
                // تنسيق التواريخ
                $event->sheet->getDelegate()->getStyle('I2:I' . $event->sheet->getHighestRow())->getNumberFormat()->setFormatCode('yyyy-mm-dd');
                $event->sheet->getDelegate()->getStyle('J2:J' . $event->sheet->getHighestRow())->getNumberFormat()->setFormatCode('yyyy-mm-dd hh:mm:ss');
            },
        ];
    }
} 
