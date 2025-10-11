<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalExtractOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'المكتب',
            'الموقع',
            'المقاول',
            'القيمة المبدئية',
            'الحالة',
            'تاريخ الإنشاء',
        ];
    }

    public function map($order): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $order->work_order_number ?? '-',
            $order->office ?? '-',
            $order->location ?? '-',
            $order->contractor_name ?? '-',
            number_format($order->order_value_without_consultant ?? 0, 2),
            'تم إعداد المستخلص الكلي وجاري الصرف',
            $order->created_at ? $order->created_at->format('Y-m-d') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

