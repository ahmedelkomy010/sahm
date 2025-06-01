<?php

namespace App\Imports;

use App\Models\WorkItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Illuminate\Validation\Rule;

class WorkItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $workOrderId;
    protected $importedItems = [];

    public function __construct($workOrderId)
    {
        $this->workOrderId = $workOrderId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // تنظيف البيانات - دعم أعمدة متعددة
        $code = trim($row['code'] ?? $row['كود'] ?? $row['البند'] ?? '');
        $description = trim($row['description'] ?? $row['الوصف'] ?? $row['وصف'] ?? $row['الوصف الكامل'] ?? $row['الوصف_الكامل'] ?? '');
        $unit = trim($row['unit'] ?? $row['الوحدة'] ?? $row['وحدة'] ?? 'عدد');
        $unitPrice = floatval($row['unit_price'] ?? $row['سعر الوحدة'] ?? $row['سعر_الوحدة'] ?? $row['السعر'] ?? 0);
        $notes = trim($row['notes'] ?? $row['ملاحظات'] ?? '');

        // إذا كان الكود فارغ، نستخدم أول كلمة من الوصف أو نصنع كود عشوائي
        if (empty($code) && !empty($description)) {
            $words = explode(' ', $description);
            $code = 'WI' . rand(1000, 9999) . '_' . substr($words[0], 0, 5);
        }

        if (empty($code) || empty($description)) {
            return null;
        }

        // البحث عن العنصر الموجود أو إنشاء جديد
        $workItem = WorkItem::firstOrCreate(
            ['code' => $code],
            [
                'description' => $description,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'notes' => $notes
            ]
        );

        // إضافة العنصر إلى قائمة المستوردة
        $this->importedItems[] = $workItem;

        return $workItem;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.code' => ['nullable', 'string', 'max:50'],
            '*.كود' => ['nullable', 'string', 'max:50'],
            '*.البند' => ['nullable', 'string', 'max:50'],
            '*.description' => ['required', 'string', 'max:500'],
            '*.الوصف' => ['required', 'string', 'max:500'],
            '*.الوصف الكامل' => ['required', 'string', 'max:500'],
            '*.الوصف_الكامل' => ['required', 'string', 'max:500'],
            '*.وصف' => ['required', 'string', 'max:500'],
            '*.unit' => ['nullable', 'string', 'max:50'],
            '*.الوحدة' => ['nullable', 'string', 'max:50'],
            '*.وحدة' => ['nullable', 'string', 'max:50'],
            '*.unit_price' => ['nullable', 'numeric', 'min:0'],
            '*.سعر الوحدة' => ['nullable', 'numeric', 'min:0'],
            '*.سعر_الوحدة' => ['nullable', 'numeric', 'min:0'],
            '*.السعر' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'description.required' => 'وصف العنصر مطلوب',
            'الوصف.required' => 'وصف العنصر مطلوب',
            'الوصف الكامل.required' => 'وصف العنصر مطلوب',
            'الوصف_الكامل.required' => 'وصف العنصر مطلوب',
            'وصف.required' => 'وصف العنصر مطلوب',
            'unit_price.numeric' => 'سعر الوحدة يجب أن يكون رقماً',
            'سعر الوحدة.numeric' => 'سعر الوحدة يجب أن يكون رقماً',
            'سعر_الوحدة.numeric' => 'سعر الوحدة يجب أن يكون رقماً',
            'السعر.numeric' => 'سعر الوحدة يجب أن يكون رقماً',
        ];
    }

    /**
     * Get imported items
     */
    public function getImportedItems()
    {
        return $this->importedItems;
    }
} 