<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ObstaclesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'يوجد معوقات',
            'تاريخ المسح',
            'ملاحظات المعوقات',
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
        
        $survey = $workOrder->survey;
        $hasObstacles = $survey && $survey->has_obstacles;
        
        return [
            $index,
            $workOrder->order_number ?? 'غير محدد',
            $workOrder->work_type ?? 'غير محدد',
            $hasObstacles ? 'يوجد معوقات' : 'لا يوجد معوقات',
            $survey && $survey->survey_date ? $survey->survey_date->format('Y-m-d') : '-',
            $hasObstacles && $survey->obstacles_notes ? $survey->obstacles_notes : '-',
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

