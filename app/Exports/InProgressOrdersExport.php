<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InProgressOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'المكتب',
            'القيمة المبدئية',
            'الحالة',
            'موقف أمر العمل',
            'تاريخ الاعتماد',
        ];
    }

    public function map($order): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $order->work_order_number ?? '-',
            $order->work_type ?? '-',
            $order->office ?? '-',
            number_format($order->order_value_without_consultant ?? 0, 2),
            'جاري العمل بالموقع',
            $order->notes ?? '-',
            $order->approval_date ? \Carbon\Carbon::parse($order->approval_date)->format('Y-m-d') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

