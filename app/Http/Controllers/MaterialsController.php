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
        $query = $workOrder->materials()->where('code', 'NOT LIKE', 'GENERAL_FILES_%');

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
        
        // جلب بيانات مقايسة المواد من work_order_materials
        $workOrderMaterials = $workOrder->workOrderMaterials()->with('material')->get();
        
        // جلب الملفات المستقلة (الملفات العامة)
        $generalFiles = $workOrder->materials()
            ->where('code', 'LIKE', 'GENERAL_FILES_%')
            ->get();
            
        $fileTypes = [
            'check_in_file' => ['label' => 'CHECK LIST', 'icon' => 'fas fa-list-check', 'color' => 'text-primary'],
            'check_out_file' => ['label' => 'CHECK OUT FILE', 'icon' => 'fas fa-check-circle', 'color' => 'text-success'],
            'stock_in_file' => ['label' => 'STORE IN', 'icon' => 'fas fa-sign-in-alt', 'color' => 'text-info'],
            'stock_out_file' => ['label' => 'STORE OUT', 'icon' => 'fas fa-sign-out-alt', 'color' => 'text-warning'],
            'gate_pass_file' => ['label' => 'GATE PASS', 'icon' => 'fas fa-id-card', 'color' => 'text-success'],
            'ddo_file' => ['label' => 'DDO FILE', 'icon' => 'fas fa-file-alt', 'color' => 'text-primary']
        ];
        
        $independentFiles = [];
        foreach($generalFiles as $generalFile) {
            foreach($fileTypes as $field => $info) {
                if($generalFile->$field) {
                    $independentFiles[] = [
                        'material_id' => $generalFile->id,
                        'file_type' => $field,
                        'file_path' => $generalFile->$field,
                        'file_name' => basename($generalFile->$field),
                        'file_info' => $info,
                        'created_at' => $generalFile->created_at
                    ];
                }
            }
        }
        
        return view('admin.work_orders.materials', compact('workOrder', 'materials', 'workOrderMaterials', 'independentFiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(WorkOrder $workOrder)
    {
        // جلب بيانات مقايسة المواد من work_order_materials
        $workOrderMaterials = $workOrder->workOrderMaterials()->with('material')->get();
        
        return view('admin.work_orders.create_material', compact('workOrder', 'workOrderMaterials'));
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
        
        // محاولة العثور على المادة المرجعية
        $referenceMaterial = ReferenceMaterial::where('code', $data['code'])->first();
        
        if ($referenceMaterial) {
            $data['description'] = $referenceMaterial->description;
            $data['name'] = $referenceMaterial->name ?? $referenceMaterial->description;
        } else {
            // إذا لم يكن هناك وصف، نستخدم الكود كوصف
            if (empty($data['description'])) {
                $data['description'] = $data['code'];
            }
            // استخدام الوصف كاسم
            $data['name'] = $data['description'];
        }

        // فحص وجود المادة بنفس الكود في نفس أمر العمل
        if ($this->materialCodeExists($data['code'], $workOrder->id)) {
            // بدلاً من منع الإضافة، نقوم بإنشاء كود فريد
            $originalCode = $data['code'];
            $counter = 1;
            
            do {
                $data['code'] = $originalCode . '-' . $counter;
                $counter++;
            } while ($this->materialCodeExists($data['code'], $workOrder->id));
            
            // إضافة ملاحظة للمستخدم عن تغيير الكود
            $codeChangeMessage = "تم تغيير كود المادة من '{$originalCode}' إلى '{$data['code']}' لتجنب التكرار";
        }

        try {
            // إنشاء المادة
            $material = Material::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            // التعامل مع خطأ الكود المكرر كإجراء احتياطي
            if ($e->getCode() == 23000) {
                // محاولة أخيرة بكود عشوائي
                $data['code'] = $data['code'] . '-' . time();
                $material = Material::create($data);
                $codeChangeMessage = "تم تغيير كود المادة إلى '{$data['code']}' لتجنب التكرار";
            } else {
                throw $e; // إعادة رمي الخطأ إذا لم يكن خطأ كود مكرر
            }
        }

        // التعامل مع الملفات المرفقة
        $this->handleFileUploads($request, $material);

        $successMessage = 'تم إضافة المادة بنجاح - ' . ($data['name'] ?? $data['code']);
        
        // إضافة رسالة تغيير الكود إذا حدث
        if (isset($codeChangeMessage)) {
            $successMessage .= '. ' . $codeChangeMessage;
        }

        return redirect()->route('admin.work-orders.materials.index', $workOrder);
    }

    /**
     * Add all remaining materials from work order materials to materials list.
     */
    public function addAllFromWorkOrderMaterials(WorkOrder $workOrder)
    {
        try {
            $workOrderMaterials = $workOrder->workOrderMaterials()->with('material')->get();
            $existingMaterials = $workOrder->materials()->get()->keyBy('code');
            
            $addedCount = 0;
            $skippedCount = 0;
            $errors = [];
            
            foreach ($workOrderMaterials as $workOrderMaterial) {
                $materialCode = $workOrderMaterial->material->code ?? '';
                $originalCode = $materialCode;
                
                // إنشاء كود فريد إذا كان الكود موجوداً
                if ($this->materialCodeExists($materialCode, $workOrder->id)) {
                    $counter = 1;
                    do {
                        $materialCode = $originalCode . '-' . $counter;
                        $counter++;
                    } while ($this->materialCodeExists($materialCode, $workOrder->id));
                }
                
                try {
                    // إعداد بيانات المادة
                    $data = [
                        'code' => $materialCode,
                        'description' => $workOrderMaterial->material->description ?? '',
                        'name' => $workOrderMaterial->material->name ?? $workOrderMaterial->material->description ?? $materialCode,
                        'planned_quantity' => $workOrderMaterial->quantity ?? 0,
                        'spent_quantity' => 0,
                        'executed_quantity' => 0,
                        'unit' => $workOrderMaterial->material->unit ?? 'قطعة',
                        'line' => '',
                        'work_order_id' => $workOrder->id,
                        'work_order_number' => $workOrder->order_number,
                        'subscriber_name' => $workOrder->subscriber_name,
                    ];

                    // إنشاء المادة
                    Material::create($data);
                    $addedCount++;
                    
                    // إضافة ملاحظة إذا تم تغيير الكود
                    if ($materialCode !== $originalCode) {
                        $errors[] = "تم إضافة المادة {$originalCode} بكود جديد: {$materialCode}";
                    }
                    
                } catch (\Illuminate\Database\QueryException $e) {
                    // التعامل مع خطأ الكود المكرر
                    if ($e->getCode() == 23000) {
                        // محاولة بكود عشوائي
                        try {
                            $data['code'] = $originalCode . '-' . time() . '-' . rand(100, 999);
                            Material::create($data);
                            $addedCount++;
                            $errors[] = "تم إضافة المادة {$originalCode} بكود جديد: {$data['code']}";
                        } catch (\Exception $e2) {
                            $errors[] = "فشل في إضافة المادة {$originalCode}: " . $e2->getMessage();
                        }
                    } else {
                        $errors[] = "خطأ في إضافة المادة {$originalCode}: " . $e->getMessage();
                    }
                } catch (\Exception $e) {
                    $errors[] = "خطأ في إضافة المادة {$originalCode}: " . $e->getMessage();
                }
            }
            
            // إرجاع JSON response للتعامل مع AJAX
            if (!empty($errors)) {
                return response()->json([
                    'success' => true,
                    'notes' => $errors,
                    'added_count' => $addedCount,
                    'skipped_count' => $skippedCount
                ]);
            }
            
            return response()->json([
                'success' => true,
                'added_count' => $addedCount,
                'skipped_count' => $skippedCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المواد: ' . $e->getMessage(),
                'added_count' => 0,
                'skipped_count' => 0
            ]);
        }
    }

    /**
     * Helper method to check if material code already exists for a work order
     */
    private function materialCodeExists($code, $workOrderId)
    {
        return Material::where('code', $code)
                      ->where('work_order_id', $workOrderId)
                      ->exists();
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

        return redirect()->route('admin.work-orders.materials.index', $workOrder);
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
            
            return redirect()->route('admin.work-orders.materials.index', $workOrder);
        } catch (\Exception $e) {
            \Log::error('Error deleting material: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المادة');
        }
    }

    /**
     * Get material description by code
     */
    public function getDescriptionByCode(Request $request, $code)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $cityName = $this->getProjectCityName($project);
            
            $normalizedCode = trim(mb_strtolower($code));
            \Log::info('Searching for material code', ['code' => $normalizedCode, 'city' => $cityName]);
            
            $material = ReferenceMaterial::whereRaw('LOWER(TRIM(code)) = ?', [$normalizedCode])
                                       ->where('city', $cityName)
                                       ->where('is_active', true)
                                       ->first();
                                       
            \Log::info('Material search result', ['found' => (bool)$material, 'material' => $material]);
            
            if ($material) {
                return response()->json([
                    'success' => true,
                    'description' => $material->description,
                    'city' => $material->city
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على المادة',
                'code' => $code,
                'city' => $cityName
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
     * Get city name from project identifier
     */
    private function getProjectCityName($project)
    {
        return match($project) {
            'riyadh' => 'الرياض',
            'madinah' => 'المدينة المنورة',
            default => 'الرياض'
        };
    }

    /**
     * Export materials to Excel for a specific work order
     */
    public function exportExcel(WorkOrder $workOrder)
    {
        try {
            // التحقق من وجود مواد لأمر العمل
            $materialsCount = $workOrder->materials()->count();
            if ($materialsCount === 0) {
                return redirect()->back()->with('error', 'لا توجد مواد لتصديرها');
            }

            $fileName = 'مواد_أمر_العمل_' . $workOrder->order_number . '_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new MaterialsExport($workOrder->id), $fileName);
        } catch (\Exception $e) {
            \Log::error('Error exporting materials: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
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
     * Handle file uploads for materials and return uploaded files info
     */
    private function handleFileUploadsWithReturn(Request $request, Material $material, $isUpdate = false)
    {
        $fileFields = [
            'check_in_file' => ['label' => 'CHECK LIST', 'icon' => 'fas fa-list-check', 'color' => 'text-primary'],
            'check_out_file' => ['label' => 'CHECK OUT FILE', 'icon' => 'fas fa-check-circle', 'color' => 'text-success'],
            'stock_in_file' => ['label' => 'STORE IN', 'icon' => 'fas fa-sign-in-alt', 'color' => 'text-info'],
            'stock_out_file' => ['label' => 'STORE OUT', 'icon' => 'fas fa-sign-out-alt', 'color' => 'text-warning'],
            'gate_pass_file' => ['label' => 'GATE PASS', 'icon' => 'fas fa-id-card', 'color' => 'text-success'],
            'ddo_file' => ['label' => 'DDO FILE', 'icon' => 'fas fa-file-alt', 'color' => 'text-primary']
        ];

        $uploadedFiles = [];

        foreach ($fileFields as $field => $info) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('materials', $fileName, 'public');
                
                // حذف الملف القديم إذا كان التحديث
                if ($isUpdate && $material->$field) {
                    Storage::disk('public')->delete($material->$field);
                }
                
                $material->$field = $filePath;
                
                // إضافة معلومات الملف المرفوع
                $uploadedFiles[] = [
                    'material_id' => $material->id,
                    'file_type' => $field,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_info' => $info,
                    'created_at' => now()
                ];
            }
        }
        
        $material->save();
        return $uploadedFiles;
    }

    /**
     * Upload files for materials in bulk
     */
    public function uploadFiles(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
            'check_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
            'stock_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
            'stock_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
            'ddo_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240',
        ]);

        try {
            // إنشاء مادة وهمية لحفظ الملفات العامة
            $generalMaterial = Material::create([
                'work_order_id' => $workOrder->id,
                'work_order_number' => $workOrder->order_number,
                'subscriber_name' => $workOrder->subscriber_name,
                'code' => 'GENERAL_FILES_' . time(),
                'description' => 'ملفات عامة للمواد',
                'planned_quantity' => 0,
                'spent_quantity' => 0,
                'executed_quantity' => 0,
                'unit' => 'قطعة',
                'line' => 'ملفات عامة'
            ]);

            // رفع الملفات
            $uploadedFiles = $this->handleFileUploadsWithReturn($request, $generalMaterial);

            // إرجاع البيانات مع معلومات الملفات المرفوعة
            return redirect()->route('admin.work-orders.materials.index', $workOrder)
                ->with('uploaded_files', $uploadedFiles);
        } catch (\Exception $e) {
            \Log::error('Error uploading material files: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    /**
     * Import reference materials from Excel and save to DB
     */
    public function importReferenceMaterials(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);
        try {
            $import = new \App\Imports\ReferenceMaterialsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            $imported = $import->getImportedMaterials();
            return response()->json([
                'success' => true,
                'imported_count' => count($imported),
                'imported_materials' => $imported
            ]);
        } catch (\Exception $e) {
            \Log::error('Error importing reference materials: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد المواد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific file from a material
     */
    public function deleteFile(Request $request, WorkOrder $workOrder, Material $material)
    {
        // التأكد من أن المادة تنتمي لأمر العمل
        if ($material->work_order_id !== $workOrder->id) {
            abort(404);
        }

        $fileType = $request->input('file_type');
        
        $allowedFileTypes = [
            'check_in_file',
            'check_out_file',
            'stock_in_file',
            'stock_out_file',
            'gate_pass_file',
            'store_in_file',
            'store_out_file',
            'ddo_file'
        ];

        if (!in_array($fileType, $allowedFileTypes)) {
            return redirect()->back()
                ->with('error', 'نوع الملف غير صحيح');
        }

        try {
            // حذف الملف من التخزين
            if ($material->$fileType) {
                Storage::disk('public')->delete($material->$fileType);
                
                // تحديث قاعدة البيانات
                $material->$fileType = null;
                $material->save();
            }

            return redirect()->route('admin.work-orders.materials.index', $workOrder);
        } catch (\Exception $e) {
            \Log::error('Error deleting material file: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الملف');
        }
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

    /**
     * البحث عن مادة بالكود
     */
    public function search(Request $request)
    {
        try {
            $code = $request->query('code');
            $project = $request->get('project', 'riyadh');
            $cityName = $this->getProjectCityName($project);
            
            if (empty($code)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود المادة مطلوب'
                ], 400);
            }

            $material = ReferenceMaterial::where('code', $code)
                                       ->where('city', $cityName)
                                       ->where('is_active', true)
                                       ->first();
            
            return response()->json([
                'success' => true,
                'material' => $material,
                'city' => $cityName
            ]);
        } catch (\Exception $e) {
            \Log::error('Error searching material: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث عن المادة'
            ], 500);
        }
    }

    /**
     * حفظ مادة في جدول المواد المرجعية
     */
    public function saveReference(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:255',
                'description' => 'required|string',
                'unit' => 'nullable|string|max:50'
            ]);

            $project = $request->get('project', 'riyadh');
            $cityName = $this->getProjectCityName($project);

            $material = ReferenceMaterial::updateOrCreate(
                ['code' => $validated['code'], 'city' => $cityName],
                [
                    'description' => $validated['description'],
                    'unit' => $validated['unit'] ?? 'قطعة',
                    'city' => $cityName,
                    'is_active' => true
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ المادة بنجاح لمدينة ' . $cityName,
                'material' => $material
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving reference material: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ المادة'
            ], 500);
        }
    }

    /**
     * Update material quantity via AJAX
     */
    public function updateQuantity(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'material_id' => 'required|exists:materials,id',
                'quantity_type' => 'required|in:spent,executed',
                'value' => 'required|numeric|min:0'
            ]);
            
            $material = Material::findOrFail($validated['material_id']);
            
            // التأكد من أن المادة تنتمي لأمر العمل
            if ($material->work_order_id !== $workOrder->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'المادة غير موجودة في أمر العمل هذا'
                ], 403);
            }
            
            // تحديث الكمية المناسبة
            $field = $validated['quantity_type'] . '_quantity';
            $material->$field = $validated['value'];
            $material->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الكمية: ' . $e->getMessage()
            ], 500);
        }
    }
}
