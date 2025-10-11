<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UnexecutedOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'حالة التنفيذ من السجل',
            'يوجد رخصة',
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
        
        // التحقق من وجود سجلات تنفيذ
        $hasExecution = $workOrder->dailyExecutionNotes()->exists();
        $executionStatus = $hasExecution ? 'تم التنفيذ' : 'لم يتم التنفيذ';
        
        // التحقق من وجود رخصة
        $hasLicense = $workOrder->licenses()->exists();
        $licenseStatus = $hasLicense ? 'يوجد' : 'لا يوجد';
        
        return [
            $index,
            $workOrder->order_number ?? 'غير محدد',
            $workOrder->work_type ?? 'غير محدد',
            $executionStatus,
            $licenseStatus,
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

