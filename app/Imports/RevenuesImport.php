<?php

namespace App\Imports;

use App\Models\Revenue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RevenuesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsEmptyRows
{
    use SkipsErrors;

    private $importedRevenues = [];
    private $customErrors = [];
    private $columnMappings = [];
    private $project;
    private $city;

    public function __construct($project = 'riyadh', $city = null)
    {
        $this->project = $project;
        $this->city = $city ?: ($project === 'madinah' ? 'المدينة المنورة' : 'الرياض');
        $this->setupColumnMappings();
    }

    /**
     * إعداد خريطة الأعمدة المدعومة
     */
    private function setupColumnMappings()
    {
        $this->columnMappings = [
            'client_name' => ['client_name', 'اسم العميل', 'الجهة المالكة', 'العميل', 'اسم الجهة', 'client', 'owner'],
            'project_area' => ['project_area', 'المشروع', 'المنطقة', 'project', 'area', 'مشروع', 'منطقة'],
            'contract_number' => ['contract_number', 'رقم العقد', 'عقد', 'contract', 'contract_no'],
            'extract_number' => ['extract_number', 'رقم المستخلص', 'مستخلص', 'extract', 'extract_no'],
            'office' => ['office', 'المكتب', 'مكتب', 'الفرع', 'فرع'],
            'extract_type' => ['extract_type', 'نوع المستخلص', 'نوع', 'type', 'extract_type'],
            'po_number' => ['po_number', 'رقم PO', 'PO', 'po', 'purchase_order'],
            'invoice_number' => ['invoice_number', 'رقم الفاتورة', 'فاتورة', 'invoice', 'invoice_no'],
            'extract_value' => ['extract_value', 'قيمة المستخلص', 'القيمة', 'value', 'amount'],
            'tax_percentage' => ['tax_percentage', 'نسبة الضريبة', 'ضريبة %', 'tax_rate', 'tax_percent'],
            'tax_value' => ['tax_value', 'قيمة الضريبة', 'ضريبة', 'tax', 'tax_amount'],
            'penalties' => ['penalties', 'الغرامات', 'غرامة', 'penalty', 'fine'],
            'first_payment_tax' => ['first_payment_tax', 'ضريبة الدفعة الأولى', 'ضريبة أولى', 'first_tax'],
            'net_extract_value' => ['net_extract_value', 'صافي قيمة المستخلص', 'صافي القيمة', 'net_value', 'net_amount'],
            'extract_date' => ['extract_date', 'تاريخ إعداد المستخلص', 'تاريخ الإعداد', 'preparation_date', 'date'],
            'year' => ['year', 'العام', 'سنة', 'السنة'],
            'payment_type' => ['payment_type', 'نوع الدفع', 'طريقة الدفع', 'payment_method'],
            'reference_number' => ['reference_number', 'الرقم المرجعي', 'مرجع', 'reference', 'ref_no'],
            'payment_date' => ['payment_date', 'تاريخ الصرف', 'تاريخ الدفع', 'disbursement_date'],
            'payment_value' => ['payment_value', 'قيمة الصرف', 'قيمة الدفع', 'payment_amount', 'disbursement_amount'],
            'extract_status' => ['extract_status', 'حالة المستخلص', 'الحالة', 'status', 'state']
        ];
    }

    /**
     * البحث عن قيمة من الصف باستخدام خريطة الأعمدة
     */
    private function findValueFromRow(array $row, string $field)
    {
        $possibleKeys = $this->columnMappings[$field] ?? [];
        
        foreach ($possibleKeys as $key) {
            $normalizedKey = strtolower(trim($key));
            
            // البحث المباشر
            if (isset($row[$normalizedKey])) {
                return $this->cleanValue($row[$normalizedKey]);
            }
            
            // البحث في جميع مفاتيح الصف
            foreach ($row as $rowKey => $value) {
                $normalizedRowKey = strtolower(trim($rowKey));
                if ($normalizedRowKey === $normalizedKey || 
                    strpos($normalizedRowKey, $normalizedKey) !== false ||
                    strpos($normalizedKey, $normalizedRowKey) !== false) {
                    return $this->cleanValue($value);
                }
            }
        }
        
        return null;
    }

    /**
     * تنظيف القيمة
     */
    private function cleanValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        
        return trim($value);
    }

    /**
     * تحويل التاريخ
     */
    private function convertDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // إذا كان رقم Excel date
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            
            // إذا كان تاريخ نصي
            if (is_string($value)) {
                $date = Carbon::parse($value);
                return $date->format('Y-m-d');
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Failed to convert date: ' . $value . ' Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * إنشاء نموذج من صف البيانات
     */
    public function model(array $row)
    {
        try {
            Log::info('Processing revenue row', ['row' => $row]);

            // استخراج البيانات من الصف
            $data = [
                'project' => $this->project,
                'city' => $this->city,
                'client_name' => $this->findValueFromRow($row, 'client_name'),
                'project_area' => $this->findValueFromRow($row, 'project_area'),
                'contract_number' => $this->findValueFromRow($row, 'contract_number'),
                'extract_number' => $this->findValueFromRow($row, 'extract_number'),
                'office' => $this->findValueFromRow($row, 'office'),
                'extract_type' => $this->findValueFromRow($row, 'extract_type'),
                'po_number' => $this->findValueFromRow($row, 'po_number'),
                'invoice_number' => $this->findValueFromRow($row, 'invoice_number'),
                'extract_value' => $this->convertToNumeric($this->findValueFromRow($row, 'extract_value')),
                'tax_percentage' => $this->convertToNumeric($this->findValueFromRow($row, 'tax_percentage')),
                'tax_value' => $this->convertToNumeric($this->findValueFromRow($row, 'tax_value')),
                'penalties' => $this->convertToNumeric($this->findValueFromRow($row, 'penalties')),
                'first_payment_tax' => $this->convertToNumeric($this->findValueFromRow($row, 'first_payment_tax')),
                'net_extract_value' => $this->convertToNumeric($this->findValueFromRow($row, 'net_extract_value')),
                'extract_date' => $this->convertDate($this->findValueFromRow($row, 'extract_date')),
                'year' => $this->findValueFromRow($row, 'year'),
                'payment_type' => $this->findValueFromRow($row, 'payment_type'),
                'reference_number' => $this->findValueFromRow($row, 'reference_number'),
                'payment_date' => $this->convertDate($this->findValueFromRow($row, 'payment_date')),
                'payment_value' => $this->convertToNumeric($this->findValueFromRow($row, 'payment_value')),
                'extract_status' => $this->findValueFromRow($row, 'extract_status'),
            ];

            // تنظيف البيانات الفارغة
            $data = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });

            // التحقق من وجود بيانات أساسية
            if (empty($data)) {
                Log::info('Skipping empty row');
                return null;
            }

            // معالجة البيانات فقط بدون حفظ في قاعدة البيانات
            Log::info('Revenue data processed (not saved)', ['data' => $data]);
            
            // إضافة البيانات للقائمة المعالجة
            $this->importedRevenues[] = $data;
            
            // عدم إنشاء سجل في قاعدة البيانات
            return null;

        } catch (\Exception $e) {
            $error = 'خطأ في معالجة الصف: ' . $e->getMessage();
            Log::error($error, ['row' => $row]);
            $this->customErrors[] = $error;
            return null;
        }
    }

    /**
     * تحويل القيمة إلى رقم
     */
    private function convertToNumeric($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // إزالة الفواصل والمسافات
        $value = str_replace([',', ' '], '', $value);
        
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        return null;
    }

    /**
     * قواعد التحقق
     */
    public function rules(): array
    {
        return [
            // لا توجد قواعد إجبارية - سنتعامل مع جميع الحقول كاختيارية
        ];
    }

    /**
     * الحصول على الإيرادات المستوردة
     */
    public function getImportedRevenues()
    {
        return $this->importedRevenues;
    }

    /**
     * الحصول على الأخطاء
     */
    public function getErrors()
    {
        return array_merge($this->customErrors, $this->failures());
    }
}
