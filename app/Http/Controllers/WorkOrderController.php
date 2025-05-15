<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderFile;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\License;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Handle project selection
        $project = $request->query('project', session('selected_project'));
        
        if ($project) {
            session(['selected_project' => $project]);
        }
        
        // Get work orders filtered by project if project is selected
        $workOrdersQuery = WorkOrder::latest();
        
        if ($project) {
            $workOrdersQuery->where('project', $project);
        }
        
        $workOrders = $workOrdersQuery->paginate(10);
        
        return view('admin.work_orders.index', compact('workOrders', 'project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $project = session('selected_project');
        return view('admin.work_orders.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:work_orders',
            'work_type' => 'required|string|max:999',
            'work_description' => 'required|string',
            'approval_date' => 'required|date',
            'subscriber_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'station_number' => 'nullable|string|max:255',
            'consultant_name' => 'nullable|string|max:255',
            'order_value_with_consultant' => 'required|numeric|min:0',
            'order_value_without_consultant' => 'required|numeric|min:0',
            'execution_status' => 'required|in:1,2,3,4,5,6',
            'actual_execution_value' => 'nullable|numeric|min:0',
            'procedure_155_delivery_date' => 'nullable|date',
            'procedure_211_date' => 'nullable|date',
            'partial_deletion' => 'nullable|boolean',
            'partial_payment_value' => 'nullable|numeric|min:0',
            'extract_number' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_order_number' => 'nullable|string|max:255',
            'tax_value' => 'nullable|numeric|min:0',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', // Max 10MB per file
        ]);

        $validated['user_id'] = Auth::id();
        $validated['project'] = session('selected_project', 'riyadh'); // Default to 'riyadh' if not set

        $workOrder = WorkOrder::create($validated);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                // Crear el directorio si no existe
                $path = 'work_orders/' . $workOrder->id;
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                // Guardar el archivo
                $filePath = $file->storeAs($path, $filename, 'public');
                
                WorkOrderFile::create([
                    'work_order_id' => $workOrder->id,
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('admin.work-orders.index')
            ->with('success', 'تم إنشاء أمر العمل بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        $workOrder->load('files');
        $workOrder->load(['surveys' => function($query) {
            $query->latest();
        }, 'surveys.files']);
        return view('admin.work_orders.show', compact('workOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        $workOrder->load('files');
        return view('admin.work_orders.edit', compact('workOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        // Debug logging to check if the request is reaching the controller
        \Illuminate\Support\Facades\Log::info('Work order update request received', [
            'request_data' => $request->all(),
            'work_order_id' => $workOrder->id,
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        // Convert string dates to proper date objects if needed
        if ($request->has('extract_date') && $request->input('extract_date')) {
            try {
                // Ensure the date is properly formatted
                $extractDate = \Carbon\Carbon::parse($request->input('extract_date'))->format('Y-m-d');
                $request->merge(['extract_date' => $extractDate]);
            } catch (\Exception $e) {
                // Log the error but continue
                \Illuminate\Support\Facades\Log::error('Date parsing error: ' . $e->getMessage());
            }
        }
        
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:work_orders,order_number,' . $workOrder->id,
            'work_type' => 'required|string|max:999',
            'work_description' => 'required|string',
            'approval_date' => 'required|date',
            'subscriber_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'station_number' => 'nullable|string|max:255',
            'consultant_name' => 'nullable|string|max:255',
            'order_value_with_consultant' => 'required|numeric|min:0',
            'order_value_without_consultant' => 'required|numeric|min:0',
            'execution_status' => 'required|in:1,2,3,4,5,6',
            'actual_execution_value' => 'nullable|numeric|min:0',
            'procedure_155_delivery_date' => 'nullable|date',
            'procedure_211_date' => 'nullable|date',
            'partial_deletion' => 'nullable|boolean',
            'partial_payment_value' => 'nullable|numeric|min:0',
            'office' => 'nullable|string|max:255',
            'extract_number' => 'nullable|string|max:255',
            'extract_date' => 'nullable|date',
            'extract_value' => 'nullable|numeric|min:0',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_order_number' => 'nullable|string|max:255',
            'tax_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', // Max 10MB per file
        ]);

        try {
            // Update the work order
            $workOrder->update($validated);
            
            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    
                    // Create directory if it doesn't exist
                    $path = 'work_orders/' . $workOrder->id;
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path);
                    }
                    
                    // Save the file
                    $filePath = $file->storeAs($path, $filename, 'public');
                    
                    WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
            
            return redirect()->route('admin.work-orders.index')
                ->with('success', 'تم تحديث أمر العمل بنجاح');
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Work order update error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث أمر العمل: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        // Delete associated files from storage
        foreach ($workOrder->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        $workOrder->delete();

        return redirect()->route('admin.work-orders.index')
            ->with('success', 'تم حذف أمر العمل بنجاح');
    }

    /**
     * Delete a file associated with a work order.
     */
    public function deleteFile($id)
    {
        $file = WorkOrderFile::findOrFail($id);
        $workOrderId = $file->work_order_id;
        
        // Delete the file from storage
        Storage::disk('public')->delete($file->file_path);
        
        // Delete the file record from database
        $file->delete();
        
        return redirect()->route('admin.work-orders.edit', $workOrderId)
            ->with('success', 'تم حذف الملف بنجاح');
    }

    /**
     * Get work descriptions based on work type.
     * 
     * @param string $workType The work type code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkDescription($workType)
    {
        $descriptions = [
            '409' => '  ازالة - نقل شبكة علي المشترك   ',
            '408' => '  أزالة عداد المشترك',
            '460' => ' استبدال الشبكات',
            '901' => ' اضافة عداد طاقة شمسية',
            '440' => 'الرفع المساحي',
            '410' => 'انشاء محطة / محول لمشترك/ مشتركين  ',
            '801' => '  تركيب عداد ايصال سريع',
            '804' => '  ايصال سريع VM تركيب محطة ش ارضية',
            '805' => 'تركيب محطة ش هوائية VM ايصال سريع',
            '480' => '(تسليم مفتاح )تمويل خارجي',
            '441' => '  تعزيز شبكة ارضية ومحطات   ',
            '442' => 'تعزيز شبكة هوائية ومحطات',
            '802' => 'ايصال سريع VL  تمديد شبكة ارضية',
            '803' => 'تمديد شبكةهوائية  VL ايصال سريع',
            '402' => 'توصيل عداد بحفرية شبكة ارضية ',
            '401' => '  أرضي / هوائي (توصيل عداد بدون حفرية ) ',
            '404' => '  VM توصيل عداد بمحطة شبكة ارضية',
            '405' => ' VM توصيل عداد بمحطة شبكة هوائية',
            '430' => 'مخططات منح وزارة البلدية',
            '450' => ' مشاريع ربط محطات التحويل',
            '403' => '  VL توصيل عداد شبكة هوائية   '
        ];
        
        // Try to get the description for the provided work type
        $description = isset($descriptions[$workType]) ? $descriptions[$workType] : '';
        
        return response()->json([
            'description' => $description,
            'success' => !empty($description)
        ]);
    }

    /**
     * Display the materials section.
     */
    public function materials()
    {
        \Log::info('Materials page is being accessed from WorkOrderController');
        try {
            // تسجيل مع تحديد المتحكم بوضوح
            \Log::info('Loading data for materials page from WorkOrderController');
            
            $workOrders = WorkOrder::where('execution_status', '!=', '5')->with('materials')->paginate(10);
            $materials = Material::with('workOrder')->latest()->paginate(20);
            
            // تسجيل بيانات التصحيح
            \Log::info('Work Orders count: ' . $workOrders->count());
            \Log::info('Materials count: ' . $materials->count());
            
            return view('admin.work_orders.materials', compact('workOrders', 'materials'));
        } catch (\Exception $e) {
            \Log::error('Error in materials page (WorkOrderController): ' . $e->getMessage());
            \Log::error('Error stack trace: ' . $e->getTraceAsString());
            abort(500, 'خطأ في تحميل صفحة المواد');
        }
    }

    /**
     * Store a new material.
     */
    public function storeMaterial(Request $request)
    {
        \Log::info('storeMaterial method called with data: ', $request->all());
        
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'code' => 'required|string|max:255',
            'description' => 'required|string',
            'planned_quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:255',
            'line' => 'nullable|string|max:255',
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'actual_quantity' => 'nullable|numeric|min:0',
        ]);

        \Log::info('Validation passed');
        
        // التعامل مع الملفات
        $data = $request->except(['check_in_file', 'gate_pass_file', 'store_in_file', 'store_out_file']);
        
        // تأكد من وضع قيم افتراضية للحقول الرقمية الفارغة
        $data['actual_quantity'] = $data['actual_quantity'] ?? 0;
        
        // تحميل ملف check_in إذا وجد
        if ($request->hasFile('check_in_file')) {
            $file = $request->file('check_in_file');
            $path = $file->store('materials/check_in', 'public');
            $data['check_in_file'] = $path;
        }
        
        // تحميل ملف gate_pass إذا وجد
        if ($request->hasFile('gate_pass_file')) {
            $file = $request->file('gate_pass_file');
            $path = $file->store('materials/gate_pass', 'public');
            $data['gate_pass_file'] = $path;
        }

        // تحميل ملف store_in إذا وجد
        if ($request->hasFile('store_in_file')) {
            $file = $request->file('store_in_file');
            $path = $file->store('materials/store_in', 'public');
            $data['store_in_file'] = $path;
        }
        
        // تحميل ملف store_out إذا وجد
        if ($request->hasFile('store_out_file')) {
            $file = $request->file('store_out_file');
            $path = $file->store('materials/store_out', 'public');
            $data['store_out_file'] = $path;
        }

        // Calculate the difference
        $planned = $data['planned_quantity'] ?? 0;
        $actual = $data['actual_quantity'] ?? 0;
        $data['difference'] = $planned - $actual;

        $material = Material::create($data);
        
        \Log::info('Material created successfully with ID: ' . $material->id);

        return redirect()->route('admin.work-orders.materials')
            ->with('success', 'تم إضافة المادة بنجاح');
    }

    /**
     * Display the form for editing a material.
     */
    public function editMaterial(Material $material)
    {
        $workOrders = WorkOrder::where('execution_status', '!=', '5')->get();
        return view('admin.work_orders.edit_material', compact('material', 'workOrders'));
    }

    /**
     * Update the specified material.
     */
    public function updateMaterial(Request $request, Material $material)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'required|string',
            'planned_quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:255',
            'line' => 'nullable|string|max:255',
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'actual_quantity' => 'nullable|numeric|min:0',
        ]);

        // التعامل مع الملفات
        $data = $request->except(['check_in_file', 'gate_pass_file', 'store_in_file', 'store_out_file']);
        
        // تحميل ملف check_in إذا وجد
        if ($request->hasFile('check_in_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->check_in_file) {
                Storage::disk('public')->delete($material->check_in_file);
            }
            
            $file = $request->file('check_in_file');
            $path = $file->store('materials/check_in', 'public');
            $data['check_in_file'] = $path;
        }
        
        // تحميل ملف gate_pass إذا وجد
        if ($request->hasFile('gate_pass_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->gate_pass_file) {
                Storage::disk('public')->delete($material->gate_pass_file);
            }
            
            $file = $request->file('gate_pass_file');
            $path = $file->store('materials/gate_pass', 'public');
            $data['gate_pass_file'] = $path;
        }

        // تحميل ملف store_in إذا وجد
        if ($request->hasFile('store_in_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->store_in_file) {
                Storage::disk('public')->delete($material->store_in_file);
            }
            
            $file = $request->file('store_in_file');
            $path = $file->store('materials/store_in', 'public');
            $data['store_in_file'] = $path;
        }
        
        // تحميل ملف store_out إذا وجد
        if ($request->hasFile('store_out_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->store_out_file) {
                Storage::disk('public')->delete($material->store_out_file);
            }
            
            $file = $request->file('store_out_file');
            $path = $file->store('materials/store_out', 'public');
            $data['store_out_file'] = $path;
        }

        // Calculate the difference
        $planned = $data['planned_quantity'] ?? 0;
        $actual = $data['actual_quantity'] ?? 0;
        $data['difference'] = $planned - $actual;

        $material->update($data);

        return redirect()->route('admin.work-orders.materials')
            ->with('success', 'تم تحديث المادة بنجاح');
    }

    /**
     * Remove the specified material.
     */
    public function destroyMaterial(Material $material)
    {
        // حذف الملفات المرفقة
        if ($material->check_in_file) {
            Storage::disk('public')->delete($material->check_in_file);
        }
        
        if ($material->gate_pass_file) {
            Storage::disk('public')->delete($material->gate_pass_file);
        }
        
        if ($material->store_in_file) {
            Storage::disk('public')->delete($material->store_in_file);
        }
        
        if ($material->store_out_file) {
            Storage::disk('public')->delete($material->store_out_file);
        }
        
        $material->delete();

        return redirect()->route('admin.work-orders.materials')
            ->with('success', 'تم حذف المادة بنجاح');
    }

    /**
     * Display the survey section.
     */
    public function survey(WorkOrder $workOrder)
    {
        return view('admin.work_orders.survey', compact('workOrder'));
    }

    /**
     * Store a new survey.
     */
    public function storeSurvey(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'survey_id' => 'nullable|exists:surveys,id',
                'start_coordinates' => 'required|string|max:255',
                'end_coordinates' => 'required|string|max:255',
                'has_obstacles' => 'required|boolean',
                'obstacles_notes' => 'nullable|string|max:1000',
                'site_images.*' => 'nullable|image|max:30720', // 30MB max per image
            ]);

            // Check if we're updating an existing survey or creating a new one
            if ($request->filled('survey_id')) {
                // Update existing survey
                $survey = Survey::findOrFail($request->survey_id);
                
                // Make sure the survey belongs to this work order
                if ($survey->work_order_id != $workOrder->id) {
                    throw new \Exception('هذا المسح لا ينتمي إلى أمر العمل الحالي');
                }
                
                $survey->update([
                    'start_coordinates' => $validated['start_coordinates'],
                    'end_coordinates' => $validated['end_coordinates'],
                    'has_obstacles' => $validated['has_obstacles'],
                    'obstacles_notes' => $validated['obstacles_notes'] ?? null,
                ]);
            } else {
                // Create new survey record
                $survey = $workOrder->surveys()->create([
                    'start_coordinates' => $validated['start_coordinates'],
                    'end_coordinates' => $validated['end_coordinates'],
                    'has_obstacles' => $validated['has_obstacles'],
                    'obstacles_notes' => $validated['obstacles_notes'] ?? null,
                ]);
            }

            // Handle file uploads
            if ($request->hasFile('site_images')) {
                $uploadPath = 'work_orders/' . $workOrder->id . '/survey';
                
                foreach ($request->file('site_images') as $image) {
                    $fileName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs($uploadPath, $fileName, 'public');
                    
                    // Create file record
                    WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'survey_id' => $survey->id,
                        'filename' => $fileName,
                        'original_filename' => $image->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $image->getMimeType(),
                        'file_size' => $image->getSize(),
                    ]);
                }
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $request->filled('survey_id') ? 'تم تحديث المسح بنجاح' : 'تم حفظ المسح بنجاح',
                    'survey' => [
                        'id' => $survey->id,
                        'start_coordinates' => $survey->start_coordinates,
                        'end_coordinates' => $survey->end_coordinates,
                        'has_obstacles' => $survey->has_obstacles,
                        'obstacles_notes' => $survey->obstacles_notes,
                        'images_count' => $survey->files()->count(),
                        'created_at' => $survey->created_at->format('Y-m-d H:i'),
                    ]
                ]);
            }

            return redirect()->back()->with('success', $request->filled('survey_id') ? 'تم تحديث المسح بنجاح' : 'تم حفظ المسح بنجاح');
        } catch (\Exception $e) {
            \Log::error('Survey store error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ المسح: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ المسح')
                ->withInput();
        }
    }

    /**
     * Display the licenses section.
     */
    public function licenses()
    {
        // بدلاً من عرض صفحة الرخص، سنقوم بإعادة التوجيه إلى صفحة رخصة أمر العمل الأول
        $firstWorkOrder = WorkOrder::first();
        
        if ($firstWorkOrder) {
            return redirect()->route('admin.work-orders.license', $firstWorkOrder);
        }
        
        // في حالة عدم وجود أوامر عمل، نعرض رسالة
        return redirect()->route('admin.work-orders.index')
            ->with('warning', 'لا توجد أوامر عمل لعرض الرخص');
    }

    public function license(WorkOrder $workOrder)
    {
        $workOrder->load(['license', 'files']);
        $workOrder->load(['surveys' => function($query) {
            $query->latest();
        }, 'surveys.files']);
        return view('admin.work_orders.license', compact('workOrder'));
    }

    /**
     * Update license information.
     */
    public function updateLicense(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('License update request data:', $request->all());

            // التحقق من صحة البيانات
            $validated = $request->validate([
                'coordination_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'has_restriction' => 'required|boolean',
                'restriction_authority' => 'required_if:has_restriction,1',
                'license_start_date' => 'nullable|date',
                'license_end_date' => 'nullable|date',
                'license_alert_days' => 'nullable|integer|min:1',
                'license_extension_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'license_extension_start_date' => 'nullable|date',
                'license_extension_end_date' => 'nullable|date',
                'license_extension_alert_days' => 'nullable|integer|min:1',
                'license_closure_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            ]);

            \Log::info('Validated data:', $validated);

            // تجهيز بيانات الرخصة
            $licenseData = [
                'work_order_id' => $workOrder->id,
                'has_restriction' => $request->boolean('has_restriction'),
                'restriction_authority' => $request->has_restriction ? $request->restriction_authority : null,
                'license_start_date' => $request->license_start_date,
                'license_end_date' => $request->license_end_date,
                'license_alert_days' => $request->license_alert_days ?? 30,
                'license_extension_start_date' => $request->license_extension_start_date,
                'license_extension_end_date' => $request->license_extension_end_date,
                'license_extension_alert_days' => $request->license_extension_alert_days ?? 30,
            ];

            \Log::info('License data before file handling:', $licenseData);

            // معالجة الملفات
            if ($request->hasFile('coordination_certificate')) {
                $file = $request->file('coordination_certificate');
                $path = $file->store('licenses/' . $workOrder->id . '/coordination_certificates', 'public');
                $licenseData['coordination_certificate_path'] = $path;
                \Log::info('Coordination certificate uploaded:', ['path' => $path]);
            }

            if ($request->hasFile('license_extension_file')) {
                $file = $request->file('license_extension_file');
                $path = $file->store('licenses/' . $workOrder->id . '/license_extensions', 'public');
                $licenseData['license_extension_file_path'] = $path;
                \Log::info('License extension file uploaded:', ['path' => $path]);
            }

            if ($request->hasFile('license_closure_file')) {
                $file = $request->file('license_closure_file');
                $path = $file->store('licenses/' . $workOrder->id . '/license_closure', 'public');
                $licenseData['license_closure_file_path'] = $path;
                \Log::info('License closure file uploaded:', ['path' => $path]);
            }

            \Log::info('Final license data to be saved:', $licenseData);

            // حذف الملفات القديمة إذا تم تحديثها
            if ($workOrder->license) {
                if ($request->hasFile('coordination_certificate') && $workOrder->license->coordination_certificate_path) {
                    Storage::disk('public')->delete($workOrder->license->coordination_certificate_path);
                }
                if ($request->hasFile('license_extension_file') && $workOrder->license->license_extension_file_path) {
                    Storage::disk('public')->delete($workOrder->license->license_extension_file_path);
                }
                if ($request->hasFile('license_closure_file') && $workOrder->license->license_closure_file_path) {
                    Storage::disk('public')->delete($workOrder->license->license_closure_file_path);
                }
            }

            // تحديث أو إنشاء الرخصة
            $license = License::updateOrCreate(
                ['work_order_id' => $workOrder->id],
                $licenseData
            );

            \Log::info('License saved successfully:', ['license_id' => $license->id, 'license_data' => $license->toArray()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث معلومات الرخص بنجاح',
                    'license' => $license
                ]);
            }

            return redirect()->back()->with('success', 'تم تحديث معلومات الرخص بنجاح');
        } catch (\Exception $e) {
            \Log::error('License update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث معلومات الرخص: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث معلومات الرخص: ' . $e->getMessage());
        }
    }

    /**
     * Display the execution section.
     */
    public function execution(WorkOrder $workOrder)
    {
        // جلب السجلات من جدول work_order_logs
        $logs = DB::table('work_order_logs')
            ->where('work_order_id', $workOrder->id)
            ->orderByDesc('created_at')
            ->get();
        return view('admin.work_orders.execution', compact('workOrder', 'logs'));
    }

    /**
     * Update execution information.
     */
    public function updateExecution(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'execution_status' => 'required|in:1,2,3,4,5,6',
            'actual_execution_value' => 'nullable|numeric|min:0',
            'execution_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
            'execution_notes' => 'nullable|string',
            'single_meter_installation' => 'required|in:yes,no,na',
            'double_meter_installation' => 'required|in:yes,no,na',
        ]);

        // Handle file upload
        if ($request->hasFile('execution_file')) {
            // Delete old file if exists
            if ($workOrder->execution_file) {
                Storage::disk('public')->delete($workOrder->execution_file);
            }

            $file = $request->file('execution_file');
            $path = $file->store('work_orders/' . $workOrder->id . '/execution', 'public');
            $validated['execution_file'] = $path;
        }

        $workOrder->update($validated);

        return redirect()->back()->with('success', 'تم تحديث معلومات التنفيذ بنجاح');
    }

    /**
     * Delete execution file.
     */
    public function deleteExecutionFile(WorkOrder $workOrder)
    {
        if ($workOrder->execution_file) {
            Storage::disk('public')->delete($workOrder->execution_file);
            $workOrder->update(['execution_file' => null]);
        }

        return redirect()->back()->with('success', 'تم حذف ملف التنفيذ بنجاح');
    }

    /**
     * Display the post-execution list or individual work order.
     */
    public function postExecution(Request $request)
    {
        $workOrderId = $request->query('id');
        
        if ($workOrderId) {
            $workOrder = WorkOrder::findOrFail($workOrderId);
            return view('admin.work_orders.post-execution', compact('workOrder'));
        }
        
        $workOrders = WorkOrder::where('execution_status', '!=', '5')->paginate(10);
        return view('admin.work_orders.post-execution-list', compact('workOrders'));
    }

    /**
     * Update post-execution information.
     */
    public function updatePostExecution(Request $request)
    {
        $workOrder = WorkOrder::findOrFail($request->input('work_order_id'));
        
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'post_execution_status' => 'required|in:1,2,3,4',
            'post_execution_value' => 'nullable|numeric|min:0',
            'post_execution_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
            'post_execution_notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('post_execution_file')) {
            // Delete old file if exists
            if ($workOrder->post_execution_file) {
                Storage::disk('public')->delete($workOrder->post_execution_file);
            }

            $file = $request->file('post_execution_file');
            $path = $file->store('work_orders/' . $workOrder->id . '/post_execution', 'public');
            $validated['post_execution_file'] = $path;
        }

        $workOrder->update($validated);

        return redirect()->route('admin.work-orders.post-execution', ['id' => $workOrder->id])
            ->with('success', 'تم تحديث معلومات ما بعد التنفيذ بنجاح');
    }

    /**
     * Delete post-execution file.
     */
    public function deletePostExecutionFile(Request $request)
    {
        $workOrder = WorkOrder::findOrFail($request->input('work_order_id'));
        
        if ($workOrder->post_execution_file) {
            Storage::disk('public')->delete($workOrder->post_execution_file);
            $workOrder->update(['post_execution_file' => null]);
        }

        return redirect()->route('admin.work-orders.post-execution', ['id' => $workOrder->id])
            ->with('success', 'تم حذف ملف ما بعد التنفيذ بنجاح');
    }

    /**
     * Edit a survey.
     */
    public function editSurvey(Survey $survey)
    {
        try {
            // التحقق من وجود المسح
            if (!$survey) {
                throw new \Exception('لم يتم العثور على المسح المطلوب');
            }

            // تحميل العلاقات المطلوبة
            $survey->load(['files' => function($query) {
                $query->where('file_type', 'like', 'image/%');
            }]);

            // تجهيز بيانات الصور
            $images = $survey->files->map(function($file) {
                return [
                    'id' => $file->id,
                    'url' => asset('storage/' . $file->file_path),
                    'name' => $file->original_filename
                ];
            });

            return response()->json([
                'success' => true,
                'survey' => [
                    'id' => $survey->id,
                    'start_coordinates' => $survey->start_coordinates,
                    'end_coordinates' => $survey->end_coordinates,
                    'has_obstacles' => (bool)$survey->has_obstacles,
                    'obstacles_notes' => $survey->obstacles_notes,
                    'images' => $images
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Survey edit error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل بيانات المسح: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض صفحة الإجراءات والتنفيذ.
     */
    public function actionsExecution(WorkOrder $workOrder)
    {
        return view('admin.work_orders.actions-execution', compact('workOrder'));
    }

    /**
     * Display the civil works page for a work order.
     */
    public function civilWorks(WorkOrder $workOrder)
    {
        $workOrder->load('civilWorksFiles');
        return view('admin.work_orders.civil_works', compact('workOrder'));
    }

    /**
     * Store civil works files and excavation data for a work order.
     */
    public function storeCivilWorks(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'civil_works_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:30720', // Max 30MB per file
            'excavation_unsurfaced_soil.*' => 'nullable|numeric|min:0',
            'excavation_surfaced_soil.*' => 'nullable|numeric|min:0',
            'excavation_unsurfaced_rock.*' => 'nullable|numeric|min:0',
            'excavation_surfaced_rock.*' => 'nullable|numeric|min:0',
            'excavation_precise.*' => 'nullable|numeric|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('civil_works_files')) {
            foreach ($request->file('civil_works_files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                $path = 'work_orders/' . $workOrder->id . '/civil_works';
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                
                WorkOrderFile::create([
                    'work_order_id' => $workOrder->id,
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_category' => 'civil_works'
                ]);
            }
        }

        // Handle excavation data
        $excavationData = [
            'excavation_unsurfaced_soil' => $request->input('excavation_unsurfaced_soil'),
            'excavation_surfaced_soil' => $request->input('excavation_surfaced_soil'),
            'excavation_unsurfaced_rock' => $request->input('excavation_unsurfaced_rock'),
            'excavation_surfaced_rock' => $request->input('excavation_surfaced_rock'),
            'excavation_precise' => $request->input('excavation_precise'),
        ];

        // Update work order with excavation data
        $workOrder->update($excavationData);

        // حفظ في جدول work_order_logs
        DB::table('work_order_logs')->insert([
            'work_order_id' => $workOrder->id,
            'section' => 'civil',
            'data' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.work-orders.civil-works', $workOrder)
            ->with('success', 'تم حفظ بيانات الأعمال المدنية بنجاح');
    }

    /**
     * Delete a civil works file.
     */
    public function deleteCivilWorksFile(WorkOrder $workOrder, WorkOrderFile $file)
    {
        if ($file->work_order_id !== $workOrder->id || $file->file_category !== 'civil_works') {
            return redirect()->back()->with('error', 'غير مصرح بحذف هذا الملف');
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->route('admin.work-orders.civil-works', $workOrder)
            ->with('success', 'تم حذف الملف بنجاح');
    }

    /**
     * Display the installations page for a work order.
     */
    public function installations(WorkOrder $workOrder)
    {
        $workOrder->load('installationsFiles');
        $installations = [
            'single_meter_box' => 'تركيب صندوق ومفرد بعداد واحد',
            'double_meter_box_single' => 'تركيب صندوق مزدوج بعداد واحد',
            'double_meter_box_double' => 'تركيب صندوق مزدوج بعدادين',
            'quad_meter_box_triple' => 'تركيب صندوق رباعي بثلاث عدادات',
            'quad_meter_box_quad' => 'تركيب صندوق رباعي 4 عدادات',
            'ct_meter_box' => 'تركيب صندوق بعداد CT',
            'mini_pillar_base' => 'تركيب قاعدة ميني بلر فقط',
            'mini_pillar_head' => 'تركيب راسي ميني بلر فقط',
            'mini_pillar_ml' => 'تركيب ميني بلر ML',
            'ring_base_triple' => 'تركيب قاعدة رنج ثلاثي',
            'ring_base_quad' => 'تركيب قاعدة رنج رباعي',
            'cole_base_500' => 'تركيب قاعدة كول 500',
            'cole_base_1000' => 'تركيب قاعدة كول 1000',
            'cole_base_1500' => 'تركيب قاعدة كول 1500',
            'pole_10m' => 'تركيب عمود 10 متر',
            'pole_13m' => 'تركيب عمود 13 متر',
            'pole_14m' => 'تركيب عمود 14 متر'
        ];
        return view('admin.work_orders.installations', compact('workOrder', 'installations'));
    }

    /**
     * Store installations files for a work order.
     */
    public function storeInstallations(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'installations_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:30720', // Max 30MB per file
        ]);

        if ($request->hasFile('installations_files')) {
            foreach ($request->file('installations_files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                $path = 'work_orders/' . $workOrder->id . '/installations';
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                
                WorkOrderFile::create([
                    'work_order_id' => $workOrder->id,
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_category' => 'installations'
                ]);
            }
        }

        // حفظ في جدول work_order_logs
        DB::table('work_order_logs')->insert([
            'work_order_id' => $workOrder->id,
            'section' => 'installations',
            'data' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.work-orders.installations', $workOrder)
            ->with('success', 'تم حفظ ملفات التركيبات بنجاح');
    }

    /**
     * Delete an installations file.
     */
    public function deleteInstallationsFile(WorkOrder $workOrder, WorkOrderFile $file)
    {
        if ($file->work_order_id !== $workOrder->id || $file->file_category !== 'installations') {
            return redirect()->back()->with('error', 'غير مصرح بحذف هذا الملف');
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->route('admin.work-orders.installations', $workOrder)
            ->with('success', 'تم حذف الملف بنجاح');
    }

    /**
     * Display the electrical works page for a work order.
     */
    public function electricalWorks(WorkOrder $workOrder)
    {
        $workOrder->load('electricalWorksFiles');
        return view('admin.work_orders.electrical_works', compact('workOrder'));
    }

    /**
     * Store electrical works files for a work order.
     */
    public function storeElectricalWorks(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'electrical_works_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:30720', // Max 30MB per file
        ]);

        if ($request->hasFile('electrical_works_files')) {
            foreach ($request->file('electrical_works_files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                $path = 'work_orders/' . $workOrder->id . '/electrical_works';
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                
                WorkOrderFile::create([
                    'work_order_id' => $workOrder->id,
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_category' => 'electrical_works'
                ]);
            }
        }

        // حفظ في جدول work_order_logs
        DB::table('work_order_logs')->insert([
            'work_order_id' => $workOrder->id,
            'section' => 'electrical',
            'data' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.work-orders.electrical-works', $workOrder)
            ->with('success', 'تم حفظ ملفات أعمال التمديد والكهرباء بنجاح');
    }

    /**
     * Delete an electrical works file.
     */
    public function deleteElectricalWorksFile(WorkOrder $workOrder, WorkOrderFile $file)
    {
        if ($file->work_order_id !== $workOrder->id || $file->file_category !== 'electrical_works') {
            return redirect()->back()->with('error', 'غير مصرح بحذف هذا الملف');
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->route('admin.work-orders.electrical-works', $workOrder)
            ->with('success', 'تم حذف الملف بنجاح');
    }

    /**
     * حذف سجل من جدول work_order_logs
     */
    public function deleteLog($logId)
    {
        $log = DB::table('work_order_logs')->where('id', $logId)->first();
        
        if (!$log) {
            return redirect()->back()->with('error', 'لم يتم العثور على السجل');
        }
        
        DB::table('work_order_logs')->where('id', $logId)->delete();
        
        return redirect()->back()->with('success', 'تم حذف السجل بنجاح');
    }
    
    /**
     * رفع ملف مرتبط بإجراءات ما بعد التنفيذ
     */
    public function uploadPostExecutionFile(Request $request, $workOrderId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:30720', // 30MB max
            'file_type' => 'required|string'
        ]);
        
        $workOrder = WorkOrder::findOrFail($workOrderId);
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            
            $path = 'work_orders/' . $workOrder->id . '/post_execution';
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }
            
            $filePath = $file->storeAs($path, $filename, 'public');
            
            // التعامل مع أنواع الملفات المختلفة
            $fileType = $request->input('file_type');
            $existingFile = null;
            
            // تحديد اسم عمود قاعدة البيانات بناءً على نوع الملف
            $columnMapping = [
                'quantities_statement' => 'quantities_statement_file',
                'final_materials' => 'final_materials_file',
                'final_measurement' => 'final_measurement_file',
                'soil_tests' => 'soil_tests_file',
                'site_drawing' => 'site_drawing_file',
                'modified_estimate' => 'modified_estimate_file',
                'completion_certificate' => 'completion_certificate_file',
                'form_200' => 'form_200_file',
                'form_190' => 'form_190_file',
                'pre_operation_tests' => 'pre_operation_tests_file',
                'extract_number' => 'extract_number_file'
            ];
            
            // التأكد من أن النوع موجود في المصفوفة
            if (isset($columnMapping[$fileType])) {
                $column = $columnMapping[$fileType];
                
                // حذف الملف القديم إذا كان موجوداً
                if ($workOrder->$column) {
                    Storage::disk('public')->delete($workOrder->$column);
                }
                
                // تحديث قاعدة البيانات بمسار الملف الجديد
                $workOrder->$column = $filePath;
                $workOrder->save();
                
                // إضافة سجل للعملية
                DB::table('work_order_logs')->insert([
                    'work_order_id' => $workOrder->id,
                    'section' => 'post_execution',
                    'data' => json_encode([
                        'file_type' => $fileType,
                        'file_path' => $filePath,
                        'original_filename' => $originalName
                    ], JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // إذا كان الطلب Ajax، نقوم بإرجاع استجابة JSON
                if ($request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => true,
                        'message' => 'تم رفع الملف بنجاح',
                        'file_path' => Storage::url($filePath),
                        'file_name' => $originalName
                    ]);
                }
                
                return redirect()->back()->with('success', 'تم رفع الملف بنجاح');
            }
            
            if ($request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الملف غير صالح'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'نوع الملف غير صالح');
        }
        
        if ($request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الملف'
            ], 400);
        }
        
        return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الملف');
    }
}