<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
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
        $query = License::with('workOrder');

        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('license_number', 'like', '%' . $search . '%')
                  ->orWhere('license_type', 'like', '%' . $search . '%')
                  ->orWhereHas('workOrder', function($q) use ($search) {
                      $q->where('order_number', 'like', '%' . $search . '%');
                  });
            });
        }

        // تصفية حسب حالة الرخصة
        if ($request->has('status') && !empty($request->status)) {
            $now = now();
            if ($request->status === 'active') {
                $query->where(function($q) use ($now) {
                    $q->where('license_end_date', '>', $now)
                      ->orWhere('license_extension_end_date', '>', $now);
                });
            } elseif ($request->status === 'expired') {
                $query->where(function($q) use ($now) {
                    $q->where('license_end_date', '<=', $now)
                      ->orWhere(function($q) use ($now) {
                          $q->whereNotNull('license_extension_end_date')
                            ->where('license_extension_end_date', '<=', $now);
                      });
                });
            }
        }

        // تصفية حسب نوع الرخصة
        if ($request->has('type') && !empty($request->type)) {
            $query->where('license_type', $request->type);
        }

        $licenses = $query->latest()->paginate(10);

        return view('admin.licenses.display', compact('licenses'));
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
                'license_extension_start_date' => 'nullable|date',
                'license_extension_end_date' => 'nullable|date|after_or_equal:license_extension_start_date',
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
        }]);
        return view('admin.licenses.show', compact('license'));
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
                      ->orWhere('license_extension_end_date', '>', $now);
                });
            } elseif ($request->status === 'expired') {
                $query->where(function($q) use ($now) {
                    $q->where('license_end_date', '<=', $now)
                      ->where(function($q) use ($now) {
                          $q->whereNull('license_extension_end_date')
                            ->orWhere('license_extension_end_date', '<=', $now);
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

            return response()->json([
                'success' => true,
                'licenses' => $licenses
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
            $licenses = License::where('work_order_id', $workOrderId)->get();
            $extensions = [];
            
            foreach ($licenses as $license) {
                // التحقق من وجود تمديد في الرخصة
                if ($license->extension_value && $license->extension_value > 0) {
                    $extensions[] = [
                        'id' => $license->id,
                        'license_id' => $license->id,
                        'license_number' => $license->license_number,
                        'extension_value' => $license->extension_value,
                        'extension_start_date' => $license->license_extension_start_date,
                        'extension_end_date' => $license->license_extension_end_date,
                        'extension_days' => $this->calculateExtensionDays($license->license_extension_start_date, $license->license_extension_end_date),
                        'extension_license_file' => $license->extension_license_file_path ? Storage::url($license->extension_license_file_path) : null,
                        'extension_payment_proof' => $license->extension_payment_proof_path ? Storage::url($license->extension_payment_proof_path) : null,
                        'extension_bank_proof' => $license->extension_bank_proof_path ? Storage::url($license->extension_bank_proof_path) : null,
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
            
            // التحقق من وجود تمديد في الرخصة
            if ($license->extension_value && $license->extension_value > 0) {
                $extensions[] = [
                    'id' => $license->id,
                    'license_id' => $license->id,
                    'license_number' => $license->license_number,
                    'extension_value' => $license->extension_value,
                    'extension_start_date' => $license->license_extension_start_date,
                    'extension_end_date' => $license->license_extension_end_date,
                    'extension_days' => $this->calculateExtensionDays($license->license_extension_start_date, $license->license_extension_end_date),
                    'extension_license_file' => $license->extension_license_file_path ? Storage::url($license->extension_license_file_path) : null,
                    'extension_payment_proof' => $license->extension_payment_proof_path ? Storage::url($license->extension_payment_proof_path) : null,
                    'extension_bank_proof' => $license->extension_bank_proof_path ? Storage::url($license->extension_bank_proof_path) : null,
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
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ القسم بنجاح',
                'license_id' => $license->id,
                'latest_license_id' => $latestLicense ? $latestLicense->id : $license->id,
                'refresh_table' => true, // إعادة تحديث الجدول دائماً
                'is_new' => $isNewLicense,
                'total_licenses' => License::where('work_order_id', $workOrderId)->count(),
                'debug_info' => [
                    'updated_license_id' => $license->id,
                    'latest_license_id' => $latestLicense ? $latestLicense->id : null,
                    'work_order_id' => $workOrderId,
                    'section_type' => $sectionType
                ]
            ]);
            
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
        // تسجيل جميع البيانات المُرسلة
        \Log::info('saveLabSection - All request data:', $request->all());
        
        $license->has_depth_test = $request->input('has_depth_test', 0) == '1';
        $license->has_soil_compaction_test = $request->input('has_soil_compaction_test', 0) == '1';
        $license->has_rc1_mc1_test = $request->input('has_rc1_mc1_test', 0) == '1';
        $license->has_asphalt_test = $request->input('has_asphalt_test', 0) == '1';
        $license->has_soil_test = $request->input('has_soil_test', 0) == '1';
        $license->has_interlock_test = $request->input('has_interlock_test', 0) == '1';
        
        // حفظ نتائج الاختبارات الجديدة
        $license->successful_tests_value = $request->input('successful_tests_value');
        $license->failed_tests_value = $request->input('failed_tests_value');
        $license->test_failure_reasons = $request->input('test_failure_reasons');
        
        // تسجيل القيم للتحقق من الحفظ
        \Log::info('Saving test results values:', [
            'successful_tests_value' => $request->input('successful_tests_value'),
            'failed_tests_value' => $request->input('failed_tests_value'),
            'test_failure_reasons' => $request->input('test_failure_reasons'),
            'license_id' => $license->id ?? 'new'
        ]);
        
        // تسجيل القيم بعد التعيين
        \Log::info('License values after assignment:', [
            'successful_tests_value' => $license->successful_tests_value,
            'failed_tests_value' => $license->failed_tests_value,
            'test_failure_reasons' => $license->test_failure_reasons,
        ]);
        
        // معالجة بيانات جدول المختبر الأول
        if ($request->has('lab_table1')) {
            $labTable1Data = $request->input('lab_table1');
            $cleanedTable1Data = [];
            
            foreach ($labTable1Data as $row) {
                // تنظيف البيانات وإزالة الصفوف الفارغة
                $cleanRow = array_filter($row, function($value, $key) {
                    if (in_array($key, ['is_dirt', 'is_asphalt', 'is_tile'])) {
                        return true; // احتفظ بقيم checkbox حتى لو كانت false
                    }
                    return !empty(trim($value));
                }, ARRAY_FILTER_USE_BOTH);
                
                if (!empty($cleanRow) && (isset($row['clearance_number']) || isset($row['clearance_date']))) {
                    $cleanedTable1Data[] = $row;
                }
            }
            
            $license->lab_table1_data = !empty($cleanedTable1Data) ? json_encode($cleanedTable1Data) : null;
        }
        
        // معالجة بيانات جدول المختبر الثاني
        if ($request->has('lab_table2')) {
            $labTable2Data = $request->input('lab_table2');
            $cleanedTable2Data = [];
            
            foreach ($labTable2Data as $row) {
                // تنظيف البيانات وإزالة الصفوف الفارغة
                $cleanRow = array_filter($row, function($value, $key) {
                    if (in_array($key, ['soil_compaction', 'mc1rc2', 'asphalt_compaction', 'is_dirt'])) {
                        return true; // احتفظ بقيم checkbox حتى لو كانت false
                    }
                    return !empty(trim($value));
                }, ARRAY_FILTER_USE_BOTH);
                
                if (!empty($cleanRow) && (isset($row['year']) || isset($row['work_type']))) {
                    $cleanedTable2Data[] = $row;
                }
            }
            
            $license->lab_table2_data = !empty($cleanedTable2Data) ? json_encode($cleanedTable2Data) : null;
        }
        
        // معالجة ملفات الاختبارات
        $this->handleLabFiles($request, $license);
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
                'violation_license_number' => $violationData['violation_license_number'],
                'violation_license_value' => $violationData['violation_license_value'],
                'violation_license_date' => $violationData['violation_license_date'],
                'violation_due_date' => $violationData['violation_due_date'],
                'violation_number' => $violationData['violation_number'],
                'violation_payment_number' => $violationData['violation_payment_number'],
                'violation_cause' => $violationData['violation_cause'],
                'violations_file_path' => $violationFilePath,
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
        // حفظ بيانات التمديد
        if ($request->has('extension_value')) {
            $license->extension_value = $request->input('extension_value');
        }
        
        if ($request->has('extension_start_date')) {
            $license->license_extension_start_date = $request->input('extension_start_date');
        }
        
        if ($request->has('extension_end_date')) {
            $license->license_extension_end_date = $request->input('extension_end_date');
        }
        
        // معالجة ملفات التمديد
        if ($request->hasFile('extension_license_file')) {
            $extensionLicenseFile = $request->file('extension_license_file');
            $extensionLicensePath = $extensionLicenseFile->store('licenses/extensions/license_files', 'public');
            $license->extension_license_file_path = $extensionLicensePath;
        }
        
        if ($request->hasFile('extension_payment_proof')) {
            $paymentProofFile = $request->file('extension_payment_proof');
            $paymentProofPath = $paymentProofFile->store('licenses/extensions/payment_proofs', 'public');
            $license->extension_payment_proof_path = $paymentProofPath;
        }
        
        if ($request->hasFile('extension_bank_proof')) {
            $bankProofFile = $request->file('extension_bank_proof');
            $bankProofPath = $bankProofFile->store('licenses/extensions/bank_proofs', 'public');
            $license->extension_bank_proof_path = $bankProofPath;
        }
        
        // حفظ الرخصة
        $license->save();
        
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
    public function saveEvacuationData(Request $request)
    {
        try {
            $validated = $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'license_id' => 'required|exists:licenses,id',
                'evacuation_data' => 'required|array',
                'evacuation_data.*.is_evacuated' => 'required|in:0,1',
                'evacuation_data.*.evacuation_date' => 'required|date',
                'evacuation_data.*.evacuation_amount' => 'required|numeric|min:0',
                'evacuation_data.*.evacuation_datetime' => 'required|date',
                'evacuation_data.*.payment_number' => 'required|string|max:255',
                'evacuation_data.*.notes' => 'nullable|string'
            ]);

            $license = License::findOrFail($validated['license_id']);
            
            // حفظ بيانات الإخلاءات في حقل JSON
            $license->evacuation_data = json_encode($validated['evacuation_data'], JSON_UNESCAPED_UNICODE);
            $license->save();

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ بيانات الإخلاءات بنجاح',
                'license_name' => $license->license_number
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error saving evacuation data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
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
            if ($license->evacuation_data) {
                $evacuationData = json_decode($license->evacuation_data, true);
                
                // التأكد من أن البيانات في تنسيق مصفوفة
                if (!is_array($evacuationData)) {
                    $evacuationData = [];
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
                $testField = str_replace('has_', '', $request->test_field);
                $fileField = $testField . '_file_path';
                
                // حذف الملف القديم إذا كان موجوداً
                if ($license->$fileField && Storage::disk('public')->exists($license->$fileField)) {
                    Storage::disk('public')->delete($license->$fileField);
                }
                
                // رفع الملف الجديد
                $file = $request->file('file');
                $filename = time() . '_' . $testField . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('licenses/lab_tests/' . $testField, $filename, 'public');
                
                $license->$fileField = $filePath;
                $license->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع المرفق بنجاح',
                    'file_path' => $filePath,
                    'file_url' => Storage::url($filePath),
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
} 