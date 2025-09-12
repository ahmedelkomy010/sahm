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

class WorkOrdersReceiptsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'نوع العمل',
            'وصف العمل',
            'اسم المشترك',
            'الحي',
            'المكتب',
            'القيمة المبدئية (بدون استشاري)',
            'تاريخ الاستلام',
            'تاريخ الإنشاء',
        ];
    }

    public function map($workOrder): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $workOrder->order_number,
            $workOrder->work_type,
            $this->getWorkTypeDescription($workOrder->work_type),
            $workOrder->subscriber_name,
            $workOrder->district ?? '-',
            $workOrder->office ?? '-',
            $workOrder->order_value_without_consultant ? number_format($workOrder->order_value_without_consultant, 2) . ' ريال' : '0.00 ريال',
            $workOrder->received_at ? $workOrder->received_at->format('Y-m-d') : '-',
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
                        'rgb' => '28a745',
                    ],
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
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
            'A:J' => [
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

    private function getWorkTypeDescription($workType)
    {
        $descriptions = [
            '409' => 'ازالة-نقل شبكة على المشترك',
            '408' => 'ازاله عداد على المشترك',
            '460' => 'استبدال شبكات',
            '901' => 'اضافة عداد طاقة شمسية',
            '440' => 'الرفع المساحي',
            '410' => 'انشاء محطة/محول لمشترك/مشتركين',
            '801' => 'تركيب عداد ايصال سريع',
            '804' => 'تركيب محطة ش ارضية VM ايصال سريع',
            '805' => 'تركيب محطة ش هوائية VM ايصال سريع',
            '480' => '(تسليم مفتاح) تمويل خارجي',
            '441' => 'تعزيز شبكة أرضية ومحطات',
            '442' => 'تعزيز شبكة هوائية ومحطات',
            '802' => 'شبكة ارضية VL ايصال سريع',
            '803' => 'شبكة هوائية VL ايصال سريع',
            '402' => 'توصيل عداد بحفرية شبكة ارضيه',
            '401' => '(عداد بدون حفرية) أرضي/هوائي',
            '404' => 'عداد بمحطة شبكة ارضية VM',
            '405' => 'توصيل عداد بمحطة شبكة هوائية VM',
            '430' => 'مخططات منح وزارة البلدية',
            '450' => 'مشاريع ربط محطات التحويل',
            '403' => 'توصيل عداد شبكة هوائية VL',
            '806' => 'ايصال وزارة الاسكان جهد منخفض',
            '444' => 'تحويل الشبكه من هوائي الي ارضي',
            '111' => 'Mv- طوارئ ضغط متوسط',
            '222' => 'Lv - طوارئ ض منخفض',
            '333' => 'Oh - طوارئ هوائي',
        ];
        
        return $descriptions[$workType] ?? 'نوع عمل غير محدد';
    }
}
