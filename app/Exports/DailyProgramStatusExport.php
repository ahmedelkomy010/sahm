<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DailyProgramStatusExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $programs;

    public function __construct($programs)
    {
        $this->programs = $programs;
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
            'المسح',
            'المواد',
            'الجودة',
            'السلامة',
            'التنفيذ',
            'نسبة الالتزام',
        ];
    }

    public function map($program): array
    {
        static $index = 0;
        $index++;

        $workOrder = $program->workOrder;

        // التحقق من وجود بيانات المسح
        $hasSurvey = $workOrder->surveys()->exists();
        
        // التحقق من وجود المواد
        $hasMaterials = $workOrder->materials()->exists() || $workOrder->workOrderMaterials()->exists();
        
        // التحقق من وجود بيانات الجودة (شهادة تنسيق)
        $hasQuality = $workOrder->licenses()
            ->where(function($query) {
                $query->whereNotNull('coordination_certificate_path')
                      ->orWhereNotNull('coordination_certificate_number');
            })
            ->exists();
        
        // التحقق من وجود بيانات السلامة
        $hasSafety = !empty($workOrder->safety_permits_images) || 
                    !empty($workOrder->safety_permits_files) ||
                    !empty($workOrder->safety_team_images) ||
                    !empty($workOrder->safety_equipment_images) ||
                    !empty($workOrder->safety_general_images) ||
                    !empty($workOrder->safety_tbt_images) ||
                    $workOrder->safetyViolations()->exists() ||
                    $workOrder->safetyHistory()->exists();

        return [
            $index,
            $workOrder->order_number ?? '-',
            $program->work_type ?? $workOrder->work_type ?? '-',
            $hasSurvey ? 'نعم' : 'لا',
            $hasMaterials ? 'نعم' : 'لا',
            $hasQuality ? 'نعم' : 'لا',
            $hasSafety ? 'نعم' : 'لا',
            $program->execution_completed ? 'مكتمل' : 'غير مكتمل',
            $program->execution_completed ? '100%' : '0%',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '28a745']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 30,
            'D' => 12,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 15,
            'I' => 15,
        ];
    }
}

