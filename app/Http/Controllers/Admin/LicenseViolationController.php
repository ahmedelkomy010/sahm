<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LicenseViolationController extends Controller
{
    public function index(License $license)
    {
        $violations = $license->violations()->orderBy('created_at', 'desc')->get();
        return view('admin.work_orders.license', compact('license', 'violations'));
    }

    /**
     * Get violations by work order ID
     */
    public function getByWorkOrder($workOrderId)
    {
        try {
            // Find the license associated with this work order
            $license = License::where('work_order_id', $workOrderId)->first();
            
            if (!$license) {
                return response()->json([
                    'success' => true,
                    'violations' => []
                ]);
            }

            $violations = $license->violations()->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'violations' => $violations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحميل المخالفات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created violation in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Violation store request received', $request->all());
        
        $validator = Validator::make($request->all(), [
            'work_order_id' => 'required|exists:work_orders,id',
            'violation_number' => 'required|string',
            'violation_date' => 'required|date',
            'violation_type' => 'required|string',
            'responsible_party' => 'required|string',
            'payment_status' => 'required|numeric',
            'violation_value' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed for violation', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find or create license for this work order
            $license = License::firstOrCreate(
                ['work_order_id' => $request->work_order_id],
                [
                    'work_order_id' => $request->work_order_id,
                    'license_number' => 'AUTO-WO-' . $request->work_order_id . '-' . time()
                ]
            );

            \Log::info('License found/created', ['license_id' => $license->id]);

            // Generate license number if not exists
            $licenseNumber = $license->license_number ?? 'AUTO-' . $license->id . '-' . time();
            
            // Update license with license number if it doesn't have one
            if (!$license->license_number) {
                $license->update(['license_number' => $licenseNumber]);
            }

            // Create the violation
            $violationData = [
                'license_id' => $license->id,
                'work_order_id' => $request->work_order_id,
                'license_number' => $licenseNumber,
                'violation_number' => $request->violation_number,
                'violation_date' => $request->violation_date,
                'violation_type' => $request->violation_type,
                'responsible_party' => $request->responsible_party,
                'payment_status' => $request->payment_status,
                'violation_amount' => $request->violation_value,
                'payment_due_date' => $request->due_date,
            ];
            
            \Log::info('Creating violation with data', $violationData);
            
            $violation = LicenseViolation::create($violationData);
            
            \Log::info('Violation created successfully', ['violation_id' => $violation->id]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المخالفة بنجاح',
                'violation' => $violation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error storing violation: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ المخالفة: ' . $e->getMessage()
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
            'violation_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
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
            DB::beginTransaction();

            $data = $request->except('violation_attachment');

            // Handle file upload if present
            if ($request->hasFile('violation_attachment')) {
                // Delete old file if exists
                if ($violation->attachment_path) {
                    Storage::disk('public')->delete($violation->attachment_path);
                }

                $file = $request->file('violation_attachment');
                $path = $file->store('violations', 'public');
                $data['attachment_path'] = $path;
            }

            $violation->update($data);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المخالفة بنجاح',
                'violation' => $violation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
            DB::beginTransaction();

            // Delete the attachment file if exists
            if ($violation->attachment_path) {
                Storage::disk('public')->delete($violation->attachment_path);
            }

            $violation->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المخالفة بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المخالفة'
            ], 500);
        }
    }
}
