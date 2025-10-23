<?php

namespace App\Exports;

use App\Models\WorkOrder;
use App\Models\License;
use App\Models\LicenseViolation;
use App\Models\SafetyViolation;
use App\Models\LicenseExtension;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductivityDashboardExport
{
    protected $project;
    protected $city;

    public function __construct($project = 'riyadh')
    {
        $this->project = $project;
        $this->city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        
        // حذف الورقة الافتراضية
        $spreadsheet->removeSheetByIndex(0);
        
        // إنشاء الأوراق المختلفة
        $this->createSummarySheet($spreadsheet);
        $this->createStatusSheet($spreadsheet);
        $this->createQualitySheet($spreadsheet);
        $this->createTimeManagementSheet($spreadsheet);
        $this->createActionsNeededSheet($spreadsheet);
        
        // تعيين الورقة الأولى كورقة نشطة
        $spreadsheet->setActiveSheetIndex(0);
        
        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'productivity-dashboard-' . $this->project . '-' . now()->format('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    protected function createSummarySheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('ملخص عام');
        
        // العنوان الرئيسي
        $sheet->setCellValue('A1', 'لوحة التحكم التحليلية - ' . ($this->project === 'madinah' ? 'المدينة المنورة' : 'الرياض'));
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        $sheet->setCellValue('A2', 'تاريخ التقرير: ' . now()->format('Y-m-d H:i'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 4;
        
        // قسم: حالات أوامر العمل
        $sheet->setCellValue('A' . $row, 'حالات أوامر العمل');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $this->styleHeader($sheet, 'A' . $row . ':D' . $row, '2196F3');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'الحالة');
        $sheet->setCellValue('B' . $row, 'العدد');
        $sheet->setCellValue('C' . $row, 'القيمة (ريال)');
        $sheet->setCellValue('D' . $row, 'النسبة %');
        $this->styleSubHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        $baseQuery = WorkOrder::where('city', $this->city);
        $totalOrders = (clone $baseQuery)->count();
        $totalValue = (clone $baseQuery)->sum('order_value_without_consultant');
        
        $statuses = [
            '2' => ['name' => 'جاري العمل بالموقع', 'field' => 'order_value_without_consultant'],
            '3' => ['name' => 'تم التنفيذ بالموقع', 'field' => 'order_value_without_consultant'],
            '4' => ['name' => 'إعداد مستخلص الدفعة الجزئية الأولى', 'field' => 'actual_execution_value_without_consultant'],
            '5' => ['name' => 'تم صرف الدفعة الأولى', 'field' => 'actual_execution_value_without_consultant'],
            '6' => ['name' => 'إعداد مستخلص الدفعة الثانية', 'field' => 'actual_execution_value_without_consultant'],
            '7' => ['name' => 'شهادة الإنجاز', 'field' => 'actual_execution_value_without_consultant'],
            '8' => ['name' => 'مستخلص كلي', 'field' => 'actual_execution_value_without_consultant'],
            '10' => ['name' => 'منتهي ومصروف', 'field' => 'actual_execution_value_without_consultant'],
        ];
        
        foreach ($statuses as $statusCode => $statusInfo) {
            $count = (clone $baseQuery)->where('execution_status', $statusCode)->count();
            $value = (clone $baseQuery)->where('execution_status', $statusCode)->sum($statusInfo['field']);
            $percentage = $totalOrders > 0 ? round(($count / $totalOrders) * 100, 1) : 0;
            
            $sheet->setCellValue('A' . $row, $statusInfo['name']);
            $sheet->setCellValue('B' . $row, number_format($count));
            $sheet->setCellValue('C' . $row, number_format($value, 2));
            $sheet->setCellValue('D' . $row, $percentage . '%');
            $row++;
        }
        
        $row++;
        
        // قسم: تقارير الجودة
        $sheet->setCellValue('A' . $row, 'تقارير الجودة');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $this->styleHeader($sheet, 'A' . $row . ':D' . $row, 'FF9800');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'البند');
        $sheet->setCellValue('B' . $row, 'العدد');
        $sheet->setCellValue('C' . $row, 'القيمة (ريال)');
        $sheet->setCellValue('D' . $row, 'ملاحظات');
        $this->styleSubHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        // الرخص
        $licensesCount = License::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->count();
        $licensesValue = License::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->sum('license_value');
        
        $sheet->setCellValue('A' . $row, 'الرخص');
        $sheet->setCellValue('B' . $row, number_format($licensesCount));
        $sheet->setCellValue('C' . $row, number_format($licensesValue, 2));
        $sheet->setCellValue('D' . $row, 'إجمالي الرخص');
        $row++;
        
        // المخالفات
        $qualityViolationsCount = LicenseViolation::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->count();
        $qualityViolationsValue = LicenseViolation::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->sum('violation_amount');
        
        $safetyViolationsCount = SafetyViolation::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->count();
        $safetyViolationsValue = SafetyViolation::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->sum('violation_amount');
        
        $sheet->setCellValue('A' . $row, 'مخالفات الجودة');
        $sheet->setCellValue('B' . $row, number_format($qualityViolationsCount));
        $sheet->setCellValue('C' . $row, number_format($qualityViolationsValue, 2));
        $sheet->setCellValue('D' . $row, 'مخالفات رخص الحفر');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'مخالفات السلامة');
        $sheet->setCellValue('B' . $row, number_format($safetyViolationsCount));
        $sheet->setCellValue('C' . $row, number_format($safetyViolationsValue, 2));
        $sheet->setCellValue('D' . $row, 'مخالفات السلامة');
        $row++;
        
        // التمديدات
        $extensionsCount = LicenseExtension::whereHas('license.workOrder', function($q) {
            $q->where('city', $this->city);
        })->count();
        $extensionsValue = LicenseExtension::whereHas('license.workOrder', function($q) {
            $q->where('city', $this->city);
        })->sum('extension_value');
        
        $sheet->setCellValue('A' . $row, 'التمديدات');
        $sheet->setCellValue('B' . $row, number_format($extensionsCount));
        $sheet->setCellValue('C' . $row, number_format($extensionsValue, 2));
        $sheet->setCellValue('D' . $row, 'تمديدات الرخص');
        $row++;
        
        // الاختبارات
        $inspectionsLicenses = License::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->where(function($q) {
            $q->where('total_tests_count', '>', 0)
              ->orWhereNotNull('lab_tests_data');
        })->get();
        
        $inspectionsCount = $inspectionsLicenses->count();
        $totalTestsCount = $inspectionsLicenses->sum('total_tests_count');
        $successfulTestsCount = $inspectionsLicenses->sum('successful_tests_count');
        $failedTestsCount = $inspectionsLicenses->sum('failed_tests_count');
        
        $sheet->setCellValue('A' . $row, 'الاختبارات');
        $sheet->setCellValue('B' . $row, number_format($totalTestsCount));
        $sheet->setCellValue('C' . $row, 'ناجح: ' . number_format($successfulTestsCount));
        $sheet->setCellValue('D' . $row, 'راسب: ' . number_format($failedTestsCount));
        $row++;
        
        $row++;
        
        // قسم: تجاوز المدة الزمنية والغير منفذه
        $sheet->setCellValue('A' . $row, 'تجاوز المدة الزمنية والغير منفذه');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $this->styleHeader($sheet, 'A' . $row . ':D' . $row, 'F44336');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'البند');
        $sheet->setCellValue('B' . $row, 'العدد');
        $sheet->setCellValue('C' . $row, 'القيمة (ريال)');
        $sheet->setCellValue('D' . $row, 'الوصف');
        $this->styleSubHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        // الأوامر المتأخرة
        $overdueCount = WorkOrder::where('city', $this->city)
            ->where('execution_status', '!=', 7)
            ->where('execution_status', '!=', 8)
            ->where('execution_status', '!=', 10)
            ->whereNotNull('approval_date')
            ->count();
        
        $overdueValue = WorkOrder::where('city', $this->city)
            ->where('execution_status', '!=', 7)
            ->where('execution_status', '!=', 8)
            ->where('execution_status', '!=', 10)
            ->whereNotNull('approval_date')
            ->sum('order_value_without_consultant');
        
        $sheet->setCellValue('A' . $row, 'أوامر متأخرة');
        $sheet->setCellValue('B' . $row, number_format($overdueCount));
        $sheet->setCellValue('C' . $row, number_format($overdueValue, 2));
        $sheet->setCellValue('D' . $row, 'أوامر تجاوزت المدة الزمنية');
        $row++;
        
        // الأوامر الغير منفذة
        $unexecutedCount = WorkOrder::where('city', $this->city)
            ->where('execution_status', 1)
            ->count();
        
        $unexecutedValue = WorkOrder::where('city', $this->city)
            ->where('execution_status', 1)
            ->sum('order_value_without_consultant');
        
        $sheet->setCellValue('A' . $row, 'أوامر غير منفذة');
        $sheet->setCellValue('B' . $row, number_format($unexecutedCount));
        $sheet->setCellValue('C' . $row, number_format($unexecutedValue, 2));
        $sheet->setCellValue('D' . $row, 'أوامر لم يتم تنفيذها');
        $row++;
        
        // أوامر عليها معوقات
        $obstaclesCount = WorkOrder::where('city', $this->city)
            ->whereHas('survey', function($q) {
                $q->where('has_obstacles', true);
            })
            ->count();
        
        $obstaclesValue = WorkOrder::where('city', $this->city)
            ->whereHas('survey', function($q) {
                $q->where('has_obstacles', true);
            })
            ->sum('order_value_without_consultant');
        
        $sheet->setCellValue('A' . $row, 'أوامر عليها معوقات تنفيذ');
        $sheet->setCellValue('B' . $row, number_format($obstaclesCount));
        $sheet->setCellValue('C' . $row, number_format($obstaclesValue, 2));
        $sheet->setCellValue('D' . $row, 'أوامر بها معوقات');
        $row++;
        
        $row++;
        
        // قسم: أوامر العمل التي تحتاج لإجراءات
        $sheet->setCellValue('A' . $row, 'أوامر العمل التي تحتاج لإجراءات');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $this->styleHeader($sheet, 'A' . $row . ':D' . $row, '9C27B0');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'البند');
        $sheet->setCellValue('B' . $row, 'العدد');
        $sheet->setCellValue('C' . $row, 'القيمة (ريال)');
        $sheet->setCellValue('D' . $row, 'الوصف');
        $this->styleSubHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        // أوامر تحتاج للمسح
        $surveyNeedsCount = WorkOrder::where('city', $this->city)
            ->where(function($q) {
                $q->whereDoesntHave('survey')
                  ->orWhereHas('survey', function($subQ) {
                      $subQ->where('status', '!=', 'completed');
                  });
            })
            ->count();
        
        $surveyNeedsValue = WorkOrder::where('city', $this->city)
            ->where(function($q) {
                $q->whereDoesntHave('survey')
                  ->orWhereHas('survey', function($subQ) {
                      $subQ->where('status', '!=', 'completed');
                  });
            })
            ->sum('order_value_without_consultant');
        
        $sheet->setCellValue('A' . $row, 'أوامر تحتاج للمسح');
        $sheet->setCellValue('B' . $row, number_format($surveyNeedsCount));
        $sheet->setCellValue('C' . $row, number_format($surveyNeedsValue, 2));
        $sheet->setCellValue('D' . $row, 'أوامر تحتاج لمسح ميداني');
        $row++;
        
        // أوامر تحتاج لملفات إنجاز (تم التنفيذ بالموقع ولم تنتقل للحالة التالية)
        $completionFilesCount = WorkOrder::where('city', $this->city)
            ->where('execution_status', '3')
            ->count();
        
        $completionFilesValue = WorkOrder::where('city', $this->city)
            ->where('execution_status', '3')
            ->sum('order_value_without_consultant');
        
        $sheet->setCellValue('A' . $row, 'أوامر تحتاج لملفات إنجاز');
        $sheet->setCellValue('B' . $row, number_format($completionFilesCount));
        $sheet->setCellValue('C' . $row, number_format($completionFilesValue, 2));
        $sheet->setCellValue('D' . $row, 'أوامر منتهية تحتاج ملفات');
        $row++;
        
        // تنسيق الأعمدة
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createStatusSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('حالات التنفيذ');
        
        // العنوان
        $sheet->setCellValue('A1', 'حالات التنفيذ - أوامر العمل');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1:F1', '2196F3');
        
        // رؤوس الأعمدة
        $headers = ['رقم الأمر', 'نوع العمل', 'حالة التنفيذ', 'تاريخ الموافقة', 'القيمة', 'الملاحظات'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '2', $header);
            $col++;
        }
        $this->styleSubHeader($sheet, 'A2:F2');
        
        // البيانات
        $workOrders = WorkOrder::where('city', $this->city)
            ->orderBy('execution_status')
            ->orderBy('approval_date', 'desc')
            ->get();
        
        $row = 3;
        $statusNames = [
            '1' => 'بانتظار بدء العمل',
            '2' => 'جاري العمل بالموقع',
            '3' => 'تم التنفيذ بالموقع',
            '4' => 'إعداد مستخلص',
            '5' => 'تم صرف أولى',
            '6' => 'مستخلص ثانية',
            '7' => 'شهادة إنجاز',
            '8' => 'مستخلص كلي',
            '9' => 'دروب',
            '10' => 'منتهي ومصروف',
        ];
        
        foreach ($workOrders as $order) {
            $sheet->setCellValue('A' . $row, $order->order_number);
            $sheet->setCellValue('B' . $row, $order->work_type);
            $sheet->setCellValue('C' . $row, $statusNames[$order->execution_status] ?? 'غير محدد');
            $sheet->setCellValue('D' . $row, $order->approval_date ? $order->approval_date->format('Y-m-d') : '');
            $sheet->setCellValue('E' . $row, number_format($order->order_value_without_consultant, 2));
            $sheet->setCellValue('F' . $row, $order->notes ?? '');
            $row++;
        }
        
        // تنسيق الأعمدة
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createQualitySheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('تقارير الجودة');
        
        // العنوان
        $sheet->setCellValue('A1', 'تقارير الجودة');
        $sheet->mergeCells('A1:E1');
        $this->styleHeader($sheet, 'A1:E1', 'FF9800');
        
        $row = 3;
        
        // الرخص
        $sheet->setCellValue('A' . $row, 'الرخص');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $this->styleSubHeader($sheet, 'A' . $row . ':E' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'رقم الرخصة');
        $sheet->setCellValue('B' . $row, 'رقم الأمر');
        $sheet->setCellValue('C' . $row, 'قيمة الرخصة');
        $sheet->setCellValue('D' . $row, 'تاريخ الرخصة');
        $sheet->setCellValue('E' . $row, 'الحالة');
        $this->styleSubHeader($sheet, 'A' . $row . ':E' . $row);
        $row++;
        
        $licenses = License::whereHas('workOrder', function($q) {
            $q->where('city', $this->city);
        })->with('workOrder')->get();
        
        foreach ($licenses as $license) {
            $sheet->setCellValue('A' . $row, $license->license_number);
            $sheet->setCellValue('B' . $row, $license->workOrder->order_number ?? '');
            $sheet->setCellValue('C' . $row, number_format($license->license_value, 2));
            $sheet->setCellValue('D' . $row, $license->license_date ? $license->license_date->format('Y-m-d') : '');
            $sheet->setCellValue('E' . $row, $license->evacuation_date ? 'مخلى' : 'نشط');
            $row++;
        }
        
        // تنسيق الأعمدة
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createTimeManagementSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('إدارة الوقت');
        
        // العنوان
        $sheet->setCellValue('A1', 'إدارة الوقت والأوامر المتأخرة');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1:F1', 'F44336');
        
        // رؤوس الأعمدة
        $sheet->setCellValue('A2', 'رقم الأمر');
        $sheet->setCellValue('B2', 'نوع العمل');
        $sheet->setCellValue('C2', 'تاريخ الموافقة');
        $sheet->setCellValue('D2', 'الأيام المنقضية');
        $sheet->setCellValue('E2', 'حالة التنفيذ');
        $sheet->setCellValue('F2', 'القيمة');
        $this->styleSubHeader($sheet, 'A2:F2');
        
        $row = 3;
        
        $overdueOrders = WorkOrder::where('city', $this->city)
            ->where('execution_status', '!=', 7)
            ->where('execution_status', '!=', 8)
            ->where('execution_status', '!=', 10)
            ->whereNotNull('approval_date')
            ->orderBy('approval_date', 'asc')
            ->get();
        
        $statusNames = [
            '1' => 'بانتظار بدء العمل',
            '2' => 'جاري العمل بالموقع',
            '3' => 'تم التنفيذ بالموقع',
            '4' => 'إعداد مستخلص',
            '5' => 'تم صرف أولى',
            '6' => 'مستخلص ثانية',
            '9' => 'دروب',
        ];
        
        foreach ($overdueOrders as $order) {
            $daysElapsed = $order->approval_date ? now()->diffInDays($order->approval_date) : 0;
            
            $sheet->setCellValue('A' . $row, $order->order_number);
            $sheet->setCellValue('B' . $row, $order->work_type);
            $sheet->setCellValue('C' . $row, $order->approval_date ? $order->approval_date->format('Y-m-d') : '');
            $sheet->setCellValue('D' . $row, $daysElapsed . ' يوم');
            $sheet->setCellValue('E' . $row, $statusNames[$order->execution_status] ?? 'غير محدد');
            $sheet->setCellValue('F' . $row, number_format($order->order_value_without_consultant, 2));
            $row++;
        }
        
        // تنسيق الأعمدة
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createActionsNeededSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('أوامر تحتاج لإجراءات');
        
        // العنوان
        $sheet->setCellValue('A1', 'أوامر العمل التي تحتاج لإجراءات');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1:F1', '9C27B0');
        
        $row = 3;
        
        // قسم أوامر تحتاج للمسح
        $sheet->setCellValue('A' . $row, 'أوامر تحتاج للمسح');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $this->styleSubHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'رقم الأمر');
        $sheet->setCellValue('B' . $row, 'نوع العمل');
        $sheet->setCellValue('C' . $row, 'حالة المسح');
        $sheet->setCellValue('D' . $row, 'تاريخ الموافقة');
        $sheet->setCellValue('E' . $row, 'القيمة');
        $sheet->setCellValue('F' . $row, 'الملاحظات');
        $this->styleSubHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $surveyNeededOrders = WorkOrder::where('city', $this->city)
            ->where(function($q) {
                $q->whereDoesntHave('survey')
                  ->orWhereHas('survey', function($subQ) {
                      $subQ->where('status', '!=', 'completed');
                  });
            })
            ->orderBy('approval_date', 'desc')
            ->get();
        
        foreach ($surveyNeededOrders as $order) {
            $surveyStatus = $order->survey ? ($order->survey->status ?? 'لم يبدأ') : 'لا يوجد';
            $sheet->setCellValue('A' . $row, $order->order_number);
            $sheet->setCellValue('B' . $row, $order->work_type);
            $sheet->setCellValue('C' . $row, $surveyStatus);
            $sheet->setCellValue('D' . $row, $order->approval_date ? $order->approval_date->format('Y-m-d') : '');
            $sheet->setCellValue('E' . $row, number_format($order->order_value_without_consultant, 2));
            $sheet->setCellValue('F' . $row, $order->notes ?? '');
            $row++;
        }
        
        $row++;
        
        // قسم أوامر تحتاج لملفات إنجاز
        $sheet->setCellValue('A' . $row, 'أوامر تحتاج لملفات إنجاز');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $this->styleSubHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'رقم الأمر');
        $sheet->setCellValue('B' . $row, 'نوع العمل');
        $sheet->setCellValue('C' . $row, 'حالة التنفيذ');
        $sheet->setCellValue('D' . $row, 'تاريخ الموافقة');
        $sheet->setCellValue('E' . $row, 'القيمة');
        $sheet->setCellValue('F' . $row, 'الملاحظات');
        $this->styleSubHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $completionFilesNeeded = WorkOrder::where('city', $this->city)
            ->where('execution_status', '3')
            ->orderBy('approval_date', 'desc')
            ->get();
        
        foreach ($completionFilesNeeded as $order) {
            $sheet->setCellValue('A' . $row, $order->order_number);
            $sheet->setCellValue('B' . $row, $order->work_type);
            $sheet->setCellValue('C' . $row, 'تم التنفيذ بالموقع');
            $sheet->setCellValue('D' . $row, $order->approval_date ? $order->approval_date->format('Y-m-d') : '');
            $sheet->setCellValue('E' . $row, number_format($order->order_value_without_consultant, 2));
            $sheet->setCellValue('F' . $row, $order->notes ?? '');
            $row++;
        }
        
        $row++;
        
        // قسم أوامر عليها معوقات
        $sheet->setCellValue('A' . $row, 'أوامر عليها معوقات تنفيذ');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $this->styleSubHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'رقم الأمر');
        $sheet->setCellValue('B' . $row, 'نوع العمل');
        $sheet->setCellValue('C' . $row, 'المعوقات');
        $sheet->setCellValue('D' . $row, 'تاريخ الموافقة');
        $sheet->setCellValue('E' . $row, 'القيمة');
        $sheet->setCellValue('F' . $row, 'الملاحظات');
        $this->styleSubHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $obstaclesOrders = WorkOrder::where('city', $this->city)
            ->whereHas('survey', function($q) {
                $q->where('has_obstacles', true);
            })
            ->with('survey')
            ->orderBy('approval_date', 'desc')
            ->get();
        
        foreach ($obstaclesOrders as $order) {
            $obstacles = $order->survey ? ($order->survey->obstacles_notes ?? 'غير محدد') : 'لا توجد معلومات';
            $sheet->setCellValue('A' . $row, $order->order_number);
            $sheet->setCellValue('B' . $row, $order->work_type);
            $sheet->setCellValue('C' . $row, $obstacles);
            $sheet->setCellValue('D' . $row, $order->approval_date ? $order->approval_date->format('Y-m-d') : '');
            $sheet->setCellValue('E' . $row, number_format($order->order_value_without_consultant, 2));
            $sheet->setCellValue('F' . $row, $order->notes ?? '');
            $row++;
        }
        
        // تنسيق الأعمدة
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function styleHeader($sheet, $range, $color = '4CAF50')
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
    }

    protected function styleSubHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
    }
}

