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
        // تنظيف البيانات - دعم أعمدة متعددة حسب ملف Excel المعروض
        $code = trim($row['item'] ?? $row['code'] ?? $row['كود'] ?? $row['البند'] ?? '');
        $description = trim($row['long_description'] ?? $row['description'] ?? $row['الوصف'] ?? $row['وصف'] ?? $row['الوصف الكامل'] ?? $row['الوصف_الكامل'] ?? '');
        $unit = trim($row['uom'] ?? $row['unit'] ?? $row['الوحدة'] ?? $row['وحدة'] ?? 'عدد');
        $unitPrice = $this->parsePrice($row['unit_price'] ?? $row['سعر الوحدة'] ?? $row['سعر_الوحدة'] ?? $row['السعر'] ?? 0);
        $notes = trim($row['notes'] ?? $row['ملاحظات'] ?? '');

        // تنظيف الكود - إزالة المسافات والأحرف الغريبة
        $code = preg_replace('/[^\w\-]/', '', $code);
        
        // إذا كان الكود فارغ، نستخدم رقم تسلسلي من الوصف
        if (empty($code) && !empty($description)) {
            // استخراج أي رقم من الوصف أو إنشاء كود فريد
            preg_match('/\d+/', $description, $matches);
            if (!empty($matches)) {
                $code = 'A' . $matches[0];
            } else {
                $code = 'WI' . rand(100000000, 999999999);
            }
        }

        // تنظيف الوصف من الأحرف غير المرغوبة
        $description = preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', $description);
        $description = trim(preg_replace('/\s+/', ' ', $description));
        
        // تنظيف الوحدة
        $unit = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $unit);
        if (empty($unit)) {
            $unit = 'عدد';
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
     * تنظيف وتحويل السعر إلى رقم
     */
    private function parsePrice($price)
    {
        if (is_numeric($price)) {
            return floatval($price);
        }
        
        // إزالة العملات والفواصل
        $price = str_replace(['$', '€', '£', '₪', 'ريال', 'درهم', ','], '', $price);
        $price = trim($price);
        
        return is_numeric($price) ? floatval($price) : 0;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            // أعمدة الكود/البند
            '*.item' => ['nullable', 'string', 'max:50'],
            '*.code' => ['nullable', 'string', 'max:50'],
            '*.كود' => ['nullable', 'string', 'max:50'],
            '*.البند' => ['nullable', 'string', 'max:50'],
            
            // أعمدة الوصف
            '*.long_description' => ['nullable', 'string', 'max:1000'],
            '*.description' => ['nullable', 'string', 'max:1000'],
            '*.الوصف' => ['nullable', 'string', 'max:1000'],
            '*.الوصف الكامل' => ['nullable', 'string', 'max:1000'],
            '*.الوصف_الكامل' => ['nullable', 'string', 'max:1000'],
            '*.وصف' => ['nullable', 'string', 'max:1000'],
            
            // أعمدة الوحدة
            '*.uom' => ['nullable', 'string', 'max:50'],
            '*.unit' => ['nullable', 'string', 'max:50'],
            '*.الوحدة' => ['nullable', 'string', 'max:50'],
            '*.وحدة' => ['nullable', 'string', 'max:50'],
            
            // أعمدة السعر
            '*.unit_price' => ['nullable'],
            '*.سعر الوحدة' => ['nullable'],
            '*.سعر_الوحدة' => ['nullable'],
            '*.السعر' => ['nullable'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            // رسائل الكود
            'item.max' => 'كود العنصر يجب ألا يتجاوز 50 حرف',
            'code.max' => 'كود العنصر يجب ألا يتجاوز 50 حرف',
            'كود.max' => 'كود العنصر يجب ألا يتجاوز 50 حرف',
            'البند.max' => 'كود العنصر يجب ألا يتجاوز 50 حرف',
            
            // رسائل الوصف
            'long_description.max' => 'وصف العنصر يجب ألا يتجاوز 1000 حرف',
            'description.max' => 'وصف العنصر يجب ألا يتجاوز 1000 حرف',
            'الوصف.max' => 'وصف العنصر يجب ألا يتجاوز 1000 حرف',
            'الوصف الكامل.max' => 'وصف العنصر يجب ألا يتجاوز 1000 حرف',
            'الوصف_الكامل.max' => 'وصف العنصر يجب ألا يتجاوز 1000 حرف',
            'وصف.max' => 'وصف العنصر يجب ألا يتجاوز 1000 حرف',
            
            // رسائل الوحدة
            'uom.max' => 'الوحدة يجب ألا تتجاوز 50 حرف',
            'unit.max' => 'الوحدة يجب ألا تتجاوز 50 حرف',
            'الوحدة.max' => 'الوحدة يجب ألا تتجاوز 50 حرف',
            'وحدة.max' => 'الوحدة يجب ألا تتجاوز 50 حرف',
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