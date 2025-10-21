<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyWorkProgramsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $programs;
    protected $project;

    public function __construct($programs, $project)
    {
        $this->programs = $programs;
        $this->project = $project;
    }

    public function collection()
    {
        return $this->programs;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'نوع العمل',
            'الموقع',
            'إحداثيات جوجل',
            'اسم الاستشاري',
            'مهندس الموقع',
            'المراقب',
            'المصدر',
            'المستلم',
            'مسئول السلامة',
            'مراقب الجودة',
            'وقت البداية',
            'وقت النهاية',
            'وصف العمل',
            'ملاحظات',
        ];
    }

    public function map($program): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $program->workOrder->order_number ?? '-',
            $program->work_type ?? '-',
            $program->location ?? '-',
            $program->google_coordinates ?? '-',
            $program->consultant_name ?? '-',
            $program->site_engineer ?? '-',
            $program->supervisor ?? '-',
            $program->issuer ?? '-',
            $program->receiver ?? '-',
            $program->safety_officer ?? '-',
            $program->quality_monitor ?? '-',
            $program->start_time ?? '-',
            $program->end_time ?? '-',
            $program->work_description ?? '-',
            $program->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
