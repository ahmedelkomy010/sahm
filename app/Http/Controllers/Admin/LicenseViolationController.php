<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LicenseViolationController extends Controller
{
    /**
     * Store a new violation for a license
     */
    public function store(Request $request)
    {
        try {
            Log::info('LicenseViolationController::store called', $request->all());

            $validated = $request->validate([
                'license_id' => 'required|exists:licenses,id',
                'violation_license_number' => 'nullable|string|max:255',
                'violation_license_value' => 'nullable|numeric|min:0',
                'violation_license_date' => 'nullable|date',
                'violation_due_date' => 'nullable|date',
                'violation_number' => 'nullable|string|max:255',
                'violation_payment_number' => 'nullable|string|max:255',
                'violation_cause' => 'nullable|string',
                'violations_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('violations_file')) {
                $file = $request->file('violations_file');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('violations/' . $request->license_id, $filename, 'public');
            }

            // Create violation
            $violation = LicenseViolation::create([
                'license_id' => $validated['license_id'],
                'violation_license_number' => $validated['violation_license_number'] ?? null,
                'violation_license_value' => $validated['violation_license_value'] ?? null,
                'violation_license_date' => $validated['violation_license_date'] ?? null,
                'violation_due_date' => $validated['violation_due_date'] ?? null,
                'violation_number' => $validated['violation_number'] ?? null,
                'violation_payment_number' => $validated['violation_payment_number'] ?? null,
                'violation_cause' => $validated['violation_cause'] ?? null,
                'violations_file_path' => $filePath,
            ]);

            // Update license violations count
            $license = License::find($validated['license_id']);
            if ($license) {
                $license->updateViolationsCount();
            }

            Log::info('Violation created successfully', ['violation_id' => $violation->id]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ المخالفة بنجاح',
                'violation' => $violation,
                'license_id' => $validated['license_id']
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating violation: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ المخالفة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing violation
     */
    public function update(Request $request, LicenseViolation $violation)
    {
        try {
            $validated = $request->validate([
                'violation_license_number' => 'nullable|string|max:255',
                'violation_license_value' => 'nullable|numeric|min:0',
                'violation_license_date' => 'nullable|date',
                'violation_due_date' => 'nullable|date',
                'violation_number' => 'nullable|string|max:255',
                'violation_payment_number' => 'nullable|string|max:255',
                'violation_cause' => 'nullable|string',
                'violations_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);

            // Handle file upload
            if ($request->hasFile('violations_file')) {
                // Delete old file if exists
                if ($violation->violations_file_path) {
                    Storage::disk('public')->delete($violation->violations_file_path);
                }

                $file = $request->file('violations_file');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $validated['violations_file_path'] = $file->storeAs('violations/' . $violation->license_id, $filename, 'public');
            }

            $violation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المخالفة بنجاح',
                'violation' => $violation
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating violation: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المخالفة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a violation
     */
    public function destroy(LicenseViolation $violation)
    {
        try {
            $licenseId = $violation->license_id;

            // Delete file if exists
            if ($violation->violations_file_path) {
                Storage::disk('public')->delete($violation->violations_file_path);
            }

            $violation->delete();

            // Update license violations count
            $license = License::find($licenseId);
            if ($license) {
                $license->updateViolationsCount();
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المخالفة بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting violation: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المخالفة: ' . $e->getMessage()
            ], 500);
        }
    }
}
