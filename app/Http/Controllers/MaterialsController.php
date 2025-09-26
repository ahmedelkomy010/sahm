<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\WorkOrder;
use App\Models\WorkOrderMaterial;
use App\Models\ReferenceMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialsExport;

class MaterialsController extends Controller
{
    /**
     * Display general materials overview for Riyadh project.
     */
    public function riyadhOverview(Request $request)
    {
        return $this->cityOverview($request, 'riyadh');
    }

    /**
     * Display general materials overview for Madinah project.
     */
    public function madinahOverview(Request $request)
    {
        return $this->cityOverview($request, 'madinah');
    }

    /**
     * Display general materials overview for a specific city.
     */
    public function cityOverview(Request $request, $cityName = 'riyadh')
    {
        $citySearchTerms = $this->getCitySearchTerms($cityName);
        
        // جلب جميع أوامر العمل للمدينة المحددة
        $cityWorkOrders = WorkOrder::where(function($query) use ($citySearchTerms) {
                foreach ($citySearchTerms as $term) {
                    $query->orWhere('project_name', 'like', "%{$term}%")
                          ->orWhere('project_description', 'like', "%{$term}%")
                          ->orWhere('city', 'like', "%{$term}%");
                }
            })
            ->pluck('id');

        // Debug: Log the search results
        \Log::info("City search for {$cityName}", [
            'search_terms' => $citySearchTerms,
            'found_work_orders' => $cityWorkOrders->count(),
            'work_order_ids' => $cityWorkOrders->toArray()
        ]);

        // التحقق من وجود أوامر عمل للمدينة
        if ($cityWorkOrders->isEmpty()) {
            // Get some sample project names for debugging
            $sampleProjects = WorkOrder::select('project_name', 'city', 'project_description')
                ->whereNotNull('project_name')
                ->orWhereNotNull('city')
                ->take(10)
                ->get();
            
            \Log::info("Sample projects in database", [
                'projects' => $sampleProjects->toArray()
            ]);

            // إذا لم توجد أوامر عمل، إنشاء paginator فارغ
            $materials = new LengthAwarePaginator(
                collect(),
                0,
                50,
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );
            $units = collect();
            $workOrders = collect();
            
            $viewName = $cityName === 'madinah' ? 'admin.materials.madinah-overview' : 'admin.materials.riyadh-overview';
            $project = $cityName === 'madinah' ? 'madinah' : 'riyadh';
            return view($viewName, compact(
                'materials', 
                'units', 
                'workOrders',
                'project'
            ))->with('cityName', $cityName);
        }

        // جلب جميع المواد للمدينة وتجميعها بالكود
        $materialsQuery = Material::whereIn('work_order_id', $cityWorkOrders)
            ->with(['workOrder'])
            ->where('code', 'NOT LIKE', 'GENERAL_FILES_%')
            ->where(function($q) {
                $q->where('line', '!=', 'ملفات مستقلة')
                  ->orWhereNull('line');
            })
            ->where(function($q) {
                // استبعاد الملفات المستقلة بناءً على الكود
                $q->where('code', 'NOT LIKE', 'check_in_file_%')
                  ->where('code', 'NOT LIKE', 'check_out_file_%')
                  ->where('code', 'NOT LIKE', 'stock_in_file_%')
                  ->where('code', 'NOT LIKE', 'stock_out_file_%')
                  ->where('code', 'NOT LIKE', 'gate_pass_file_%')
                  ->where('code', 'NOT LIKE', 'ddo_file_%');
            });

        // تطبيق الفلاتر
        if ($request->filled('search_code')) {
            $materialsQuery->where('code', 'like', '%' . $request->search_code . '%');
        }

        if ($request->filled('work_order_number')) {
            $materialsQuery->whereHas('workOrder', function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->work_order_number . '%');
            });
        }

        if ($request->filled('unit_filter')) {
            $materialsQuery->where('unit', $request->unit_filter);
        }

        if ($request->filled('work_order_filter')) {
            $materialsQuery->where('work_order_id', $request->work_order_filter);
        }

        // جلب جميع المواد
        $allMaterials = $materialsQuery->get();
        
        // تجميع المواد بالكود وحساب الرصيد الإجمالي
        $groupedMaterials = $allMaterials->groupBy('code')->map(function($materials, $code) {
            $firstMaterial = $materials->first();
            
            // حساب الرصيد الإجمالي لكل مادة
            $totalSpentQuantity = $materials->sum('spent_quantity') ?? 0;
            $totalRecoveryQuantity = $materials->sum('recovery_quantity') ?? 0;
            $totalExecutedQuantity = $materials->sum('executed_quantity') ?? 0;
            $totalCompletionQuantity = $materials->sum('completion_quantity') ?? 0;
            
            $finalBalance = $totalSpentQuantity + $totalRecoveryQuantity - $totalExecutedQuantity - $totalCompletionQuantity;
            
            // جمع أوامر العمل المرتبطة
            $workOrders = $materials->map(function($material) {
                return $material->workOrder;
            })->filter()->unique('id');
            
            return (object) [
                'id' => $firstMaterial->id,
                'code' => $code,
                'name' => $firstMaterial->name,
                'description' => $firstMaterial->description ?: $firstMaterial->name,
                'unit' => $firstMaterial->unit,
                'total_spent_quantity' => $totalSpentQuantity,
                'total_recovery_quantity' => $totalRecoveryQuantity,
                'total_executed_quantity' => $totalExecutedQuantity,
                'total_completion_quantity' => $totalCompletionQuantity,
                'final_balance' => $finalBalance,
                'work_orders' => $workOrders,
                'work_orders_count' => $workOrders->count(),
                'created_at' => $firstMaterial->created_at,
            ];
        });

        // استبعاد المواد المتوازنة (رصيد = صفر) تلقائياً
        $groupedMaterials = $groupedMaterials->filter(function($material) {
            return $material->final_balance != 0;
        });

        // تطبيق فلتر الرصيد النهائي على المواد المجمعة
        if ($request->filled('balance_filter')) {
            $balanceFilter = $request->balance_filter;
            
            $groupedMaterials = $groupedMaterials->filter(function($material) use ($balanceFilter) {
                switch ($balanceFilter) {
                    case 'positive':
                        return $material->final_balance > 0;
                    case 'negative':
                        return $material->final_balance < 0;
                    default:
                        return true;
                }
            });
        }

        // تطبيق فلتر نوع العملية
        if ($request->filled('operation_filter')) {
            $operationFilter = $request->operation_filter;
            
            $groupedMaterials = $groupedMaterials->filter(function($material) use ($operationFilter) {
                switch ($operationFilter) {
                    case 'recovery':
                        return $material->total_recovery_quantity > 0;
                    case 'completion':
                        return $material->total_completion_quantity > 0;
                    default:
                        return true;
                }
            });
        }

        // ترتيب المواد حسب الكود
        $groupedMaterials = $groupedMaterials->sortBy('code');

        // تطبيق التصفح اليدوي
        $currentPage = request()->get('page', 1);
        $perPage = 50;
        $total = $groupedMaterials->count();
        $items = $groupedMaterials->forPage($currentPage, $perPage)->values();

        $materials = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
        $materials->appends(request()->query());

        // جلب قائمة الوحدات المتاحة للفلتر
        $units = Material::whereIn('work_order_id', $cityWorkOrders)
            ->whereNotNull('unit')
            ->distinct()
            ->pluck('unit')
            ->sort();

        // جلب قائمة أوامر العمل للفلتر
        $workOrders = WorkOrder::whereIn('id', $cityWorkOrders)
            ->select('id', 'order_number', 'project_name', 'project_description')
            ->orderBy('order_number')
            ->get();

            $viewName = $cityName === 'madinah' ? 'admin.materials.madinah-overview' : 'admin.materials.riyadh-overview';
            $project = $cityName === 'madinah' ? 'madinah' : 'riyadh';
            return view($viewName, compact(
                'materials', 
                'units', 
                'workOrders',
                'project'
            ))->with('cityName', $cityName);
    }

    /**
     * Display a listing of the resource for a specific work order.
     */
    public function index(WorkOrder $workOrder, Request $request)
    {
        $query = $workOrder->materials()
            ->where('code', 'NOT LIKE', 'GENERAL_FILES_%')
            ->where(function($q) {
                $q->where('line', '!=', 'ملفات مستقلة')
                  ->orWhereNull('line');
            })
            ->where(function($q) {
                // استبعاد الملفات المستقلة بناءً على الكود
                $q->where('code', 'NOT LIKE', 'check_in_file_%')
                  ->where('code', 'NOT LIKE', 'check_out_file_%')
                  ->where('code', 'NOT LIKE', 'stock_in_file_%')
                  ->where('code', 'NOT LIKE', 'stock_out_file_%')
                  ->where('code', 'NOT LIKE', 'gate_pass_file_%')
                  ->where('code', 'NOT LIKE', 'ddo_file_%');
            });

        // البحث بالكود
        if ($request->filled('search_code')) {
            $query->where('code', 'like', '%' . $request->search_code . '%');
        }

        // البحث برقم أمر العمل
        if ($request->filled('work_order_number')) {
            $query->whereHas('workOrder', function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->work_order_number . '%');
            });
        }

        // فلتر الوحدة
        if ($request->filled('unit_filter')) {
            $query->where('unit', $request->unit_filter);
        }

        // فلتر الرصيد النهائي
        if ($request->filled('balance_filter')) {
            $balanceFilter = $request->balance_filter;
            
            $query->where(function($q) use ($balanceFilter) {
                // حساب الرصيد النهائي لكل مادة
                $q->whereRaw("
                    CASE 
                        WHEN ? = 'positive' THEN (COALESCE(spent_quantity, 0) + COALESCE(recovery_quantity, 0) - COALESCE(executed_quantity, 0) - COALESCE(completion_quantity, 0)) > 0
                        WHEN ? = 'negative' THEN (COALESCE(spent_quantity, 0) + COALESCE(recovery_quantity, 0) - COALESCE(executed_quantity, 0) - COALESCE(completion_quantity, 0)) < 0
                        WHEN ? = 'zero' THEN (COALESCE(spent_quantity, 0) + COALESCE(recovery_quantity, 0) - COALESCE(executed_quantity, 0) - COALESCE(completion_quantity, 0)) = 0
                        ELSE 1 = 1
                    END
                ", [$balanceFilter, $balanceFilter, $balanceFilter]);
            });
        }

        $materials = $query->latest()->paginate(15)->appends($request->query());
        
        // جلب بيانات مقايسة المواد من work_order_materials
        $workOrderMaterials = $workOrder->workOrderMaterials()->with('material')->get();
        
        // جلب الملفات المستقلة (الملفات العامة والجديدة)
        $generalFiles = $workOrder->materials()
            ->where(function($q) {
                $q->where('code', 'LIKE', 'GENERAL_FILES_%')
                  ->orWhere('line', '=', 'ملفات مستقلة')
                  ->orWhere('code', 'LIKE', 'check_in_file_%')
                  ->orWhere('code', 'LIKE', 'check_out_file_%')
                  ->orWhere('code', 'LIKE', 'stock_in_file_%')
                  ->orWhere('code', 'LIKE', 'stock_out_file_%')
                  ->orWhere('code', 'LIKE', 'gate_pass_file_%')
                  ->orWhere('code', 'LIKE', 'ddo_file_%');
            })
            ->get();
            
        // إضافة debugging
        \Log::info('Independent files found', [
            'work_order_id' => $workOrder->id,
            'files_count' => $generalFiles->count(),
            'files' => $generalFiles->toArray()
        ]);
            
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
        
         // إضافة logging للتأكد من البيانات
         \Log::info('Removal scrap materials for work order ' . $workOrder->id, [
             'data' => $workOrder->removal_scrap_materials,
             'count' => count($workOrder->removal_scrap_materials ?? [])
         ]);
         
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
        try {
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

            // إنشاء المادة
            $material = Material::create($data);

            // التعامل مع الملفات المرفقة
            $this->handleFileUploads($request, $material);

            $successMessage = 'تم إضافة المادة بنجاح - ' . ($data['name'] ?? $data['code']);
            
            // إضافة رسالة تغيير الكود إذا حدث
            if (isset($codeChangeMessage)) {
                $successMessage .= '. ' . $codeChangeMessage;
            }

            // التحقق من نوع الطلب - إذا كان AJAX ترجع JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'material' => $material,
                    'code_changed' => isset($codeChangeMessage)
                ]);
            }

            return redirect()->route('admin.work-orders.materials.index', $workOrder)
                ->with('success', $successMessage);
            
        } catch (\Exception $e) {
            // معالجة عامة نهائية للأخطاء
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إضافة المادة: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المادة: ' . $e->getMessage());
        }
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
        
        // التأكد من أن الوحدة لا تكون null
        if (empty($data['unit'])) {
            $data['unit'] = 'قطعة'; // وحدة افتراضية
        }
        
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
            
            // التحقق من نوع الطلب
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الملف بنجاح'
                ]);
            }
            
            return redirect()->route('admin.work-orders.materials.index', $workOrder)
                ->with('success', 'تم حذف المادة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting material: ' . $e->getMessage());
            
            // التحقق من نوع الطلب
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الملف: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المادة');
        }
    }

    /**
     * Remove WorkOrderMaterial from work order
     */
    public function destroyWorkOrderMaterial(WorkOrderMaterial $workOrderMaterial)
    {
        try {
            $workOrderMaterial->delete();
            
            return redirect()->back()->with('success', 'تم حذف المادة من المقايسة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting work order material: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المادة من المقايسة');
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
                $files = $request->file($field);
                
                // التأكد من أن $files هو array
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                foreach ($files as $index => $file) {
                    $fileName = time() . '_' . uniqid() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('materials', $fileName, 'public');
                    
                    // حذف الملف القديم إذا كان التحديث (فقط للملف الأول)
                    if ($isUpdate && $index === 0 && $material->$field) {
                        Storage::disk('public')->delete($material->$field);
                    }
                    
                    // حفظ مسار الملف الأول في حقل المادة (للتوافق مع النظام القديم)
                    if ($index === 0) {
                        $material->$field = $filePath;
                    }
                    
                    // إضافة معلومات الملف المرفوع
                    $uploadedFiles[] = [
                        'material_id' => $material->id,
                        'file_type' => $field,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_info' => $info,
                        'file_index' => $index,
                        'created_at' => now()
                    ];
                }
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
        // تحديد قواعد التحقق الديناميكية
        $rules = [];
        $fileFields = ['check_in_file', 'check_out_file', 'stock_in_file', 'stock_out_file', 'gate_pass_file', 'ddo_file'];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if (is_array($request->file($field))) {
                    $rules[$field . '.*'] = 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240';
                } else {
                    $rules[$field] = 'file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:10240';
                }
            }
        }
        
        if (!empty($rules)) {
            $request->validate($rules);
        }

        try {
            // إضافة logging للتشخيص
            \Log::info('Upload files request received', [
                'has_files' => !empty($request->allFiles()),
                'all_files' => $request->allFiles(),
                'input_keys' => array_keys($request->all())
            ]);
            
            $uploadedFilesCount = 0;
            $allUploadedFiles = [];
            
            // معالجة كل نوع ملف على حدة
            $fileFields = [
                'check_in_file' => 'CHECK LIST',
                'check_out_file' => 'CHECK OUT FILE',
                'stock_in_file' => 'STORE IN',
                'stock_out_file' => 'STORE OUT',
                'gate_pass_file' => 'GATE PASS',
                'ddo_file' => 'DDO FILE'
            ];
            
            foreach ($fileFields as $fieldName => $fieldLabel) {
                if ($request->hasFile($fieldName)) {
                    $files = $request->file($fieldName);
                    
                    // التأكد من أن $files هو array
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    
                    foreach ($files as $index => $file) {
                        // إنشاء مادة منفصلة لكل ملف
                        $material = Material::create([
                            'work_order_id' => $workOrder->id,
                            'work_order_number' => $workOrder->order_number,
                            'subscriber_name' => $workOrder->subscriber_name,
                            'code' => $fieldName . '_' . time() . '_' . $index,
                            'description' => $fieldLabel . ' - ملف ' . ($index + 1),
                            'planned_quantity' => 0,
                            'spent_quantity' => 0,
                            'executed_quantity' => 0,
                            'unit' => 'قطعة',
                            'line' => 'ملفات مستقلة'
                        ]);
                        
                        // حفظ الملف
                        $fileName = time() . '_' . uniqid() . '_' . $fieldName . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('materials', $fileName, 'public');
                        
                        // حفظ مسار الملف في المادة
                        $material->$fieldName = $filePath;
                        $material->save();
                        
                        $allUploadedFiles[] = [
                            'material_id' => $material->id,
                            'file_type' => $fieldName,
                            'file_path' => $filePath,
                            'file_name' => $file->getClientOriginalName(),
                            'field_label' => $fieldLabel
                        ];
                        
                        $uploadedFilesCount++;
                    }
                }
            }

            if ($uploadedFilesCount > 0) {
                return redirect()->route('admin.work-orders.materials.index', $workOrder)
                    ->with('success', "تم رفع {$uploadedFilesCount} ملف بنجاح")
                    ->with('uploaded_files', $allUploadedFiles);
            } else {
                return redirect()->back()
                    ->with('warning', 'لم يتم اختيار أي ملفات للرفع');
            }
        } catch (\Exception $e) {
            \Log::error('Error uploading material files: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage());
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
                'quantity_type' => 'required|in:spent,executed,completion,recovery',
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
            
            // التأكد من أن الوحدة ليست null قبل الحفظ
            if (empty($material->unit)) {
                $material->unit = 'قطعة';
            }
            
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

    /**
     * Update material notes via AJAX
     */
    public function updateNotes(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'material_id' => 'required|exists:materials,id',
                'notes_type' => 'required|in:spent_notes,executed_notes',
                'value' => 'nullable|string|max:1000'
            ]);
            
            $material = Material::findOrFail($validated['material_id']);
            
            // التأكد من أن المادة تنتمي لأمر العمل
            if ($material->work_order_id !== $workOrder->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'المادة غير موجودة في أمر العمل هذا'
                ], 403);
            }
            
            // تحديث الملاحظات المناسبة
            $field = $validated['notes_type'];
            $material->$field = $validated['value'];
            
            // التأكد من أن الوحدة ليست null قبل الحفظ
            if (empty($material->unit)) {
                $material->unit = 'قطعة';
            }
            
            $material->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملاحظات بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الملاحظات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save materials notes for work order
     */
    public function saveMaterialsNotes(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'materials_notes' => 'nullable|string|max:65535'
            ]);

            $workOrder->materials_notes = $request->input('materials_notes');
            $workOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملاحظات بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving materials notes: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الملاحظات'
            ], 500);
        }
    }

    /**
     * Get search terms for different cities
     */
    private function getCitySearchTerms($cityName)
    {
        $searchTerms = [
            'riyadh' => ['الرياض', 'riyadh', 'Riyadh', 'RIYADH'],
            'jeddah' => ['جدة', 'jeddah', 'Jeddah', 'JEDDAH'],
            'dammam' => ['الدمام', 'dammam', 'Dammam', 'DAMMAM'],
            'makkah' => ['مكة', 'مكة المكرمة', 'makkah', 'Makkah', 'MAKKAH'],
            'madinah' => ['المدينة', 'المدينة المنورة', 'madinah', 'Madinah', 'MADINAH'],
        ];

        return $searchTerms[strtolower($cityName)] ?? [$cityName];
    }

    /**
     * إضافة مادة إزالة/سكراب جديدة
     */
    public function addRemovalScrapMaterial(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'material_code' => 'required|string|max:50',
                'material_description' => 'required|string|max:500',
                'unit' => 'required|string|max:20',
                'quantity' => 'required|numeric|min:0.01',
                'notes' => 'nullable|string|max:1000'
            ]);

            // الحصول على المواد الموجودة
            $existingMaterials = $workOrder->removal_scrap_materials ?? [];

            // إضافة المادة الجديدة
            $newMaterial = [
                'id' => uniqid(), // معرف فريد للمادة
                'material_code' => $validated['material_code'],
                'material_description' => $validated['material_description'],
                'unit' => $validated['unit'],
                'quantity' => (float)$validated['quantity'],
                'notes' => $validated['notes'] ?? null,
                'created_at' => now()->toISOString()
            ];

            $existingMaterials[] = $newMaterial;

            // حفظ في قاعدة البيانات
            $workOrder->update([
                'removal_scrap_materials' => $existingMaterials
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة مادة الإزالة بنجاح',
                'material' => $newMaterial
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المادة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف مادة إزالة/سكراب
     */
    public function deleteRemovalScrapMaterial(Request $request, WorkOrder $workOrder, $index)
    {
        try {
            // الحصول على المواد الموجودة
            $existingMaterials = $workOrder->removal_scrap_materials ?? [];

            // التحقق من صحة الفهرس
            if (!isset($existingMaterials[$index])) {
                return response()->json([
                    'success' => false,
                    'message' => 'المادة المطلوب حذفها غير موجودة'
                ], 404);
            }

            // حذف المادة
            unset($existingMaterials[$index]);
            
            // إعادة ترتيب الفهارس
            $existingMaterials = array_values($existingMaterials);

            // حفظ في قاعدة البيانات
            $workOrder->update([
                'removal_scrap_materials' => $existingMaterials
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف مادة الإزالة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المادة: ' . $e->getMessage()
            ], 500);
        }
    }
}
