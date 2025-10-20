<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpecialProjectRevenuesExport;

class SpecialProjectController extends Controller
{
    /**
     * Display a listing of special projects.
     */
    public function index()
    {
        return view('admin.special-projects.index');
    }

    /**
     * Show the form for creating a new special project.
     */
    public function create()
    {
        return view('admin.special-projects.create');
    }

    /**
     * Store a newly created special project.
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contract_number' => 'required|string|max:255|unique:projects,contract_number',
                'location' => 'required|string|max:255',
                'amount' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'project_type' => 'required|string|in:special',
                'srgn_date' => 'nullable|date',
                'kick_off_date' => 'nullable|date',
                'tcc_date' => 'nullable|date',
                'pac_date' => 'nullable|date',
                'fat_date' => 'nullable|date',
            ]);

            // Add default status if not provided
            $validated['status'] = $validated['status'] ?? 'active';

            // Create project
            $project = Project::create($validated);

            Log::info('Special project created successfully', [
                'project_id' => $project->id,
                'name' => $project->name,
                'user_id' => auth()->id()
            ]);

            return redirect()
                ->route('admin.special-projects.index')
                ->with('success', 'تم إنشاء المشروع الخاص بنجاح!');

        } catch (\Exception $e) {
            Log::error('Error creating special project', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المشروع: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        // Ensure it's a special project
        if ($project->project_type !== 'special') {
            return redirect()
                ->route('admin.special-projects.index')
                ->with('error', 'هذا المشروع ليس مشروع خاص');
        }

        return view('admin.special-projects.edit', compact('project'));
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        try {
            // Ensure it's a special project
            if ($project->project_type !== 'special') {
                return redirect()
                    ->route('admin.special-projects.index')
                    ->with('error', 'هذا المشروع ليس مشروع خاص');
            }

            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contract_number' => 'required|string|max:255|unique:projects,contract_number,' . $project->id,
                'location' => 'required|string|max:255',
                'amount' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'srgn_date' => 'nullable|date',
                'kick_off_date' => 'nullable|date',
                'tcc_date' => 'nullable|date',
                'pac_date' => 'nullable|date',
                'fat_date' => 'nullable|date',
            ]);

            // Update project
            $project->update($validated);

            Log::info('Special project updated successfully', [
                'project_id' => $project->id,
                'name' => $project->name,
                'user_id' => auth()->id()
            ]);

            return redirect()
                ->route('admin.special-projects.index')
                ->with('success', 'تم تحديث المشروع الخاص بنجاح!');

        } catch (\Exception $e) {
            Log::error('Error updating special project', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المشروع: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified special project.
     */
    public function show(Project $project)
    {
        // Ensure it's a special project
        if ($project->project_type !== 'special') {
            return redirect()
                ->route('admin.special-projects.index')
                ->with('error', 'هذا المشروع ليس مشروع خاص');
        }

        return view('admin.special-projects.show', compact('project'));
    }

    /**
     * Display revenues for the specified special project.
     */
    public function revenues(Project $project)
    {
        // Ensure it's a special project
        if ($project->project_type !== 'special') {
            return redirect()
                ->route('admin.special-projects.index')
                ->with('error', 'هذا المشروع ليس مشروع خاص');
        }

        $revenues = \App\Models\SpecialProjectRevenue::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.special-projects.revenues', compact('project', 'revenues'));
    }

    /**
     * Store a new revenue for the special project.
     */
    public function storeRevenue(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'client_name' => 'nullable|string',
                'project' => 'nullable|string',
                'contract_number' => 'nullable|string',
                'extract_number' => 'nullable|string',
                'office' => 'nullable|string',
                'extract_type' => 'nullable|string',
                'po_number' => 'nullable|string',
                'invoice_number' => 'nullable|string',
                'total_value' => 'nullable|numeric',
                'extract_entity' => 'nullable|in:SAP,UDS',
                'tax_value' => 'nullable|numeric',
                'penalties' => 'nullable|numeric',
                'advance_payment_tax' => 'nullable|numeric',
                'net_value' => 'nullable|numeric',
                'preparation_date' => 'nullable|date',
                'year' => 'nullable|integer',
                'extract_status' => 'nullable|in:المقاول,ادارة الكهرباء,المالية,الخزينة,تم الصرف',
                'reference_number' => 'nullable|string',
                'payment_date' => 'nullable|date',
                'payment_amount' => 'nullable|numeric',
                'payment_status' => 'nullable|in:unpaid,paid',
                'procedures' => 'nullable|string',
            ]);

            // تحويل empty strings إلى null للتواريخ
            if (isset($validated['preparation_date']) && $validated['preparation_date'] === '') {
                $validated['preparation_date'] = null;
            }
            if (isset($validated['payment_date']) && $validated['payment_date'] === '') {
                $validated['payment_date'] = null;
            }

            $validated['project_id'] = $project->id;

            $revenue = \App\Models\SpecialProjectRevenue::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الإيراد بنجاح',
                'revenue' => $revenue
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing special project revenue', [
                'error' => $e->getMessage(),
                'project_id' => $project->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الإيراد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a revenue for the special project.
     */
    public function updateRevenue(Request $request, Project $project, $revenueId)
    {
        try {
            $revenue = \App\Models\SpecialProjectRevenue::where('project_id', $project->id)
                ->findOrFail($revenueId);

            $validated = $request->validate([
                'client_name' => 'nullable|string',
                'project' => 'nullable|string',
                'contract_number' => 'nullable|string',
                'extract_number' => 'nullable|string',
                'office' => 'nullable|string',
                'extract_type' => 'nullable|string',
                'po_number' => 'nullable|string',
                'invoice_number' => 'nullable|string',
                'total_value' => 'nullable|numeric',
                'extract_entity' => 'nullable|in:SAP,UDS',
                'tax_value' => 'nullable|numeric',
                'penalties' => 'nullable|numeric',
                'advance_payment_tax' => 'nullable|numeric',
                'net_value' => 'nullable|numeric',
                'preparation_date' => 'nullable|date',
                'year' => 'nullable|integer',
                'extract_status' => 'nullable|in:المقاول,ادارة الكهرباء,المالية,الخزينة,تم الصرف',
                'reference_number' => 'nullable|string',
                'payment_date' => 'nullable|date',
                'payment_amount' => 'nullable|numeric',
                'payment_status' => 'nullable|in:unpaid,paid',
                'procedures' => 'nullable|string',
            ]);

            // تحويل empty strings إلى null للتواريخ
            if (isset($validated['preparation_date']) && $validated['preparation_date'] === '') {
                $validated['preparation_date'] = null;
            }
            if (isset($validated['payment_date']) && $validated['payment_date'] === '') {
                $validated['payment_date'] = null;
            }

            $revenue->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الإيراد بنجاح',
                'revenue' => $revenue
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating special project revenue', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'revenue_id' => $revenueId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الإيراد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a revenue for the special project.
     */
    public function destroyRevenue(Project $project, $revenueId)
    {
        try {
            $revenue = \App\Models\SpecialProjectRevenue::where('project_id', $project->id)
                ->findOrFail($revenueId);

            $revenue->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الإيراد بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting special project revenue', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'revenue_id' => $revenueId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الإيراد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload attachment for a revenue
     */
    public function uploadRevenueAttachment(Request $request, Project $project, $revenueId)
    {
        try {
            $request->validate([
                'attachment' => 'required|file|max:51200', // 10MB max
            ]);

            $revenue = \App\Models\SpecialProjectRevenue::where('project_id', $project->id)
                ->findOrFail($revenueId);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('special_project_revenues_attachments', $fileName, 'public');
                
                // Update revenue with attachment path
                $revenue->attachment_path = '/storage/' . $path;
                $revenue->save();

                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع المرفق بنجاح',
                    'attachment_path' => $revenue->attachment_path
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'لم يتم اختيار ملف'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error uploading revenue attachment', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'revenue_id' => $revenueId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع المرفق: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export revenues to Excel
     */
    public function exportRevenues(Project $project)
    {
        try {
            // Ensure it's a special project
            if ($project->project_type !== 'special') {
                return redirect()
                    ->route('admin.special-projects.index')
                    ->with('error', 'هذا المشروع ليس مشروع خاص');
            }

            $fileName = 'special_project_revenues_' . $project->name . '_' . date('Y-m-d') . '.xlsx';

            return Excel::download(
                new SpecialProjectRevenuesExport($project->id), 
                $fileName
            );

        } catch (\Exception $e) {
            Log::error('Error exporting special project revenues', [
                'error' => $e->getMessage(),
                'project_id' => $project->id
            ]);

            return back()
                ->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project)
    {
        try {
            // Ensure it's a special project
            if ($project->project_type !== 'special') {
                return redirect()
                    ->route('admin.special-projects.index')
                    ->with('error', 'هذا المشروع ليس مشروع خاص');
            }

            $projectName = $project->name;
            $project->delete();

            Log::info('Special project deleted successfully', [
                'project_name' => $projectName,
                'user_id' => auth()->id()
            ]);

            return redirect()
                ->route('admin.special-projects.index')
                ->with('success', 'تم حذف المشروع الخاص بنجاح!');

        } catch (\Exception $e) {
            Log::error('Error deleting special project', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id()
            ]);

            return back()
                ->with('error', 'حدث خطأ أثناء حذف المشروع: ' . $e->getMessage());
        }
    }

    /**
     * Upload attachments for special project
     */
    public function uploadAttachment(Request $request, Project $project)
    {
        try {
            $request->validate([
                'attachments.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif|max:20480',
            ]);

            $uploadedFiles = [];
            $existingAttachments = $project->attachments ?? [];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'special_projects/' . $project->id . '/attachments';
                    
                    if (!\Storage::disk('public')->exists($path)) {
                        \Storage::disk('public')->makeDirectory($path);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    $uploadedFiles[] = $filePath;

                    Log::info('Special project attachment uploaded', [
                        'project_id' => $project->id,
                        'filename' => $filename,
                        'original_name' => $originalName,
                        'user_id' => auth()->id()
                    ]);
                }
            }

            // دمج الملفات الجديدة مع الموجودة
            $allAttachments = array_merge($existingAttachments, $uploadedFiles);
            
            $project->update([
                'attachments' => $allAttachments
            ]);

            return redirect()->route('admin.special-projects.show', $project)
                ->with('success', 'تم رفع المرفقات بنجاح');

        } catch (\Exception $e) {
            Log::error('Error uploading special project attachments', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'user_id' => auth()->id()
            ]);

            return back()
                ->with('error', 'حدث خطأ أثناء رفع المرفقات');
        }
    }

    /**
     * Delete attachment from special project
     */
    public function deleteAttachment(Project $project, $index)
    {
        try {
            $attachments = $project->attachments ?? [];
            
            if (!isset($attachments[$index])) {
                return back()->with('error', 'المرفق غير موجود');
            }

            $filePath = $attachments[$index];
            
            // حذف الملف من Storage
            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }

            // إزالة المرفق من المصفوفة
            unset($attachments[$index]);
            $attachments = array_values($attachments); // إعادة ترتيب المصفوفة

            $project->update([
                'attachments' => $attachments
            ]);

            Log::info('Special project attachment deleted', [
                'project_id' => $project->id,
                'file_path' => $filePath,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.special-projects.show', $project)
                ->with('success', 'تم حذف المرفق بنجاح');

        } catch (\Exception $e) {
            Log::error('Error deleting special project attachment', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
                'index' => $index,
                'user_id' => auth()->id()
            ]);

            return back()
                ->with('error', 'حدث خطأ أثناء حذف المرفق');
        }
    }
}
