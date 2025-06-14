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
            'الكمية المنفذة',
            'الفرق',
            'الكمية المصروفة',
            'الوحدة',
            'السطر',
            'DATE GATEPASS',
            'GATEPASS متوفر',
            'تاريخ الإنشاء'
        ];
    }

    public function map($material): array
    {
        $difference = ($material->planned_quantity ?? 0) - ($material->executed_quantity ?? 0);
        return [
            $material->work_order_number ?? '',
            $material->subscriber_name ?? '',
            $material->code,
            $material->description,
            $material->planned_quantity,
            $material->executed_quantity ?? 0,
            $difference,
            $material->spent_quantity,
            $material->unit,
            $material->line,
            $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '',
            $material->gate_pass_file ? 'نعم' : 'لا',
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
            'F' => 15, // الكمية المنفذة
            'G' => 12, // الفرق
            'H' => 15, // الكمية المصروفة
            'I' => 10, // الوحدة
            'J' => 10, // السطر
            'K' => 15, // تاريخ GATEPASS
            'L' => 15, // GATEPASS متوفر
            'M' => 20, // تاريخ الإنشاء
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
            'A2:M' . ($sheet->getHighestRow()) => [
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
                $event->sheet->getDelegate()->getStyle('A1:M1')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A2:M' . $event->sheet->getHighestRow())->getFont()->setSize(10);
                
                // تنسيق الأرقام
                $event->sheet->getDelegate()->getStyle('E2:H' . $event->sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0.00');
                
                // تنسيق التواريخ
                $event->sheet->getDelegate()->getStyle('K2:K' . $event->sheet->getHighestRow())->getNumberFormat()->setFormatCode('yyyy-mm-dd');
                $event->sheet->getDelegate()->getStyle('M2:M' . $event->sheet->getHighestRow())->getNumberFormat()->setFormatCode('yyyy-mm-dd hh:mm:ss');
            },
        ];
    }
} 
