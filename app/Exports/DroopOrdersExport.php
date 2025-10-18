<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DroopOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'نوع أمر العمل',
            'المدينة',
            'تاريخ الإنشاء',
            'حالة التنفيذ',
            'المكتب',
        ];
    }

    public function map($order): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $order->order_number ?? '-',
            $order->work_type ?? '-',
            $order->city ?? '-',
            $order->created_at ? $order->created_at->format('Y-m-d') : '-',
            $this->getExecutionStatus($order->execution_status),
            $order->office ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    private function getExecutionStatus($status)
    {
        $statuses = [
            0 => 'أمر مستلم',
            1 => 'جاري العمل بالموقع',
            2 => 'تم التنفيذ بالموقع',
            3 => 'تسليم 155',
            4 => 'إعداد مستخلص أولى',
            5 => 'تم صرف أولى',
            6 => 'إعداد مستخلص ثانية',
            7 => 'إعداد مستخلص كلي',
            8 => 'تم إصدار شهادة الإنجاز',
            9 => 'منتهي صرف',
        ];

        return $statuses[$status] ?? '-';
    }
}

