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
        $license->load('workOrder');
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
                'violation_license_number' => 'nullable|string|max:255',
                'violation_license_value' => 'nullable|numeric|min:0',
                'violation_license_date' => 'nullable|date',
                'violation_due_date' => 'nullable|date',
                'violation_number' => 'nullable|string|max:255',
                'violation_payment_number' => 'nullable|string|max:255',
                'violation_cause' => 'nullable|string',
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
            $workOrderId = $license->work_order_id;
            $license->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الرخصة بنجاح'
                ]);
            }

            return redirect()->route('admin.work-orders.show', $workOrderId)
                ->with('success', 'تم حذف الرخصة بنجاح');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الرخصة'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الرخصة');
        }
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
} 