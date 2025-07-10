<?php

namespace App\Imports;

use App\Models\WorkItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class WorkItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    private $workOrderId;
    private $columnMappings = [];
    private $importedItems = [];

    public function __construct($workOrderId)
    {
        $this->workOrderId = $workOrderId;
        $this->setupColumnMappings();
    }

    /**
     * إعداد خريطة الأعمدة المدعومة
     */
    private function setupColumnMappings()
    {
        $this->columnMappings = [
            'code' => ['item', 'code', 'كود', 'البند', 'رقم البند', 'item_code', 'item_no'],
            'description' => ['description', 'long_description', 'الوصف', 'وصف البند', 'التفاصيل', 'البيان'],
            'unit' => ['uom', 'unit', 'الوحدة', 'وحدة القياس', 'units'],
            'unit_price' => ['unit_price', 'price', 'السعر', 'سعر الوحدة', 'التكلفة', 'cost']
        ];
    }

    /**
     * البحث عن قيمة من الصف باستخدام خريطة الأعمدة
     */
    private function findValueFromRow(array $row, string $field): string
    {
        $possibleKeys = $this->columnMappings[$field] ?? [];
        
        foreach ($possibleKeys as $key) {
            // البحث بالمفتاح الدقيق
            if (isset($row[$key]) && !empty(trim((string)$row[$key]))) {
                return trim((string)$row[$key]);
            }
            
            // البحث بالمفتاح بأحرف صغيرة
            $lowerKey = strtolower($key);
            if (isset($row[$lowerKey]) && !empty(trim((string)$row[$lowerKey]))) {
                return trim((string)$row[$lowerKey]);
            }
            
            // البحث في مفاتيح الصف
            foreach (array_keys($row) as $rowKey) {
                if (strtolower((string)$rowKey) === $lowerKey && !empty(trim((string)$row[$rowKey]))) {
                    return trim((string)$row[$rowKey]);
                }
            }
        }

        return '';
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // تنظيف البيانات باستخدام الخريطة المحسنة
        $code = $this->findValueFromRow($row, 'code');
        $description = $this->findValueFromRow($row, 'description');
        $unit = $this->findValueFromRow($row, 'unit') ?: 'عدد';
        $unitPrice = $this->parsePrice($this->findValueFromRow($row, 'unit_price'));

        // تنظيف الكود - إزالة المسافات والأحرف الغريبة
        $code = $this->cleanCode($code);
        
        // إذا كان الكود فارغ، نحاول استخراجه من الوصف أو إنشاء كود فريد
        if (empty($code) && !empty($description)) {
            $code = $this->generateCodeFromDescription($description);
        }

        // تنظيف الوصف والتأكد من الـ encoding
        $description = $this->ensureUtf8($this->cleanDescription($description));
        
        // تنظيف الوحدة
        $unit = $this->ensureUtf8($this->cleanUnit($unit));

        // التحقق من وجود البيانات الأساسية
        if (empty($code) || empty($description)) {
            return null;
        }

        // البحث عن العنصر الموجود أو إنشاء جديد
        $workItem = WorkItem::firstOrCreate(
            ['code' => $code],
            [
                'name' => $description, // اسم البند
                'description' => $description,
                'unit' => $unit,
                'unit_price' => $unitPrice
            ]
        );

        // إضافة العنصر إلى قائمة المستوردة
        $this->importedItems[] = $workItem;

        return $workItem;
    }

    /**
     * التأكد من أن النص بترميز UTF-8 صحيح
     */
    private function ensureUtf8($text): string
    {
        if (!is_string($text)) {
            return '';
        }

        // تحويل الترميز إلى UTF-8 إذا كان مختلفًا
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text, ['UTF-8', 'Windows-1256', 'ISO-8859-6', 'ASCII'], true));
        }

        // تنظيف أي أحرف غير صالحة
        $text = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]|[\x00-\x7F][\x80-\xBF]+|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
                           '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
                           '', $text);

        return trim($text);
    }

    /**
     * تنظيف الكود
     */
    private function cleanCode(string $code): string
    {
        $code = trim($code);
        // إزالة الأحرف غير المرغوبة والاحتفاظ بالأرقام والحروف والشرطات
        $code = preg_replace('/[^\w\-]/', '', $code);
        return $code;
    }

    /**
     * توليد كود من الوصف
     */
    private function generateCodeFromDescription(string $description): string
    {
        // استخراج أي رقم من الوصف
        preg_match('/\d+/', $description, $matches);
        if (!empty($matches)) {
            return 'A' . $matches[0];
        }
        
        // استخراج أول كلمة من الوصف
        $words = explode(' ', trim($description));
        if (!empty($words[0])) {
            $firstWord = preg_replace('/[^\w]/', '', $words[0]);
            if (strlen($firstWord) > 0) {
                return strtoupper(substr($firstWord, 0, 3)) . rand(100, 999);
            }
        }
        
        // إنشاء كود عشوائي
        return 'WI' . rand(100000, 999999);
    }

    /**
     * تنظيف الوصف
     */
    private function cleanDescription(string $description): string
    {
        $description = trim($description);
        // إزالة الأحرف غير المطبوعة
        $description = preg_replace('/[\x00-\x1F\x7F]/', ' ', $description);
        // توحيد المسافات
        $description = preg_replace('/\s+/', ' ', $description);
        // تنظيف علامات الترقيم المكررة
        $description = preg_replace('/[,،]{2,}/', '، ', $description);
        
        return trim($description);
    }

    /**
     * تنظيف الوحدة
     */
    private function cleanUnit(string $unit): string
    {
        $unit = trim($unit);
        // إزالة الأحرف غير المطبوعة
        $unit = preg_replace('/[\x00-\x1F\x7F]/', '', $unit);
        
        // خريطة الوحدات الشائعة
        $unitMappings = [
            'each' => 'عدد',
            'ech' => 'عدد',
            'pcs' => 'قطعة',
            'piece' => 'قطعة',
            'meter' => 'متر',
            'm' => 'متر',
            'km' => 'كيلومتر',
            'l.m' => 'متر طولي',
            'lm' => 'متر طولي',
            'kit' => 'طقم'
        ];
        
        $lowerUnit = strtolower($unit);
        return $unitMappings[$lowerUnit] ?? ($unit ?: 'عدد');
    }

    /**
     * إنشاء ملاحظات من بيانات الصف
     */
    private function generateNotesFromRow(array $row): string
    {
        $notes = [];
        
        // إضافة أي معلومات إضافية من الصف
        $additionalFields = ['notes', 'ملاحظات', 'تعليقات', 'remarks'];
        foreach ($additionalFields as $field) {
            if (isset($row[$field]) && !empty(trim((string)$row[$field]))) {
                $notes[] = trim((string)$row[$field]);
            }
        }
        
        return implode('; ', $notes);
    }
    
    /**
     * تنظيف وتحويل السعر إلى رقم
     */
    private function parsePrice($price): float
    {
        if (is_numeric($price)) {
            return floatval($price);
        }
        
        if (empty($price)) {
            return 0.0;
        }
        
        // إزالة العملات والفواصل والنصوص
        $price = preg_replace('/[^\d.,\-]/', '', (string)$price);
        $price = str_replace(',', '.', $price);
        $price = trim($price);
        
        return is_numeric($price) ? floatval($price) : 0.0;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            // قواعد مرنة للأعمدة المختلفة
            '*' => ['nullable']
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'max' => 'القيمة تتجاوز الحد المسموح',
            'numeric' => 'يجب أن تكون القيمة رقماً'
        ];
    }

    /**
     * Get imported items
     */
    public function getImportedItems(): array
    {
        return $this->importedItems;
    }

    /**
     * Get column mappings for debugging
     */
    public function getColumnMappings(): array
    {
        return $this->columnMappings;
    }
} 