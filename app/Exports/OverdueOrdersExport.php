<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class OverdueOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $workOrders;

    public function __construct($workOrders)
    {
        $this->workOrders = $workOrders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->workOrders;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'نوع العمل',
            'تاريخ الاعتماد',
            'الأيام المتأخرة',
            'حالة التنفيذ',
        ];
    }

    /**
     * @param $workOrder
     * @return array
     */
    public function map($workOrder): array
    {
        static $index = 0;
        $index++;
        
        $daysOverdue = $workOrder->approval_date ? (int) now()->diffInDays($workOrder->approval_date) : 0;
        
        $statusLabels = [
            1 => 'جاري العمل بالموقع',
            2 => 'تم التنفيذ بالموقع',
            3 => 'تم تسليم 155',
            4 => 'إعداد مستخلص أول',
            5 => 'تم صرف مستخلص أول',
            6 => 'إعداد مستخلص ثاني',
            8 => 'تم إصدار شهادة الإنجاز',
            10 => 'إعداد مستخلص كلي'
        ];
        
        return [
            $index,
            $workOrder->order_number ?? 'غير محدد',
            $workOrder->work_type ?? 'غير محدد',
            $workOrder->approval_date ? $workOrder->approval_date->format('Y-m-d') : '-',
            $daysOverdue,
            $statusLabels[$workOrder->execution_status] ?? 'غير محدد',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

