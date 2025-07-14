<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['license', 'files'])->get();
        return view('admin.work_orders.licenses', compact('workOrders'));
    }

    /**
     * Display all licenses data in a dedicated view with search and filter capabilities
     */
    public function display(Request $request)
    {
        try {
            $query = License::query();
            $now = now();

            // تطبيق فلتر الحالة
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where(function($q) use ($now) {
                        $q->where('license_end_date', '>', $now)
                          ->orWhereHas('extensions', function($q) use ($now) {
                              $q->where('end_date', '>', $now);
                          });
                    });
                } elseif ($request->status === 'expired') {
                    $query->whereNotNull('license_end_date')
                          ->where('license_end_date', '<=', $now)
                          ->whereDoesntHave('extensions', function($q) use ($now) {
                              $q->where('end_date', '>', $now);
                          });
                }
            }

            // حساب الإحصائيات
            $activeCount = License::where(function($q) use ($now) {
                $q->where('license_end_date', '>', $now)
                  ->orWhereHas('extensions', function($q) use ($now) {
                      $q->where('end_date', '>', $now);
                  });
            })->count();

            $expiredCount = License::whereNotNull('license_end_date')
                ->where('license_end_date', '<=', $now)
                ->whereDoesntHave('extensions', function($q) use ($now) {
                    $q->where('end_date', '>', $now);
                })->count();

            $violationsCount = License::has('violations')->count();
            $extensionsCount = License::has('extensions')->count();
            $passedTestsCount = License::where('successful_tests_count', '>', 0)->count();
            $failedTestsCount = License::where('failed_tests_count', '>', 0)->count();
            $evacuationsCount = License::where('is_evacuated', true)->count();

            $licenses = $query->with(['workOrder', 'extensions'])->latest()->paginate(10);

            // Calculate total license values for filtered data
            $totalLicenseValue = $licenses->sum('license_value');
            $totalExtensionValue = $licenses->sum('extension_value');

            return view('admin.licenses.display', compact(
                'licenses',
                'activeCount',
                'expiredCount',
                'violationsCount',
                'extensionsCount',
                'passedTestsCount',
                'failedTestsCount',
                'evacuationsCount',
                'totalLicenseValue',
                'totalExtensionValue'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in license display: ' . $e->getMessage());
            return view('admin.licenses.display', [
                'licenses' => collect(),
                'activeCount' => 0,
                'expiredCount' => 0,
                'violationsCount' => 0,
                'extensionsCount' => 0,
                'passedTestsCount' => 0,
                'failedTestsCount' => 0,
                'evacuationsCount' => 0,
                'totalLicenseValue' => 0,
                'totalExtensionValue' => 0
            ])->withErrors('حدث خطأ أثناء تحميل البيانات');
        }
    }

    /**
     * Get license status text based on dates and extensions
     */
    private function getLicenseStatusText($license, $now)
    {
        // تحقق من وجود تمديد ساري
        $hasActiveExtension = $license->extensions()
            ->where('end_date', '>', $now)
            ->exists();

        if ($license->license_end_date > $now || $hasActiveExtension) {
            return 'سارية';
        } else {
            return 'منتهية';
        }
    }

    /**
     * Get license status color based on dates and extensions
     */
    private function getLicenseStatusColor($license, $now)
    {
        // تحقق من وجود تمديد ساري
        $hasActiveExtension = $license->extensions()
            ->where('end_date', '>', $now)
            ->exists();

        if ($license->license_end_date > $now || $hasActiveExtension) {
            return 'bg-green-100 text-green-800'; // سارية
        } else {
            return 'bg-red-100 text-red-800'; // منتهية
        }
    }

    public function update(Request $request, License $license)
    {
        try {
            $validated = $request->validate([
                'license_number' => 'required|string|max:255',
                'license_date' => 'nullable|date',
                'license_type' => 'nullable|string|max:255',
                'license_value' => 'nullable|numeric|min:0',
                'extension_value' => 'nullable|numeric|min:0',
                'license_start_date' => 'nullable|date|before_or_equal:license_end_date',
                'license_end_date' => 'nullable|date|after_or_equal:license_start_date',
                'extension_start_date' => 'nullable|date',
                'extension_end_date' => 'nullable|date|after_or_equal:extension_start_date',
                'excavation_length' => 'nullable|numeric|min:0',
                'excavation_width' => 'nullable|numeric|min:0',
                'excavation_depth' => 'nullable|numeric|min:0',
                'has_restriction' => 'nullable|boolean',
                'restriction_authority' => 'nullable|string|max:255',
                'has_depth_test' => 'nullable|boolean',
                'has_soil_test' => 'nullable|boolean',
                'has_asphalt_test' => 'nullable|boolean',
                'has_soil_compaction_test' => 'nullable|boolean',
                'has_rc1_mc1_test' => 'nullable|boolean',
                'has_interlock_test' => 'nullable|boolean',
                'is_evacuated' => 'nullable|boolean',
                'evac_license_number' => 'nullable|string|max:255',
                'evac_license_value' => 'nullable|numeric|min:0',
                'evac_payment_number' => 'nullable|string|max:255',
                'evac_date' => 'nullable|date',
                'evac_amount' => 'nullable|numeric|min:0',
                'violation_number' => 'nullable|string|max:255',
                'violation_license_value' => 'nullable|numeric|min:0',
                'violation_license_date' => 'nullable|date',
                'violation_due_date' => 'nullable|date',
                'violation_cause' => 'nullable|string',
                'successful_tests_value' => 'nullable|numeric|min:0',
                'failed_tests_value' => 'nullable|numeric|min:0',
                'test_failure_reasons' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // معالجة البيانات Boolean
            $booleanFields = [
                'has_restriction',
                'has_depth_test',
                'has_soil_test',
                'has_asphalt_test',
                'has_soil_compaction_test',
                'has_rc1_mc1_test',
                'has_interlock_test',
                'is_evacuated'
            ];

            foreach ($booleanFields as $field) {
                $validated[$field] = isset($validated[$field]) ? (bool)$validated[$field] : false;
            }

            // تحديث البيانات
            $license->update($validated);

            return redirect()->route('admin.licenses.show', $license)
                ->with('success', 'تم تحديث الرخصة بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('License update error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الرخصة')
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل رخصة واحدة
     */
    public function show(License $license)
    {
        $license->load(['workOrder', 'violations' => function($query) {
            $query->orderBy('violation_date', 'desc');
        }, 'extensions' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'attachments']);
        
        // إذا كان الطلب JSON، إرجاع البيانات كـ JSON
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'license' => $license
            ]);
        }
        
        // تحضير البيانات المطلوبة للعرض
        $additionalDetails = $license->additional_details ? json_decode($license->additional_details, true) : [];
        
        $labTechnicalData = [];
        if ($license->lab_table2_data) {
            $labTechnicalData = json_decode($license->lab_table2_data, true) ?: [];
        }
        
        $evacTable1Data = [];
        if ($license->evac_table1_data) {
            $evacTable1Data = json_decode($license->evac_table1_data, true) ?: [];
        }
        
        return view('admin.licenses.show', compact('license', 'labTechnicalData', 'evacTable1Data', 'additionalDetails'));
    }

    /**
     * عرض نموذج تعديل الرخصة
     */
    public function edit(License $license)
    {
        $license->load('workOrder');
        return view('admin.licenses.edit', compact('license'));
    }

    /**
     * معالجة بيانات الجداول
     */
    private function processTableData($tableData)
    {
        $processedData = [];
        
        foreach ($tableData as $rowData) {
            // تنظيف البيانات وإزالة الصفوف الفارغة
            $cleanRow = array_filter($rowData, function($value) {
                return !empty(trim($value));
            });
            
            if (!empty($cleanRow)) {
                $processedData[] = $rowData;
            }
        }
        
        return $processedData;
    }

    /**
     * عرض صفحة بيانات الجودة والرخص
     */
    public function data(Request $request)
    {
        $query = License::with(['workOrder']);

        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('license_number', 'like', '%' . $search . '%')
                  ->orWhere('license_type', 'like', '%' . $search . '%')
                  ->orWhere('restriction_authority', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%')
                  ->orWhereHas('workOrder', function($q) use ($search) {
                      $q->where('order_number', 'like', '%' . $search . '%')
                        ->orWhere('subscriber_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // تصفية حسب حالة الرخصة
        if ($request->has('status') && !empty($request->status)) {
            $now = now();
            if ($request->status === 'active') {
                $query->where(function($q) use ($now) {
                    $q->where('license_end_date', '>', $now)
                      ->orWhere('extension_end_date', '>', $now);
                });
            } elseif ($request->status === 'expired') {
                $query->where(function($q) use ($now) {
                    $q->where('license_end_date', '<=', $now)
                      ->where(function($q) use ($now) {
                          $q->whereNull('extension_end_date')
                            ->orWhere('extension_end_date', '<=', $now);
                      });
                });
            } elseif ($request->status === 'pending') {
                $query->where(function($q) {
                    $q->whereNull('license_start_date')
                      ->orWhereNull('license_end_date');
                });
            }
        }

        // تصفية حسب نوع الرخصة
        if ($request->has('type') && !empty($request->type)) {
            $query->where('license_type', $request->type);
        }

        $licenses = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.licenses.data', compact('licenses'));
    }

    /**
     * إنشاء رخصة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'license_number' => 'required|string|max:255',
                'license_date' => 'nullable|date',
                'license_type' => 'nullable|string|max:255',
                'license_value' => 'nullable|numeric|min:0',
                'extension_value' => 'nullable|numeric|min:0',
                'license_start_date' => 'nullable|date',
                'license_end_date' => 'nullable|date',
                'excavation_length' => 'nullable|numeric|min:0',
                'excavation_width' => 'nullable|numeric|min:0',
                'excavation_depth' => 'nullable|numeric|min:0',
                'has_restriction' => 'nullable|boolean',
                'restriction_authority' => 'nullable|string|max:255',
                'has_depth_test' => 'nullable|boolean',
                'has_soil_compaction_test' => 'nullable|boolean',
                'has_rc1_mc1_test' => 'nullable|boolean',
                'has_asphalt_test' => 'nullable|boolean',
                'has_soil_test' => 'nullable|boolean',
                'has_interlock_test' => 'nullable|boolean',
                'is_evacuated' => 'nullable|boolean',
                'evac_license_number' => 'nullable|string|max:255',
                'evac_license_value' => 'nullable|numeric|min:0',
                'evac_payment_number' => 'nullable|string|max:255',
                'evac_date' => 'nullable|date',
                'evac_amount' => 'nullable|numeric|min:0',
                'coordination_certificate_notes' => 'nullable|string',
                'lab_table1_data' => 'nullable|string',
                'lab_table2_data' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // معالجة البيانات المنطقية
            $validated['has_restriction'] = $request->input('has_restriction', 0) == '1' ? true : false;
            $validated['has_depth_test'] = $request->input('has_depth_test', 0) == '1' ? true : false;
            $validated['has_soil_compaction_test'] = $request->input('has_soil_compaction_test', 0) == '1' ? true : false;
            $validated['has_rc1_mc1_test'] = $request->input('has_rc1_mc1_test', 0) == '1' ? true : false;
            $validated['has_asphalt_test'] = $request->input('has_asphalt_test', 0) == '1' ? true : false;
            $validated['has_soil_test'] = $request->input('has_soil_test', 0) == '1' ? true : false;
            $validated['has_interlock_test'] = $request->input('has_interlock_test', 0) == '1' ? true : false;
            $validated['is_evacuated'] = $request->input('is_evacuated', 0) == '1' ? true : false;

            // معالجة بيانات الجداول
            if ($request->has('lab_table1_data') && !empty($request->lab_table1_data)) {
                $validated['lab_table1_data'] = json_decode($request->lab_table1_data, true);
            } else {
                $validated['lab_table1_data'] = null;
            }
            
            if ($request->has('lab_table2_data') && !empty($request->lab_table2_data)) {
                $validated['lab_table2_data'] = json_decode($request->lab_table2_data, true);
            } else {
                $validated['lab_table2_data'] = null;
            }

            $license = License::create($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الرخصة بنجاح',
                    'license_id' => $license->id,
                    'license' => $license
                ]);
            }

            return redirect()->route('admin.licenses.show', $license)
                ->with('success', 'تم إنشاء الرخصة بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('License creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الرخصة: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الرخصة')
                ->withInput();
        }
    }

    /**
     * حذف رخصة
     */
    public function destroy(License $license)
    {
        try {
            $license->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرخصة بنجاح'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('License deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الرخصة'
            ], 500);
        }
    }

    /**
     * الحصول على الرخص حسب أمر العمل
     */
    public function getByWorkOrder($workOrderId)
    {
        try {
            $licenses = License::where('work_order_id', $workOrderId)
                ->orderBy('created_at', 'desc')
                ->get();

            // إضافة مسارات الملفات الصحيحة
            $licensesWithFiles = $licenses->map(function ($license) {
                $licenseArray = $license->toArray();
                
                // إضافة روابط الملفات
                $licenseArray['license_file_url'] = $license->getFileUrl('license_file_path');
                $licenseArray['payment_proof_url'] = $license->getFileUrl('payment_proof_path');
                $licenseArray['payment_proof_urls'] = $license->getMultipleFileUrls('payment_proof_path');
                $licenseArray['payment_invoices_urls'] = $license->getMultipleFileUrls('payment_invoices_path');
                $licenseArray['license_activation_urls'] = $license->getMultipleFileUrls('license_activation_path');
                
                return $licenseArray;
            });

            return response()->json([
                'success' => true,
                'licenses' => $licensesWithFiles
            ]);

        } catch (\Exception $e) {
            \Log::error('Get licenses by work order error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الرخص',
                'licenses' => []
            ], 500);
        }
    }

    /**
     * الحصول على التمديدات الخاصة بأمر عمل محدد
     */
    public function getExtensionsByWorkOrder($workOrderId)
    {
        try {
            // جلب التمديدات من جدول license_extensions
            $licenses = License::where('work_order_id', $workOrderId)->get();
            $extensions = [];
            
            foreach ($licenses as $license) {
                // جلب التمديدات من جدول LicenseExtension
                $licenseExtensions = $license->extensions()->orderBy('created_at', 'desc')->get();
                
                foreach ($licenseExtensions as $extension) {
                    $attachments = [];
                    
                    // معالجة المرفقات إذا كانت موجودة
                    if ($extension->attachments && is_array($extension->attachments)) {
                        foreach ($extension->attachments as $attachment) {
                            if ($attachment && \Storage::disk('public')->exists($attachment)) {
                                $attachments[] = [
                                    'url' => \Storage::disk('public')->url($attachment),
                                    'path' => $attachment
                                ];
                            }
                        }
                    }
                    
                    $extensions[] = [
                        'id' => $extension->id,
                        'license_id' => $license->id,
                        'license_number' => $license->license_number,
                        'extension_value' => $license->extension_value,
                        'extension_start_date' => $extension->start_date,
                        'extension_end_date' => $extension->end_date,
                        'extension_days' => $extension->days_count,
                        'reason' => $extension->reason,
                        'attachments' => $attachments,
                        'created_at' => $extension->created_at?->format('Y-m-d H:i:s'),
                    ];
                }
                
                // في حالة عدم وجود تمديدات في جدول license_extensions، عرض من جدول licenses
                if ($licenseExtensions->isEmpty() && $license->extension_value && $license->extension_value > 0) {
                    $extensions[] = [
                        'id' => $license->id,
                        'license_id' => $license->id,
                        'license_number' => $license->license_number,
                        'extension_value' => $license->extension_value,
                        'extension_start_date' => $license->extension_start_date,
                        'extension_end_date' => $license->extension_end_date,
                        'extension_days' => $this->calculateExtensionDays($license->extension_start_date, $license->extension_end_date),
                        'reason' => null,
                        'attachments' => [],
                        'created_at' => $license->updated_at?->format('Y-m-d H:i:s'),
                    ];
                }
            }
            
            return response()->json(['extensions' => $extensions], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error getting extensions by work order: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في تحميل التمديدات'], 500);
        }
    }

    /**
     * الحصول على التمديدات الخاصة برخصة محددة
     */
    public function getExtensionsByLicense($licenseId)
    {
        try {
            $license = License::findOrFail($licenseId);
            $extensions = [];
            
            // جلب التمديدات من جدول LicenseExtension
            $licenseExtensions = $license->extensions()->orderBy('created_at', 'desc')->get();
            
            foreach ($licenseExtensions as $extension) {
                $attachments = [];
                
                // معالجة المرفقات إذا كانت موجودة
                if ($extension->attachments && is_array($extension->attachments)) {
                    foreach ($extension->attachments as $attachment) {
                        if ($attachment && \Storage::disk('public')->exists($attachment)) {
                            $attachments[] = [
                                'url' => \Storage::disk('public')->url($attachment),
                                'path' => $attachment
                            ];
                        }
                    }
                }
                
                $extensions[] = [
                    'id' => $extension->id,
                    'license_id' => $license->id,
                    'license_number' => $license->license_number,
                    'extension_value' => $license->extension_value,
                    'extension_start_date' => $extension->start_date,
                    'extension_end_date' => $extension->end_date,
                    'extension_days' => $extension->days_count,
                    'reason' => $extension->reason,
                    'attachments' => $attachments,
                    'created_at' => $extension->created_at?->format('Y-m-d H:i:s'),
                ];
            }
            
            // في حالة عدم وجود تمديدات في جدول license_extensions، عرض من جدول licenses
            if ($licenseExtensions->isEmpty() && $license->extension_value && $license->extension_value > 0) {
                $extensions[] = [
                    'id' => $license->id,
                    'license_id' => $license->id,
                    'license_number' => $license->license_number,
                    'extension_value' => $license->extension_value,
                    'extension_start_date' => $license->extension_start_date,
                    'extension_end_date' => $license->extension_end_date,
                    'extension_days' => $this->calculateExtensionDays($license->extension_start_date, $license->extension_end_date),
                    'reason' => null,
                    'attachments' => [],
                    'created_at' => $license->updated_at?->format('Y-m-d H:i:s'),
                ];
            }
            
            return response()->json(['extensions' => $extensions], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error getting extensions by license: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في تحميل التمديدات'], 500);
        }
    }

    /**
     * حساب عدد أيام التمديد
     */
    private function calculateExtensionDays($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return 0;
        }
        
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        
        return $start->diffInDays($end) + 1; // +1 لتشمل اليوم الأخير
    }

    /**
     * تصدير البيانات إلى Excel
     */
    public function exportExcel()
    {
        $licenses = License::with('workOrder')->get();
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="licenses_data_' . date('Y-m-d') . '.xlsx"',
        ];

        $callback = function() use ($licenses) {
            $file = fopen('php://output', 'w');
            
            // كتابة العناوين
            fputcsv($file, [
                'رقم الرخصة',
                'رقم أمر العمل',
                'نوع الرخصة',
                'تاريخ الرخصة',
                'قيمة الرخصة',
                'قيمة التمديد',
                'تاريخ البداية',
                'تاريخ النهاية',
                'يوجد حظر',
                'جهة الحظر',
                'اختبار العمق',
                'اختبار التربة',
                'اختبار الأسفلت',
                'اختبار الدك',
                'تم الإخلاء',
                'ملاحظات'
            ]);
            
            // كتابة البيانات
            foreach ($licenses as $license) {
                fputcsv($file, [
                    $license->license_number,
                    $license->workOrder->order_number ?? '',
                    $license->license_type,
                    $license->license_date?->format('Y-m-d'),
                    $license->license_value,
                    $license->extension_value,
                    $license->license_start_date?->format('Y-m-d'),
                    $license->license_end_date?->format('Y-m-d'),
                    $license->has_restriction ? 'نعم' : 'لا',
                    $license->restriction_authority,
                    $license->has_depth_test ? 'نعم' : 'لا',
                    $license->has_soil_test ? 'نعم' : 'لا',
                    $license->has_asphalt_test ? 'نعم' : 'لا',
                    $license->has_soil_compaction_test ? 'نعم' : 'لا',
                    $license->is_evacuated ? 'نعم' : 'لا',
                    $license->notes
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * حفظ قسم منفصل من الرخصة
     */
    public function saveSection(Request $request)
    {
        try {
            $sectionType = $request->input('section_type') ?: $request->input('section');
            $workOrderId = $request->input('work_order_id');
            $licenseId = $request->input('license_id'); // معرف الرخصة المحدد
            $forceNew = $request->input('force_new', false); // إجبار إنشاء رخصة جديدة
            
            // التحقق من صحة المعاملات الأساسية
            if (!$sectionType || (!$workOrderId && !$licenseId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع القسم أو معرف أمر العمل/الرخصة مفقود'
                ], 400);
            }
            
            // تحديد الرخصة المراد العمل عليها
            if ($forceNew || $sectionType === 'complete_license') {
                // إنشاء رخصة جديدة دائماً - لا تحديث على رخصة موجودة
                $license = new License();
                $license->work_order_id = $workOrderId;
                $license->user_id = auth()->id();
                $isNewLicense = true;
                
                \Log::info('Creating NEW license (force_new = true)', [
                    'work_order_id' => $workOrderId,
                    'force_new' => $forceNew
                ]);
            } elseif ($licenseId) {
                // البحث عن الرخصة المحددة بالـ ID
                $license = License::find($licenseId);
                
                if (!$license) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الرخصة المحددة غير موجودة'
                    ], 404);
                }
                
                // استخدام work_order_id من الرخصة إذا لم يكن موجوداً في الطلب
                if (!$workOrderId) {
                    $workOrderId = $license->work_order_id;
                }
                
                // التحقق من أن الرخصة تنتمي لنفس أمر العمل
                if ($workOrderId && $license->work_order_id != $workOrderId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الرخصة المحددة لا تنتمي لأمر العمل المحدد'
                    ], 400);
                }
                
                $isNewLicense = false;
                
                \Log::info('Updating SPECIFIC license', [
                    'license_id' => $license->id,
                    'work_order_id' => $workOrderId,
                    'section_type' => $sectionType
                ]);
            } else {
                // البحث عن آخر رخصة تم إنشاؤها أو إنشاء واحدة جديدة
                $license = License::where('work_order_id', $workOrderId)
                                 ->orderBy('created_at', 'desc')
                                 ->first();
                $isNewLicense = false;
                
                if (!$license) {
                    $license = new License();
                    $license->work_order_id = $workOrderId;
                    $license->user_id = auth()->id();
                    $isNewLicense = true;
                    
                    \Log::info('Creating NEW license (no existing license found)', [
                        'work_order_id' => $workOrderId
                    ]);
                } else {
                    \Log::info('Updating LATEST existing license', [
                        'license_id' => $license->id,
                        'work_order_id' => $workOrderId,
                        'created_at' => $license->created_at
                    ]);
                }
            }
            
            // حفظ البيانات حسب نوع القسم
            switch ($sectionType) {
                case 'coordination':
                    \Log::info('Processing coordination section');
                    $this->saveCoordinationSection($request, $license);
                    break;
                case 'dig_license':
                    \Log::info('Processing dig_license section');
                    $this->saveDigLicenseSection($request, $license);
                    break;
                case 'lab':
                    \Log::info('Processing lab section');
                    $this->saveLabSection($request, $license);
                    break;
                case 'evacuations':
                    \Log::info('Processing evacuations section');
                    $this->saveEvacuationsSection($request, $license);
                    break;
                case 'violations':
                    \Log::info('Processing violations section');
                    $this->saveViolationsSection($request, $license);
                    break;
                case 'extension':
                    \Log::info('Processing extension section');
                    $this->saveExtensionSection($request, $license);
                    break;
                case 'notes':
                    \Log::info('Processing notes section');
                    $this->saveNotesSection($request, $license);
                    break;
                case 'complete_license':
                    // حفظ جميع الأقسام
                    \Log::info('Processing complete_license - all sections');
                    $this->saveCoordinationSection($request, $license);
                    $this->saveDigLicenseSection($request, $license);
                    $this->saveLabSection($request, $license);
                    $this->saveEvacuationsSection($request, $license);
                    $this->saveViolationsSection($request, $license);
                    $this->saveNotesSection($request, $license);
                    break;
                case 'complete_license_with_coordination':
                    // حفظ جميع الأقسام مع التركيز على شهادة التنسيق
                    \Log::info('Processing complete_license_with_coordination - all sections with coordination focus');
                    $this->saveCoordinationSection($request, $license);
                    $this->saveDigLicenseSection($request, $license);
                    $this->saveLabSection($request, $license);
                    $this->saveEvacuationsSection($request, $license);
                    $this->saveViolationsSection($request, $license);
                    $this->saveNotesSection($request, $license);
                    break;
                case 'lab_table1':
                case 'lab_table1_data':
                    // حفظ جدول الفسح ونوع الشارع للمختبر
                    \Log::info('Processing lab_table1 section');
                    $this->saveLabTable1Data($request, $license);
                    break;
                case 'lab_table2':
                case 'lab_table2_data':
                    // حفظ جدول التفاصيل الفنية للمختبر
                    \Log::info('Processing lab_table2 section');
                    $this->saveLabTable2Data($request, $license);
                    break;
                case 'evac_table1':
                case 'evac_table1_data':
                    // حفظ جدول فسح الإخلاءات
                    \Log::info('Processing evac_table1 section');
                    $this->saveEvacTable1Data($request, $license);
                    break;
                case 'evac_table2':
                case 'evac_table2_data':
                    // حفظ جدول التفاصيل الفنية للإخلاءات
                    \Log::info('Processing evac_table2 section');
                    $this->saveEvacTable2Data($request, $license);
                    break;
                case 'lab_tests':
                    // حفظ بيانات الاختبارات الجديدة
                    \Log::info('Processing lab_tests section');
                    $this->saveNewLabTests($request, $license);
                    break;
                default:
                    \Log::error('Unknown section type: ' . $sectionType);
                    return response()->json([
                        'success' => false,
                        'message' => 'نوع القسم غير معروف'
                    ], 400);
            }
            
            \Log::info('About to save license with ID: ' . ($license->id ?? 'new'));
            $license->save();
            \Log::info('License saved successfully with ID: ' . $license->id);
            
            // جلب آخر رخصة للعمل لضمان إرجاع آخر رقم رخصة
            $latestLicense = License::where('work_order_id', $workOrderId)
                                   ->orderBy('created_at', 'desc')
                                   ->first();
            
            // إضافة ملخص خاص لقسم الاختبارات الجديدة
            $response = [
                'success' => true,
                'message' => 'تم حفظ القسم بنجاح',
                'license_id' => $license->id,
                'latest_license_id' => $latestLicense ? $latestLicense->id : $license->id,
                'refresh_table' => true,
                'is_new' => $isNewLicense,
                'total_licenses' => License::where('work_order_id', $workOrderId)->count(),
            ];
            
            // إضافة ملخص خاص للاختبارات الجديدة
            if ($sectionType === 'lab_tests' && isset($request->totals)) {
                $response['summary'] = $request->totals;
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Error saving license section: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ القسم: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * حفظ قسم شهادة التنسيق
     */
    private function saveCoordinationSection(Request $request, License $license)
    {
        try {
            \Log::info('=== saveCoordinationSection started ===', [
                'license_id' => $license->id ?? 'new',
                'coordination_number' => $request->input('coordination_certificate_number'),
                'force_new' => $request->input('force_new', false)
            ]);
            
            // تعيين رقم الرخصة التلقائي إذا لم يكن موجوداً
            if (!$license->license_number) {
                $workOrderId = $license->work_order_id ?: $request->input('work_order_id');
                $licenseCount = License::where('work_order_id', $workOrderId)->count() + 1;
                $license->license_number = 'LIC-' . $workOrderId . '-' . str_pad($licenseCount, 3, '0', STR_PAD_LEFT);
            }
            
            // حفظ بيانات شهادة التنسيق
            $license->coordination_certificate_number = $request->input('coordination_certificate_number');
            $license->coordination_certificate_notes = $request->input('coordination_certificate_notes');
            $license->has_restriction = $request->input('has_restriction', 0) == '1';
            $license->restriction_authority = $request->input('restriction_authority');
            $license->restriction_reason = $request->input('restriction_reason');
            $license->restriction_notes = $request->input('restriction_notes');
            
            \Log::info('Coordination data set successfully', [
                'license_number' => $license->license_number,
                'coordination_certificate_number' => $license->coordination_certificate_number,
                'has_restriction' => $license->has_restriction
            ]);
            
            // معالجة ملفات شهادة التنسيق
            if ($request->hasFile('coordination_certificate_path')) {
                // حذف الملف القديم إذا كان موجوداً
                if ($license->coordination_certificate_path && \Storage::disk('public')->exists($license->coordination_certificate_path)) {
                    \Storage::disk('public')->delete($license->coordination_certificate_path);
                }
                
                $file = $request->file('coordination_certificate_path');
                $filename = time() . '_coordination_cert_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/coordination', $filename, 'public');
                $license->coordination_certificate_path = $path;
                
                \Log::info('Coordination certificate file uploaded', ['path' => $path]);
            }
            
            // معالجة ملفات الخطابات والتعهدات
            if ($request->hasFile('letters_commitments_files')) {
                // حذف الملفات القديمة إذا كانت موجودة
                if ($license->letters_commitments_file_path) {
                    $oldFiles = json_decode($license->letters_commitments_file_path, true);
                    if (is_array($oldFiles)) {
                        foreach ($oldFiles as $oldFile) {
                            if (\Storage::disk('public')->exists($oldFile)) {
                                \Storage::disk('public')->delete($oldFile);
                            }
                        }
                    }
                }
                
                $files = $request->file('letters_commitments_files');
                $filePaths = [];
                
                foreach ($files as $file) {
                    $filename = time() . '_' . uniqid() . '_letters_' . $file->getClientOriginalName();
                    $path = $file->storeAs('licenses/letters', $filename, 'public');
                    $filePaths[] = $path;
                }
                
                $license->letters_commitments_file_path = json_encode($filePaths);
                
                \Log::info('Letters and commitments files uploaded', ['count' => count($filePaths)]);
            }
            
            // تعيين تاريخ إنشاء الرخصة إذا لم يكن موجوداً
            if (!$license->license_date) {
                $license->license_date = now()->format('Y-m-d');
            }
            
            \Log::info('Coordination section saved successfully', [
                'license_id' => $license->id ?? 'new',
                'work_order_id' => $license->work_order_id,
                'license_date' => $license->license_date
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving coordination section: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * حفظ قسم رخص الحفر
     */
    private function saveDigLicenseSection(Request $request, License $license)
    {
        try {
            \Log::info('=== saveDigLicenseSection started ===', [
                'license_id' => $license->id ?? 'new',
                'request_data' => $request->all()
            ]);
            
            $license->license_number = $request->input('license_number');
            $license->license_date = $request->input('license_date');
            $license->license_type = $request->input('license_type');
            $license->license_value = $request->input('license_value');
            $license->extension_value = $request->input('extension_value');
            $license->excavation_length = $request->input('excavation_length');
            $license->excavation_width = $request->input('excavation_width');
            $license->excavation_depth = $request->input('excavation_depth');
            $license->license_start_date = $request->input('license_start_date');
            $license->license_end_date = $request->input('license_end_date');
            $license->extension_start_date = $request->input('extension_start_date');
            $license->extension_end_date = $request->input('extension_end_date');
            
            \Log::info('License fields set', [
                'license_number' => $license->license_number,
                'license_type' => $license->license_type,
                'license_value' => $license->license_value
            ]);
            
            // معالجة الملفات
            $this->handleDigLicenseFiles($request, $license);
            
            \Log::info('=== saveDigLicenseSection completed ===');
            
        } catch (\Exception $e) {
            \Log::error('Error in saveDigLicenseSection: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * حفظ قسم المختبر
     */
    private function saveLabSection(Request $request, License $license)
    {
        try {
            // تحديث حالة الاختبارات
            $license->has_depth_test = $request->boolean('has_depth_test');
            $license->has_soil_compaction_test = $request->boolean('has_soil_compaction_test');
            $license->has_rc1_mc1_test = $request->boolean('has_rc1_mc1_test');
            $license->has_asphalt_test = $request->boolean('has_asphalt_test');
            $license->has_soil_test = $request->boolean('has_soil_test');
            $license->has_interlock_test = $request->boolean('has_interlock_test');
            
            // حفظ بيانات الاختبارات
            if ($request->has('tests_data')) {
                $testsData = is_string($request->tests_data) ? 
                    json_decode($request->tests_data, true) : 
                    $request->tests_data;
                
                if (is_array($testsData)) {
                    $license->lab_tests_data = $testsData;
                    $license->updateLabTestsSummary();
                }
            }
            
            // حفظ التغييرات
            $license->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ بيانات المختبر بنجاح',
                'license' => $license
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving lab section: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * حفظ قسم الإخلاءات
     */
    private function saveEvacuationsSection(Request $request, License $license)
    {
        $license->is_evacuated = $request->input('is_evacuated', 0) == '1';
        $license->evac_license_number = $request->input('evac_license_number');
        $license->evac_license_value = $request->input('evac_license_value');
        $license->evac_payment_number = $request->input('evac_payment_number');
        $license->evac_date = $request->input('evac_date');
        $license->evac_amount = $request->input('evac_amount');
        
        // معالجة بيانات جدول الإخلاءات الأول
        if ($request->has('evac_table1_data')) {
            $evacTable1Data = json_decode($request->input('evac_table1_data'), true);
            if (!empty($evacTable1Data)) {
                $license->evac_table1_data = json_encode($evacTable1Data);
            }
        } elseif ($request->has('evac_table1')) {
            $evacTable1Data = $request->input('evac_table1');
            $cleanedTable1Data = [];
            
            foreach ($evacTable1Data as $row) {
                $cleanRow = array_filter($row, function($value) {
                    return !empty(trim($value));
                });
                
                if (!empty($cleanRow) && (isset($row['clearance_number']) || isset($row['clearance_date']))) {
                    $cleanedTable1Data[] = $row;
                }
            }
            
            $license->evac_table1_data = !empty($cleanedTable1Data) ? json_encode($cleanedTable1Data) : null;
        }
        
        // معالجة بيانات جدول الإخلاءات الثاني
        if ($request->has('evac_table2_data')) {
            $evacTable2Data = json_decode($request->input('evac_table2_data'), true);
            if (!empty($evacTable2Data)) {
                $license->evac_table2_data = json_encode($evacTable2Data);
            }
        } elseif ($request->has('evac_table2')) {
            $evacTable2Data = $request->input('evac_table2');
            $cleanedTable2Data = [];
            
            foreach ($evacTable2Data as $row) {
                $cleanRow = array_filter($row, function($value, $key) {
                    if (in_array($key, ['soil_compaction', 'mc1rc2', 'asphalt_compaction', 'is_dirt'])) {
                        return true;
                    }
                    return !empty(trim($value));
                }, ARRAY_FILTER_USE_BOTH);
                
                if (!empty($cleanRow) && (isset($row['year']) || isset($row['work_type']))) {
                    $cleanedTable2Data[] = $row;
                }
            }
            
            $license->evac_table2_data = !empty($cleanedTable2Data) ? json_encode($cleanedTable2Data) : null;
        }
        
        // معالجة ملفات الإخلاءات
        if ($request->hasFile('evacuations_files')) {
            $files = $request->file('evacuations_files');
            $filePaths = [];
            
            foreach ($files as $file) {
                $filename = time() . '_evacuation_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/evacuations', $filename, 'public');
                $filePaths[] = $path;
            }
            
            $license->evacuations_file_path = json_encode($filePaths);
        }
    }
    
    /**
     * حفظ قسم المخالفات
     */
    private function saveViolationsSection(Request $request, License $license)
    {
        // التحقق من وجود بيانات مخالفات للحفظ
        $violationData = [
            'violation_license_number' => $request->input('violation_license_number'),
            'violation_license_value' => $request->input('violation_license_value'),
            'violation_license_date' => $request->input('violation_license_date'),
            'violation_due_date' => $request->input('violation_due_date'),
            'violation_number' => $request->input('violation_number'),
            'violation_payment_number' => $request->input('violation_payment_number'),
            'violation_cause' => $request->input('violation_cause'),
        ];

        // التحقق من وجود أي بيانات مخالفات
        $hasViolationData = false;
        foreach ($violationData as $key => $value) {
            if (!empty($value)) {
                $hasViolationData = true;
                break;
            }
        }

        // حفظ المخالفة في جدول منفصل إذا كانت هناك بيانات
        if ($hasViolationData || $request->hasFile('violations_files')) {
            $violationFilePath = null;
            
            // معالجة ملفات المخالفات
            if ($request->hasFile('violations_files')) {
                $files = $request->file('violations_files');
                $filePaths = [];
                
                foreach ($files as $file) {
                    $filename = time() . '_violation_' . $file->getClientOriginalName();
                    $path = $file->storeAs('licenses/violations', $filename, 'public');
                    $filePaths[] = $path;
                }
                
                $violationFilePath = json_encode($filePaths);
            }

            // إنشاء سجل مخالفة جديد
            \App\Models\LicenseViolation::create([
                'license_id' => $license->id,
                'reported_by' => Auth::id(),
                'violation_type' => $violationData['violation_cause'] ?? 'غير محدد',
                'description' => $violationData['violation_cause'] ?? 'غير محدد',
                'violation_date' => $violationData['violation_license_date'] ?? now(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'violation_number' => $violationData['violation_number'],
                'violation_amount' => $violationData['violation_license_value'],
                'payment_due_date' => $violationData['violation_due_date'],
                'payment_invoice_number' => $violationData['violation_payment_number'],
                'attachment_path' => $violationFilePath,
            ]);

            // تحديث عدد المخالفات في الرخصة
            $license->updateViolationsCount();
        }
    }
    
    /**
     * حفظ قسم التمديدات
     */
    private function saveExtensionSection(Request $request, License $license)
    {
        // حفظ بيانات التمديد في جدول التمديدات
        if ($request->has(['extension_start_date', 'extension_end_date'])) {
            $startDate = $request->input('extension_start_date');
            $endDate = $request->input('extension_end_date');
            
            // حساب عدد أيام التمديد
            $daysCount = $this->calculateExtensionDays($startDate, $endDate);
            
            // تجهيز مصفوفة المرفقات
            $attachments = [];
            
            // معالجة ملفات التمديد
            if ($request->hasFile('extension_license_file')) {
                $extensionLicenseFile = $request->file('extension_license_file');
                $extensionLicensePath = $extensionLicenseFile->store('licenses/extensions/license_files', 'public');
                $attachments[] = $extensionLicensePath;
            }
            
            if ($request->hasFile('extension_payment_proof')) {
                $paymentProofFile = $request->file('extension_payment_proof');
                $paymentProofPath = $paymentProofFile->store('licenses/extensions/payment_proofs', 'public');
                $attachments[] = $paymentProofPath;
            }
            
            if ($request->hasFile('extension_bank_proof')) {
                $bankProofFile = $request->file('extension_bank_proof');
                $bankProofPath = $bankProofFile->store('licenses/extensions/bank_proofs', 'public');
                $attachments[] = $bankProofPath;
            }
            
            // تأكد من أن الرخصة لديها ID (محفوظة في قاعدة البيانات)
            if (!$license->id) {
                $license->save();
            }
            
            // إنشاء سجل تمديد جديد
            $extension = new \App\Models\LicenseExtension([
                'license_id' => $license->id,
                'days_count' => $daysCount,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'reason' => $request->input('extension_reason'),
                'attachments' => $attachments,
            ]);
            
            $extension->save();
            
            // تحديث بيانات التمديد في الرخصة
            $license->extension_value = $request->input('extension_value');
            $license->extension_start_date = $startDate;
            $license->extension_end_date = $endDate;
        }
        
        \Log::info('Extension section saved', [
            'license_id' => $license->id,
            'extension_value' => $license->extension_value
        ]);
    }

    /**
     * حفظ قسم الملاحظات الإضافية
     */
    private function saveNotesSection(Request $request, License $license)
    {
        $license->notes = $request->input('notes');
        
        // معالجة مرفقات الملاحظات
        if ($request->hasFile('notes_attachments')) {
            $files = $request->file('notes_attachments');
            $filePaths = [];
            
            foreach ($files as $file) {
                $filename = time() . '_notes_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/notes', $filename, 'public');
                $filePaths[] = $path;
            }
            
            $license->notes_attachments_path = json_encode($filePaths);
        }
    }
    
    /**
     * معالجة ملفات رخص الحفر
     */
    private function handleDigLicenseFiles(Request $request, License $license)
    {
        // معالجة ملف الرخصة الرئيسي
        if ($request->hasFile('license_file')) {
            // حذف الملف القديم
            if ($license->license_file_path && \Storage::disk('public')->exists($license->license_file_path)) {
                \Storage::disk('public')->delete($license->license_file_path);
            }
            
            $file = $request->file('license_file');
            $filename = time() . '_license_' . $file->getClientOriginalName();
            $path = $file->storeAs('licenses/files', $filename, 'public');
            $license->license_file_path = $path;
            
            \Log::info('License file uploaded', ['path' => $path]);
        }
        
        // معالجة إيصالات الدفع
        if ($request->hasFile('payment_invoices')) {
            // حذف الملفات القديمة
            if ($license->payment_invoices_path) {
                $oldFiles = json_decode($license->payment_invoices_path, true);
                if (is_array($oldFiles)) {
                    foreach ($oldFiles as $oldFile) {
                        if (\Storage::disk('public')->exists($oldFile)) {
                            \Storage::disk('public')->delete($oldFile);
                        }
                    }
                }
            }
            
            $files = $request->file('payment_invoices');
            $filePaths = [];
            
            foreach ($files as $file) {
                $filename = time() . '_' . uniqid() . '_invoice_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/invoices', $filename, 'public');
                $filePaths[] = $path;
            }
            
            $license->payment_invoices_path = json_encode($filePaths);
            \Log::info('Payment invoices uploaded', ['count' => count($filePaths)]);
        }
        
        // معالجة إثباتات الدفع
        if ($request->hasFile('payment_proof')) {
            // حذف الملفات القديمة
            if ($license->payment_proof_path) {
                $oldFiles = json_decode($license->payment_proof_path, true);
                if (is_array($oldFiles)) {
                    foreach ($oldFiles as $oldFile) {
                        if (\Storage::disk('public')->exists($oldFile)) {
                            \Storage::disk('public')->delete($oldFile);
                        }
                    }
                }
            }
            
            $files = $request->file('payment_proof');
            $filePaths = [];
            
            foreach ($files as $file) {
                $filename = time() . '_' . uniqid() . '_proof_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/proof', $filename, 'public');
                $filePaths[] = $path;
            }
            
            $license->payment_proof_path = json_encode($filePaths);
            \Log::info('Payment proofs uploaded', ['count' => count($filePaths)]);
        }
        
        // معالجة ملفات التفعيل
        if ($request->hasFile('license_activation')) {
            // حذف الملفات القديمة
            if ($license->license_activation_path) {
                $oldFiles = json_decode($license->license_activation_path, true);
                if (is_array($oldFiles)) {
                    foreach ($oldFiles as $oldFile) {
                        if (\Storage::disk('public')->exists($oldFile)) {
                            \Storage::disk('public')->delete($oldFile);
                        }
                    }
                }
            }
            
            $files = $request->file('license_activation');
            $filePaths = [];
            
            foreach ($files as $file) {
                $filename = time() . '_' . uniqid() . '_activation_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/activation', $filename, 'public');
                $filePaths[] = $path;
            }
            
            $license->license_activation_path = json_encode($filePaths);
            \Log::info('License activation files uploaded', ['count' => count($filePaths)]);
        }
    }
    
    /**
     * معالجة ملفات المختبر
     */
    private function handleLabFiles(Request $request, License $license)
    {
        $labFiles = [
            'depth_test_file_path' => 'depth_test_file_path',
            'soil_compaction_file_path' => 'soil_compaction_file_path',
            'rc1_mc1_file_path' => 'rc1_mc1_file_path',
            'asphalt_test_file_path' => 'asphalt_test_file_path',
            'soil_test_file_path' => 'soil_test_file_path',
            'interlock_test_file_path' => 'interlock_test_file_path'
        ];
        
        foreach ($labFiles as $fileField => $dbField) {
            if ($request->hasFile($fileField)) {
                $file = $request->file($fileField);
                $filename = time() . '_' . $fileField . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('licenses/lab', $filename, 'public');
                $license->{$dbField} = $path;
            }
        }
    }

    /**
     * تصدير تفاصيل الرخصة كملف PDF
     */
    public function exportPdf(License $license)
    {
        try {
            $license->load('workOrder');
            
            // استخدام DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.licenses.pdf', compact('license'))
                ->setPaper('A4', 'portrait')
                ->setOption('defaultFont', 'DejaVu Sans')
                ->setOption('isRemoteEnabled', true)
                ->setOption('isHtml5ParserEnabled', true);
            
            $filename = 'license_' . ($license->license_number ?? $license->id) . '_' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage(), [
                'license_id' => $license->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير ملف PDF: ' . $e->getMessage());
        }
    }

    /**
     * حذف ملف إخلاءات محدد
     */
    public function removeEvacuationFile(Request $request, License $license)
    {
        try {
            $fileIndex = $request->input('file_index');
            
            if (!is_numeric($fileIndex)) {
                return response()->json(['success' => false, 'message' => 'فهرس الملف غير صحيح']);
            }
            
            $evacuationFiles = json_decode($license->evacuations_file_path, true) ?? [];
            
            if (!isset($evacuationFiles[$fileIndex])) {
                return response()->json(['success' => false, 'message' => 'الملف غير موجود']);
            }
            
            // حذف الملف من التخزين
            $filePath = $evacuationFiles[$fileIndex];
            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }
            
            // إزالة الملف من المصفوفة
            unset($evacuationFiles[$fileIndex]);
            
            // إعادة ترقيم المصفوفة
            $evacuationFiles = array_values($evacuationFiles);
            
            // تحديث قاعدة البيانات
            $license->evacuations_file_path = count($evacuationFiles) > 0 ? json_encode($evacuationFiles) : null;
            $license->save();
            
            return response()->json(['success' => true, 'message' => 'تم حذف الملف بنجاح']);
            
        } catch (\Exception $e) {
            \Log::error('Error removing evacuation file: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف الملف']);
        }
    }

    /**
     * تحديث بيانات الفسح للإخلاء
     */
    public function updateEvacStreets(Request $request, WorkOrder $workOrder)
    {
        try {
            // البحث عن آخر رخصة للعمل أو إنشاء واحدة جديدة
            $license = $workOrder->licenses()->latest()->first();
            if (!$license) {
                $license = new License();
                $license->work_order_id = $workOrder->id;
                $license->save();
            }

            $evacStreetsData = $request->input('evac_streets');
            if (is_array($evacStreetsData)) {
                $license->evac_table1_data = json_encode($evacStreetsData);
                $license->save();
                
                return response()->json(['message' => 'تم تحديث بيانات الفسح بنجاح']);
            }

            return response()->json(['message' => 'البيانات المرسلة غير صحيحة'], 400);
        } catch (\Exception $e) {
            \Log::error('Error updating evacuation streets: ' . $e->getMessage());
            return response()->json(['message' => 'حدث خطأ أثناء تحديث بيانات الفسح'], 500);
        }
    }

    /**
     * حفظ بيانات الإخلاءات التفصيلية
     */
    public function saveEvacuationDataSimple(Request $request)
    {
        try {
            $license = License::findOrFail($request->license_id);
            
            // تحويل بيانات الإخلاء من JSON إلى مصفوفة
            $evacuationDataArray = json_decode($request->evacuation_data, true);
            
            // التأكد من أن البيانات في تنسيق array صحيح
            if (!is_array($evacuationDataArray)) {
                throw new \Exception('بيانات الإخلاء غير صحيحة');
            }
            
            // معالجة بيانات الإخلاء (بدون مرفقات في الجدول)
            $processedEvacuationData = $evacuationDataArray;
            
            // معالجة المرفق المنفصل
            $evacuationAttachmentPath = null;
            if ($request->hasFile('evacuation_attachment')) {
                $file = $request->file('evacuation_attachment');
                
                // التأكد من وجود المجلد
                if (!\Storage::disk('public')->exists('licenses/evacuations')) {
                    \Storage::disk('public')->makeDirectory('licenses/evacuations');
                }
                
                // حفظ الملف
                if ($file->isValid()) {
                    $filename = time() . '_evacuation_attachment_' . $file->getClientOriginalName();
                    $evacuationAttachmentPath = $file->storeAs('licenses/evacuations', $filename, 'public');
                }
            }
            
            // الحصول على البيانات الإضافية الحالية أو إنشاء مصفوفة فارغة
            $additionalDetails = $license->additional_details ? json_decode($license->additional_details, true) : [];
            
            // تحديث بيانات الإخلاء
            $additionalDetails['evacuation_data'] = $processedEvacuationData;
            
            // إضافة المرفق الجديد إذا تم رفعه
            if ($evacuationAttachmentPath) {
                if (!isset($additionalDetails['evacuation_attachments'])) {
                    $additionalDetails['evacuation_attachments'] = [];
                }
                $additionalDetails['evacuation_attachments'][] = [
                    'path' => $evacuationAttachmentPath,
                    'name' => $file->getClientOriginalName(),
                    'uploaded_at' => now()->format('Y-m-d H:i:s')
                ];
            }
            
            $license->additional_details = json_encode($additionalDetails, JSON_UNESCAPED_UNICODE);
            $license->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ البيانات بنجاح',
                'license_name' => $license->license_number
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Simple save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب مرفقات الإخلاء
     */
    public function getEvacuationAttachments($licenseId)
    {
        try {
            $license = License::findOrFail($licenseId);
            $additionalDetails = $license->additional_details ? json_decode($license->additional_details, true) : [];
            
            $attachments = [];
            if (isset($additionalDetails['evacuation_attachments']) && is_array($additionalDetails['evacuation_attachments'])) {
                foreach ($additionalDetails['evacuation_attachments'] as $attachment) {
                    $attachments[] = [
                        'name' => $attachment['name'] ?? 'مرفق إخلاء',
                        'path' => $attachment['path'] ?? '',
                        'url' => \Storage::disk('public')->url($attachment['path'] ?? ''),
                        'download_url' => \Storage::disk('public')->url($attachment['path'] ?? ''),
                        'date' => isset($attachment['uploaded_at']) ? 
                            \Carbon\Carbon::parse($attachment['uploaded_at'])->format('Y-m-d H:i') : 
                            'غير محدد'
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'attachments' => $attachments
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching evacuation attachments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطأ في تحميل المرفقات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض مرفق الإخلاء
     */
    public function showEvacuationFile($licenseId, $index)
    {
        try {
            $license = License::findOrFail($licenseId);
            $additionalDetails = json_decode($license->additional_details, true);
            
            if (isset($additionalDetails['evacuation_data'][$index]['evacuation_file'])) {
                $filePath = $additionalDetails['evacuation_data'][$index]['evacuation_file'];
                
                if (Storage::disk('public')->exists($filePath)) {
                    return response()->file(storage_path('app/public/' . $filePath));
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'الملف غير موجود'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في عرض الملف'
            ], 500);
        }
    }

    public function saveEvacuationData(Request $request)
    {
        try {
            // طباعة تفاصيل الطلب للتشخيص
            \Log::info('saveEvacuationData request received', [
                'evacuation_data_type' => gettype($request->evacuation_data),
                'files_count' => count($request->allFiles()),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method(),
                'all_inputs' => array_keys($request->all()),
                'detailed_file_structure' => array_map(function($files) {
                    if (is_array($files)) {
                        return array_map(function($file) {
                            return ($file && $file instanceof \Illuminate\Http\UploadedFile) ? $file->getClientOriginalName() : 'unknown';
                        }, $files);
                    }
                    return ($files && $files instanceof \Illuminate\Http\UploadedFile) ? $files->getClientOriginalName() : 'unknown';
                }, $request->allFiles())
            ]);

            // التحقق من صحة البيانات الأساسية
            $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'license_id' => 'required|exists:licenses,id',
                'evacuation_data' => 'required'
            ]);

            $license = License::findOrFail($request->license_id);
            
            // التحقق من وجود الرخصة
            if (!$license) {
                throw new \Exception('الرخصة غير موجودة');
            }
            
            // تحويل بيانات الإخلاء إلى array
            $evacuationDataArray = $request->evacuation_data;
            
            // إذا كانت البيانات string، حولها إلى array
            if (is_string($evacuationDataArray)) {
                $evacuationDataArray = json_decode($evacuationDataArray, true);
            }
            
            // التأكد من أن البيانات في تنسيق array صحيح
            if (!is_array($evacuationDataArray)) {
                throw new \Exception('بيانات الإخلاء غير صحيحة');
            }
            
            \Log::info('Evacuation data received', [
                'license_id' => $request->license_id,
                'work_order_id' => $request->work_order_id,
                'evacuation_count' => count($evacuationDataArray),
                'evacuation_data_raw' => is_string($request->evacuation_data) ? $request->evacuation_data : 'array',
                'evacuation_data_type' => gettype($request->evacuation_data),
                'evacuation_data_parsed' => $evacuationDataArray,
                'has_files' => $request->hasFile('evacuation_data'),
                'all_files' => array_keys($request->allFiles()),
                'all_inputs' => array_keys($request->all()),
                'request_files_detail' => $this->getFileDetails($request),
                'detailed_files' => $request->allFiles()
            ]);
            
            // معالجة المرفقات لكل سجل إخلاء
            $processedEvacuationData = [];
            $attachmentIndex = 0; // مؤشر للمرفقات
            
            foreach ($evacuationDataArray as $dataIndex => $evacuation) {
                $processedEvacuation = $evacuation;
                
                // معالجة المرفقات إذا كانت موجودة - البحث بعدة تنسيقات
                $attachmentKeys = [
                    "evacuation_data.{$dataIndex}.attachments",
                    "evacuation_data[{$dataIndex}][attachments]",
                    "evacuation_data[{$attachmentIndex}][attachments]",
                    "evacuation_data_{$dataIndex}_attachments",
                    "evacuation_data_{$attachmentIndex}_attachments"
                ];
                
                \Log::info("Searching for attachments for evacuation {$dataIndex}", [
                    'attachment_index' => $attachmentIndex,
                    'searching_keys' => $attachmentKeys,
                    'all_file_keys' => array_keys($request->allFiles()),
                    'detailed_file_keys' => array_map(function($key) use ($request) {
                        $files = $request->file($key);
                        if (is_array($files)) {
                            return [
                                'key' => $key,
                                'count' => count($files),
                                'files' => array_map(function($file) {
                                    return ($file && $file instanceof \Illuminate\Http\UploadedFile) ? $file->getClientOriginalName() : 'null';
                                }, $files)
                            ];
                        }
                        return [
                            'key' => $key,
                            'count' => 1,
                            'file' => ($files && $files instanceof \Illuminate\Http\UploadedFile) ? $files->getClientOriginalName() : 'null'
                        ];
                    }, array_keys($request->allFiles()))
                ]);
                
                $attachmentFiles = null;
                foreach ($attachmentKeys as $key) {
                    if ($request->hasFile($key)) {
                        $attachmentFiles = $request->file($key);
                        \Log::info("Found attachments with key: {$key}", [
                            'file_count' => is_array($attachmentFiles) ? count($attachmentFiles) : 1
                        ]);
                        break;
                    }
                }
                
                // إذا لم توجد المرفقات، جرب البحث في جميع الملفات المرسلة
                if (!$attachmentFiles) {
                    foreach ($request->allFiles() as $key => $files) {
                        if (strpos($key, 'evacuation_data') !== false && 
                            strpos($key, 'attachments') !== false && 
                            (strpos($key, "[{$dataIndex}]") !== false || strpos($key, "[{$attachmentIndex}]") !== false)) {
                            $attachmentFiles = $files;
                            \Log::info("Found attachments with alternate search, key: {$key}", [
                                'file_count' => is_array($attachmentFiles) ? count($attachmentFiles) : 1
                            ]);
                            break;
                        }
                    }
                }
                
                // إذا لم توجد المرفقات بعد، جرب البحث المرن في جميع الملفات
                if (!$attachmentFiles) {
                    $allEvacuationFiles = [];
                    foreach ($request->allFiles() as $key => $files) {
                        if (strpos($key, 'evacuation_data') !== false && strpos($key, 'attachments') !== false) {
                            $allEvacuationFiles[$key] = $files;
                        }
                    }
                    
                    if (count($allEvacuationFiles) > 0) {
                        \Log::info("Found evacuation files for flexible matching", [
                            'available_files' => array_keys($allEvacuationFiles),
                            'looking_for_index' => $attachmentIndex
                        ]);
                        
                        // استخدام المرفقات بالترتيب
                        $fileKeys = array_keys($allEvacuationFiles);
                        if (isset($fileKeys[$attachmentIndex])) {
                            $attachmentFiles = $allEvacuationFiles[$fileKeys[$attachmentIndex]];
                            \Log::info("Using flexible matching for evacuation {$dataIndex}", [
                                'used_key' => $fileKeys[$attachmentIndex],
                                'file_count' => is_array($attachmentFiles) ? count($attachmentFiles) : 1
                            ]);
                        }
                    }
                }
                
                // إذا لم نجد المرفقات بعد، جرب البحث بناءً على pattern matching
                if (!$attachmentFiles) {
                    foreach ($request->allFiles() as $key => $files) {
                        // البحث عن أي مفتاح يحتوي على evacuation_data و attachments
                        if (preg_match('/evacuation_data\[(\d+)\]\[attachments\]/', $key, $matches)) {
                            $foundIndex = intval($matches[1]);
                            // استخدام المرفقات إذا كان الفهرس يطابق أي من الفهارس المتوقعة
                            if ($foundIndex === $dataIndex || $foundIndex === $attachmentIndex) {
                                $attachmentFiles = $files;
                                \Log::info("Found attachments using regex pattern for evacuation {$dataIndex}", [
                                    'found_key' => $key,
                                    'found_index' => $foundIndex,
                                    'file_count' => is_array($attachmentFiles) ? count($attachmentFiles) : 1
                                ]);
                                break;
                            }
                        }
                    }
                }
                
                // الطريقة الأخيرة: إذا لم نجد المرفقات بعد، استخدم أي مرفق متاح
                if (!$attachmentFiles && $attachmentIndex === 0) {
                    $allEvacuationFiles = [];
                    foreach ($request->allFiles() as $key => $files) {
                        if (strpos($key, 'evacuation_data') !== false && strpos($key, 'attachments') !== false) {
                            $allEvacuationFiles[] = ['key' => $key, 'files' => $files];
                        }
                    }
                    
                    if (count($allEvacuationFiles) > 0) {
                        $attachmentFiles = $allEvacuationFiles[0]['files'];
                        \Log::info("Using fallback: first available evacuation files for evacuation {$dataIndex}", [
                            'used_key' => $allEvacuationFiles[0]['key'],
                            'file_count' => is_array($attachmentFiles) ? count($attachmentFiles) : 1
                        ]);
                    }
                }
                
                if ($attachmentFiles) {
                    $attachmentPaths = [];
                    
                    // التأكد من وجود المجلد
                    if (!\Storage::disk('public')->exists('licenses/evacuations')) {
                        \Storage::disk('public')->makeDirectory('licenses/evacuations');
                    }
                    
                    foreach ($attachmentFiles as $fileIndex => $file) {
                        // التحقق من صحة الملف
                        if ($file->isValid()) {
                            $sanitizedFileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                            $filename = time() . '_' . $dataIndex . '_' . $fileIndex . '_' . $sanitizedFileName;
                            $path = $file->storeAs('licenses/evacuations', $filename, 'public');
                            $attachmentPaths[] = $path;
                                        } else {
                    \Log::warning("Invalid file uploaded for evacuation {$dataIndex}", [
                        'file_error' => $file->getError(),
                        'file_name' => $file->getClientOriginalName()
                    ]);
                }
            }
            
            $processedEvacuation['attachments'] = $attachmentPaths;
            
            \Log::info("Processed attachments for evacuation {$dataIndex}", [
                'attachment_count' => count($attachmentPaths),
                'files' => $attachmentPaths
            ]);
        } else {
            \Log::warning("No attachments found for evacuation {$dataIndex}", [
                'attachment_index' => $attachmentIndex,
                'all_file_keys' => array_keys($request->allFiles())
            ]);
            
            // إذا لم توجد مرفقات جديدة، احتفظ بالمرفقات الموجودة إن وجدت
            $additionalDetailsTemp = $license->additional_details ? json_decode($license->additional_details, true) : [];
            $existingEvacuationDataTemp = $additionalDetailsTemp['evacuation_data'] ?? [];
            
            if (isset($existingEvacuationDataTemp[$dataIndex]['attachments']) 
                && !empty($existingEvacuationDataTemp[$dataIndex]['attachments'])) {
                $processedEvacuation['attachments'] = $existingEvacuationDataTemp[$dataIndex]['attachments'];
                
                \Log::info("Preserving existing attachments for evacuation {$dataIndex} (no new files)", [
                    'existing_attachments' => $existingEvacuationDataTemp[$dataIndex]['attachments']
                ]);
            }
        }
        
        $processedEvacuationData[] = $processedEvacuation;
        $attachmentIndex++;
    }
            
            // الحصول على البيانات الإضافية الحالية أو إنشاء مصفوفة فارغة
            $additionalDetails = $license->additional_details ? json_decode($license->additional_details, true) : [];
            
            // الحصول على بيانات الإخلاء الموجودة للحفاظ على المرفقات
            $existingEvacuationData = $additionalDetails['evacuation_data'] ?? [];
            
            // دمج البيانات الجديدة مع المرفقات الموجودة
            $mergedEvacuationData = [];
            
            foreach ($processedEvacuationData as $index => $newEvacuation) {
                $mergedEvacuation = $newEvacuation;
                
                // إذا لم تكن هناك مرفقات جديدة في البيانات المرسلة، احتفظ بالمرفقات الموجودة
                if ((!isset($newEvacuation['attachments']) || empty($newEvacuation['attachments'])) 
                    && isset($existingEvacuationData[$index]['attachments']) 
                    && !empty($existingEvacuationData[$index]['attachments'])) {
                    $mergedEvacuation['attachments'] = $existingEvacuationData[$index]['attachments'];
                    
                    \Log::info("Preserving existing attachments for evacuation {$index}", [
                        'existing_attachments' => $existingEvacuationData[$index]['attachments'],
                        'new_data_keys' => array_keys($newEvacuation)
                    ]);
                }
                
                $mergedEvacuationData[] = $mergedEvacuation;
            }
            
            // إذا كان هناك بيانات إخلاء موجودة أكثر من الجديدة، احتفظ بها
            if (count($existingEvacuationData) > count($processedEvacuationData)) {
                for ($i = count($processedEvacuationData); $i < count($existingEvacuationData); $i++) {
                    $mergedEvacuationData[] = $existingEvacuationData[$i];
                    \Log::info("Preserving existing evacuation data at index {$i}");
                }
            }
            
            // حفظ البيانات المدموجة
            $additionalDetails['evacuation_data'] = $mergedEvacuationData;
            
            \Log::info('Merging evacuation data', [
                'new_count' => count($processedEvacuationData),
                'existing_count' => count($existingEvacuationData),
                'merged_count' => count($mergedEvacuationData),
                'evacuation_data' => $processedEvacuationData,
                'merged_data' => $mergedEvacuationData,
                'additional_details_before' => $license->additional_details,
                'additional_details_after_prep' => json_encode($additionalDetails, JSON_UNESCAPED_UNICODE)
            ]);
            
            // حفظ البيانات الإضافية المحدثة
            $license->additional_details = json_encode($additionalDetails, JSON_UNESCAPED_UNICODE);
            $saved = $license->save();
            
            // التحقق من الحفظ
            $license->refresh(); // إعادة تحميل البيانات من قاعدة البيانات
            \Log::info('Data saved verification', [
                'save_result' => $saved,
                'license_id' => $license->id,
                'merged_data_count' => count($mergedEvacuationData),
                'additional_details_saved' => $license->additional_details,
                'evacuation_data_in_db' => $license->additional_details ? json_decode($license->additional_details, true)['evacuation_data'] ?? 'NOT FOUND' : 'NULL'
            ]);

            \Log::info('Evacuation data saved successfully', [
                'license_id' => $license->id,
                'evacuation_count' => count($mergedEvacuationData),
                'attachments_count' => array_sum(array_map(function($item) {
                    return isset($item['attachments']) ? count($item['attachments']) : 0;
                }, $mergedEvacuationData))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ بيانات الإخلاءات بنجاح',
                'license_name' => $license->license_number
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in saveEvacuationData', [
                'errors' => $e->errors(),
                'request_data' => [
                    'work_order_id' => $request->work_order_id,
                    'license_id' => $request->license_id,
                    'evacuation_data_type' => gettype($request->evacuation_data),
                    'evacuation_data' => is_string($request->evacuation_data) ? $request->evacuation_data : 'array',
                    'has_evacuation_data' => $request->has('evacuation_data'),
                    'evacuation_data_length' => is_string($request->evacuation_data) ? strlen($request->evacuation_data) : (is_array($request->evacuation_data) ? count($request->evacuation_data) : 0),
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors())),
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error saving evacuation data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'work_order_id' => $request->work_order_id,
                    'license_id' => $request->license_id,
                    'evacuation_data_type' => gettype($request->evacuation_data),
                    'evacuation_data_length' => is_string($request->evacuation_data) ? strlen($request->evacuation_data) : (is_array($request->evacuation_data) ? count($request->evacuation_data) : 0),
                    'has_files' => $request->hasFile('evacuation_data')
                ]
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ بيانات الإخلاءات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * استرجاع بيانات الإخلاءات التفصيلية
     */
    public function getEvacuationData($licenseId)
    {
        try {
            $license = License::findOrFail($licenseId);
            
            $evacuationData = [];
            if ($license->additional_details) {
                $additionalDetails = json_decode($license->additional_details, true);
                
                // التأكد من أن البيانات في تنسيق مصفوفة
                if (is_array($additionalDetails) && isset($additionalDetails['evacuation_data'])) {
                    $evacuationData = $additionalDetails['evacuation_data'];
                    
                    // التأكد من أن بيانات الإخلاءات في تنسيق مصفوفة
                    if (!is_array($evacuationData)) {
                        $evacuationData = [];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'evacuation_data' => $evacuationData,
                'license_number' => $license->license_number
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting evacuation data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في استرجاع بيانات الإخلاءات',
                'evacuation_data' => []
            ], 500);
        }
    }

    /**
     * دالة مساعدة لفهم تفاصيل الملفات المرسلة
     */
    private function getFileDetails($request)
    {
        $details = [];
        foreach ($request->allFiles() as $key => $files) {
            if (is_array($files)) {
                $details[$key] = count($files) . ' files';
            } else {
                $details[$key] = '1 file';
            }
        }
        return $details;
    }

    /**
     * حفظ بيانات جدول الفسح ونوع الشارع للمختبر
     */
    private function saveLabTable1Data(Request $request, License $license)
    {
        try {
            $tableData = $request->input('data');
            
            if (is_array($tableData) && count($tableData) > 0) {
                $license->lab_table1_data = json_encode($tableData, JSON_UNESCAPED_UNICODE);
                \Log::info('Lab table1 data saved', ['license_id' => $license->id, 'data_count' => count($tableData)]);
            } else {
                $license->lab_table1_data = null;
                \Log::info('Lab table1 data cleared', ['license_id' => $license->id]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error saving lab table1 data: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * حفظ بيانات جدول التفاصيل الفنية للمختبر
     */
    private function saveLabTable2Data(Request $request, License $license)
    {
        try {
            $tableData = $request->input('data');
            
            if (is_array($tableData) && count($tableData) > 0) {
                $license->lab_table2_data = json_encode($tableData, JSON_UNESCAPED_UNICODE);
                \Log::info('Lab table2 data saved', ['license_id' => $license->id, 'data_count' => count($tableData)]);
            } else {
                $license->lab_table2_data = null;
                \Log::info('Lab table2 data cleared', ['license_id' => $license->id]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error saving lab table2 data: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * حفظ بيانات جدول فسح الإخلاءات
     */
    private function saveEvacTable1Data(Request $request, License $license)
    {
        try {
            $tableData = $request->input('data');
            
            if (is_array($tableData) && count($tableData) > 0) {
                $license->evac_table1_data = json_encode($tableData, JSON_UNESCAPED_UNICODE);
                \Log::info('Evac table1 data saved', ['license_id' => $license->id, 'data_count' => count($tableData)]);
            } else {
                $license->evac_table1_data = null;
                \Log::info('Evac table1 data cleared', ['license_id' => $license->id]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error saving evac table1 data: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * حفظ بيانات جدول التفاصيل الفنية للإخلاءات
     */
    private function saveEvacTable2Data(Request $request, License $license)
    {
        try {
            $tableData = $request->input('data');
            
            if (is_array($tableData) && count($tableData) > 0) {
                $license->evac_table2_data = json_encode($tableData, JSON_UNESCAPED_UNICODE);
                \Log::info('Evac table2 data saved', ['license_id' => $license->id, 'data_count' => count($tableData)]);
            } else {
                $license->evac_table2_data = null;
                \Log::info('Evac table2 data cleared', ['license_id' => $license->id]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error saving evac table2 data: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * حفظ حالة اختبار المختبر ديناميكياً
     */
    public function saveLabTestStatus(Request $request)
    {
        try {
            $request->validate([
                'license_id' => 'required|exists:licenses,id',
                'test_field' => 'required|string',
                'status' => 'required|in:passed,failed',
                'value' => 'nullable|numeric|min:0',
                'result_text' => 'nullable|string|max:255'
            ]);

            $license = License::findOrFail($request->license_id);
            
            // تحديث حالة الاختبار
            $testField = $request->test_field;
            $license->$testField = ($request->status === 'passed');
            
            // تحديث قيمة الاختبار إذا تم توفيرها
            if ($request->has('value')) {
                $valueField = str_replace('has_', '', $testField) . '_value';
                $license->$valueField = $request->value;
            }
            
            // تحديث نتيجة الاختبار إذا تم توفيرها
            if ($request->has('result_text')) {
                $resultField = str_replace('has_', '', $testField) . '_result';
                $license->$resultField = $request->result_text;
            }
            
            $license->save();
            
            // إعادة حساب إجمالي القيم
            $totals = $this->calculateTestTotals($license);
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ حالة الاختبار بنجاح',
                'test_status' => $request->status,
                'totals' => $totals
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving lab test status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ حالة الاختبار: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع مرفق اختبار المختبر
     */
    public function uploadLabTestFile(Request $request)
    {
        try {
            $request->validate([
                'license_id' => 'required|exists:licenses,id',
                'test_field' => 'required|string',
                'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
            ]);

            $license = License::findOrFail($request->license_id);
            
            if ($request->hasFile('file')) {
                // رفع الملف الجديد
                $file = $request->file('file');
                $filename = time() . '_lab_test_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('licenses/lab_tests', $filename, 'public');
                
                // إنشاء URL كامل للملف
                $fileUrl = asset('storage/' . $filePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع المرفق بنجاح',
                    'file_path' => $filePath,
                    'file_url' => $fileUrl,
                    'file_name' => $filename
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على ملف للرفع'
            ], 400);
            
        } catch (\Exception $e) {
            \Log::error('Error uploading lab test file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع المرفق: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف مرفق اختبار المختبر
     */
    public function deleteLabTestFile(Request $request)
    {
        try {
            $request->validate([
                'license_id' => 'required|exists:licenses,id',
                'test_field' => 'required|string'
            ]);

            $license = License::findOrFail($request->license_id);
            
            $testField = str_replace('has_', '', $request->test_field);
            $fileField = $testField . '_file_path';
            
            if ($license->$fileField) {
                // حذف الملف من التخزين
                if (Storage::disk('public')->exists($license->$fileField)) {
                    Storage::disk('public')->delete($license->$fileField);
                }
                
                // حذف مسار الملف من قاعدة البيانات
                $license->$fileField = null;
                $license->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف المرفق بنجاح'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد مرفق لحذفه'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting lab test file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المرفق: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حساب إجمالي قيم الاختبارات
     */
    private function calculateTestTotals(License $license)
    {
        $basicTests = [
            'has_depth_test' => 'depth_test_value',
            'has_soil_compaction_test' => 'soil_compaction_test_value', 
            'has_rc1_mc1_test' => 'rc1_mc1_test_value',
            'has_asphalt_test' => 'asphalt_test_value',
            'has_soil_test' => 'soil_test_value',
            'has_interlock_test' => 'interlock_test_value'
        ];
        
        $passedCount = 0;
        $failedCount = 0;
        $passedValue = 0;
        $failedValue = 0;
        
        foreach ($basicTests as $testField => $valueField) {
            $testStatus = $license->$testField;
            $testValue = floatval($license->$valueField ?? 0);
            
            if ($testStatus === true) {
                $passedCount++;
                $passedValue += $testValue;
            } elseif ($testStatus === false) {
                $failedCount++;
                $failedValue += $testValue;
            }
        }
        
        // تحديث إجمالي القيم في قاعدة البيانات
        $license->successful_tests_value = $passedValue;
        $license->failed_tests_value = $failedValue;
        $license->save();
        
        return [
            'passed_count' => $passedCount,
            'failed_count' => $failedCount,
            'passed_value' => $passedValue,
            'failed_value' => $failedValue,
            'total_tests' => $passedCount + $failedCount
        ];
    }

    /**
     * حفظ بيانات الاختبارات الجديدة
     */
    private function saveNewLabTests(Request $request, License $license)
    {
        try {
            $testsData = $request->input('tests_data', []);
            $totals = $request->input('totals', []);
            
            // تحديث بيانات الاختبارات
            $license->lab_tests_data = !empty($testsData) ? json_encode($testsData) : null;
            
            // تحديث الإجماليات
            $license->successful_tests_value = $totals['passed_amount'] ?? 0;
            $license->failed_tests_value = $totals['failed_amount'] ?? 0;
            $license->total_tests_count = $totals['total_tests'] ?? 0;
            $license->total_tests_amount = $totals['total_amount'] ?? 0;
            
            \Log::info('Lab tests data updated', [
                'license_id' => $license->id,
                'tests_count' => is_array($testsData) ? count($testsData) : 0,
                'total_amount' => $totals['total_amount'] ?? 0
            ]);
            
            return $license;
            
        } catch (\Exception $e) {
            \Log::error('Error saving lab tests: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * تحميل ملفات الرخص
     */
    public function downloadFile(License $license, $type)
    {
        try {
            $filePath = null;
            
            switch ($type) {
                case 'license':
                    $filePath = $license->license_file_path;
                    break;
                case 'coordination':
                    $filePath = $license->coordination_certificate_path;
                    break;
                case 'commitments':
                    $filePath = $license->letters_commitments_file_path;
                    break;
                case 'activation':
                    $filePath = $license->activation_file_path;
                    break;
                case 'payment_invoices':
                    $filePath = $license->payment_invoices_path;
                    break;
                case 'bank_payment':
                    $filePath = $license->bank_payment_proof_path;
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'نوع الملف غير مدعوم'], 400);
            }
            
            if (!$filePath) {
                return response()->json(['success' => false, 'message' => 'الملف غير موجود'], 404);
            }
            
            // التعامل مع الملفات المتعددة
            if (in_array($type, ['payment_invoices', 'bank_payment'])) {
                $files = json_decode($filePath, true);
                if (is_array($files) && count($files) > 0) {
                    $filePath = $files[0]; // أول ملف
                } else {
                    return response()->json(['success' => false, 'message' => 'الملف غير موجود'], 404);
                }
            }
            
            if (Storage::disk('public')->exists($filePath)) {
                return response()->file(storage_path('app/public/' . $filePath));
            }
            
            return response()->json(['success' => false, 'message' => 'الملف غير موجود'], 404);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'خطأ في تحميل الملف'], 500);
        }
    }

    /**
     * حذف مرفق إخلاء محدد
     */
    public function deleteEvacuationAttachment(License $license, $index)
    {
        try {
            $additionalDetails = $license->additional_details ? json_decode($license->additional_details, true) : [];
            
            if (!isset($additionalDetails['evacuation_attachments']) || !isset($additionalDetails['evacuation_attachments'][$index])) {
                return response()->json(['success' => false, 'message' => 'المرفق غير موجود']);
            }

            // حذف الملف من التخزين
            $attachment = $additionalDetails['evacuation_attachments'][$index];
            if (\Storage::disk('public')->exists($attachment['path'])) {
                \Storage::disk('public')->delete($attachment['path']);
            }

            // حذف المرفق من المصفوفة
            array_splice($additionalDetails['evacuation_attachments'], $index, 1);

            // تحديث قاعدة البيانات
            $license->additional_details = json_encode($additionalDetails, JSON_UNESCAPED_UNICODE);
            $license->save();

            return response()->json(['success' => true, 'message' => 'تم حذف المرفق بنجاح']);

        } catch (\Exception $e) {
            \Log::error('Error deleting evacuation attachment: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف المرفق']);
        }
    }
} 