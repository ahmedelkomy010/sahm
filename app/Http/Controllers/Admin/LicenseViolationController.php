<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            // Find all licenses associated with this work order
            $licenses = License::where('work_order_id', $workOrderId)->get();
            
            if ($licenses->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'violations' => [],
                    'licenses' => []
                ]);
            }

            $allViolations = collect();
            foreach ($licenses as $license) {
                $violations = $license->violations()->orderBy('created_at', 'desc')->get();
                $allViolations = $allViolations->merge($violations);
            }
            
            return response()->json([
                'success' => true,
                'violations' => $allViolations->sortByDesc('created_at')->values(),
                'licenses' => $licenses->map(function($license) {
                    return [
                        'id' => $license->id,
                        'license_number' => $license->license_number,
                        'license_type' => $license->license_type,
                        'violations_count' => $license->violations->count()
                    ];
                })
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
     * Get licenses by work order ID for violation form
     */
    public function getLicensesByWorkOrder($workOrderId)
    {
        try {
            $licenses = License::where('work_order_id', $workOrderId)
                              ->whereNotNull('license_number')
                              ->select('id', 'license_number', 'license_type', 'issue_date as license_date')
                              ->orderBy('issue_date', 'desc')
                              ->get();
            
            return response()->json([
                'success' => true,
                'licenses' => $licenses
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getLicensesByWorkOrder: ' . $e->getMessage(), [
                'work_order_id' => $workOrderId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحميل الرخص',
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
            'license_id' => 'required|exists:licenses,id',
            'license_number' => 'required|string',
            'work_order_id' => 'required|exists:work_orders,id',
            'violation_number' => 'required|string|unique:license_violations,violation_number',
            'violation_date' => 'required|date',
            'violation_type' => 'required|string',
            'responsible_party' => 'required|string',
            'payment_status' => 'nullable|numeric|in:0,1,2,3',
            'violation_amount' => 'required|numeric|min:0',
            'payment_due_date' => 'required|date',
            'violation_description' => 'nullable|string',
            'payment_invoice_number' => 'nullable|string|max:255',
            'violation_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
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

            // التحقق من صحة ربط الرخصة برقم أمر العمل
            $license = License::where('id', $request->license_id)
                             ->where('work_order_id', $request->work_order_id)
                             ->first();

            if (!$license) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرخصة المحددة غير موجودة أو غير مرتبطة بأمر العمل المحدد',
                    'errors' => ['license_id' => ['الرخصة غير صحيحة']]
                ], 422);
            }

            // التحقق من تطابق رقم الرخصة
            if ($license->license_number !== $request->license_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'رقم الرخصة المدخل لا يتطابق مع الرخصة المحددة',
                    'errors' => ['license_number' => ['رقم الرخصة غير صحيح']]
                ], 422);
            }

            \Log::info('License verified', ['license_id' => $license->id, 'license_number' => $license->license_number]);

            // Create the violation
            $violationData = [
                'license_id' => $license->id,
                'reported_by' => Auth::id(),
                'work_order_id' => $request->work_order_id,
                'license_number' => $request->license_number,
                'violation_number' => $request->violation_number,
                'violation_date' => $request->violation_date,
                'violation_type' => $request->violation_type,
                'description' => $request->violation_description ?? $request->violation_type,
                'status' => 'pending',
                'responsible_party' => $request->responsible_party,
                'payment_status' => $request->payment_status ?? 'pending',
                'violation_amount' => $request->violation_amount,
                'payment_due_date' => $request->payment_due_date,
                'violation_description' => $request->violation_description,
                'payment_invoice_number' => $request->payment_invoice_number,
                'notes' => $request->notes,
            ];

            // Handle file upload if present
            if ($request->hasFile('violation_attachment')) {
                $file = $request->file('violation_attachment');
                $filename = time() . '_violation_' . $file->getClientOriginalName();
                $path = $file->storeAs('violations', $filename, 'public');
                $violationData['attachment_path'] = $path;
            }
            
            \Log::info('Creating violation with data', $violationData);
            
            $violation = LicenseViolation::create($violationData);
            
            \Log::info('Violation created successfully', ['violation_id' => $violation->id]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المخالفة بنجاح للرخصة رقم: ' . $request->license_number,
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
