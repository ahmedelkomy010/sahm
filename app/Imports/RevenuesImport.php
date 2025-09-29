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
    private $rowCount = 0;
    private $project;
    private $city;

    public function __construct($project = 'riyadh', $city = null)
    {
        $this->project = $project;
        $this->city = $city ?: ($project === 'madinah' ? 'المدينة المنورة' : 'الرياض');
    }

    public function model(array $row)
    {
        $this->rowCount++;
        
        try {
            // استخراج البيانات الأساسية
            $clientName = $this->getFieldValue($row, ['client_name', 'اسم العميل', 'الجهة المالكة', 'العميل']);
            $contractNumber = $this->getFieldValue($row, ['contract_number', 'رقم العقد', 'عقد']);
            $extractNumber = $this->getFieldValue($row, ['extract_number', 'رقم المستخلص', 'مستخلص']);

            // التحقق من البيانات الأساسية
            if (empty($clientName) && empty($contractNumber) && empty($extractNumber)) {
                Log::warning('Skipping row due to missing essential data', [
                    'row_number' => $this->rowCount,
                    'row' => $row
                ]);
            return null;
        }

            // إعداد البيانات
            $data = [
                'project' => $this->project,
                'city' => $this->city,
                'client_name' => $clientName,
                'project_area' => $this->getFieldValue($row, ['project_area', 'المشروع', 'المنطقة']),
                'contract_number' => $contractNumber,
                'extract_number' => $extractNumber,
                'office' => $this->getFieldValue($row, ['office', 'المكتب', 'مكتب']),
                'extract_type' => $this->getFieldValue($row, ['extract_type', 'نوع المستخلص', 'نوع']),
                'po_number' => $this->getFieldValue($row, ['po_number', 'رقم PO', 'PO']),
                'invoice_number' => $this->getFieldValue($row, ['invoice_number', 'رقم الفاتورة', 'فاتورة']),
                'extract_value' => $this->convertToNumeric($this->getFieldValue($row, ['extract_value', 'قيمة المستخلص', 'القيمة'])),
                'tax_percentage' => $this->convertToNumeric($this->getFieldValue($row, ['tax_percentage', 'نسبة الضريبة', 'ضريبة %'])),
                'tax_value' => $this->convertToNumeric($this->getFieldValue($row, ['tax_value', 'قيمة الضريبة', 'ضريبة'])),
                'penalties' => $this->convertToNumeric($this->getFieldValue($row, ['penalties', 'الغرامات', 'غرامة'])),
                'first_payment_tax' => $this->convertToNumeric($this->getFieldValue($row, ['first_payment_tax', 'ضريبة الدفعة الأولى'])),
                'net_extract_value' => $this->convertToNumeric($this->getFieldValue($row, ['net_extract_value', 'صافي قيمة المستخلص', 'صافي القيمة'])),
                'extract_date' => $this->convertDate($this->getFieldValue($row, ['extract_date', 'تاريخ إعداد المستخلص', 'تاريخ الإعداد'])),
                'year' => $this->getFieldValue($row, ['year', 'العام', 'سنة']),
                'payment_type' => $this->getFieldValue($row, ['payment_type', 'نوع الدفع', 'طريقة الدفع']),
                'reference_number' => $this->getFieldValue($row, ['reference_number', 'الرقم المرجعي', 'مرجع']),
                'payment_date' => $this->convertDate($this->getFieldValue($row, ['payment_date', 'تاريخ الصرف', 'تاريخ الدفع'])),
                'payment_value' => $this->convertToNumeric($this->getFieldValue($row, ['payment_value', 'قيمة الصرف', 'قيمة الدفع'])),
                'extract_status' => $this->getFieldValue($row, ['extract_status', 'حالة المستخلص', 'الحالة']),
            ];

            // تنظيف البيانات الفارغة
            $cleanData = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });

            // إضافة القيم الافتراضية
            $cleanData['project'] = $this->project;
            $cleanData['city'] = $this->city;

            // حفظ البيانات
            $revenue = Revenue::create($cleanData);
            $this->importedRevenues[] = $revenue;

            Log::info('Revenue imported successfully', [
                'row_number' => $this->rowCount,
                'revenue_id' => $revenue->id
            ]);

            return $revenue;

        } catch (\Exception $e) {
            Log::error('Error importing revenue: ' . $e->getMessage(), [
                'row_number' => $this->rowCount,
                'row' => $row
            ]);
            return null;
        }
    }

    /**
     * البحث عن قيمة الحقل من الصف
     */
    private function getFieldValue(array $row, array $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            // البحث المباشر بالمفتاح الأصلي
            if (isset($row[$key]) && !empty(trim($row[$key]))) {
                return trim($row[$key]);
            }
            
            // البحث بالمفتاح المحول لأحرف صغيرة
            $normalizedKey = strtolower(trim($key));
            if (isset($row[$normalizedKey]) && !empty(trim($row[$normalizedKey]))) {
                return trim($row[$normalizedKey]);
            }
        }
        
        return null;
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

    public function rules(): array
    {
        return [
            // قواعد اختيارية للتحقق
        ];
    }

    public function getImportedRevenues(): array
    {
        return $this->importedRevenues;
    }

    public function getErrors()
    {
        return $this->failures();
    }
} 