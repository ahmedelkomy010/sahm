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

    public function __construct()
    {
        $this->setupColumnMappings();
    }

    /**
     * إعداد خريطة الأعمدة المدعومة
     */
    private function setupColumnMappings()
    {
        $this->columnMappings = [
            'code' => ['code', 'material_code', 'كود المادة', 'كود', 'الكود', 'رمز المادة', 'item_code'],
            'description' => ['description', 'material_description', 'وصف المادة', 'الوصف', 'البيان', 'التفاصيل', 'material_name', 'name'],
            'quantity' => ['quantity', 'planned_quantity', 'الكمية', 'الكمية المخططة', 'qty', 'amount'],
            'unit' => ['unit', 'uom', 'الوحدة', 'وحدة القياس', 'units', 'measure_unit']
        ];
    }

    /**
     * البحث عن قيمة من الصف باستخدام خريطة الأعمدة
     */
    private function findValueFromRow(array $row, string $field): string
    {
        $possibleKeys = $this->columnMappings[$field] ?? [];
        
        foreach ($possibleKeys as $key) {
            $normalizedKey = $this->normalizeKey($key);
            foreach ($row as $rowKey => $value) {
                if ($this->normalizeKey($rowKey) === $normalizedKey && !empty($value)) {
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
     * إنشاء نموذج من الصف
     */
    public function model(array $row)
    {
        try {
            $code = $this->findValueFromRow($row, 'code');
            $description = $this->findValueFromRow($row, 'description');
            $quantity = $this->findValueFromRow($row, 'quantity') ?: '1';
            $unit = $this->findValueFromRow($row, 'unit') ?: 'قطعة';

            // التحقق من وجود الكود والوصف
            if (empty($code) || empty($description)) {
                return null;
            }

            // البحث عن المادة في جدول المراجع أو إنشاؤها
            try {
                $referenceMaterial = ReferenceMaterial::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => $description,
                        'description' => $description,
                        'unit' => $unit,
                        'unit_price' => 0,
                        'is_active' => true,
                    ]
                );
            } catch (\Exception $e) {
                // إذا فشل إنشاء المادة المرجعية، نستمر بدونها
                Log::warning('Failed to create reference material: ' . $e->getMessage(), ['code' => $code]);
            }

            // نحاول العثور على المادة أولاً، ثم ننشئها إذا لم توجد
            $material = Material::where('code', $code)->first();
            if (!$material) {
                try {
                    $material = Material::create([
                        'name' => $description,
                        'code' => $code,
                        'description' => $description,
                        'unit' => $unit,
                        'unit_price' => 0,
                        'is_active' => true,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create material: ' . $e->getMessage(), ['code' => $code]);
                    return null;
                }
            }

            // إضافة المادة إلى قائمة المواد المستوردة
            $this->importedMaterials[] = [
                'id' => $material->id,
                'code' => $code,
                'description' => $description,
                'quantity' => (float) $quantity,
                'unit' => $unit,
                'unit_price' => 0,
                'notes' => '',
            ];

            return null; // لا نحتاج لإنشاء نموذج جديد هنا
        } catch (\Exception $e) {
            Log::error('Error importing material: ' . $e->getMessage(), [
                'row' => $row,
                'code' => $code ?? 'N/A',
                'description' => $description ?? 'N/A',
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
     * الحصول على أخطاء الاستيراد
     */
    public function errors(): array
    {
        try {
            return collect($this->failures())->map(function ($failure) {
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
     * تحويل المواد المستوردة إلى تنسيق مناسب للاستخدام في النموذج
     */
    public function getFormattedMaterials(): array
    {
        return collect($this->importedMaterials)->map(function ($material, $index) {
            return [
                'material_code' => $material['code'],
                'material_description' => $material['description'],
                'planned_quantity' => $material['quantity'],
                'unit' => $material['unit'],
                'unit_price' => $material['unit_price'],
                'notes' => $material['notes'],
            ];
        })->toArray();
    }
} 