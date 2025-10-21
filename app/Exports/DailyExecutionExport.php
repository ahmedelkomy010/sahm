<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyExecutionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $executions;
    protected $project;
    protected $date;

    public function __construct($executions, $project, $date)
    {
        $this->executions = $executions;
        $this->project = $project;
        $this->date = $date;
    }

    public function collection()
    {
        return $this->executions;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'وصف البند',
            'الوحدة',
            'الكمية المنفذة',
            'سعر الوحدة (ر.س)',
            'القيمة الإجمالية (ر.س)',
            'المنفذ بواسطة',
            'تاريخ الإدخال',
        ];
    }

    public function map($execution): array
    {
        static $index = 0;
        $index++;

        $workItemDescription = '-';
        $unit = '-';
        $unitPrice = 0;
        
        if ($execution->workOrderItem && $execution->workOrderItem->workItem) {
            $workItemDescription = $execution->workOrderItem->workItem->description;
            $unit = $execution->workOrderItem->workItem->unit;
            $unitPrice = $execution->workOrderItem->unit_price ?? 0;
        }

        $totalValue = $execution->executed_quantity * $unitPrice;
        $executedBy = $execution->createdBy ? $execution->createdBy->name : '-';
        $createdAt = $execution->created_at ? $execution->created_at->format('Y-m-d H:i') : '-';

        return [
            $index,
            $execution->workOrder->order_number ?? '-',
            $workItemDescription,
            $unit,
            number_format($execution->executed_quantity, 2),
            number_format($unitPrice, 2),
            number_format($totalValue, 2),
            $executedBy,
            $createdAt,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
