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

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'license_number' => 'nullable|string|max:255',
                'license_date' => 'nullable|date',
                'license_type' => 'nullable|string|max:255',
                'license_value' => 'nullable|numeric|min:0',
                'extension_value' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'has_restriction' => 'boolean',
                'restriction_authority' => 'nullable|string|max:255',
                'restriction_reason' => 'nullable|string|max:255',
                'restriction_notes' => 'nullable|string',
                'coordination_certificate_notes' => 'nullable|string',
                'license_start_date' => 'nullable|date',
                'license_end_date' => 'nullable|date',
                'license_alert_days' => 'nullable|integer|min:0',
                'license_length' => 'nullable|numeric|min:0',
                'has_depth_test' => 'boolean',
                'has_soil_compaction_test' => 'boolean',
                'has_rc1_mc1_test' => 'boolean',
                'has_asphalt_test' => 'boolean',
                'has_soil_test' => 'boolean',
                'has_interlock_test' => 'boolean',
                'lab_table1' => 'nullable|array',
                'lab_table2' => 'nullable|array',
                'is_evacuated' => 'boolean',
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
                'violation_cause' => 'nullable|string|max:255',
                // ملفات
                'coordination_certificate_path' => 'nullable|file|max:5120', // 5MB
                'letters_commitments_files.*' => 'nullable|file|max:5120',
                'evacuations_files.*' => 'nullable|file|max:5120',
                'violations_files.*' => 'nullable|file|max:5120',
            ]);

            $license = License::findOrFail($id);
            
            // تحديث البيانات الأساسية
            $licenseData = $request->only([
                'license_number', 'license_date', 'license_type', 'license_value', 
                'extension_value', 'notes', 'has_restriction', 'restriction_authority',
                'restriction_reason', 'restriction_notes', 'coordination_certificate_notes',
                'license_start_date', 'license_end_date', 'license_alert_days', 
                'license_length', 'has_depth_test', 'has_soil_compaction_test',
                'has_rc1_mc1_test', 'has_asphalt_test', 'has_soil_test', 
                'has_interlock_test', 'is_evacuated', 'evac_license_number',
                'evac_license_value', 'evac_payment_number', 'evac_date', 
                'evac_amount', 'violation_license_number', 'violation_license_value',
                'violation_license_date', 'violation_due_date', 'violation_number',
                'violation_payment_number', 'violation_cause'
            ]);

            // معالجة جداول المختبر
            if ($request->has('lab_table1')) {
                $licenseData['lab_table1_data'] = $this->processTableData($request->lab_table1);
            }
            
            if ($request->has('lab_table2')) {
                $licenseData['lab_table2_data'] = $this->processTableData($request->lab_table2);
            }

            // معالجة الملفات
            $fileFields = [
                'coordination_certificate_path' => 'coordination_certificate_path',
                'letters_commitments_files' => 'letters_commitments_file_path',
                'evacuations_files' => 'evac_files_path',
                'violations_files' => 'violation_files_path'
            ];

            foreach ($fileFields as $requestField => $dbField) {
                if ($request->hasFile($requestField)) {
                    $files = $request->file($requestField);
                    
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    
                    $filePaths = [];
                    foreach ($files as $file) {
                        if ($file && $file->isValid()) {
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $path = $file->storeAs('licenses', $filename, 'public');
                            $filePaths[] = $path;
                        }
                    }
                    
                    if (!empty($filePaths)) {
                        $licenseData[$dbField] = json_encode($filePaths);
                    }
                }
            }

            $license->update($licenseData);

            // تحديد المكان المناسب للعودة
            if ($license->workOrder) {
                return redirect()
                    ->route('admin.work-orders.license', $license->workOrder)
                    ->with('success', 'تم تحديث بيانات الرخصة بنجاح');
            } else {
                return redirect()
                    ->route('admin.licenses.data')
                    ->with('success', 'تم تحديث بيانات الرخصة بنجاح');
            }

        } catch (\Exception $e) {
            \Log::error('Error updating license: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات الرخصة: ' . $e->getMessage());
        }
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

    public function show($id)
    {
        $license = \App\Models\License::findOrFail($id);
        return view('admin.licenses.show', compact('license'));
    }

    public function edit($id)
    {
        $license = License::with('workOrder')->findOrFail($id);
        $workOrder = $license->workOrder;
        return view('admin.licenses.edit', compact('license', 'workOrder'));
    }

    /**
     * عرض صفحة بيانات الجودة والرخص
     */
    public function data()
    {
        $licenses = License::with('workOrder')->get();
        return view('admin.licenses.data', compact('licenses'));
    }
} 