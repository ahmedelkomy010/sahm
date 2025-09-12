<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkOrdersCompletedExport implements FromCollection, WithHeadings, WithMapping
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
            'القيمة المبدئية',
            'القيمة الكلية النهائية',
            'تاريخ الصرف',
            'حالة التنفيذ'
        ];
    }

    public function map($workOrder): array
    {
        return [
            $workOrder->id,
            $workOrder->order_number,
            $workOrder->office ?? 'غير محدد',
            $workOrder->order_value_without_consultant ?? 0,
            $workOrder->final_value ?? 0,
            $workOrder->payment_date ? $workOrder->payment_date->format('m/d/Y') : '-',
            'منتهي تم الصرف'
        ];
    }
}