<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RevenueController extends Controller
{
    public function index(Project $project)
    {
        $revenues = Revenue::where('project', $project->name)->get();
        return response()->json($revenues);
    }

    public function store(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'client_name' => 'required|string|max:255',
                'project_area' => 'required|string|max:255',
                'contract_number' => 'required|string|max:255',
                'extract_number' => 'required|string|max:255',
                'office' => 'required|string|max:255',
                'extract_type' => 'required|string|max:255',
                'po_number' => 'nullable|string|max:255',
                'invoice_number' => 'nullable|string|max:255',
                'extract_value' => 'required|numeric|min:0',
                'tax_percentage' => 'required|numeric|min:0|max:100',
                'tax_value' => 'required|numeric|min:0',
                'penalties' => 'nullable|numeric|min:0',
                'first_payment_tax' => 'nullable|numeric|min:0',
                'net_extract_value' => 'required|numeric',
                'extract_date' => 'nullable|date',
                'year' => 'required|integer|min:2020|max:2030',
                'payment_type' => 'nullable|string|max:255',
                'reference_number' => 'nullable|string|max:255',
                'payment_date' => 'nullable|date',
                'payment_value' => 'nullable|numeric|min:0',
                'extract_status' => 'required|string|max:255'
            ]);

            $validated['project'] = $project->name;
            $validated['city'] = $project->location ?? 'غير محدد';
            
            $revenue = Revenue::create($validated);

            return response()->json([
                'success' => true,
                'revenue' => $revenue,
                'message' => 'تم حفظ المستخلص بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Revenue creation failed', [
                'error' => $e->getMessage(),
                'project_id' => $project->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في حفظ المستخلص: ' . $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request, Project $project)
    {
        try {
            Log::info('Revenue import started', [
                'project_id' => $project->id,
                'data_count' => count($request->input('revenues', []))
            ]);

            $revenues = $request->input('revenues', []);
            $imported = 0;
            $errors = 0;

            foreach ($revenues as $revenueData) {
                try {
                    // Validate required fields
                    if (empty($revenueData['client_name']) || empty($revenueData['project_name']) || 
                        empty($revenueData['contract_number']) || empty($revenueData['extract_number'])) {
                        $errors++;
                        continue;
                    }

                    // Prepare data
                    $data = [
                        'project' => $project->name,
                        'city' => $project->location ?? 'غير محدد',
                        'client_name' => $revenueData['client_name'],
                        'project_area' => $revenueData['project_name'],
                        'contract_number' => $revenueData['contract_number'],
                        'extract_number' => $revenueData['extract_number'],
                        'office' => $revenueData['office'] ?? 'المكتب الرئيسي',
                        'extract_type' => $revenueData['extract_type'] ?? 'مرحلي',
                        'po_number' => $revenueData['po_number'] ?? null,
                        'invoice_number' => $revenueData['invoice_number'] ?? null,
                        'extract_value' => floatval($revenueData['extract_value'] ?? 0),
                        'tax_percentage' => floatval($revenueData['tax_percentage'] ?? 15),
                        'tax_value' => floatval($revenueData['tax_value'] ?? 0),
                        'penalties' => floatval($revenueData['penalties'] ?? 0),
                        'first_payment_tax' => floatval($revenueData['first_payment_tax'] ?? 0),
                        'net_extract_value' => floatval($revenueData['net_value'] ?? 0),
                        'extract_date' => $revenueData['preparation_date'] ?? null,
                        'year' => intval($revenueData['year'] ?? date('Y')),
                        'payment_type' => $revenueData['payment_type'] ?? 'حوالة بنكية',
                        'reference_number' => $revenueData['reference_number'] ?? null,
                        'payment_date' => $revenueData['payment_date'] ?? null,
                        'payment_value' => floatval($revenueData['payment_value'] ?? 0),
                        'extract_status' => $revenueData['status'] ?? 'معلق'
                    ];

                    Revenue::create($data);
                    $imported++;

                } catch (\Exception $e) {
                    Log::error('Revenue import row failed', [
                        'error' => $e->getMessage(),
                        'data' => $revenueData
                    ]);
                    $errors++;
                }
            }

            Log::info('Revenue import completed', [
                'imported' => $imported,
                'errors' => $errors
            ]);

            return response()->json([
                'success' => true,
                'imported' => $imported,
                'errors' => $errors,
                'message' => "تم استيراد {$imported} مستخلص بنجاح" . ($errors > 0 ? " ({$errors} خطأ)" : "")
            ]);

        } catch (\Exception $e) {
            Log::error('Revenue import failed', [
                'error' => $e->getMessage(),
                'project_id' => $project->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في استيراد البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Project $project, Revenue $revenue)
    {
        try {
            $validated = $request->validate([
                'client_name' => 'required|string|max:255',
                'project_area' => 'required|string|max:255',
                'contract_number' => 'required|string|max:255',
                'extract_number' => 'required|string|max:255',
                'office' => 'required|string|max:255',
                'extract_type' => 'required|string|max:255',
                'po_number' => 'nullable|string|max:255',
                'invoice_number' => 'nullable|string|max:255',
                'extract_value' => 'required|numeric|min:0',
                'tax_percentage' => 'required|numeric|min:0|max:100',
                'tax_value' => 'required|numeric|min:0',
                'penalties' => 'nullable|numeric|min:0',
                'first_payment_tax' => 'nullable|numeric|min:0',
                'net_extract_value' => 'required|numeric',
                'extract_date' => 'nullable|date',
                'year' => 'required|integer|min:2020|max:2030',
                'payment_type' => 'nullable|string|max:255',
                'reference_number' => 'nullable|string|max:255',
                'payment_date' => 'nullable|date',
                'payment_value' => 'nullable|numeric|min:0',
                'extract_status' => 'required|string|max:255'
            ]);

            $revenue->update($validated);

            return response()->json([
                'success' => true,
                'revenue' => $revenue,
                'message' => 'تم تحديث المستخلص بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Revenue update failed', [
                'error' => $e->getMessage(),
                'revenue_id' => $revenue->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في تحديث المستخلص: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Project $project, Revenue $revenue)
    {
        try {
            $revenue->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخلص بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Revenue deletion failed', [
                'error' => $e->getMessage(),
                'revenue_id' => $revenue->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في حذف المستخلص: ' . $e->getMessage()
            ], 500);
        }
    }
}