<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\WorkOrder;
use App\Models\ReferenceMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialsExport;

class MaterialsController extends Controller
{
    /**
     * Display a listing of the resource for a specific work order.
     */
    public function index(WorkOrder $workOrder, Request $request)
    {
        $query = $workOrder->materials();

        // البحث بالكود
        if ($request->filled('search_code')) {
            $query->where('code', 'like', '%' . $request->search_code . '%');
        }

        // البحث بالوصف
        if ($request->filled('search_description')) {
            $query->where('description', 'like', '%' . $request->search_description . '%');
        }

        // فلتر الوحدة
        if ($request->filled('unit_filter')) {
            $query->where('unit', $request->unit_filter);
        }

        $materials = $query->latest()->paginate(15)->appends($request->query());
        
        return view('admin.work_orders.materials', compact('workOrder', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(WorkOrder $workOrder)
    {
        return view('admin.work_orders.create_material', compact('workOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WorkOrder $workOrder)
    {
                $validated = $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_quantity' => 'nullable|numeric|min:0',
            'spent_quantity' => 'nullable|numeric|min:0',
            'executed_quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:255',
            'line' => 'nullable|string|max:255',
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'check_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'stock_in' => 'nullable|string|max:255',
            'stock_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'stock_out' => 'nullable|string|max:255',
            'stock_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'ddo_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $data = $request->except([
            'check_in_file', 'check_out_file', 'stock_in_file', 'stock_out_file',
            'gate_pass_file', 'store_in_file', 'store_out_file', 'ddo_file'
        ]);

        // التأكد من أن القيم الرقمية لا تكون null
        $data['planned_quantity'] = $data['planned_quantity'] ?? 0;
        $data['spent_quantity'] = $data['spent_quantity'] ?? 0;
        $data['executed_quantity'] = $data['executed_quantity'] ?? 0;
        
        // التأكد من أن الحقول النصية لا تكون null
        $data['unit'] = $data['unit'] ?? 'قطعة'; // قيمة افتراضية
        $data['line'] = $data['line'] ?? '';
        
        // ربط المادة بأمر العمل
        $data['work_order_id'] = $workOrder->id;
        $data['work_order_number'] = $workOrder->order_number;
        $data['subscriber_name'] = $workOrder->subscriber_name;
        
        // إذا كان الوصف فارغًا، نحاول جلب الوصف من جدول المواد المرجعية
        if (empty($data['description'])) {
            $referenceMaterial = ReferenceMaterial::where('code', $data['code'])->first();
            if ($referenceMaterial) {
                $data['description'] = $referenceMaterial->description;
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['description' => 'يرجى إدخال وصف المادة أو التأكد من وجود الكود في جدول المواد المرجعية']);
            }
        }

        // إنشاء المادة
        $material = Material::create($data);

        // التعامل مع الملفات المرفقة
        $this->handleFileUploads($request, $material);

        return redirect()->route('admin.work-orders.materials.index', $workOrder)
            ->with('success', 'تم إضافة المادة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder, Material $material)
    {
        // التأكد من أن المادة تنتمي لأمر العمل
        if ($material->work_order_id !== $workOrder->id) {
            abort(404);
        }
        
        return view('admin.work_orders.show_material', compact('workOrder', 'material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder, Material $material)
    {
        // التأكد من أن المادة تنتمي لأمر العمل
        if ($material->work_order_id !== $workOrder->id) {
            abort(404);
        }
        
        return view('admin.work_orders.edit_material', compact('workOrder', 'material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder, Material $material)
    {
        // التأكد من أن المادة تنتمي لأمر العمل
        if ($material->work_order_id !== $workOrder->id) {
            abort(404);
        }
        
                $validated = $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_quantity' => 'nullable|numeric|min:0',
            'spent_quantity' => 'nullable|numeric|min:0',
            'executed_quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:255',
            'line' => 'nullable|string|max:255',
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'check_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'stock_in' => 'nullable|string|max:255',
            'stock_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'stock_out' => 'nullable|string|max:255',
            'stock_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'ddo_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $data = $request->except([
            'check_in_file', 'check_out_file', 'stock_in_file', 'stock_out_file', 
            'gate_pass_file', 'store_in_file', 'store_out_file', 'ddo_file'
        ]);

        // التأكد من أن القيم الرقمية لا تكون null
        $data['planned_quantity'] = $data['planned_quantity'] ?? 0;
        $data['spent_quantity'] = $data['spent_quantity'] ?? 0;
        $data['executed_quantity'] = $data['executed_quantity'] ?? 0;
        
        // إذا كان الوصف فارغًا، نحاول جلب الوصف من جدول المواد المرجعية
        if (empty($data['description'])) {
            $referenceMaterial = ReferenceMaterial::where('code', $data['code'])->first();
            if ($referenceMaterial) {
                $data['description'] = $referenceMaterial->description;
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['description' => 'يرجى إدخال وصف المادة أو التأكد من وجود الكود في جدول المواد المرجعية']);
            }
        }

        $material->update($data);

        // تحديث الملفات
        $this->handleFileUploads($request, $material, true);

        return redirect()->route('admin.work-orders.materials.index', $workOrder)
            ->with('success', 'تم تحديث المادة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder, Material $material)
    {
        // التأكد من أن المادة تنتمي لأمر العمل
        if ($material->work_order_id !== $workOrder->id) {
            abort(404);
        }
        
        try {
            // حذف الملفات المرفقة
            $this->deleteMaterialFiles($material);
            
            // حذف المادة
            $material->delete();
            
            return redirect()->route('admin.work-orders.materials.index', $workOrder)
                ->with('success', 'تم حذف المادة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting material: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المادة');
        }
    }

    /**
     * Get material description by code
     */
    public function getDescriptionByCode($code)
    {
        try {
            $material = ReferenceMaterial::where('code', $code)->first();
            
            if ($material) {
                return response()->json([
                    'success' => true,
                    'description' => $material->description
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على المادة'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting material description: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث عن المادة'
            ], 500);
        }
    }

    /**
     * Export materials to Excel for a specific work order
     */
    public function exportExcel(WorkOrder $workOrder)
    {
        try {
            return Excel::download(new MaterialsExport($workOrder->id), 'materials_work_order_' . $workOrder->order_number . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Error exporting materials: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تصدير البيانات');
        }
    }

    /**
     * Handle file uploads for materials
     */
    private function handleFileUploads(Request $request, Material $material, $isUpdate = false)
    {
        $fileFields = [
            'check_in_file',
            'check_out_file',
            'stock_in_file',
            'stock_out_file',
            'gate_pass_file', 
            'store_in_file',
            'store_out_file',
            'ddo_file'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('materials', $fileName, 'public');
                
                // حذف الملف القديم إذا كان التحديث
                if ($isUpdate && $material->$field) {
                    Storage::disk('public')->delete($material->$field);
                }
                
                $material->$field = $filePath;
            }
        }
        
        $material->save();
    }

    /**
     * Delete all files associated with a material
     */
    private function deleteMaterialFiles(Material $material)
    {
        $fileFields = [
            'check_in_file',
            'check_out_file', 
            'stock_in_file',
            'stock_out_file',
            'gate_pass_file',
            'store_in_file',
            'store_out_file',
            'ddo_file'
        ];

        foreach ($fileFields as $field) {
            if ($material->$field) {
                Storage::disk('public')->delete($material->$field);
            }
        }
    }
}
