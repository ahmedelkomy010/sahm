<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LicenseViolationController extends Controller
{
    public function index(License $license)
    {
        $violations = $license->violations()->orderBy('created_at', 'desc')->get();
        return view('admin.work_orders.license', compact('license', 'violations'));
    }

    /**
     * Store a newly created violation in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_order_id' => 'required|exists:work_orders,id',
            'license_number' => 'required|string',
            'violation_date' => 'required|date',
            'payment_due_date' => 'required|date',
            'violation_amount' => 'required|numeric|min:0',
            'violation_number' => 'required|string',
            'responsible_party' => 'required|string',
            'violation_description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $violation = LicenseViolation::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المخالفة بنجاح',
                'violation' => $violation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ المخالفة'
            ], 500);
        }
    }

    /**
     * Display the specified violation.
     */
    public function show(LicenseViolation $violation)
    {
        return response()->json([
            'success' => true,
            'violation' => $violation
        ]);
    }

    /**
     * Update the specified violation in storage.
     */
    public function update(Request $request, LicenseViolation $violation)
    {
        $validator = Validator::make($request->all(), [
            'license_number' => 'required|string',
            'violation_date' => 'required|date',
            'payment_due_date' => 'required|date',
            'violation_amount' => 'required|numeric|min:0',
            'violation_number' => 'required|string',
            'responsible_party' => 'required|string',
            'violation_description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $violation->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المخالفة بنجاح',
                'violation' => $violation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المخالفة'
            ], 500);
        }
    }

    /**
     * Remove the specified violation from storage.
     */
    public function destroy(LicenseViolation $violation)
    {
        try {
            $violation->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المخالفة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المخالفة'
            ], 500);
        }
    }
}
