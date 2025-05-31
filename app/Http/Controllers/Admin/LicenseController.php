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
        $request->validate([
            'coordination_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'letters_and_commitments' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'license_1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'license_extension_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'invoice_extension_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'test_results_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'final_inspection_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'license_closure_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'has_restriction' => 'required|boolean',
            'restriction_authority' => 'required_if:has_restriction,1|nullable|string|max:255',
            'restriction_reason' => 'nullable|string',
            'license_length' => 'nullable|numeric|min:0',
            'license_value' => 'nullable|numeric|min:0',
            'extension_value' => 'nullable|numeric|min:0',
            'license_start_date' => 'nullable|date',
            'license_end_date' => 'nullable|date|after_or_equal:license_start_date',
            'license_extension_start_date' => 'nullable|date',
            'license_extension_end_date' => 'nullable|date|after_or_equal:license_extension_start_date',
            'invoice_extension_start_date' => 'nullable|date',
            'invoice_extension_end_date' => 'nullable|date|after_or_equal:invoice_extension_start_date',
        ]);

        $license = License::findOrFail($id);

        // Handle file uploads
        $fileFields = [
            'coordination_certificate' => 'coordination_certificate_path',
            'letters_and_commitments' => 'letters_and_commitments_path',
            'license_1' => 'license_1_path',
            'license_extension_file' => 'license_extension_file_path',
            'invoice_extension_file' => 'invoice_extension_file_path',
            'test_results_file' => 'test_results_file_path',
            'final_inspection_file' => 'final_inspection_file_path',
            'license_closure_file' => 'license_closure_file_path',
        ];

        foreach ($fileFields as $inputName => $dbField) {
            if ($request->hasFile($inputName)) {
                // Delete old file if exists
                if ($license->$dbField) {
                    Storage::delete($license->$dbField);
                }

                // Store new file
                $path = $request->file($inputName)->store('licenses/' . $license->id, 'public');
                $license->$dbField = $path;
            }
        }

        // Update other fields
        $license->has_restriction = $request->has_restriction;
        $license->restriction_authority = $request->restriction_authority;
        $license->restriction_reason = $request->restriction_reason;
        $license->license_length = $request->license_length;
        $license->license_value = $request->license_value;
        $license->extension_value = $request->extension_value;
        $license->license_start_date = $request->license_start_date;
        $license->license_end_date = $request->license_end_date;
        $license->license_extension_start_date = $request->license_extension_start_date;
        $license->license_extension_end_date = $request->license_extension_end_date;
        $license->invoice_extension_start_date = $request->invoice_extension_start_date;
        $license->invoice_extension_end_date = $request->invoice_extension_end_date;

        $license->save();

        return redirect()->route('admin.licenses.show', $license->id)
            ->with('success', 'تم تحديث معلومات الترخيص بنجاح');
    }

    public function show($id)
    {
        $license = \App\Models\License::findOrFail($id);
        return view('admin.licenses.show', compact('license'));
    }

    public function edit($id)
    {
        $license = \App\Models\License::findOrFail($id);
        $workOrder = $license->workOrder;
        return view('admin.licenses.edit', compact('license', 'workOrder'));
    }
} 