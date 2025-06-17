<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReferenceMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReferenceMaterialSeeder extends Seeder
{
    /**
     * تصنيفات المواد الرئيسية
     */
    private const CATEGORIES = [
        'CABLE' => 'Cables & Power',
        'CNDCTR' => 'Conductors',
        'TFMR' => 'Transformers',
        'FUSE' => 'Fuses & Protection',
        'CONNECTOR' => 'Connectors',
        'SLEEVE' => 'Sleeves & Repairs',
        'TUBING' => 'Tubing Materials',
        'SWGR' => 'Switchgear',
        'GLOVES' => 'Safety Equipment',
        'MASK' => 'Safety Equipment',
        'SHOES' => 'Safety Equipment',
    ];

    /**
     * الكلمات المفتاحية للمواصفات الفنية
     */
    private const SPEC_KEYWORDS = [
        'mm' => 'مم',
        'cm' => 'سم',
        'kv' => 'ك.ف',
        'v' => 'فولت',
        'amp' => 'أمبير',
        'meter' => 'متر',
        'm' => 'متر',
    ];

    /**
     * Run the database seeds.
     * تنفيذ عملية إدخال البيانات المرجعية للمواد
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            ReferenceMaterial::truncate();
            
            // تجهيز وتصنيف البيانات
            $materials = $this->prepareMaterialsData();
            
            // إدخال البيانات على دفعات
            foreach (array_chunk($materials->toArray(), 100) as $chunk) {
                ReferenceMaterial::insert($chunk);
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * تجهيز وتصنيف البيانات
     * @return Collection
     */
    private function prepareMaterialsData(): Collection
    {
        $now = now();
        return collect($this->getMaterialsData())
            ->map(function($item) use ($now) {
                $category = $this->detectCategory($item['description']);
                return [
                    'code' => $item['code'],
                    'description' => $this->formatDescription($item['description']),
                    'category' => $category,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            })
            ->sortBy('category');
    }

    /**
     * تنسيق وصف المادة
     * @param string $description
     * @return string
     */
    private function formatDescription(string $description): string
    {
        // تنظيف الوصف وإزالة الرموز الزائدة
        $description = $this->cleanDescription($description);
        
        // تحويل المواصفات الفنية
        $description = $this->convertTechnicalSpecs($description);
        
        // تنظيم الأجزاء
        $description = $this->organizeDescriptionParts($description);
        
        return $description;
    }

    /**
     * تنظيف الوصف من الرموز الزائدة
     * @param string $description
     * @return string
     */
    private function cleanDescription(string $description): string
    {
        $description = trim($description);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = preg_replace('/,+/', ',', $description);
        $description = str_replace([':', '#', '  '], [' ', ' ', ' '], $description);
        
        return trim($description);
    }

    /**
     * تحويل المواصفات الفنية إلى الصيغة العربية
     * @param string $description
     * @return string
     */
    private function convertTechnicalSpecs(string $description): string
    {
        foreach (self::SPEC_KEYWORDS as $english => $arabic) {
            // تحويل القيم الرقمية مع وحداتها
            $pattern = '/(\d+)\s*' . preg_quote($english, '/') . '/i';
            $description = preg_replace($pattern, '$1 ' . $arabic, $description);
        }
        
        return $description;
    }

    /**
     * تنظيم أجزاء الوصف
     * @param string $description
     * @return string
     */
    private function organizeDescriptionParts(string $description): string
    {
        // تقسيم الوصف إلى أجزاء
        $parts = explode(',', $description);
        $parts = array_map('trim', $parts);
        $parts = array_filter($parts);
        
        // ترتيب الأجزاء حسب الأهمية
        usort($parts, function($a, $b) {
            // الأجزاء التي تحتوي على أرقام تأتي في النهاية
            $aHasNumbers = preg_match('/\d/', $a);
            $bHasNumbers = preg_match('/\d/', $b);
            
            if ($aHasNumbers && !$bHasNumbers) return 1;
            if (!$aHasNumbers && $bHasNumbers) return -1;
            
            return strlen($b) - strlen($a);
        });
        
        return implode('، ', $parts);
    }

    /**
     * تحديد تصنيف المادة بناءً على وصفها
     * @param string $description
     * @return string
     */
    private function detectCategory(string $description): string
    {
        foreach (self::CATEGORIES as $keyword => $category) {
            if (stripos($description, $keyword) !== false) {
                return $category;
            }
        }
        return 'Other';
    }

    /**
     * الحصول على مصفوفة البيانات المرجعية للمواد
     * @return array
     */
    private function getMaterialsData(): array
    {
        return [
            // ... existing materials data ...
            // لا تغيير في البيانات الأصلية
        ];
    }
}
