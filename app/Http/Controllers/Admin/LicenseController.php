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
        $validated = $request->validate([
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
            'excavation_length' => 'nullable|numeric|min:0',
            'excavation_width' => 'nullable|numeric|min:0',
            'excavation_depth' => 'nullable|numeric|min:0',
            'has_depth_test' => 'boolean',
            'has_soil_compaction_test' => 'boolean',
            'has_rc1_mc1_test' => 'boolean',
            'has_asphalt_test' => 'boolean',
            'has_soil_test' => 'boolean',
            'has_interlock_test' => 'boolean',
            'is_evacuated' => 'boolean',
            'evac_license_number' => 'nullable|string|max:255',
            'evac_license_value' => 'nullable|numeric|min:0',
            'evac_payment_number' => 'nullable|string|max:255',
            'evac_date' => 'nullable|date',
            'evac_amount' => 'nullable|numeric|min:0',
        ]);

        $license->update($validated);

        return redirect()->route('admin.licenses.data')
            ->with('success', 'تم تحديث بيانات الرخصة بنجاح');
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

    public function show(License $license)
    {
        $license->load('workOrder');
        return view('admin.licenses.show', compact('license'));
    }

    public function edit(License $license)
    {
        $license->load('workOrder');
        return view('admin.licenses.edit', compact('license'));
    }

    /**
     * عرض صفحة بيانات الجودة والرخص
     */
    public function data()
    {
        $licenses = License::with(['workOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.licenses.data', compact('licenses'));
    }
} 