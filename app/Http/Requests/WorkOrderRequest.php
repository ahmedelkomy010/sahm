<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'order_number' => 'required|string',
            'order_date' => 'required|date',
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0',
            'materials.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'materials.required' => 'يجب إدخال المواد',
            'materials.*.material_id.required' => 'يجب اختيار المادة',
            'materials.*.quantity.required' => 'يجب إدخال الكمية',
            'materials.*.quantity.numeric' => 'يجب أن تكون الكمية رقم',
            'materials.*.quantity.min' => 'يجب أن تكون الكمية أكبر من صفر',
            'materials.*.unit_price.required' => 'يجب إدخال سعر الوحدة',
            'materials.*.unit_price.numeric' => 'يجب أن يكون سعر الوحدة رقم',
            'materials.*.unit_price.min' => 'يجب أن يكون سعر الوحدة أكبر من صفر',
        ];
    }
}