<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\ReferenceMaterial;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class WorkOrderMaterialsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    private $importedMaterials = [];
    private $columnMappings = [];
    private $rowCount = 0;
    private $processedCount = 0;

    public function __construct()
    {
        $this->setupColumnMappings();
    }

    /**
     * إعداد خريطة الأعمدة المدعومة - مبسطة لعمودين فقط
     */
    private function setupColumnMappings()
    {
        $this->columnMappings = [
            'code' => ['code', 'material_code', 'كود المادة', 'كود', 'الكود', 'رمز المادة'],
            'description' => ['description', 'material_description', 'وصف المادة', 'الوصف', 'البيان', 'التفاصيل']
        ];
    }

    /**
     * البحث عن قيمة من الصف باستخدام خريطة الأعمدة - مبسط
     */
    private function findValueFromRow(array $row, string $field): string
    {
        $possibleKeys = $this->columnMappings[$field] ?? [];
        
        // البحث المباشر أولاً
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && !empty(trim($row[$key]))) {
                return trim((string) $row[$key]);
            }
        }
        
        // البحث المعياري (بتطبيع الأسماء)
        foreach ($possibleKeys as $key) {
            $normalizedKey = $this->normalizeKey($key);
            foreach ($row as $rowKey => $value) {
                if ($this->normalizeKey($rowKey) === $normalizedKey && !empty(trim($value))) {
                    return trim((string) $value);
                }
            }
        }
        
        return '';
    }

    /**
     * تطبيع مفاتيح الأعمدة للمقارنة
     */
    private function normalizeKey(string $key): string
    {
        // إزالة المسافات والرموز والتحويل للأحرف الصغيرة
        $normalized = preg_replace('/[^\p{L}\p{N}]/u', '', $key);
        return mb_strtolower(trim($normalized));
    }

    /**
     * إنشاء نموذج من الصف - مبسط للغاية
     */
    public function model(array $row)
    {
        $this->rowCount++;
        
        try {
            // تسجيل الصف الأول للتحقق من البيانات
            if ($this->rowCount === 1) {
                Log::info('First row headers detected', ['headers' => array_keys($row)]);
            }
            
            // استخراج القيم من الصف - عمودين فقط
            try {
                $code = $this->findValueFromRow($row, 'code');
                $description = $this->findValueFromRow($row, 'description');
            } catch (\Exception $e) {
                Log::error('Error extracting values from row: ' . $e->getMessage(), [
                    'row_number' => $this->rowCount,
                    'row' => $row
                ]);
                return null;
            }

            // التحقق من وجود الكود والوصف (الحد الأدنى المطلوب)
            if (empty($code) || empty($description)) {
                Log::warning('Skipping row due to missing code or description', [
                    'row_number' => $this->rowCount,
                    'code' => $code,
                    'description' => $description,
                    'available_columns' => array_keys($row)
                ]);
                return null;
            }

            // تنظيف البيانات
            $code = trim(mb_strtolower($code));
            $description = trim($description);

            // إضافة المادة إلى قائمة المواد المستوردة - عمودين فقط
            $this->importedMaterials[] = [
                'code' => $code,
                'description' => $description,
            ];

            // Save the material to the reference_materials table
            \Log::info('Saving material', ['code' => $code, 'description' => $description]);
            \App\Models\ReferenceMaterial::updateOrCreate(
                ['code' => $code],
                ['description' => $description]
            );

            $this->processedCount++;
            return null;
        } catch (\Exception $e) {
            Log::error('Error importing material: ' . $e->getMessage(), [
                'row_number' => $this->rowCount,
                'row_data' => $row,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * قواعد التحقق
     */
    public function rules(): array
    {
        return [
            // سنتعامل مع التحقق يدوياً في model method
        ];
    }

    /**
     * الحصول على المواد المستوردة
     */
    public function getImportedMaterials(): array
    {
        return $this->importedMaterials;
    }

    /**
     * الحصول على أخطاء الاستيراد - مبسط
     */
    public function errors(): array
    {
        try {
            if (!method_exists($this, 'failures')) {
                return [];
            }
            
            $failures = $this->failures();
            if (empty($failures)) {
                return [];
            }
            
            return collect($failures)->map(function ($failure) {
                return [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values()
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting import failures: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * تحويل المواد المستوردة إلى تنسيق مناسب للاستخدام في النموذج - عمودين فقط
     */
    public function getFormattedMaterials(): array
    {
        return collect($this->importedMaterials)->map(function ($material, $index) {
            return [
                'material_code' => $material['code'],
                'material_description' => $material['description'],
                'planned_quantity' => 1, // قيمة افتراضية
                'unit' => 'قطعة', // قيمة افتراضية
                'unit_price' => 0, // قيمة افتراضية
                'notes' => '', // قيمة افتراضية
            ];
        })->toArray();
    }

    /**
     * الحصول على إحصائيات الاستيراد
     */
    public function getImportStatistics(): array
    {
        return [
            'total_rows_processed' => $this->rowCount,
            'materials_imported' => $this->processedCount,
            'materials_skipped' => $this->rowCount - $this->processedCount,
            'success_rate' => $this->rowCount > 0 ? round(($this->processedCount / $this->rowCount) * 100, 2) : 0
        ];
    }
} 