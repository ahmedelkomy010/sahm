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
     * Display all licenses data in a dedicated view
     */
    public function display()
    {
        $licenses = License::with('workOrder')->get();
        return view('admin.licenses.display', compact('licenses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'coordination_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'has_restriction' => 'required|boolean',
            'restriction_authority' => 'required_if:has_restriction,1|nullable|string|max:255',
        ]);

        $license = License::findOrFail($id);

        if ($request->hasFile('coordination_certificate')) {
            // Delete old file if exists
            if ($license->coordination_certificate_path) {
                Storage::delete($license->coordination_certificate_path);
            }

            // Store new file
            $path = $request->file('coordination_certificate')->store('licenses', 'public');
            $license->coordination_certificate_path = $path;
        }

        $license->has_restriction = $request->has_restriction;
        $license->restriction_authority = $request->restriction_authority;
        $license->save();

        return redirect()->back()->with('success', 'تم تحديث معلومات الترخيص بنجاح');
    }
} 