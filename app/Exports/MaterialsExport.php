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
    protected $workOrderNumber;

    public function __construct($workOrderNumber = null)
    {
        $this->workOrderNumber = $workOrderNumber;
    }

    public function collection()
    {
        $query = Material::with('workOrder');
        
        if ($this->workOrderNumber) {
            $query->where('work_order_number', $this->workOrderNumber);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'كود المادة',
            'وصف المادة',
            'أمر العمل',
            'السطر',
            'الكمية المخططة',
            'الكمية الفعلية',
            'الكمية المصروفة',
            'الكمية المنفذة بالموقع',
            'الفرق',
            'الوحدة',
            'تاريخ تصريح البوابة',
            'ملف دخول المواد',
            'ملف تصريح البوابة',
            'ملف إدخال المخزن',
            'ملف إخراج المخزن',
            'تاريخ الإنشاء',
        ];
    }

    public function map($material): array
    {
        return [
            $material->code,
            $material->description,
            $material->work_order_number ?? ($material->workOrder ? $material->workOrder->order_number : '-'),
            $material->line ?? '-',
            $material->planned_quantity ?? 0,
            $material->actual_quantity ?? 0,
            $material->spent_quantity ?? 0,
            $material->executed_site_quantity ?? 0,
            $material->difference ?? 0,
            $material->unit ?? '-',
            $material->date_gatepass ? $material->date_gatepass->format('Y-m-d') : '-',
            $material->check_in_file ? 'نعم' : 'لا',
            $material->gate_pass_file ? 'نعم' : 'لا',
            $material->store_in_file ? 'نعم' : 'لا',
            $material->store_out_file ? 'نعم' : 'لا',
            $material->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // كود المادة
            'B' => 30, // وصف المادة
            'C' => 15, // أمر العمل
            'D' => 10, // السطر
            'E' => 12, // الكمية المخططة
            'F' => 12, // الكمية الفعلية
            'G' => 12, // الكمية المصروفة
            'H' => 15, // الكمية المنفذة بالموقع
            'I' => 10, // الفرق
            'J' => 10, // الوحدة
            'K' => 15, // تاريخ تصريح البوابة
            'L' => 15, // ملف دخول المواد
            'M' => 15, // ملف تصريح البوابة
            'N' => 15, // ملف إدخال المخزن
            'O' => 15, // ملف إخراج المخزن
            'P' => 20  // تاريخ الإنشاء
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // تطبيق الحدود على جميع الخلايا المستخدمة
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // تطبيق تنسيق خاص للبيانات (غير الرأس)
                if ($highestRow > 1) {
                    $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8F9FA']
                        ]
                    ]);
                }

                // تطبيق تنسيق متناوب للصفوف
                for ($i = 2; $i <= $highestRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle('A' . $i . ':' . $highestColumn . $i)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'E3F2FD']
                            ]
                        ]);
                    }
                }
            }
        ];
    }
} 