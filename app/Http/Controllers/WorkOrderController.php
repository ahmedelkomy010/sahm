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
            'office' => 'nullable|string|max:255',
            'extract_number' => 'nullable|string|max:255',
            'extract_date' => 'nullable|date',
            'extract_value' => 'nullable|numeric|min:0',
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
        \Log::info('Materials page is being accessed');
        try {
            $workOrders = WorkOrder::where('execution_status', '!=', '5')->with('materials')->paginate(10);
            $materials = Material::with('workOrder')->latest()->paginate(20);
            return view('admin.work_orders.materials', compact('workOrders', 'materials'));
        } catch (\Exception $e) {
            \Log::error('Error in materials page: ' . $e->getMessage());
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
            'check_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'stock_in' => 'nullable|numeric|min:0',
            'stock_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'stock_out' => 'nullable|numeric|min:0',
            'stock_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'actual_quantity' => 'nullable|numeric|min:0',
        ]);

        \Log::info('Validation passed');
        
        // التعامل مع ملفات check_in و check_out وملفات المخزون
        $data = $request->except(['check_in_file', 'check_out_file', 'stock_in_file', 'stock_out_file']);
        
        // تأكد من وضع قيم افتراضية للحقول الرقمية الفارغة
        $data['stock_in'] = $data['stock_in'] ?? 0;
        $data['stock_out'] = $data['stock_out'] ?? 0;
        $data['actual_quantity'] = $data['actual_quantity'] ?? 0;
        
        // تحميل ملف check_in إذا وجد
        if ($request->hasFile('check_in_file')) {
            $file = $request->file('check_in_file');
            $path = $file->store('materials/check_in', 'public');
            $data['check_in_file'] = $path;
        }
        
        // تحميل ملف check_out إذا وجد
        if ($request->hasFile('check_out_file')) {
            $file = $request->file('check_out_file');
            $path = $file->store('materials/check_out', 'public');
            $data['check_out_file'] = $path;
        }

        // تحميل ملف stock_in إذا وجد
        if ($request->hasFile('stock_in_file')) {
            $file = $request->file('stock_in_file');
            $path = $file->store('materials/stock_in', 'public');
            $data['stock_in_file'] = $path;
        }
        
        // تحميل ملف stock_out إذا وجد
        if ($request->hasFile('stock_out_file')) {
            $file = $request->file('stock_out_file');
            $path = $file->store('materials/stock_out', 'public');
            $data['stock_out_file'] = $path;
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
            'check_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'stock_in' => 'nullable|numeric|min:0',
            'stock_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'stock_out' => 'nullable|numeric|min:0',
            'stock_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'actual_quantity' => 'nullable|numeric|min:0',
        ]);

        // التعامل مع ملفات check_in و check_out وملفات المخزون
        $data = $request->except(['check_in_file', 'check_out_file', 'stock_in_file', 'stock_out_file']);
        
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
        
        // تحميل ملف check_out إذا وجد
        if ($request->hasFile('check_out_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->check_out_file) {
                Storage::disk('public')->delete($material->check_out_file);
            }
            
            $file = $request->file('check_out_file');
            $path = $file->store('materials/check_out', 'public');
            $data['check_out_file'] = $path;
        }

        // تحميل ملف stock_in إذا وجد
        if ($request->hasFile('stock_in_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->stock_in_file) {
                Storage::disk('public')->delete($material->stock_in_file);
            }
            
            $file = $request->file('stock_in_file');
            $path = $file->store('materials/stock_in', 'public');
            $data['stock_in_file'] = $path;
        }
        
        // تحميل ملف stock_out إذا وجد
        if ($request->hasFile('stock_out_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->stock_out_file) {
                Storage::disk('public')->delete($material->stock_out_file);
            }
            
            $file = $request->file('stock_out_file');
            $path = $file->store('materials/stock_out', 'public');
            $data['stock_out_file'] = $path;
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
        
        if ($material->check_out_file) {
            Storage::disk('public')->delete($material->check_out_file);
        }
        
        if ($material->stock_in_file) {
            Storage::disk('public')->delete($material->stock_in_file);
        }
        
        if ($material->stock_out_file) {
            Storage::disk('public')->delete($material->stock_out_file);
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
        $workOrders = WorkOrder::with(['license', 'files'])->get();
        return view('admin.work_orders.licenses', compact('workOrders'));
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
    public function execution()
    {
        $workOrders = WorkOrder::where('execution_status', '!=', '5')->paginate(10);
        return view('admin.work_orders.execution', compact('workOrders'));
    }

    /**
     * Display the post-execution section.
     */
    public function postExecution()
    {
        $workOrders = WorkOrder::where('execution_status', '!=', '5')->paginate(10);
        return view('admin.work_orders.post-execution', compact('workOrders'));
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
}