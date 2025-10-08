<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EvacuationsExport implements FromArray, WithHeadings
{
    protected $licenses;
    protected $city;

    public function __construct($licenses, $city)
    {
        $this->licenses = $licenses;
        $this->city = $city;
    }

    public function array(): array
    {
        $data = [];
        $counter = 1;
        
        foreach ($this->licenses as $license) {
            // التحقق من بيانات الإخلاء في JSON
            $evacuationData = null;
            if (!empty($license->evacuation_data)) {
                $evacuationData = is_string($license->evacuation_data) ? json_decode($license->evacuation_data, true) : $license->evacuation_data;
            } elseif (!empty($license->additional_details)) {
                $additionalDetails = is_string($license->additional_details) ? json_decode($license->additional_details, true) : $license->additional_details;
                $evacuationData = $additionalDetails['evacuation_data'] ?? null;
            }
            
            $hasEvacuation = !empty($evacuationData) && is_array($evacuationData) && count($evacuationData) > 0;
            
            $data[] = [
                $counter++,
                $license->workOrder->order_number ?? '-',
                $license->workOrder->work_type ?? '-',
                $license->license_number ?? '-',
                $hasEvacuation ? 'نعم' : 'لا',
                $this->city,
            ];
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم أمر العمل',
            'نوع العمل',
            'رقم الرخصة',
            'يوجد إخلاء',
            'المدينة',
        ];
    }
}

