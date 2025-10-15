<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderFile;
use App\Models\WorkOrderMaterial;
use App\Models\WorkOrderInspectionDate;
use App\Models\WorkOrderSafetyHistory;
use App\Models\Survey;
use App\Models\User;
use App\Imports\RevenuesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Models\License;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\DailyWorkExecution;
use App\Models\DailyExecutionNote;

class WorkOrderController extends Controller
{
    // عرض قائمة أوامر العمل
    public function index(Request $request)
    {
        // التحقق من وجود المشروع
        $project = $request->get('project');
        
        if (!$project || !in_array($project, ['riyadh', 'madinah'])) {
            return redirect()->route('project.selection');
        }
        
        // تحديد المدينة بناءً على المشروع
        $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
        $projectName = $project === 'madinah' ? 'مشروع المدينة المنورة' : 'مشروع الرياض';
        
        $query = WorkOrder::where('city', $city);
        
        // فلتر رقم أمر العمل
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
        
        // فلتر نوع العمل
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }
        
        // فلتر اسم المشترك
        if ($request->filled('subscriber_name')) {
            $query->where('subscriber_name', 'like', '%' . $request->subscriber_name . '%');
        }
        
        // فلتر الحي
        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }
        
        // فلتر المكتب
        if ($request->filled('office')) {
            $query->where('office', $request->office);
        }
        
        // فلتر اسم الاستشاري
        if ($request->filled('consultant_name')) {
            $query->where('consultant_name', 'like', '%' . $request->consultant_name . '%');
        }
        
        // فلتر رقم المحطة
        if ($request->filled('station_number')) {
            $query->where('station_number', 'like', '%' . $request->station_number . '%');
        }
        
        // فلتر حالة التنفيذ
        if ($request->filled('execution_status')) {
            $query->where('execution_status', $request->execution_status);
        }
        
        // فلتر رقم المستخلص
        if ($request->filled('extract_number')) {
            $query->where('extract_number', 'like', '%' . $request->extract_number . '%');
        }
        
        // فلتر تاريخ الاعتماد من
        if ($request->filled('approval_date_from')) {
            $query->whereDate('approval_date', '>=', $request->approval_date_from);
        }
        
        // فلتر تاريخ الاعتماد إلى
        if ($request->filled('approval_date_to')) {
            $query->whereDate('approval_date', '<=', $request->approval_date_to);
        }
        
        // فلتر قيمة أمر العمل الأدنى
        if ($request->filled('min_value')) {
            $query->where('order_value_without_consultant', '>=', $request->min_value);
        }
        
        // فلتر قيمة أمر العمل الأعلى
        if ($request->filled('max_value')) {
            $query->where('order_value_without_consultant', '<=', $request->max_value);
        }
        
        // ترتيب النتائج
        if ($request->filled('sort_by_date')) {
            if ($request->sort_by_date === 'asc') {
                // ترتيب تصاعدي: الأقدم للأجدد (null في النهاية)
                $query->orderByRaw('approval_date IS NULL, approval_date ASC');
            } else {
                // ترتيب تنازلي: الأجدد للأقدم (null في النهاية)
                $query->orderByRaw('approval_date IS NULL, approval_date DESC');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // عدد العناصر في الصفحة (يمكن تخصيصه من خلال الفلتر)
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100, 300, 500, 1000]) ? $perPage : 15;
        
        $workOrders = $query->with('notesUpdatedBy')->paginate($perPage);
        
        return view('admin.work_orders.index', compact('workOrders', 'project', 'projectName'));
    }

    // عرض نموذج إنشاء أمر عمل جديد
    public function create(Request $request)
    {
        // التحقق من وجود المشروع
        $project = $request->get('project');

        // التحقق من الصلاحيات
        $user = auth()->user();
        $createPermission = $project . '_create_work_order';
        
        if (!$user->hasPermission($createPermission) && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية لإنشاء أمر عمل جديد');
        }
        
        if (!$project || !in_array($project, ['riyadh', 'madinah'])) {
            return redirect()->route('project.selection');
        }
        
        // تصفية بنود العمل والمواد حسب المشروع
        $workItems = \App\Models\WorkItem::byProject($project)
                                          ->where('is_active', true)
                                          ->orderBy('code')
                                          ->get();
        
        $referenceMaterials = \App\Models\ReferenceMaterial::byProject($project)
                                                           ->where('is_active', true)
                                                           ->orderBy('code')
                                                           ->get();
        
        return view('admin.work_orders.create', compact('workItems', 'referenceMaterials', 'project'));
    }

    // حفظ أمر عمل جديد
    public function store(Request $request)
    {
        // التحقق من وجود المشروع
        $project = $request->get('project');
        
        if (!$project || !in_array($project, ['riyadh', 'madinah'])) {
            return redirect()->route('project.selection');
        }
        
        $messages = [
            'materials.required' => 'يجب إدخال المواد',
            'materials.*.material_code.required' => 'يجب إدخال كود المادة',
            'materials.*.material_description.required' => 'يجب إدخال وصف المادة',
            'materials.*.planned_quantity.required' => 'يجب إدخال الكمية المخططة',
            'materials.*.planned_quantity.numeric' => 'يجب أن تكون الكمية المخططة رقم',
            'materials.*.planned_quantity.min' => 'يجب أن تكون الكمية المخططة أكبر من صفر',
            'materials.*.unit.required' => 'يجب إدخال الوحدة',
            'order_number.required' => 'يجب إدخال رقم أمر العمل',
            'work_type.required' => 'يجب إدخال نوع العمل',
            'work_description.required' => 'يجب إدخال وصف العمل',
            'approval_date.required' => 'يجب إدخال تاريخ الاعتماد',
            'subscriber_name.required' => 'يجب إدخال اسم المشترك',
            'district.required' => 'يجب إدخال الحي',
            'order_value_with_consultant.required' => 'يجب إدخال قيمة أمر العمل شامل الاستشاري',
            'order_value_with_consultant.numeric' => 'يجب أن تكون قيمة أمر العمل شامل الاستشاري رقم',
            'order_value_with_consultant.min' => 'يجب أن تكون قيمة أمر العمل شامل الاستشاري أكبر من صفر',
            'order_value_without_consultant.required' => 'يجب إدخال قيمة أمر العمل بدون استشاري',
            'order_value_without_consultant.numeric' => 'يجب أن تكون قيمة أمر العمل بدون استشاري رقم',
            'order_value_without_consultant.min' => 'يجب أن تكون قيمة أمر العمل بدون استشاري أكبر من صفر',
            'execution_status.required' => 'يجب إدخال حالة تنفيذ أمر العمل',
            'execution_status.in' => 'حالة تنفيذ أمر العمل غير صحيحة',
            'city.required' => 'يجب إدخال المدينة',
            'manual_days.required' => 'يجب إدخال مدة التنفيذ',
            'manual_days.numeric' => 'يجب أن تكون مدة التنفيذ رقم',
            'manual_days.min' => 'يجب أن تكون مدة التنفيذ أكبر من أو تساوي صفر',
        ];

        $rules = array_merge(WorkOrder::$rules, [
            // بنود العمل
            'work_items' => 'nullable|array',
            'work_items.*.work_item_id' => 'required_with:work_items|exists:work_items,id',
            'work_items.*.planned_quantity' => 'required_with:work_items|numeric|min:0',
            'work_items.*.unit_price' => 'nullable|numeric|min:0',
            'work_items.*.notes' => 'nullable|string',
            // المرفقات - ملف واحد فقط بحد أقصى 5 ميجابايت
            'files.license_estimate' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            'files.daily_measurement' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            'files.attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $validated = $request->validate($rules, $messages);
        
        // تعيين المدينة تلقائياً حسب المشروع
        switch ($project) {
            case 'riyadh':
                $validated['city'] = 'الرياض';
                break;
            case 'madinah':
                $validated['city'] = 'المدينة المنورة';
                break;
        }
        
        $validated['user_id'] = Auth::id();
        
        // إنشاء أمر العمل
        $workOrder = WorkOrder::create($validated);
        
        // حفظ بنود العمل
        if ($request->has('work_items') && is_array($request->work_items)) {
            foreach ($request->work_items as $workItem) {
                if (!empty($workItem['work_item_id']) && !empty($workItem['planned_quantity'])) {
                    // الحصول على بيانات بند العمل لاستخراج سعر الوحدة
                    $workItemDetails = \App\Models\WorkItem::find($workItem['work_item_id']);
                    $unitPrice = $workItemDetails ? $workItemDetails->unit_price : 0;
                    
                    $workOrder->workOrderItems()->create([
                        'work_item_id' => $workItem['work_item_id'],
                        'quantity' => $workItem['planned_quantity'],
                        'unit_price' => $workItem['unit_price'] ?? $unitPrice,
                        'unit' => $workItem['unit'] ?? null,
                        'notes' => $workItem['notes'] ?? null,
                    ]);
                }
            }
        }
        
        // حفظ المواد
        if ($request->has('materials') && is_array($request->materials)) {
            foreach ($request->materials as $material) {
                // التحقق من وجود البيانات المطلوبة
                if (empty($material['material_code']) || empty($material['material_description']) || 
                    empty($material['planned_quantity']) || empty($material['unit'])) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['materials' => 'يجب إدخال جميع بيانات المواد المطلوبة']);
                }

                // التحقق من صحة البيانات الرقمية
                if (!is_numeric($material['planned_quantity']) || $material['planned_quantity'] <= 0) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['materials' => 'يجب أن تكون الكمية المخططة رقم أكبر من صفر']);
                }



                // إنشاء أو العثور على المادة في جدول materials
                $materialRecord = Material::firstOrCreate(
                    ['code' => $material['material_code']],
                    [
                        'name' => $material['material_description'],
                        'description' => $material['material_description'],
                        'unit' => $material['unit'],
                        'is_active' => true,
                    ]
                );
                
                // ربط المادة بأمر العمل في جدول work_order_materials
                $workOrder->workOrderMaterials()->create([
                    'material_id' => $materialRecord->id,
                    'quantity' => $material['planned_quantity'],
                    'notes' => $material['notes'] ?? null,
                ]);
            }
        }
        
        // Debug: فحص المرفقات الواردة
        \Log::info('=== File Upload Debug ===');
        \Log::info('Request has files array: ' . ($request->hasFile('files') ? 'YES' : 'NO'));
        \Log::info('Request has files.attachments: ' . ($request->hasFile('files.attachments') ? 'YES' : 'NO'));
        \Log::info('All files in request: ' . json_encode(array_keys($request->allFiles())));
        \Log::info('Files input content: ' . json_encode($request->input('files')));
        
        // حفظ المرفقات الأساسية
        if ($request->hasFile('files.attachments')) {
            // التعامل مع المرفقات المتعددة الجديدة
            $attachments = $request->file('files.attachments');
            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }
            
            \Log::info('Found attachments: ' . count($attachments));
            
            foreach ($attachments as $file) {
                try {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/basic_attachments';
                    
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path, 0755, true);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    
                    if ($filePath) {
                        WorkOrderFile::create([
                            'work_order_id' => $workOrder->id,
                            'filename' => $filename,
                            'original_filename' => $originalName,
                            'file_name' => $originalName,
                            'file_path' => $filePath,
                            'file_type' => $file->getClientMimeType(),
                            'mime_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'file_category' => 'basic_attachments'
                        ]);
                        \Log::info("File uploaded successfully: {$originalName}");
                    } else {
                        \Log::error("Failed to store file: {$originalName}");
                    }
                } catch (\Exception $e) {
                    \Log::error("Error uploading file {$originalName}: " . $e->getMessage());
                }
            }
        } elseif ($request->hasFile('files')) {
            // التعامل مع المرفقات المتعددة الجديدة (الطريقة القديمة)
            if ($request->hasFile('files.attachments')) {
                $attachments = $request->file('files.attachments');
                if (!is_array($attachments)) {
                    $attachments = [$attachments];
                }
                
                foreach ($attachments as $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = 'work_orders/' . $workOrder->id . '/basic_attachments';
                        
                        if (!Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->makeDirectory($path, 0755, true);
                        }
                        
                        $filePath = $file->storeAs($path, $filename, 'public');
                        
                        if ($filePath) {
                            WorkOrderFile::create([
                                'work_order_id' => $workOrder->id,
                                'filename' => $filename,
                                'original_filename' => $originalName,
                                'file_name' => $originalName,
                                'file_path' => $filePath,
                                'file_type' => $file->getClientMimeType(),
                                'mime_type' => $file->getClientMimeType(),
                                'file_size' => $file->getSize(),
                                'file_category' => 'basic_attachments'
                            ]);
                            \Log::info("File uploaded successfully: {$originalName}");
                        } else {
                            \Log::error("Failed to store file: {$originalName}");
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error uploading file {$originalName}: " . $e->getMessage());
                    }
                }
            }
            
            // التعامل مع المرفقات المفردة القديمة (للتوافق مع النظام القديم)
            $fileFields = [
                'license_estimate' => 'مقايسة الأعمال',
                'daily_measurement' => 'مقايسة المواد', 
                'backup_1' => 'مرفق احتياطي'
            ];
            
            foreach ($fileFields as $field => $description) {
                if ($request->hasFile("files.$field")) {
                    $file = $request->file("files.$field");
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/basic_attachments';
                    
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    
                    WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_name' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'basic_attachments',
                        'attachment_type' => $field,
                    ]);
                }
            }
        } else {
            \Log::info('No files found in request for work order: ' . $workOrder->id);
        }
        
        // رسالة نجاح مع تفاصيل المرفقات
        $attachmentsCount = $workOrder->files()->where('file_category', 'basic_attachments')->count();
        $message = 'تم إنشاء أمر العمل بنجاح';
        if ($attachmentsCount > 0) {
            $message .= ' مع ' . $attachmentsCount . ' مرفق(ات)';
        }
        
        return redirect()->route('admin.work-orders.index', ['project' => $project])->with('success', $message);
    }

    // عرض أمر عمل محدد
    public function show(WorkOrder $workOrder)
    {
        // إذا كان الطلب عبر AJAX، إرجاع JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id' => $workOrder->id,
                'order_number' => $workOrder->order_number,
                'notes' => $workOrder->notes
            ]);
        }
        
        $workOrder->load(['files', 'basicAttachments', 'invoiceAttachments', 'licenses.violations', 'safetyViolations', 'notesUpdatedBy']);
        
        // تحديد المشروع بناءً على المدينة
        $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
        
        // حساب إجمالي قيمة مخالفات السلامة
        $totalSafetyViolations = $workOrder->safetyViolations->sum('violation_amount');
        
        // حساب إجماليات القيم
        $licensesTotals = [
            'total_license_value' => 0,
            'total_extension_value' => 0,
            'total_evacuation_value' => 0,
            'total_violations_value' => 0,
            'total_violations_count' => 0,
            'pending_violations_value' => 0,
            'paid_violations_value' => 0
        ];
        
        foreach ($workOrder->licenses as $license) {
            // إجمالي قيمة الرخص
            $licensesTotals['total_license_value'] += $license->license_value ?? 0;
            $licensesTotals['total_extension_value'] += $license->extension_value ?? 0;
            
            // إجمالي قيمة الإخلاءات
            $licensesTotals['total_evacuation_value'] += $license->evac_amount ?? 0;
            
            // إجمالي قيمة المخالفات
            foreach ($license->violations as $violation) {
                $licensesTotals['total_violations_count']++;
                $licensesTotals['total_violations_value'] += $violation->violation_amount ?? 0;
                
                // تقسيم المخالفات حسب حالة الدفع
                if ($violation->payment_status == 1) { // مدفوع
                    $licensesTotals['paid_violations_value'] += $violation->violation_amount ?? 0;
                } else { // غير مدفوع أو جزئي
                    $licensesTotals['pending_violations_value'] += $violation->violation_amount ?? 0;
                }
            }
        }
        
        return view('admin.work_orders.show', compact('workOrder', 'licensesTotals', 'project', 'totalSafetyViolations'));
    }

    /**
     * عرض صفحة الرخص لأمر العمل
     */
    public function license(WorkOrder $workOrder)
    {
        $workOrder->load(['licenses.violations', 'licenses.attachments', 'licenses.workOrder', 'surveys.files']);
        
        // تحديد المشروع بناءً على المدينة
        $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
        
        return view('admin.work_orders.license', compact('workOrder', 'project'));
    }

    /**
     * الحصول على بيانات رخصة محددة لأمر العمل
     */
    public function getLicenseData(Request $request)
    {
        try {
            $licenseId = $request->get('license_id');
            $workOrderId = $request->get('work_order_id');
            
            if (!$licenseId || !$workOrderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'معرف الرخصة أو أمر العمل مفقود'
                ], 400);
            }
            
            // جلب الرخصة مع العلاقات المطلوبة
            $license = \App\Models\License::with([
                'workOrder',
                'extensions' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'violations' => function($query) {
                    $query->orderBy('violation_date', 'desc');
                }
            ])
            ->where('id', $licenseId)
            ->where('work_order_id', $workOrderId)
            ->first();
            
            if (!$license) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرخصة غير موجودة'
                ], 404);
            }
            
            // تحضير البيانات الإضافية
            $licenseData = $license->toArray();
            
            // إضافة عدد التمديدات
            $licenseData['extensions_count'] = $license->extensions->count();
            
            // إضافة آخر تمديد
            $licenseData['latest_extension'] = $license->extensions->first();
            
            // إضافة عدد المخالفات
            $licenseData['violations_count'] = $license->violations->count();
            
            // تحديد حالة الرخصة
            $licenseData['status'] = $this->determineLicenseStatus($license);
            
            // إضافة روابط الملفات
            $licenseData['license_file_url'] = $license->getFileUrl('license_file_path');
            $licenseData['payment_proof_urls'] = $license->getMultipleFileUrls('payment_proof_path');
            
            return response()->json([
                'success' => true,
                'license' => $licenseData,
                'message' => 'تم جلب بيانات الرخصة بنجاح'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('خطأ في جلب بيانات الرخصة: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الرخصة'
            ], 500);
        }
    }
    
    /**
     * تحديد حالة الرخصة
     */
    private function determineLicenseStatus($license)
    {
        $now = now();
        
        // إذا كانت الرخصة ملغاة
        if ($license->is_cancelled) {
            return 'cancelled';
        }
        
        // إذا كان هناك تمديدات، نتحقق من آخر تمديد
        if ($license->extensions->count() > 0) {
            $latestExtension = $license->extensions->first();
            if ($latestExtension->end_date && $now->gt($latestExtension->end_date)) {
                return 'expired';
            }
            return 'extended';
        }
        
        // التحقق من تاريخ انتهاء الرخصة الأصلية
        if ($license->expiry_date && $now->gt($license->expiry_date)) {
            return 'expired';
        }
        
        return 'active';
    }

    /**
     * عرض صفحة المسح لأمر العمل
     */
    public function survey(WorkOrder $workOrder)
    {
        $workOrder->load(['surveys.files', 'surveys.completionFiles']);
        return view('admin.work_orders.survey', compact('workOrder'));
    }

    /**
     * رفع ملفات بعد انتهاء العمل منفصل
     */
    public function uploadCompletionFiles(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'survey_id' => 'required|exists:surveys,id',
                'completion_images.*' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx|max:51200', // 50MB max
            ]);

            $survey = Survey::findOrFail($validated['survey_id']);
            
            // التأكد من أن المسح ينتمي لأمر العمل
            if ($survey->work_order_id !== $workOrder->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'المسح المحدد لا ينتمي لأمر العمل هذا'
                ], 403);
            }

            DB::beginTransaction();

            try {
                // إنشاء مجلد منفصل لملفات بعد انتهاء العمل
                $completionPath = 'work_orders/' . $workOrder->id . '/surveys/' . $survey->id . '/completion';
                if (!Storage::disk('public')->exists($completionPath)) {
                    Storage::disk('public')->makeDirectory($completionPath, 0755, true);
                }
                
                $uploadedFiles = [];
                
                foreach ($request->file('completion_images') as $file) {
                    try {
                        // التحقق من صحة الملف
                        if (!$file->isValid()) {
                            throw new \Exception('الملف غير صالح: ' . $file->getErrorMessage());
                        }

                        // التحقق من نوع الملف
                        $allowedTypes = [
                            'image/jpeg', 'image/png', 'image/jpg',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ];
                        if (!in_array($file->getMimeType(), $allowedTypes)) {
                            throw new \Exception('نوع الملف غير مدعوم: ' . $file->getMimeType());
                        }

                        // التحقق من حجم الملف (50MB)
                        if ($file->getSize() > 50 * 1024 * 1024) {
                            throw new \Exception('حجم الملف يتجاوز الحد المسموح به (50 ميجابايت)');
                        }

                        // إنشاء اسم فريد للملف
                        $filename = uniqid('completion_') . '_' . time() . '.' . $file->getClientOriginalExtension();
                        
                        // حفظ الملف
                        $filePath = $file->storeAs($completionPath, $filename, 'public');
                        
                        if (!$filePath) {
                            throw new \Exception('فشل في حفظ الملف');
                        }

                        // إنشاء سجل الملف
                        $completionFile = new WorkOrderFile([
                            'work_order_id' => $workOrder->id,
                            'survey_id' => $survey->id,
                            'filename' => $filename,
                            'original_filename' => $file->getClientOriginalName(),
                            'file_path' => $filePath,
                            'file_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'file_category' => 'completion_files',
                            'file_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType()
                        ]);

                        if (!$completionFile->save()) {
                            throw new \Exception('فشل في حفظ بيانات الملف في قاعدة البيانات');
                        }
                        
                        $uploadedFiles[] = $completionFile;
                        
                    } catch (\Exception $e) {
                        \Log::error('خطأ في معالجة ملف بعد انتهاء العمل: ' . $e->getMessage());
                        // حذف الملف إذا تم حفظه
                        if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                            Storage::disk('public')->delete($filePath);
                        }
                        throw $e;
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح',
                    'files_count' => count($uploadedFiles)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                
                // حذف الملفات المحفوظة في حالة الفشل
                foreach ($uploadedFiles ?? [] as $file) {
                    if (Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                }
                
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('خطأ في رفع ملفات بعد انتهاء العمل: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حفظ بيانات المسح لأمر العمل
     */
    public function storeSurvey(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('بيانات الطلب:', $request->all());

            $validated = $request->validate([
                'start_coordinates' => 'required|string|max:500',
                'end_coordinates' => 'required|string|max:500',
                'has_obstacles' => 'required|boolean',
                'obstacles_notes' => 'required_if:has_obstacles,1|nullable|string|max:1000',
                'site_images.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:30720', // 30MB max
            ]);

            \Log::info('البيانات المتحقق منها:', $validated);

            DB::beginTransaction();

            try {
                // إنشاء المسح
                $survey = new Survey([
                    'work_order_id' => $workOrder->id,
                    'start_coordinates' => $validated['start_coordinates'],
                    'end_coordinates' => $validated['end_coordinates'],
                    'has_obstacles' => (bool)$validated['has_obstacles'],
                    'obstacles_notes' => $validated['obstacles_notes'] ?? null,
                    'created_by' => auth()->id(),
                    'survey_number' => 'SV-' . time() . '-' . $workOrder->id,
                    'survey_type' => 'site',
                    'survey_date' => now(),
                ]);

                if (!$survey->save()) {
                    throw new \Exception('فشل في حفظ بيانات المسح');
                }

                \Log::info('تم إنشاء المسح:', $survey->toArray());

                // معالجة الصور
                if ($request->hasFile('site_images')) {
                    \Log::info('عدد الصور المرفقة:', ['count' => count($request->file('site_images'))]);
                    
                    // إنشاء المجلد
                    $path = 'work_orders/' . $workOrder->id . '/surveys/' . $survey->id;
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path, 0755, true);
                    }
                    
                    foreach ($request->file('site_images') as $image) {
                        try {
                            // التحقق من صحة الملف
                            if (!$image->isValid()) {
                                throw new \Exception('الملف غير صالح: ' . $image->getErrorMessage());
                            }

                            // التحقق من نوع الملف
                            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
                            if (!in_array($image->getMimeType(), $allowedTypes)) {
                                throw new \Exception('نوع الملف غير مدعوم: ' . $image->getMimeType());
                            }

                            // التحقق من حجم الملف
                            if ($image->getSize() > 30 * 1024 * 1024) { // 30MB
                                throw new \Exception('حجم الملف يتجاوز الحد المسموح به (30 ميجابايت)');
                            }

                            // إنشاء اسم فريد للملف
                            $filename = uniqid('survey_') . '_' . time() . '.' . $image->getClientOriginalExtension();
                            
                            // حفظ الصورة
                            $filePath = $image->storeAs($path, $filename, 'public');
                            
                            if (!$filePath) {
                                throw new \Exception('فشل في حفظ الملف');
                            }

                            \Log::info('تم حفظ الصورة:', ['path' => $filePath]);
                            
                            // إنشاء سجل الملف
                            $file = new WorkOrderFile([
                                'work_order_id' => $workOrder->id,
                                'survey_id' => $survey->id,
                                'filename' => $filename,
                                'original_filename' => $image->getClientOriginalName(),
                                'file_path' => $filePath,
                                'file_type' => $image->getMimeType(),
                                'file_size' => $image->getSize(),
                                'file_category' => 'survey_images',
                                'file_name' => $image->getClientOriginalName(), // إضافة حقل file_name
                                'mime_type' => $image->getMimeType() // إضافة حقل mime_type
                            ]);

                            if (!$file->save()) {
                                throw new \Exception('فشل في حفظ بيانات الملف في قاعدة البيانات');
                            }
                            
                            \Log::info('تم إنشاء سجل الملف:', $file->toArray());
                        } catch (\Exception $e) {
                            \Log::error('خطأ في معالجة الصورة: ' . $e->getMessage());
                            // حذف الملف إذا تم حفظه
                            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                                Storage::disk('public')->delete($filePath);
                            }
                            throw $e;
                        }
                    }
                }

                DB::commit();

                // تحميل المسح مع الملفات
                $survey->load('files');
                
                // تحضير بيانات الصور للرد
                $images = $survey->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->original_filename,
                        'url' => \App\Helpers\FileHelper::getImageUrl($file->file_path)
                    ];
                });

                return response()->json([
                    'success' => true,
                    'message' => 'تم حفظ المسح بنجاح',
                    'survey' => array_merge($survey->toArray(), ['images' => $images])
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                
                // حذف الملفات المحفوظة في حالة الفشل
                if (isset($survey) && isset($path)) {
                    Storage::disk('public')->deleteDirectory($path);
                }
                
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('خطأ في حفظ المسح: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ المسح: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض نموذج تعديل أمر عمل
     */
    public function edit(WorkOrder $workOrder)
    {
        // تحديد المشروع بناءً على المدينة
        $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
        
        // التحقق من الصلاحيات
        $user = auth()->user();
        $editPermission = $project . '_edit_work_order';
        
        if (!$user->hasPermission($editPermission) && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية لتعديل أمر العمل');
        }

        $workOrder->load('files');
        
        return view('admin.work_orders.edit', compact('workOrder', 'project'));
    }

    // تحديث أمر عمل
    public function update(Request $request, WorkOrder $workOrder)
    {
        // تحديد المشروع بناءً على المدينة
        $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
        
        // التحقق من الصلاحيات
        $user = auth()->user();
        $editPermission = $project . '_edit_work_order';
        
        if (!$user->hasPermission($editPermission) && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية لتعديل أمر العمل');
        }

        // دعم التحديث الجزئي للملاحظات فقط
        if ($request->has('notes') && !$request->has('order_number')) {
            $workOrder->notes = $request->input('notes');
            $workOrder->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملاحظات بنجاح',
                'updated_at' => now()->format('Y-m-d H:i')
            ]);
        }
        
        // دعم التحديث الجزئي لحقول رقم أمر الشراء وصحيفة الإدخال ورقم المستخلص والحقول الجديدة
        if ($request->input('_section') === 'extract_number_group') {
            $updateData = $request->only([
                'purchase_order_number',
                'entry_sheet',
                'entry_sheet_1',
                'entry_sheet_2',
                'extract_number',
                'delay_penalties',
                'actual_execution_value_consultant',
                'actual_execution_value_without_consultant',
                'first_partial_payment_without_tax',
                'second_partial_payment_with_tax',
                'tax_value',
                'procedure_155_delivery_date',
                'final_total_value',
                'execution_status',
                'pre_operation_tests',
            ]);
            
            // إذا تم تغيير حالة التنفيذ، احفظ تاريخ التغيير
            if (isset($updateData['execution_status']) && $updateData['execution_status'] != $workOrder->execution_status) {
                $updateData['execution_status_date'] = now();
            }
            
            $workOrder->update($updateData);
            return redirect()->route('admin.work-orders.actions-execution', $workOrder->id)->with('success', 'تم تحديث البيانات بنجاح');
        }
        
        $validated = $request->validate([
            'order_number' => 'required|string|max:255',
            'work_type' => 'required|string|max:999',
            'work_description' => 'required|string',
            'approval_date' => 'required|date',
            'subscriber_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'order_value_with_consultant' => 'required|numeric|min:0',
            'order_value_without_consultant' => 'required|numeric|min:0',
            'execution_status' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'manual_days' => 'required|integer|min:0',
            'municipality' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'station_number' => 'nullable|string|max:255',
            'consultant_name' => 'nullable|string|max:255',
            'task_number' => 'nullable|string|max:255',
        ]);
        // إذا تم تغيير حالة التنفيذ، احفظ تاريخ التغيير
        if (isset($validated['execution_status']) && $validated['execution_status'] != $workOrder->execution_status) {
            $validated['execution_status_date'] = now();
        }
        
        // تحديث بيانات أمر العمل
        $workOrder->update($validated);

        // معالجة المرفقات الجديدة - التحقق من validation (ملف واحد، حد أقصى 5 ميجابايت)
        if ($request->hasFile('files.attachments')) {
            // Validate attachments
            $request->validate([
                'files.attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            ]);
            
            foreach ($request->file('files.attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'work_orders/' . $workOrder->id . '/attachments';
                
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                
                WorkOrderFile::create([
                    'work_order_id' => $workOrder->id,
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_name' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'file_category' => 'basic_attachments'
                ]);
            }
        }

        return redirect()->route('admin.work-orders.show', $workOrder)->with('success', 'تم تحديث أمر العمل بنجاح');
    }

    // حذف أمر عمل
    public function destroy(WorkOrder $workOrder)
    {
        try {
            // حذف الملفات المرتبطة
            foreach ($workOrder->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // حذف المسوحات المرتبطة
            $workOrder->surveys()->delete();
            
            // حذف بنود العمل المرتبطة
            $workOrder->workOrderItems()->delete();
            
            // حذف مواد العمل المرتبطة
            $workOrder->workOrderMaterials()->delete();
            
            // حذف الملفات المرتبطة من قاعدة البيانات
            $workOrder->files()->delete();
            
            // حذف أمر العمل
            $workOrder->delete();
            
            return redirect()->route('admin.work-orders.index')->with('success', 'تم حذف أمر العمل بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.work-orders.index')->with('error', 'حدث خطأ أثناء حذف أمر العمل: ' . $e->getMessage());
        }
    }

    // رفع ملف عام (مثال)
    public function uploadFile(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
        ]);
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'work_orders/' . $workOrder->id;
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }
        $filePath = $file->storeAs($path, $filename, 'public');
        WorkOrderFile::create([
            'work_order_id' => $workOrder->id,
            'filename' => $filename,
            'original_filename' => $originalName,
            'file_name' => $originalName,
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);
        return back()->with('success', 'تم رفع الملف بنجاح');
    }

    // مثال: دالة electricalWorks
    public function electricalWorks(WorkOrder $workOrder)
    {
        $workOrder->load('electricalWorksFiles');
        return view('admin.work_orders.electrical_works', compact('workOrder'));
    }

    public function execution(WorkOrder $workOrder)
    {
        // تحديث البيانات من قاعدة البيانات
        $workOrder = $workOrder->fresh();
        
        // Get the requested date or default to today
        $workDate = request('date', now()->format('Y-m-d'));
        
        // تحديد المشروع بناءً على مدينة أمر العمل
        $project = 'riyadh'; // افتراضي
        if ($workOrder->city === 'المدينة المنورة') {
            $project = 'madinah';
        } elseif (empty($workOrder->city)) {
            // إذا لم تكن المدينة محددة، استخدم الرياض كافتراضي
            $project = 'riyadh';
        }
        
        // جلب بنود العمل المُفلترة حسب المشروع
        $workItems = \App\Models\WorkItem::byProject($project)
                                          ->where('is_active', true)
                                          ->orderBy('code')
                                          ->get();
        
        $workOrder->load([
            'files', 
            'basicAttachments', 
            'workOrderItems.workItem', 
            'workOrderItems.dailyExecutions',
            'workOrderMaterials.material',
            'dailyExecutionNotes'
        ]);

        // جلب صور التنفيذ
        $executionImages = $workOrder->files()
            ->where('file_category', 'execution_images')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // عرض جميع البنود بغض النظر عن التاريخ
        // سيتم تفريغ حقول الكمية المنفذة في Frontend عند تغيير التاريخ
        
        $hasWorkItems = $workOrder->workOrderItems()->count() > 0;
        $hasMaterials = $workOrder->workOrderMaterials()->count() > 0;
        
        $totalWorkItemsValue = $workOrder->workOrderItems()->sum(DB::raw('quantity * unit_price'));
        $totalMaterialsValue = 0; // تم إزالة حساب unit_price من المواد
        
        $grandTotal = $totalWorkItemsValue + $totalMaterialsValue;
        
        return view('admin.work_orders.execution', compact(
            'workOrder', 
            'hasWorkItems', 
            'hasMaterials', 
            'totalWorkItemsValue', 
            'totalMaterialsValue', 
            'grandTotal',
            'workDate',
            'workItems',
            'project',
            'executionImages'
        ));
    }

    public function actionsExecution(WorkOrder $workOrder)
    {
        // تحديث البيانات من قاعدة البيانات
        $workOrder = $workOrder->fresh();
        
        $workOrder->load([
            'files', 
            'basicAttachments', 
            'workOrderItems.workItem', 
            'workOrderMaterials.material',
            'invoiceAttachments'
        ]);
        
        // جلب صور التنفيذ
        $executionImages = $workOrder->files()
            ->where('file_category', 'execution_images')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $hasWorkItems = $workOrder->workOrderItems()->count() > 0;
        $hasMaterials = $workOrder->workOrderMaterials()->count() > 0;
        
        $totalWorkItemsValue = $workOrder->workOrderItems()->sum(DB::raw('quantity * unit_price'));
        $totalMaterialsValue = 0; // تم إزالة حساب unit_price من المواد
        
        $grandTotal = $totalWorkItemsValue + $totalMaterialsValue;
        
        // جلب سجل التنفيذ اليومي
        $dailyExecutions = DailyWorkExecution::where('work_order_id', $workOrder->id)
            ->with(['workOrderItem.workItem', 'createdBy'])
            ->orderBy('work_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // جلب ملاحظات التنفيذ اليومي
        $dailyNotes = DailyExecutionNote::where('work_order_id', $workOrder->id)
            ->with('createdBy')
            ->orderBy('execution_date', 'desc')
            ->get();
        
        return view('admin.work_orders.actions-execution', compact(
            'workOrder', 
            'hasWorkItems', 
            'hasMaterials', 
            'totalWorkItemsValue', 
            'totalMaterialsValue', 
            'grandTotal',
            'executionImages',
            'dailyExecutions',
            'dailyNotes'
        ));
    }

    /**
     * إضافة بند عمل جديد
     */
    public function addWorkItem(Request $request)
    {
        try {
            $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'work_item_id' => 'required|exists:work_items,id',
                'quantity' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'work_date' => 'required|date'
            ]);

            // الحصول على بيانات بند العمل
            $workItem = \App\Models\WorkItem::findOrFail($request->work_item_id);

            // التحقق من عدم وجود البند مسبقاً في نفس أمر العمل ونفس التاريخ
            $existingItem = \App\Models\WorkOrderItem::where('work_order_id', $request->work_order_id)
                ->where('work_item_id', $request->work_item_id)
                ->where('work_date', $request->work_date)
                ->first();

            if ($existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا البند موجود بالفعل في أمر العمل لهذا التاريخ'
                ]);
            }

            // تحديد الكمية المخططة - إما من الإدخال أو القيمة الافتراضية
            $plannedQuantity = $request->quantity ?? 0.00;
            
            // إنشاء ربط بين أمر العمل وبند العمل
            $workOrderItem = \App\Models\WorkOrderItem::create([
                'work_order_id' => $request->work_order_id,
                'work_item_id' => $workItem->id,
                'quantity' => $plannedQuantity, // الكمية المخططة من الإدخال أو افتراضية
                'unit_price' => $workItem->unit_price ?? 0, // سعر الوحدة من بند العمل
                'unit' => $workItem->unit ?? 'EA', // الوحدة من بند العمل
                'executed_quantity' => 0,
                'notes' => $request->notes,
                'work_date' => $request->work_date
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة بند العمل بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة بند العمل: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * تحديث بند عمل - حفظ الكمية اليومية
     */
    public function updateWorkItem(Request $request, \App\Models\WorkOrderItem $workOrderItem)
    {
        try {
            $request->validate([
                'executed_quantity' => 'required|numeric|min:0',
                'work_date' => 'required|date'
            ]);

            $workDate = $request->work_date;
            $executedQuantity = $request->executed_quantity;

            // البحث عن التنفيذ اليومي الموجود أو إنشاء جديد
            $dailyExecution = \App\Models\DailyWorkExecution::updateOrCreate(
                [
                    'work_order_item_id' => $workOrderItem->id,
                    'work_date' => $workDate
                ],
                [
                    'work_order_id' => $workOrderItem->work_order_id,
                    'executed_quantity' => $executedQuantity,
                    'created_by' => auth()->id()
                ]
            );

            // تحديث إجمالي الكمية المنفذة في work_order_item
            $totalExecutedQuantity = $workOrderItem->dailyExecutions()->sum('executed_quantity');
            $workOrderItem->update([
                'executed_quantity' => $totalExecutedQuantity,
                'work_date' => $workDate // تحديث آخر تاريخ عمل
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الكمية المنفذة لتاريخ ' . $workDate . ' بنجاح',
                'daily_execution_id' => $dailyExecution->id,
                'total_executed_quantity' => $totalExecutedQuantity
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف بند عمل
     */
    public function deleteWorkItem(\App\Models\WorkOrderItem $workOrderItem)
    {
        try {
            $workOrderItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف بند العمل بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف بند العمل: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * عرض صفحة التركيبات
     */
    public function installations(WorkOrder $workOrder)
    {
        // تحديث البيانات من قاعدة البيانات
        $workOrder = $workOrder->fresh();
        
        // تسجيل البيانات المسترجعة للتشخيص
        Log::info('Retrieved installations data for work order ' . $workOrder->id);
        Log::info('Raw from DB:', ['data' => $workOrder->getOriginal('installations_data')]);
        Log::info('Cast data:', ['data' => $workOrder->installations_data]);
        Log::info('Accessor data:', ['data' => $workOrder->installations]);
        
        // أيضاً تحقق من نوع البيانات
        Log::info('Data types:');
        Log::info('Raw type:', ['type' => gettype($workOrder->getOriginal('installations_data'))]);
        Log::info('Cast type:', ['type' => gettype($workOrder->installations_data)]);
        Log::info('Accessor type:', ['type' => gettype($workOrder->installations)]);
        
        $installations = [
            'single_meter_box' => 'تركيب صندوق ومفرد بعداد واحد',
            'double_meter_box_single' => 'تركيب صندوق مزدوج بعداد واحد',
            'double_meter_box_double' => 'تركيب صندوق مزدوج بعدادين',
            'quad_meter_box_triple' => 'تركيب صندوق رباعي بثلاث عدادات',
            'quad_meter_box_quad' => 'تركيب صندوق رباعي 4 عدادات',
            'ct_meter_box' => 'تركيب صندوق بعداد CT',
            'mini_pillar_base' => 'تركيب قاعدة ميني بلر فقط',
            'mini_pillar_head' => 'تركيب راسي ميني بلر فقط',
            'mini_pillar_ml' => 'تركيب ميني بلر كامل',
            'ring_base_triple' => 'تركيب قاعدة رنج ثلاثي',
            'ring_base_quad' => 'تركيب قاعدة رنج رباعي',
            'cole_khar_500' => 'تركيب قاعدة خرسان محول 500',
            'cole_base_500' => 'تركيب محول 500',
            'cole_base_1000' => 'تركيب قاعدة خرسانة محول 1000',
            'cole_1000' => 'تركيب محول 1000',
            'cole_base_1500' => 'تركيب قاعدة خرسانة محول 1500',
            'cole_khara_1500' => 'تركيب محول 1500',
            'pole_10m' => 'تركيب عمود 10 متر',
            'pole_13m' => 'تركيب عمود 13 متر',
            'pole_14m' => 'تركيب عمود 14 متر',
            'Installing_antenna_100' => 'تركيب محول هوائي 100',
            'Installing_antenna_200' => 'تركيب محول هوائي 200',
            'Installing_antenna_300' => 'تركيب محول هوائي 300',
            'Installing_knife' => 'تركيب سكينة LBS',
            'Class_teacher' => 'تركيب معيد الفصل',
            'Split_installation' => 'تركيب مجزئ',
            'Low_plate_1600' => 'تركيب لوحة منخفض 1600',
            'Low_plate_3000' => 'تركيب لوحة منخفض 3000'
        ];
        
        return view('admin.work_orders.installations', compact('workOrder', 'installations'));
    }

    /**
     * حفظ بيانات التركيبات
     */
    public function saveInstallations(Request $request, WorkOrder $workOrder)
    {
        try {
            DB::beginTransaction();

            // Delete existing installations for this work order
            \App\Models\WorkOrderInstallation::where('work_order_id', $workOrder->id)->delete();

            // Save new installations
            foreach ($request->all() as $type => $data) {
                if ($data['price'] > 0 && $data['number'] > 0) {
                    \App\Models\WorkOrderInstallation::create([
                        'work_order_id' => $workOrder->id,
                        'installation_type' => $type,
                        'price' => $data['price'],
                        'quantity' => $data['number'],
                        'total' => $data['total']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حفظ التركيبات بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saving installations: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ التركيبات'
            ], 500);
        }
    }

    /**
     * استرجاع بيانات التركيبات
     */
    public function getInstallations(WorkOrder $workOrder)
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => $workOrder->installations_data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء استرجاع البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع صور التركيبات
     */
    public function uploadInstallationsImages(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $uploadedFiles = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('installations', 'public');
                    $uploadedFiles[] = [
                        'path' => $path,
                        'name' => $image->getClientOriginalName()
                    ];
                }
            }

            // تحديث بيانات الصور في أمر العمل
            $currentImages = $workOrder->installations_images ?? [];
            $workOrder->update([
                'installations_images' => array_merge($currentImages, $uploadedFiles)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم رفع الصور بنجاح',
                'files' => $uploadedFiles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة تركيبات
     */
    public function deleteInstallationImage($imageId)
    {
        try {
            // تنفيذ عملية الحذف
            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الصورة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * عرض صفحة الأعمال المدنية الجديدة (النظام المحسن)
     */
    public function civilWorksNew(Request $request, WorkOrder $workOrder)
    {
        // الحصول على التاريخ المطلوب، الافتراضي هو اليوم
        $workDate = $request->get('date', now()->format('Y-m-d'));
        
        // الحصول على البيانات المحفوظة لهذا التاريخ
        $savedDailyData = \App\Models\DailyCivilWork::getByWorkOrderAndDate($workOrder->id, $workDate);
        
        // الحصول على التواريخ المتاحة
        $availableDates = \App\Models\DailyCivilWork::getAvailableDatesByWorkOrder($workOrder->id);
        
        // حساب الإحصائيات اليومية
        $dailyStats = [
            'total_items' => $savedDailyData->count(),
            'total_cost' => $savedDailyData->sum('total_cost'),
            'total_length' => $savedDailyData->sum('length'),
            'total_volume' => $savedDailyData->sum('volume'),
        ];
        
        return view('admin.work_orders.civil_works_new', compact(
            'workOrder', 
            'savedDailyData', 
            'workDate', 
            'availableDates', 
            'dailyStats'
        ));
    }

    /**
     * حفظ بيانات الأعمال المدنية اليومية (النظام الجديد)
     */
    public function saveDailyCivilWork(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'work_date' => 'required|date',
                'work_type' => 'required|string|max:255',
                'cable_type' => 'required|string|max:255',
                'length' => 'required|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'depth' => 'nullable|numeric|min:0',
                'unit_price' => 'required|numeric|min:0'
            ]);

            $dailyCivilWork = \App\Models\DailyCivilWork::create([
                'work_order_id' => $workOrder->id,
                'work_date' => $validated['work_date'],
                'work_type' => $validated['work_type'],
                'cable_type' => $validated['cable_type'],
                'length' => $validated['length'],
                'width' => $validated['width'],
                'depth' => $validated['depth'],
                'unit_price' => $validated['unit_price']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ البيانات بنجاح',
                'data' => $dailyCivilWork
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving daily civil work', [
                'error' => $e->getMessage(),
                'work_order_id' => $workOrder->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على بيانات الأعمال المدنية لتاريخ معين (النظام الجديد)
     */
    public function getDailyCivilWorksData(Request $request, WorkOrder $workOrder)
    {
        $workDate = $request->get('date', now()->format('Y-m-d'));
        
        $data = \App\Models\DailyCivilWork::getByWorkOrderAndDate($workOrder->id, $workDate);
        
        $stats = [
            'total_items' => $data->count(),
            'total_cost' => $data->sum('total_cost'),
            'total_length' => $data->sum('length'),
            'total_volume' => $data->sum('volume'),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'stats' => $stats,
            'date' => $workDate
        ]);
    }

    /**
     * حذف عنصر من الأعمال المدنية اليومية
     */
    public function deleteDailyCivilWork(Request $request, WorkOrder $workOrder, $itemId)
    {
        try {
            $item = \App\Models\DailyCivilWork::where('work_order_id', $workOrder->id)
                                               ->where('id', $itemId)
                                               ->first();
            
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنصر غير موجود'
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العنصر بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في حذف العنصر: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * مسح جميع بيانات تاريخ معين
     */
    public function clearDailyCivilWorksByDate(Request $request, WorkOrder $workOrder)
    {
        try {
            $workDate = $request->get('date');
            
            if (!$workDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'التاريخ مطلوب'
                ], 400);
            }

            $deletedCount = \App\Models\DailyCivilWork::clearByWorkOrderAndDate($workOrder->id, $workDate);

            return response()->json([
                'success' => true,
                'message' => "تم حذف {$deletedCount} عنصر بنجاح"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في حذف البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get excavation details for AJAX request
     */
    public function getExcavationDetails(WorkOrder $workOrder)
    {
        $excavationDetails = $workOrder->excavation_details_table ?? [];
        
        return response()->json([
            'success' => true,
            'excavationDetails' => $excavationDetails
        ]);
    }

    /**
     * Save daily civil works data
     */
    public function saveDailyData(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('Starting saveDailyData', [
                'work_order_id' => $workOrder->id,
                'request_data' => $request->all()
            ]);
            
            // استلام البيانات اليومية
            $dailyDataJson = $request->input('daily_data');
            \Log::info('Received daily_data', ['daily_data_json' => $dailyDataJson]);
            
            $dailyData = json_decode($dailyDataJson, true);
            
            if (!$dailyData || !is_array($dailyData)) {
                \Log::warning('Invalid daily data received', [
                    'daily_data' => $dailyData,
                    'json_error' => json_last_error_msg()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد بيانات صحيحة لحفظها'
                ], 400);
            }
            
            // تحضير البيانات للحفظ
            $existingDailyData = $workOrder->daily_civil_works_data ?? $workOrder->excavation_details_table ?? [];
            $newDailyEntries = [];
            
            foreach ($dailyData as $item) {
                $newDailyEntries[] = [
                    'work_type' => $item['work_type'] ?? '',
                    'cable_type' => $item['cable_type'] ?? '',
                    'length' => floatval($item['length'] ?? 0),
                    'width' => isset($item['width']) ? floatval($item['width']) : null,
                    'depth' => isset($item['depth']) ? floatval($item['depth']) : null,
                    'volume' => isset($item['volume']) ? floatval($item['volume']) : null,
                    'price' => floatval($item['price'] ?? 0),
                    'total' => floatval($item['total'] ?? 0),
                    'date' => $item['date'] ?? now()->format('Y-m-d'),
                    'time' => $item['time'] ?? now()->format('H:i:s'),
                    'created_at' => now()->toISOString()
                ];
            }
            
            \Log::info('Prepared daily entries', [
                'count' => count($newDailyEntries),
                'entries' => $newDailyEntries
            ]);
            
            // دمج البيانات الجديدة مع الموجودة
            $allDailyData = array_merge($existingDailyData, $newDailyEntries);
            
            // حفظ البيانات في قاعدة البيانات
            // استخدام excavation_details_table كبديل إذا لم يكن daily_civil_works_data موجود
            $updateData = [];
            
            // التحقق من وجود العمود
            if (Schema::hasColumn('work_orders', 'daily_civil_works_data')) {
                $updateData['daily_civil_works_data'] = $allDailyData;
            } else {
                // استخدام excavation_details_table كبديل
                $updateData['excavation_details_table'] = $allDailyData;
            }
            
            $updateResult = $workOrder->update($updateData);
            
            \Log::info('Database update result', [
                'success' => $updateResult,
                'total_entries' => count($allDailyData)
            ]);
            
            // حساب الإحصائيات
            $totalAmount = array_sum(array_column($newDailyEntries, 'total'));
            $totalItems = count($newDailyEntries);
            
            return response()->json([
                'success' => true,
                'message' => "تم حفظ {$totalItems} عنصر بإجمالي {$totalAmount} ريال",
                'data' => [
                    'saved_items' => $totalItems,
                    'total_amount' => $totalAmount,
                    'daily_entries' => $newDailyEntries
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving daily civil works data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'work_order_id' => $workOrder->id ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear daily civil works data
     */
    public function clearDailyData(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('Starting clearDailyData', [
                'work_order_id' => $workOrder->id
            ]);
            
            // حفظ بيانات فارغة في قاعدة البيانات
            $updateData = [];
            
            // التحقق من وجود العمود
            if (Schema::hasColumn('work_orders', 'daily_civil_works_data')) {
                $updateData['daily_civil_works_data'] = [];
            } else {
                // استخدام excavation_details_table كبديل
                $updateData['excavation_details_table'] = [];
            }
            
            $updateResult = $workOrder->update($updateData);
            
            \Log::info('Database clear result', [
                'success' => $updateResult
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "تم إفراغ الملخص اليومي بنجاح"
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error clearing daily civil works data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'work_order_id' => $workOrder->id ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إفراغ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadElectricalWorksImages(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'electrical_works_images.*' => 'nullable|image|max:30720', // 30MB max per image
            ]);

            $newFiles = [];
            if ($request->hasFile('electrical_works_images')) {
                $files = $request->file('electrical_works_images');
                $totalSize = 0;
                foreach ($files as $file) {
                    $totalSize += $file->getSize();
                }
                if ($totalSize > 31457280) { // 30MB
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'الحجم الإجمالي للصور يجب أن لا يتجاوز 30 ميجابايت'
                        ], 422);
                    } else {
                        return back()->with('error', 'الحجم الإجمالي للصور يجب أن لا يتجاوز 30 ميجابايت');
                    }
                }
                if (count($files) > 70) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'يمكنك رفع 70 صورة كحد أقصى'
                        ], 422);
                    } else {
                        return back()->with('error', 'يمكنك رفع 70 صورة كحد أقصى');
                    }
                }
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $path = 'work_orders/' . $workOrder->id . '/electrical_works';
                    if (!\Storage::disk('public')->exists($path)) {
                        \Storage::disk('public')->makeDirectory($path);
                    }
                    $filePath = $file->storeAs($path, $filename, 'public');
                    $newFile = \App\Models\WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_name' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'electrical_works'
                    ]);
                    $newFiles[] = $newFile;
                }
            }

            // جلب جميع الصور المحدثة
            $allFiles = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
                ->where('file_category', 'electrical_works')
                ->where('file_type', 'like', 'image/%')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع الصور بنجاح',
                    'images' => $allFiles->map(function($file) {
                        return [
                            'id' => $file->id,
                            'url' => asset('storage/' . $file->file_path),
                            'name' => $file->original_filename,
                            'size' => round($file->file_size / 1024 / 1024, 2),
                            'created_at' => $file->created_at->format('Y-m-d H:i:s')
                        ];
                    }),
                    'total_count' => $allFiles->count()
                ]);
            } else {
                return back()->with('success', 'تم رفع الصور بنجاح');
            }
        } catch (\Exception $e) {
            Log::error('Error uploading electrical works images: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage()
                ], 500);
            } else {
                return back()->with('error', 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage());
            }
        }
    }

    public function getMaterialDescription($code)
    {
        $material = \App\Models\ReferenceMaterial::where('code', $code)->first();
        
        if ($material) {
            return response()->json([
                'success' => true,
                'description' => $material->description
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Material not found'
        ]);
    }

    /**
     * Import work items from Excel file
     */
    public function importWorkItems(Request $request)
    {
        ini_set('memory_limit', '2G');
        
        try {
            $file = $request->file('file');
            $project = $request->get('project', 'riyadh'); // افتراضياً الرياض
            $city = $this->getProjectCityName($project);
            
            Log::info('File details', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'project' => $project,
                'city' => $city
            ]);

            // التحقق من أن مكتبة Excel متاحة
            if (!class_exists('\Maatwebsite\Excel\Facades\Excel')) {
                return response()->json([
                    'success' => false,
                    'message' => 'مكتبة Excel غير متاحة'
                ], 500);
            }

            // التحقق من أن كلاس WorkItemsImport يعمل
            if (!class_exists('\App\Imports\WorkItemsImport')) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلاس الاستيراد غير متاح'
                ], 500);
            }

            $import = new \App\Imports\WorkItemsImport(0, $city);
            Log::info('Import class created successfully');

            \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            Log::info('Excel import completed');

            $importedItems = $import->getImportedItems();
            $errors = $import->errors();

            Log::info('Import results', [
                'imported_count' => count($importedItems),
                'errors_count' => count($errors)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد ' . count($importedItems) . ' عنصر بنجاح لمدينة ' . $city,
                'imported_count' => count($importedItems),
                'errors_count' => count($errors),
                'errors' => $errors,
                'imported_items' => $importedItems,
                'city' => $city
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من صحة البيانات: ' . implode(', ', $e->errors()['file'] ?? ['خطأ غير محدد'])
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error importing work items:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import work order materials from Excel file
     */
    public function importWorkOrderMaterials(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $city = $this->getProjectCityName($project);
            
            Log::info('Starting materials import', ['project' => $project, 'city' => $city]);
            
            // التحقق من وجود الملف
            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تحديد ملف للاستيراد'
                ], 400);
            }

            $file = $request->file('file');
            
            // التحقق من نوع الملف
            if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls', 'csv'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الملف غير مدعوم. الأنواع المدعومة هي: xlsx, xls, csv'
                ], 400);
            }

            // استخدام WorkOrderMaterialsImport للاستيراد
            $import = new \App\Imports\WorkOrderMaterialsImport($city);
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            
            $importedMaterials = $import->getImportedMaterials();
            $errors = $import->errors();

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد ' . count($importedMaterials) . ' مادة بنجاح لمدينة ' . $city,
                'imported_materials' => $importedMaterials,
                'errors' => $errors,
                'city' => $city
            ]);

                        // الطريقة القديمة - تم استبدالها بالطريقة الجديدة أعلاه
            /*
            // قراءة الملف باستخدام PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // تجاهل الصف الأول (العناوين)
            array_shift($rows);

            $importedMaterials = [];
            $errors = [];

            DB::beginTransaction();

            try {
                foreach ($rows as $index => $row) {
                    // تجاهل الصفوف الفارغة
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $code = trim($row[0] ?? '');
                    $description = trim($row[1] ?? '');

                    // التحقق من وجود البيانات المطلوبة
                    if (empty($code) || empty($description)) {
                        $errors[] = "الصف " . ($index + 2) . ": كود المادة أو الوصف مفقود";
                        continue;
                    }

                    try {
                        // البحث عن المادة في جدول المواد المرجعية أو إنشاء واحدة جديدة
                        $project = $request->get('project', 'riyadh');
                        $city = $this->getProjectCityName($project);
                        
                        $material = \App\Models\ReferenceMaterial::firstOrCreate(
                            ['code' => $code, 'city' => $city],
                            [
                                'description' => $description,
                                'unit' => 'قطعة', // وحدة افتراضية
                                'city' => $city,
                                'is_active' => true
                            ]
                        );

                        // تنسيق البيانات للعرض في الواجهة
                        $importedMaterials[] = [
                            'code' => $material->code,
                            'description' => $material->description,
                            'unit' => $material->unit,
                            'planned_quantity' => 1
                        ];

                        Log::info('Material imported successfully', [
                            'code' => $material->code,
                            'description' => $material->description
                        ]);

                    } catch (\Exception $e) {
                        Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
                        $errors[] = "خطأ في معالجة الصف " . ($index + 2) . ": " . $e->getMessage();
                    }
                }

                if (empty($importedMaterials) && !empty($errors)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'فشل استيراد المواد',
                        'errors' => $errors
                    ], 422);
                }

                DB::commit();

                Log::info('Import completed successfully', [
                    'imported_count' => count($importedMaterials),
                    'errors_count' => count($errors)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم استيراد ' . count($importedMaterials) . ' مادة بنجاح لمدينة ' . $city,
                    'imported_materials' => $importedMaterials,
                    'errors' => $errors,
                    'city' => $city
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Transaction error: " . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage()
            ], 500);
            */
        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage()
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
     * تحميل نموذج Excel للمواد
     */
    public function downloadMaterialsTemplate()
    {
        try {
            // التحقق من وجود PhpSpreadsheet
            if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                // إنشاء ملف CSV بدلاً من Excel
                return $this->downloadMaterialsCSVTemplate();
            }

            $headers = [
                'code',
                'description'
            ];

            $sampleData = [
                ['M001', 'كابل كهرباء 10 مم'],
                ['M002', 'مفتاح كهرباء'],
                ['M003', 'علبة توصيل'],
            ];

            // إنشاء ملف Excel
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // إضافة العناوين
            $sheet->fromArray($headers, NULL, 'A1');
            
            // إضافة البيانات النموذجية
            $sheet->fromArray($sampleData, NULL, 'A2');

            // تنسيق العناوين
            $sheet->getStyle('A1:B1')->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFCCCCCC']
                ]
            ]);

            // تعديل عرض الأعمدة
            foreach (range('A', 'B') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // حفظ الملف
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $fileName = 'materials_template.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($temp_file);

            return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error creating Excel template: ' . $e->getMessage());
            // إنشاء ملف CSV بدلاً من Excel
            return $this->downloadMaterialsCSVTemplate();
        }
    }

    /**
     * تحميل نموذج CSV للمواد كبديل
     */
    private function downloadMaterialsCSVTemplate()
    {
                 $headers = [
             'كود المادة',
             'وصف المادة'
         ];

         $sampleData = [
             ['M001', 'كابل كهرباء 10 مم'],
             ['M002', 'مفتاح كهرباء'],
             ['M003', 'علبة توصيل'],
         ];

        $fileName = 'materials_template.csv';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $handle = fopen($temp_file, 'w');
        
        // إضافة UTF-8 BOM للتعامل مع الأحرف العربية
        fwrite($handle, "\xEF\xBB\xBF");
        
        // إضافة العناوين
        fputcsv($handle, $headers);
        
        // إضافة البيانات النموذجية
        foreach ($sampleData as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Get work items with search functionality
     */
    public function getWorkItems(Request $request)
    {
        $query = \App\Models\WorkItem::query();

        // تصفية حسب المشروع إذا تم تمريره
        if ($request->has('project') && !empty($request->project)) {
            $query->byProject($request->project);
        }

        // البحث في كل الأعمدة
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('code', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('unit', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // إظهار العناصر النشطة فقط
        $query->where('is_active', true);

        $workItems = $query->orderBy('code')->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $workItems->items(),
                'pagination' => [
                    'current_page' => $workItems->currentPage(),
                    'last_page' => $workItems->lastPage(),
                    'total' => $workItems->total(),
                    'per_page' => $workItems->perPage(),
                ]
            ]);
        }

        return view('admin.work_items.index', compact('workItems'));
    }

    /**
     * Search reference materials for work orders
     */
    public function searchReferenceMaterials(Request $request)
    {
        try {
            $query = \App\Models\ReferenceMaterial::query();

            // تصفية حسب المشروع/المدينة
            $project = $request->get('project', 'riyadh');
            $query->byProject($project);

            // البحث بالكود
            if ($request->filled('code')) {
                $query->where('code', 'LIKE', '%' . $request->code . '%');
            }

            // البحث بالوصف
            if ($request->filled('description')) {
                $query->where('description', 'LIKE', '%' . $request->description . '%');
            }

            // البحث العام (في الكود والوصف)
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('code', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }

            // ترتيب النتائج
            $materials = $query->where('is_active', true)
                              ->orderBy('code')
                              ->limit(100) // حد أقصى 100 نتيجة
                              ->get();

            return response()->json([
                'success' => true,
                'data' => $materials,
                'count' => $materials->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching reference materials: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث في المواد',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateLicense(Request $request, WorkOrder $workOrder)
    {
        try {
            Log::info('Received license update request:', $request->all());

            $validatedData = $request->validate([
                'license_number' => 'nullable|string|max:255',
                'license_date' => 'nullable|date',
                'license_type' => 'nullable|string|max:255',
                'license_value' => 'nullable|numeric|min:0',
                'extension_value' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'has_restriction' => 'nullable|boolean',
                'restriction_authority' => 'nullable|string|max:255',
                'restriction_reason' => 'nullable|string|max:255',
                'restriction_notes' => 'nullable|string',
                'coordination_certificate_notes' => 'nullable|string',
                'license_start_date' => 'nullable|date',
                'license_end_date' => 'nullable|date',
                'license_alert_days' => 'required|integer|min:0',
                'license_length' => 'nullable|numeric|min:0',
                'excavation_length' => 'nullable|numeric|min:0',
                'excavation_width' => 'nullable|numeric|min:0',
                'excavation_depth' => 'nullable|numeric|min:0',
                'has_depth_test' => 'nullable|boolean',
                'has_soil_compaction_test' => 'nullable|boolean',
                'has_rc1_mc1_test' => 'nullable|boolean',
                'has_asphalt_test' => 'nullable|boolean',
                'has_soil_test' => 'nullable|boolean',
                'has_interlock_test' => 'nullable|boolean',
                'is_evacuated' => 'nullable|boolean',
                'evac_license_number' => 'nullable|string|max:255',
                'evac_license_value' => 'nullable|numeric|min:0',
                'evac_payment_number' => 'nullable|string|max:255',
                'evac_date' => 'nullable|date',
                'evac_amount' => 'nullable|numeric|min:0',
                'successful_tests_value' => 'nullable|numeric|min:0',
                'failed_tests_value' => 'nullable|numeric|min:0',
                'test_failure_reasons' => 'nullable|string',
                'lab_table1_data' => 'nullable|string',
                'lab_table2_data' => 'nullable|string',
            ]);

            Log::info('Validation passed');
            
            // تسجيل قيم نتائج الاختبارات للتحقق
            Log::info('Test results values from request:', [
                'successful_tests_value' => $validatedData['successful_tests_value'] ?? null,
                'failed_tests_value' => $validatedData['failed_tests_value'] ?? null,
                'test_failure_reasons' => $validatedData['test_failure_reasons'] ?? null
            ]);

            // تحديث أو إنشاء سجل الرخصة
            $license = \App\Models\License::updateOrCreate(
                ['work_order_id' => $workOrder->id],
                [
                    'user_id' => auth()->id(),
                    'license_number' => $validatedData['license_number'] ?? null,
                    'license_date' => $validatedData['license_date'] ?? null,
                    'license_type' => $validatedData['license_type'] ?? null,
                    'license_value' => $validatedData['license_value'] ?? null,
                    'extension_value' => $validatedData['extension_value'] ?? null,
                    'notes' => $validatedData['notes'] ?? null,
                    'has_restriction' => $request->boolean('has_restriction'),
                    'restriction_authority' => $validatedData['restriction_authority'] ?? null,
                    'restriction_reason' => $validatedData['restriction_reason'] ?? null,
                    'restriction_notes' => $validatedData['restriction_notes'] ?? null,
                    'coordination_certificate_notes' => $validatedData['coordination_certificate_notes'] ?? null,
                    'license_start_date' => $validatedData['license_start_date'] ?? null,
                    'license_end_date' => $validatedData['license_end_date'] ?? null,
                    'license_alert_days' => $validatedData['license_alert_days'] ?? 30,
                    'license_length' => $validatedData['license_length'] ?? null,
                    'excavation_length' => $validatedData['excavation_length'] ?? null,
                    'excavation_width' => $validatedData['excavation_width'] ?? null,
                    'excavation_depth' => $validatedData['excavation_depth'] ?? null,
                    'has_depth_test' => $request->boolean('has_depth_test'),
                    'has_soil_compaction_test' => $request->boolean('has_soil_compaction_test'),
                    'has_rc1_mc1_test' => $request->boolean('has_rc1_mc1_test'),
                    'has_asphalt_test' => $request->boolean('has_asphalt_test'),
                    'has_soil_test' => $request->boolean('has_soil_test'),
                    'has_interlock_test' => $request->boolean('has_interlock_test'),
                    'is_evacuated' => $request->boolean('is_evacuated'),
                    'evac_license_number' => $validatedData['evac_license_number'] ?? null,
                    'evac_license_value' => $validatedData['evac_license_value'] ?? null,
                    'evac_payment_number' => $validatedData['evac_payment_number'] ?? null,
                    'evac_date' => $validatedData['evac_date'] ?? null,
                    'evac_amount' => $validatedData['evac_amount'] ?? null,
                    'successful_tests_value' => $validatedData['successful_tests_value'] ?? null,
                    'failed_tests_value' => $validatedData['failed_tests_value'] ?? null,
                    'test_failure_reasons' => $validatedData['test_failure_reasons'] ?? null,
                ]
            );

            Log::info('License record created/updated:', ['license_id' => $license->id]);

            // معالجة بيانات الجداول
            if ($request->has('lab_table1_data')) {
                $table1Data = json_decode($request->lab_table1_data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $license->lab_table1_data = $table1Data;
                    $license->save();
                    Log::info('Lab table 1 data processed');
                }
            }
            
            if ($request->has('lab_table2_data')) {
                $table2Data = json_decode($request->lab_table2_data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $license->lab_table2_data = $table2Data;
                    $license->save();
                    Log::info('Lab table 2 data processed');
                }
            }

            // معالجة بيانات جداول الإخلاءات
            if ($request->has('evac_table1')) {
                $evacTable1Data = $request->input('evac_table1');
                if (is_array($evacTable1Data) && !empty($evacTable1Data)) {
                    $license->evac_table1_data = $evacTable1Data;
                    $license->save();
                    Log::info('Evacuation table 1 data processed');
                }
            }
            
            if ($request->has('evac_table2')) {
                $evacTable2Data = $request->input('evac_table2');
                if (is_array($evacTable2Data) && !empty($evacTable2Data)) {
                    $license->evac_table2_data = $evacTable2Data;
                    $license->save();
                    Log::info('Evacuation table 2 data processed');
                }
            }

            // معالجة الملفات
            $fileFields = [
                'coordination_certificate_path' => 'coordination_certificate_path',
                'letters_commitments_files' => 'letters_commitments_file_path',
                'payment_invoices' => 'payment_invoices_path',
                'payment_proof' => 'payment_proof_path',
                'license_activation' => 'activation_file_path',
                'extension_invoice' => 'invoice_extension_file_path',
                'soil_test_images' => 'soil_test_images_path',
                'evacuations_files' => 'evacuations_file_path',
                'violations_files' => 'violation_files_path'
            ];

            foreach ($fileFields as $requestField => $dbField) {
                if ($request->hasFile($requestField)) {
                    $files = $request->file($requestField);
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    
                    $filePaths = [];
                    foreach ($files as $file) {
                        if ($file && $file->isValid()) {
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $path = $file->storeAs("work_orders/{$workOrder->id}/licenses", $filename, 'public');
                            $filePaths[] = $path;
                            Log::info('File uploaded:', ['field' => $requestField, 'path' => $path]);
                        }
                    }
                    
                    if (!empty($filePaths)) {
                        $license->$dbField = count($filePaths) === 1 ? $filePaths[0] : json_encode($filePaths, JSON_UNESCAPED_UNICODE);
                        $license->save();
                    }
                }
            }

            // معالجة مرفقات الملاحظات
            if ($request->hasFile('notes_attachments')) {
                $paths = [];
                foreach ($request->file('notes_attachments') as $file) {
                    $path = $file->store('licenses/notes_attachments', 'public');
                    $paths[] = $path;
                    Log::info('Notes attachment uploaded:', ['path' => $path]);
                }
                $license->notes_attachments_path = json_encode($paths, JSON_UNESCAPED_UNICODE);
                $license->save();
            }

            Log::info('License update completed successfully');

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ بيانات الرخصة بنجاح',
                'redirect' => route('admin.licenses.data')
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            Log::error('Error updating license: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ بيانات الرخصة: ' . $e->getMessage()
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * تثبيت بيانات الأعمال المدنية
     */
    public function lockCivilWorksImages(Request $request, WorkOrder $workOrder)
    {
        try {
            Log::info('Locking civil works data', [
                'work_order_id' => $workOrder->id,
                'user_id' => auth()->id()
            ]);

            // تحديث حالة التثبيت
            $workOrder->update([
                'civil_works_images_locked' => true,
                'civil_works_images_locked_at' => now(),
                'civil_works_images_locked_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تثبيت بيانات الأعمال المدنية بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error locking civil works data', [
                'error' => $e->getMessage(),
                'work_order_id' => $workOrder->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التثبيت'
            ], 500);
        }
    }

    /**
     * حفظ صور الأعمال المدنية
     */
    public function saveCivilWorksImages(Request $request, WorkOrder $workOrder)
    {
        try {
            Log::info('Starting saveCivilWorksImages', [
                'work_order_id' => $workOrder->id,
                'user_id' => auth()->id(),
                'request_has_files' => $request->hasFile('civil_works_images'),
                'request_all' => $request->all(),
                'files_count' => $request->hasFile('civil_works_images') ? count($request->file('civil_works_images')) : 0,
                'content_type' => $request->header('Content-Type'),
                'is_ajax' => $request->ajax(),
                'is_json' => $request->isJson(),
                'headers' => $request->headers->all()
            ]);

            $request->validate([
                'civil_works_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff|max:10240', // 10MB max per image
            ]);

            $savedImages = [];

            if ($request->hasFile('civil_works_images')) {
                $files = $request->file('civil_works_images');

                foreach ($files as $file) {
                    Log::info('Processing file', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize()
                    ]);

                    if (!$file->isValid()) {
                        Log::error('Invalid file', [
                            'original_name' => $file->getClientOriginalName(),
                            'error' => $file->getError(),
                            'error_message' => $file->getErrorMessage()
                        ]);
                        continue;
                    }

                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    // تحسين اسم الملف للخادم العالمي
                    $filename = 'civil_' . $workOrder->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                    // مسار مهيأ للخادم العالمي
                    $directory = 'work_orders/' . $workOrder->id . '/civil_exec';
                    
                    // إنشاء المجلد إذا لم يكن موجوداً
                    if (!\Storage::disk('public')->exists($directory)) {
                        \Storage::disk('public')->makeDirectory($directory, 0755, true);
                    }
                    
                    $filePath = $file->storeAs($directory, $filename, 'public');
                    
                    if (!$filePath) {
                        Log::error('Failed to store file', [
                            'original_name' => $originalName,
                            'directory' => $directory,
                            'filename' => $filename
                        ]);
                        continue;
                    }
                    
                    // حفظ بيانات الملف في قاعدة البيانات
                    $savedFile = \App\Models\WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_name' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'civil_exec' // تغيير التصنيف للصور
                    ]);
                    
                    $savedImages[] = $savedFile;
                    
                    Log::info('Image saved successfully', [
                        'filename' => $filename,
                        'path' => $filePath,
                        'size' => $file->getSize()
                    ]);
                }
            } else {
                Log::warning('No files found in request');
            }

            $response = [
                'success' => true,
                'message' => 'تم حفظ ' . count($savedImages) . ' صورة بنجاح',
                'images_count' => count($savedImages),
                'images' => collect($savedImages)->map(function($img) {
                    return [
                        'id' => $img->id,
                        'filename' => $img->filename,
                        'original_filename' => $img->original_filename,
                        'url' => asset('storage/' . $img->file_path),
                        'size' => $img->file_size
                    ];
                })
            ];

            Log::info('Returning response', $response);
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error saving civil works images', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'work_order_id' => $workOrder->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الصور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حفظ مرفقات الأعمال المدنية
     */
    public function saveCivilWorksAttachments(Request $request, WorkOrder $workOrder)
    {
        try {
            Log::info('Saving civil works attachments', [
                'work_order_id' => $workOrder->id,
                'user_id' => auth()->id()
            ]);

            $request->validate([
                'civil_works_attachments.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:20480', // 20MB max per file
            ]);

            $savedAttachments = [];

            if ($request->hasFile('civil_works_attachments')) {
                $files = $request->file('civil_works_attachments');

                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    // تحسين اسم الملف للخادم العالمي
                    $filename = 'attach_' . $workOrder->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                    // مسار مهيأ للخادم العالمي
                    $directory = 'work_orders/' . $workOrder->id . '/civil_attach';
                    
                    // إنشاء المجلد إذا لم يكن موجوداً
                    if (!\Storage::disk('public')->exists($directory)) {
                        \Storage::disk('public')->makeDirectory($directory, 0755, true);
                    }
                    
                    $filePath = $file->storeAs($directory, $filename, 'public');
                    
                    // حفظ بيانات الملف في قاعدة البيانات
                    $savedFile = \App\Models\WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_name' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'civil_attach' // تقصير الاسم
                    ]);
                    
                    $savedAttachments[] = [
                        'id' => $savedFile->id,
                        'filename' => $savedFile->filename,
                        'original_filename' => $savedFile->original_filename,
                        'file_path' => $savedFile->file_path,
                        'file_type' => $savedFile->file_type,
                        'file_size' => $savedFile->file_size,
                        'url' => asset('storage/' . $savedFile->file_path),
                        'icon' => $this->getFileIcon($savedFile->original_filename),
                        'size_formatted' => $this->formatFileSize($savedFile->file_size)
                    ];
                    
                    Log::info('Attachment saved successfully', [
                        'filename' => $filename,
                        'path' => $filePath,
                        'size' => $file->getSize()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ ' . count($savedAttachments) . ' مرفق بنجاح',
                'attachments' => $savedAttachments
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving civil works attachments', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'work_order_id' => $workOrder->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ المرفقات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديد أيقونة الملف بناءً على نوعه
     */
    private function getFileIcon($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        switch($ext) {
            case 'pdf': return 'pdf';
            case 'doc':
            case 'docx': return 'word';
            case 'xls':
            case 'xlsx': return 'excel';
            case 'ppt':
            case 'pptx': return 'powerpoint';
            case 'txt': return 'text';
            case 'zip':
            case 'rar': return 'archive';
            default: return 'file';
        }
    }

    /**
     * تنسيق حجم الملف
     */
    /**
     * حذف ملف مرفق
     */
    public function deleteFile(WorkOrderFile $file)
    {
        try {
            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // حذف السجل من قاعدة البيانات
            $file->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملف بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الملف'
            ], 500);
        }
    }

    private function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * حذف مرفق من الأعمال المدنية
     */
    public function deleteAttachment($attachmentId)
    {
        try {
            $attachment = \App\Models\WorkOrderFile::findOrFail($attachmentId);
            
            // التحقق من أن المرفق ينتمي لفئة الأعمال المدنية
            if ($attachment->file_category !== 'civil_attach') {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا المرفق لا ينتمي للأعمال المدنية'
                ], 403);
            }
            
            // حذف الملف من التخزين
            if (\Storage::disk('public')->exists($attachment->file_path)) {
                \Storage::disk('public')->delete($attachment->file_path);
            }
            
            // حذف السجل من قاعدة البيانات
            $attachment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المرفق بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting attachment', [
                'error' => $e->getMessage(),
                'attachment_id' => $attachmentId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المرفق'
            ], 500);
        }
    }

    public function saveExcavationDetails(Request $request, WorkOrder $workOrder)
    {
        try {
            $validatedData = $request->validate([
                'excavation_type' => 'required|string',
                'length' => 'required|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'depth' => 'nullable|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'is_open_excavation' => 'required|boolean',
                'soil_type' => 'required|string'
            ]);

            $excavationDetail = $workOrder->excavationDetails()->create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ تفاصيل الحفرية بنجاح',
                'data' => $excavationDetail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ تفاصيل الحفرية',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTodayExcavations(WorkOrder $workOrder)
    {
        try {
            \Log::info('Getting today excavations for work order: ' . $workOrder->id);
            
            // Get today's date in the correct timezone
            $today = now()->setTimezone(config('app.timezone', 'UTC'))->format('Y-m-d');
            
            \Log::info('Fetching excavations for date: ' . $today);
            
            // First try to get data from daily_civil_works_data
            $dailyData = $workOrder->daily_civil_works_data ?? [];
            
            if (!empty($dailyData) && is_array($dailyData)) {
                \Log::info('Found data in daily_civil_works_data');
                // Filter today's entries
                $todayEntries = array_filter($dailyData, function($entry) use ($today) {
                    return isset($entry['date']) && substr($entry['date'], 0, 10) === $today;
                });
                
                return response()->json([
                    'success' => true,
                    'data' => array_values($todayEntries)
                ]);
            }
            
            // Fallback to excavation_details table
            \Log::info('Falling back to excavation_details table');
            
            // Check if excavation_details table exists and has data
            if (!\Schema::hasTable('excavation_details')) {
                \Log::warning('excavation_details table does not exist');
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            
            $excavationDetails = $workOrder->excavationDetails()
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($detail) {
                    return [
                        'work_type' => $detail->excavation_type ?? 'غير محدد',
                        'cable_type' => $detail->title ?? 'غير محدد',
                        'length' => $detail->length ?? 0,
                        'width' => $detail->width ?? 0,
                        'depth' => $detail->depth ?? 0,
                        'volume' => ($detail->width && $detail->depth) ? ($detail->length * $detail->width * $detail->depth) : null,
                        'price' => $detail->price ?? 0,
                        'total' => $detail->total ?? 0,
                        'date' => $detail->created_at->format('Y-m-d'),
                        'time' => $detail->created_at->format('H:i:s'),
                        'created_at' => $detail->created_at->toISOString()
                    ];
                });
            
            \Log::info('Found ' . $excavationDetails->count() . ' excavation details');
            
            return response()->json([
                'success' => true,
                'data' => $excavationDetails
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting today excavations: ' . $e->getMessage(), [
                'work_order_id' => $workOrder->id ?? 'unknown',
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            // Return empty data instead of error to prevent frontend crashes
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'لا توجد بيانات حفريات لليوم'
            ]);
        }
    }

    public function uploadPostExecutionFile(Request $request, WorkOrder $workOrder)
    {
        try {
            
            $fileTypes = [
                'quantities_statement_file' => 'بيان كميات التنفيذ',
                'final_materials_file' => 'كميات المواد النهائية',
                'final_measurement_file' => 'ورقة القياس النهائية',
                'soil_tests_file' => 'اختبارات التربة',
                'site_drawing_file' => 'الرسم الهندسي للموقع',
                'modified_estimate_file' => 'تعديل المقايسة',
                'completion_certificate_file' => 'شهادة الانجاز',
                'form_200_file' => 'نموذج 200',
                'form_190_file' => 'نموذج 190',
                'pre_operation_tests_file' => 'اختبارات ما قبل التشغيل 211',
                'first_payment_extract_file' => 'مستخلص دفعة أولى',
                'second_payment_extract_file' => 'مستخلص دفعة ثانية',
                'total_extract_file' => 'مستخلص كلي',
            ];
            
            $uploadedFiles = [];
            
            foreach ($fileTypes as $fieldName => $description) {
                if ($request->hasFile($fieldName)) {
                    $files = $request->file($fieldName);
                    
                    // التأكد من أن الملفات في array
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    
                    // التحقق من صحة الملفات
                    $request->validate([
                        $fieldName . '.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480'
                    ]);
                    
                    foreach ($files as $file) {
                        $originalName = $file->getClientOriginalName();
                        $filename = time() . '_' . uniqid() . '_' . $fieldName . '.' . $file->getClientOriginalExtension();
                        $path = 'work_orders/' . $workOrder->id . '/post_execution_files';
                        
                        if (!Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->makeDirectory($path);
                        }
                        
                        $filePath = $file->storeAs($path, $filename, 'public');
                        
                        WorkOrderFile::create([
                            'work_order_id' => $workOrder->id,
                            'filename' => $filename,
                            'original_filename' => $originalName,
                            'file_name' => $originalName,
                            'file_path' => $filePath,
                            'file_type' => $file->getClientMimeType(),
                            'mime_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'file_category' => 'post_execution_files',
                            'attachment_type' => $fieldName,
                        ]);
                    }
                    
                    $uploadedFiles[] = $description . ' (' . count($files) . ' ملف)';
                }
            }
            
            if (count($uploadedFiles) > 0) {
                // التحقق من نوع الطلب
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تم رفع الملفات بنجاح: ' . implode(', ', $uploadedFiles)
                    ]);
                }
                return redirect()->back()->with('success', 'تم رفع الملفات بنجاح: ' . implode(', ', $uploadedFiles));
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'لم يتم اختيار أي ملفات للرفع'
                    ]);
                }
                return redirect()->back()->with('error', 'لم يتم اختيار أي ملفات للرفع');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error uploading post execution file: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function getFiles(Request $request, WorkOrder $workOrder)
    {
        try {
            $field = $request->get('field');
            
            if (!$field) {
                return response()->json([
                    'success' => false,
                    'message' => 'Field parameter is required'
                ]);
            }
            
            $files = $workOrder->files()
                ->where('file_category', 'post_execution_files')
                ->where('attachment_type', $field)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $filesData = $files->map(function ($file) {
                return [
                    'id' => $file->id,
                    'original_filename' => $file->original_filename,
                    'file_size_formatted' => $this->formatFileSize($file->file_size),
                    'created_at' => $file->created_at->format('Y-m-d H:i'),
                    'url' => Storage::url($file->file_path)
                ];
            });
            
            return response()->json([
                'success' => true,
                'files' => $filesData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الملفات'
            ]);
        }
    }

    /**
     * عرض نموذج تعديل المسح
     */
    public function editSurvey(Survey $survey)
    {
        try {
            $survey->load(['files', 'workOrder']);
            
            // تحضير بيانات الصور باستخدام FileHelper
            $images = $survey->files->map(function($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->original_filename,
                    'url' => \App\Helpers\FileHelper::getImageUrl($file->file_path)
                ];
            });
            
            return response()->json([
                'success' => true,
                'survey' => array_merge($survey->toArray(), ['images' => $images])
            ]);
        } catch (\Exception $e) {
            \Log::error('خطأ في تحميل بيانات المسح: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل بيانات المسح'
            ], 500);
        }
    }

    /**
     * تحديث بيانات المسح
     */
    public function updateSurvey(Request $request, Survey $survey)
    {
        try {
            \Log::info('بيانات تحديث المسح:', $request->all());

            $validated = $request->validate([
                'start_coordinates' => 'required|string|max:500',
                'end_coordinates' => 'required|string|max:500',
                'has_obstacles' => 'required|boolean',
                'obstacles_notes' => 'required_if:has_obstacles,1|nullable|string|max:1000',
                'site_images.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:30720', // 30MB max
                'deleted_files' => 'nullable|array',
                'deleted_files.*' => 'exists:work_order_files,id'
            ]);

            \Log::info('البيانات المتحقق منها:', $validated);

            DB::beginTransaction();

            try {
                // تحديث بيانات المسح
                $survey->update([
                    'start_coordinates' => $validated['start_coordinates'],
                    'end_coordinates' => $validated['end_coordinates'],
                    'has_obstacles' => (bool)$validated['has_obstacles'],
                    'obstacles_notes' => $validated['obstacles_notes'] ?? null
                ]);

                \Log::info('تم تحديث المسح:', $survey->toArray());

                // حذف الملفات المحددة
                if ($request->has('deleted_files')) {
                    $deletedFiles = $request->input('deleted_files');
                    if (is_array($deletedFiles)) {
                        foreach ($deletedFiles as $fileId) {
                            $file = WorkOrderFile::where('id', (int)$fileId)
                                ->where('survey_id', $survey->id)
                                ->first();

                            if ($file) {
                                try {
                                    // حذف الملف الفعلي
                                    if (Storage::disk('public')->exists($file->file_path)) {
                                        Storage::disk('public')->delete($file->file_path);
                                    }
                                    // حذف السجل
                                    $file->delete();
                                    \Log::info('تم حذف الملف بنجاح:', ['file_id' => $fileId]);
                                } catch (\Exception $e) {
                                    \Log::error('خطأ في حذف الملف:', [
                                        'file_id' => $fileId,
                                        'error' => $e->getMessage()
                                    ]);
                                    throw $e;
                                }
                            }
                        }
                    }
                }

                // معالجة الصور الجديدة
                if ($request->hasFile('site_images')) {
                    \Log::info('عدد الصور المرفقة:', ['count' => count($request->file('site_images'))]);
                    
                    $path = 'work_orders/' . $survey->work_order_id . '/surveys/' . $survey->id;
                    Storage::disk('public')->makeDirectory($path, 0755, true);

                    foreach ($request->file('site_images') as $image) {
                        try {
                            // التحقق من صحة الملف
                            if (!$image->isValid()) {
                                throw new \Exception('الملف غير صالح: ' . $image->getErrorMessage());
                            }

                            // التحقق من نوع الملف
                            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
                            if (!in_array($image->getMimeType(), $allowedTypes)) {
                                throw new \Exception('نوع الملف غير مدعوم: ' . $image->getMimeType());
                            }

                            // التحقق من حجم الملف
                            if ($image->getSize() > 30 * 1024 * 1024) { // 30MB
                                throw new \Exception('حجم الملف يتجاوز الحد المسموح به (30 ميجابايت)');
                            }

                            // إنشاء اسم فريد للملف
                            $filename = uniqid('survey_') . '_' . time() . '.' . $image->getClientOriginalExtension();
                            
                            // حفظ الصورة
                            $filePath = $image->storeAs($path, $filename, 'public');
                            
                            if (!$filePath) {
                                throw new \Exception('فشل في حفظ الملف');
                            }

                            \Log::info('تم حفظ الصورة:', ['path' => $filePath]);
                            
                            // إنشاء سجل الملف
                            $file = new WorkOrderFile([
                                'work_order_id' => $survey->work_order_id,
                                'survey_id' => $survey->id,
                                'filename' => $filename,
                                'original_filename' => $image->getClientOriginalName(),
                                'file_path' => $filePath,
                                'file_type' => $image->getMimeType(),
                                'file_size' => $image->getSize(),
                                'file_category' => 'survey_images',
                                'file_name' => $image->getClientOriginalName(), // إضافة حقل file_name
                                'mime_type' => $image->getMimeType() // إضافة حقل mime_type
                            ]);
                            
                            if (!$file->save()) {
                                throw new \Exception('فشل في حفظ بيانات الملف في قاعدة البيانات');
                            }
                            
                            \Log::info('تم إنشاء سجل الملف:', $file->toArray());
                        } catch (\Exception $e) {
                            \Log::error('خطأ في معالجة الصورة: ' . $e->getMessage());
                            // حذف الملف إذا تم حفظه
                            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                                Storage::disk('public')->delete($filePath);
                            }
                            throw $e;
                        }
                    }
                }

                DB::commit();

                // تحميل المسح مع الملفات المحدثة
                $survey->load('files');
                
                // تحضير بيانات الصور للرد
                $images = $survey->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->original_filename,
                        'url' => \App\Helpers\FileHelper::getImageUrl($file->file_path)
                    ];
                });

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث المسح بنجاح',
                    'survey' => array_merge($survey->toArray(), ['images' => $images])
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                
                // حذف الملفات الجديدة في حالة الفشل
                if (isset($path)) {
                    $newFiles = Storage::disk('public')->files($path);
                    foreach ($newFiles as $file) {
                        if (strpos($file, uniqid('survey_')) !== false) {
                            Storage::disk('public')->delete($file);
                        }
                    }
                }
                
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث المسح: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $errorMessage = 'حدث خطأ أثناء تحديث المسح';
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage = 'خطأ في التحقق من البيانات: ' . implode(', ', $e->errors());
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage = 'خطأ في قاعدة البيانات';
            } else {
                $errorMessage = $e->getMessage();
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف ملف مسح
     */
    public function deleteSurveyFile(WorkOrderFile $file)
    {
        try {
            \Log::info('جاري حذف ملف المسح:', ['file_id' => $file->id]);

            // التحقق من وجود الملف
            if (!$file || !$file->survey_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود أو غير مرتبط بمسح'
                ], 404);
            }

            // حذف الملف الفعلي من التخزين
            if (\Storage::disk('public')->exists($file->file_path)) {
                \Storage::disk('public')->delete($file->file_path);
            }

            // حذف السجل من قاعدة البيانات
            $file->delete();

            \Log::info('تم حذف ملف المسح بنجاح');

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملف بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ في حذف ملف المسح: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الملف'
            ], 500);
        }
    }

    public function destroySurvey(WorkOrder $workOrder, Survey $survey)
    {
        try {
            DB::beginTransaction();

            // التحقق من أن المسح ينتمي لأمر العمل المحدد
            if ($survey->work_order_id !== $workOrder->id) {
                return back()->with('error', 'المسح غير موجود في أمر العمل المحدد');
            }

            // حذف الملفات المرتبطة بالمسح
            foreach ($survey->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
                $file->delete();
            }

            // حذف المسح
            $survey->delete();

            DB::commit();
            return back()->with('success', 'تم حذف المسح بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('خطأ في حذف المسح: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف المسح');
        }
    }

    /**
     * حفظ ملخص الأعمال المدنية اليومي
     */
    public function saveDailyCivilWorks(Request $request)
    {
        try {
            Log::info('Save daily civil works request received', [
                'work_order_id' => $request->work_order_id,
                'data_length' => strlen($request->daily_data ?? ''),
                'request_data' => $request->all()
            ]);

            // التحقق من صحة البيانات
            $validated = $request->validate([
                'work_order_id' => 'required|integer|exists:work_orders,id',
                'daily_data' => 'required|string',
                '_token' => 'required'
            ]);

            // العثور على أمر العمل
            $workOrder = WorkOrder::findOrFail($validated['work_order_id']);

            // تحويل البيانات من JSON
            $dailyData = json_decode($validated['daily_data'], true);
            
            if (!is_array($dailyData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات المرسلة غير صالحة'
                ], 400);
            }

            Log::info('Parsed daily data', ['data' => $dailyData]);

            // بدء معاملة قاعدة البيانات
            DB::beginTransaction();

            // حفظ البيانات في جدول excavation_details
            // أولاً نحتاج للعثور على رخصة مرتبطة بأمر العمل أو إنشاء واحدة
            $license = $workOrder->license;
            if (!$license || !$license->exists) {
                // إنشاء رخصة افتراضية لأمر العمل
                $license = \App\Models\License::create([
                    'license_number' => 'AUTO-' . $workOrder->id . '-' . time(),
                    'user_id' => auth()->id(),
                    'license_type' => 'civil_works',
                    'status' => 'active',
                    'issue_date' => now()->toDateString(),
                    'description' => 'رخصة تلقائية للأعمال المدنية'
                ]);
                
                // ربط الرخصة بأمر العمل
                $workOrder->update(['license_id' => $license->id]);
                
                Log::info('Created automatic license for work order', [
                    'work_order_id' => $workOrder->id,
                    'license_id' => $license->id,
                    'license_number' => $license->license_number
                ]);
            }
            
            foreach ($dailyData as $item) {
                \App\Models\ExcavationDetail::create([
                    'license_id' => $license->id,
                    'work_order_id' => $workOrder->id,
                    'title' => $item['work_type'] ?? 'غير محدد',
                    'location' => $workOrder->address ?? 'غير محدد',
                    'contractor' => $workOrder->subscriber_name ?? 'غير محدد',
                    'duration' => 1, // يوم واحد افتراضي
                    'length' => $item['length'] ?? 0,
                    'width' => 0, // قيمة افتراضية
                    'depth' => 0, // قيمة افتراضية
                    'status' => 'active',
                    'status_text' => 'تم الحفظ من الملخص اليومي',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // تحديث أمر العمل بالبيانات الجديدة
            $workOrder->update([
                'daily_civil_works_data' => json_encode($dailyData),
                'daily_civil_works_last_update' => now()
            ]);

            DB::commit();

            Log::info('Daily civil works data saved successfully', [
                'work_order_id' => $workOrder->id,
                'items_count' => count($dailyData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملخص اليومي بنجاح',
                'data' => [
                    'items_count' => count($dailyData),
                    'total_amount' => array_sum(array_column($dailyData, 'total')),
                    'last_update' => now()->toISOString()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in saveDailyCivilWorks', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving daily civil works', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديد نوع القسم من نوع العمل
     */
    private function getSectionTypeFromWorkType($workType)
    {
        if (str_contains($workType, 'حفريات')) {
            return 'حفريات أساسية';
        } elseif (str_contains($workType, 'دقيقة')) {
            return 'حفريات دقيقة';
        } elseif (str_contains($workType, 'تمديدات')) {
            return 'تمديدات كهربائية';
        } elseif (str_contains($workType, 'مفتوح')) {
            return 'حفر مفتوح';
        }
        return 'غير محدد';
    }

    /**
     * تحديد نوع الكابل من نوع العمل
     */
    private function getCableTypeFromWorkType($workType)
    {
        if (str_contains($workType, 'منخفض')) {
            return 'كابل منخفض';
        } elseif (str_contains($workType, 'متوسط')) {
            return 'كابل متوسط';
        } elseif (str_contains($workType, 'مفتوح')) {
            return 'أكبر من 4 كابلات';
        }
        return 'غير محدد';
    }

    /**
     * Get daily civil works data for a work order
     */
    public function getDailyCivilWorks($id)
    {
        try {
            $workOrder = WorkOrder::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'daily_data' => $workOrder->daily_civil_works_data
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة من صور الأعمال المدنية
     */
    public function deleteCivilWorksImage(WorkOrder $workOrder, $image)
    {
        try {
            Log::info('Deleting civil works image', [
                'work_order_id' => $workOrder->id,
                'image_id' => $image,
                'user_id' => auth()->id()
            ]);

            $file = \App\Models\WorkOrderFile::where('id', $image)
                ->where('work_order_id', $workOrder->id)
                ->where('file_category', 'civil_exec') // تغيير التصنيف للصور
                ->firstOrFail();

            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // حذف السجل من قاعدة البيانات
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting civil works image', [
                'error' => $e->getMessage(),
                'work_order_id' => $workOrder->id,
                'image_id' => $image
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع صور التنفيذ
     */
    public function uploadExecutionImages(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('Starting image upload for work order: ' . $workOrder->id);
            
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
            ]);

            if (!$request->hasFile('images')) {
                \Log::warning('No images found in request');
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم اختيار أي صور للرفع'
                ], 400);
            }

            \Log::info('Found ' . count($request->file('images')) . ' images to upload');

            $uploadedImages = [];
            foreach ($request->file('images') as $index => $image) {
                \Log::info("Processing image {$index}: " . $image->getClientOriginalName());
                
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $originalName = $image->getClientOriginalName();
                
                // حفظ الصورة
                $path = $image->storeAs('work_orders/' . $workOrder->id . '/execution', $filename, 'public');
                \Log::info("Image stored at path: {$path}");
                
                // التحقق من وجود الملف
                if (!Storage::disk('public')->exists($path)) {
                    \Log::error("File was not saved properly: {$path}");
                    continue;
                }
                
                // إنشاء سجل للصورة
                $file = $workOrder->files()->create([
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_path' => $path,
                    'file_type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'file_category' => 'execution_images',
                ]);

                \Log::info("Database record created with ID: {$file->id}");

                $uploadedImages[] = [
                    'id' => $file->id,
                    'name' => $originalName,
                    'path' => Storage::url($path),
                    'size' => $this->formatFileSize($file->file_size),
                    'created_at' => $file->created_at->format('Y-m-d H:i:s')
                ];
            }

            \Log::info('Successfully uploaded ' . count($uploadedImages) . ' images');

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصور بنجاح',
                'images' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            \Log::error('Error uploading execution images: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة تنفيذ
     */
    public function exportExcel(Request $request)
    {
        // التحقق من وجود المشروع
        $project = $request->get('project');
        
        if (!$project || !in_array($project, ['riyadh', 'madinah'])) {
            return redirect()->route('project.selection');
        }

        // إنشاء ملف إكسل جديد
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // تعيين اتجاه الصفحة من اليمين لليسار
        $sheet->setRightToLeft(true);

        // تعيين عناوين الأعمدة
        $headers = [
            'رقم أمر العمل',
            'نوع العمل',
            'وصف العمل',
            'تاريخ الاعتماد',
            'اسم المشترك',
            'الحي',
            'البلدية',
            'المكتب',
            'رقم المحطة',
            'اسم الاستشاري',
            'قيمة أمر العمل (شامل)',
            'قيمة أمر العمل (بدون)',
            'حالة التنفيذ',
            'المدينة'
        ];

        // كتابة العناوين
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index); // تحويل الرقم إلى حرف (A, B, C, ...)
            $sheet->setCellValue($column . '1', $header);
            
            // تنسيق خلايا العناوين
            $sheet->getStyle($column . '1')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ]);
            
            // تعيين عرض العمود تلقائياً
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // جلب البيانات
        $query = WorkOrder::query();
        
        // فلترة حسب المشروع
        if ($project === 'riyadh') {
            $query->where('city', 'الرياض');
        } elseif ($project === 'madinah') {
            $query->where('city', 'المدينة المنورة');
        }

        // تطبيق نفس الفلاتر الموجودة في الصفحة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('work_type', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('subscriber_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('district', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('execution_status')) {
            $query->where('execution_status', $request->execution_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('approval_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('approval_date', '<=', $request->date_to);
        }

        $workOrders = $query->get();

        // كتابة البيانات
        $row = 2;
        foreach ($workOrders as $workOrder) {
            $sheet->setCellValue('A' . $row, $workOrder->order_number);
            $sheet->setCellValue('B' . $row, $workOrder->work_type);
            $sheet->setCellValue('C' . $row, $workOrder->work_description);
            $sheet->setCellValue('D' . $row, $workOrder->approval_date ? $workOrder->approval_date->format('Y-m-d') : '');
            $sheet->setCellValue('E' . $row, $workOrder->subscriber_name);
            $sheet->setCellValue('F' . $row, $workOrder->district);
            $sheet->setCellValue('G' . $row, $workOrder->municipality);
            $sheet->setCellValue('H' . $row, $workOrder->office);
            $sheet->setCellValue('I' . $row, $workOrder->station_number);
            $sheet->setCellValue('J' . $row, $workOrder->consultant_name);
            $sheet->setCellValue('K' . $row, $workOrder->order_value_with_consultant);
            $sheet->setCellValue('L' . $row, $workOrder->order_value_without_consultant);
            $sheet->setCellValue('M' . $row, $this->getExecutionStatusText($workOrder->execution_status));
            $sheet->setCellValue('N' . $row, $workOrder->city);

            // تنسيق خلايا البيانات
            $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            $row++;
        }

        // إنشاء ملف الإكسل
        $writer = new Xlsx($spreadsheet);
        $fileName = 'work-orders-' . $project . '-' . date('Y-m-d') . '.xlsx';
        
        // حفظ الملف وتحميله
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    private function getExecutionStatusText($status)
    {
        $statuses = [
            '1' => 'جاري العمل بالموقع',
            '2' => 'تم التنفيذ بالموقع وجاري تسليم 155',
            '3' => 'تم تسليم 155 جاري اصدار شهادة الانجاز',
            '4' => 'اعداد مستخلص الدفعة الجزئية الاولي وجاري الصرف',
            '5' => 'تم صرف مستخلص الدفعة الجزئية الاولي',
            '6' => 'اعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف',
            '7' => 'تم الصرف وتم الانتهاء',
            '8' => 'تم اصدار شهادة الانجاز',
            '9' => 'تم الالغاء او تحويل امر العمل',
            '10' => 'تم اعداد المستخلص الكلي وجاري الصرف'
        ];

        return $statuses[$status] ?? 'غير محدد';
    }

    public function deleteExecutionImage(WorkOrder $workOrder, $imageId)
    {
        try {
            $file = $workOrder->files()->where('id', $imageId)->where('file_category', 'execution_images')->firstOrFail();
            
            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // حذف السجل
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting execution image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة'
            ], 500);
        }
    }

    /**
     * عرض صفحة السلامة لأمر العمل
     */
    public function safety(WorkOrder $workOrder)
    {
        // تحديد المشروع بناءً على المدينة
        $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
        
        // جلب مخالفات السلامة
        $safetyViolations = $workOrder->safetyViolations()->orderBy('violation_date', 'desc')->get();
        
        // جلب تواريخ التفتيش مباشرة
        $inspectionDates = WorkOrderInspectionDate::where('work_order_id', $workOrder->id)
            ->orderBy('inspection_date', 'desc')
            ->get();

        // جلب سجل السلامة
        $safetyHistory = \DB::table('work_order_safety_history')
            ->where('work_order_id', $workOrder->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.work_orders.safety', compact('workOrder', 'project', 'safetyViolations', 'inspectionDates', 'safetyHistory'));
    }

    /**
     * عرض صفحة برنامج العمل اليومي
     */
    public function dailyProgram(Request $request)
    {
        $project = $request->get('project', 'riyadh');
        $selectedDate = $request->get('selected_date', today()->toDateString());
        
        // التحقق من صلاحيات المستخدم
        $user = auth()->user();
        $requiredPermission = $project === 'riyadh' ? 'riyadh_daily_work_program' : 'madinah_daily_work_program';
        
        // التحقق من أن المستخدم لديه الصلاحية المطلوبة
        if (!$user->is_admin && !$user->hasPermission($requiredPermission)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى برنامج العمل اليومي لهذا المشروع');
        }
        
        // جلب برامج العمل للتاريخ المحدد
        $programs = \App\Models\DailyWorkProgram::with('workOrder')
            ->whereDate('program_date', $selectedDate)
            ->whereHas('workOrder', function($q) use ($project) {
                if ($project === 'riyadh') {
                    $q->where('city', 'الرياض');
                } else {
                    $q->where('city', 'المدينة المنورة');
                }
            })
            ->orderBy('start_time')
            ->get();
        
        // جلب أوامر العمل المتاحة للإضافة (السماح بالتكرار)
        $availableWorkOrders = WorkOrder::where('city', $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة')
            ->with(['surveys' => function($query) {
                $query->select('id', 'work_order_id', 'start_coordinates', 'end_coordinates')
                      ->latest()
                      ->limit(1);
            }])
            ->select('id', 'order_number', 'work_type', 'district', 'consultant_name', 'address')
            ->orderBy('order_number', 'desc')
            ->get();
        
        return view('admin.work_orders.daily-program', compact('programs', 'availableWorkOrders', 'project', 'selectedDate'));
    }

    /**
     * إضافة أمر عمل لبرنامج اليوم
     */
    public function storeDailyProgram(Request $request)
    {
        try {
            $validated = $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'program_date' => 'nullable|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
                'work_type' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'google_coordinates' => 'nullable|string|max:500',
                'consultant_name' => 'nullable|string|max:255',
                'site_engineer' => 'nullable|string|max:255',
                'supervisor' => 'nullable|string|max:255',
                'issuer' => 'nullable|string|max:255',
                'receiver' => 'nullable|string|max:255',
                'safety_officer' => 'nullable|string|max:255',
                'quality_monitor' => 'nullable|string|max:255',
                'work_description' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // التحقق من صلاحيات المستخدم بناءً على مدينة أمر العمل
            $workOrder = WorkOrder::findOrFail($validated['work_order_id']);
            $user = auth()->user();
            $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
            $requiredPermission = $project . '_daily_work_program';
            
            if (!$user->is_admin && !$user->hasPermission($requiredPermission)) {
                return redirect()->back()->with('error', 'ليس لديك صلاحية لإضافة برنامج عمل يومي لهذا المشروع');
            }

            // إذا لم يتم تحديد التاريخ، استخدم اليوم
            if (!isset($validated['program_date'])) {
                $validated['program_date'] = today();
            }
            
            \App\Models\DailyWorkProgram::create($validated);

            return redirect()->back()->with('success', 'تم إضافة أمر العمل لبرنامج اليوم بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error storing daily program: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة أمر العمل');
        }
    }

    /**
     * تحديث بيانات برنامج العمل اليومي
     */
    public function updateDailyProgram(Request $request, \App\Models\DailyWorkProgram $dailyWorkProgram)
    {
        try {
            // التحقق من صلاحيات المستخدم بناءً على مدينة أمر العمل
            $workOrder = $dailyWorkProgram->workOrder;
            $user = auth()->user();
            $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
            $requiredPermission = $project . '_daily_work_program';
            
            if (!$user->is_admin && !$user->hasPermission($requiredPermission)) {
                return redirect()->back()->with('error', 'ليس لديك صلاحية لتعديل برنامج العمل اليومي لهذا المشروع');
            }

            $validated = $request->validate([
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
                'work_type' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'google_coordinates' => 'nullable|string|max:500',
                'consultant_name' => 'nullable|string|max:255',
                'site_engineer' => 'nullable|string|max:255',
                'supervisor' => 'nullable|string|max:255',
                'issuer' => 'nullable|string|max:255',
                'receiver' => 'nullable|string|max:255',
                'safety_officer' => 'nullable|string|max:255',
                'quality_monitor' => 'nullable|string|max:255',
                'work_description' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $dailyWorkProgram->update($validated);

            return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error updating daily program: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث البيانات');
        }
    }

    /**
     * حذف سجل من برنامج العمل اليومي
     */
    public function destroyDailyProgram(\App\Models\DailyWorkProgram $dailyWorkProgram)
    {
        try {
            // التحقق من صلاحيات المستخدم بناءً على مدينة أمر العمل
            $workOrder = $dailyWorkProgram->workOrder;
            $user = auth()->user();
            $project = $workOrder->city === 'المدينة المنورة' ? 'madinah' : 'riyadh';
            $requiredPermission = $project . '_daily_work_program';
            
            if (!$user->is_admin && !$user->hasPermission($requiredPermission)) {
                return redirect()->back()->with('error', 'ليس لديك صلاحية لحذف برنامج العمل اليومي لهذا المشروع');
            }

            $dailyWorkProgram->delete();
            return redirect()->back()->with('success', 'تم حذف السجل بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting daily program: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف السجل');
        }
    }

    /**
     * جلب المستخدمين المتاحين لإرسال الإشعار
     */
    public function getDailyProgramUsers(Request $request)
    {
        try {
            $project = $request->input('project', 'riyadh');
            
            // جلب جميع المستخدمين
            $allUsers = User::select('id', 'name', 'email', 'permissions')->get();
            
            \Log::info('Total users found: ' . $allUsers->count());
            
            // فلترة المستخدمين حسب الصلاحيات - جلب كل من لديه أي صلاحية للمشروع
            $users = $allUsers->filter(function($user) use ($project) {
                $permissions = $user->permissions;
                
                // التأكد من أن الـ permissions عبارة عن array
                if (is_string($permissions)) {
                    $permissions = json_decode($permissions, true) ?? [];
                }
                
                if (!is_array($permissions)) {
                    return false;
                }
                
                // فحص أي صلاحية تحتوي على اسم المشروع
                foreach ($permissions as $permission) {
                    if (is_string($permission)) {
                        // للرياض: أي صلاحية تحتوي على 'riyadh'
                        if ($project === 'riyadh' && stripos($permission, 'riyadh') !== false) {
                            return true;
                        }
                        // للمدينة: أي صلاحية تحتوي على 'madinah'
                        if ($project === 'madinah' && stripos($permission, 'madinah') !== false) {
                            return true;
                        }
                    }
                }
                
                return false;
            })->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
            })->values();
            
            \Log::info('Filtered users count: ' . $users->count() . ' for project: ' . $project);
            
            // إذا لم يتم العثور على مستخدمين بصلاحيات معينة، إرجاع جميع المستخدمين
            if ($users->isEmpty()) {
                $users = $allUsers->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ];
                })->values();
                
                \Log::info('No users found with specific permissions, returning all users: ' . $users->count());
            }
            
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching users: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب المستخدمين: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إرسال برنامج العمل اليومي كإشعار للمستخدمين المحددين
     */
    public function sendDailyProgramNotification(Request $request)
    {
        try {
            $selectedDate = $request->input('selected_date', today()->toDateString());
            $project = $request->input('project', 'riyadh');
            $userIds = $request->input('user_ids', []);
            
            if (empty($userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تحديد أي مستخدم'
                ]);
            }
            
            // جلب برامج العمل للتاريخ المحدد
            $programs = \App\Models\DailyWorkProgram::with('workOrder')
                ->whereDate('program_date', $selectedDate)
                ->whereHas('workOrder', function($q) use ($project) {
                    if ($project === 'riyadh') {
                        $q->where('city', 'الرياض');
                    } else {
                        $q->where('city', 'المدينة المنورة');
                    }
                })
                ->orderBy('start_time')
                ->get();
            
            if ($programs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد أوامر عمل في برنامج هذا اليوم'
                ]);
            }
            
            // تحديد المدينة
            $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            // إنشاء محتوى الإشعار
            $dateFormatted = \Carbon\Carbon::parse($selectedDate)->locale('ar')->translatedFormat('l j F Y');
            $message = "📋 برنامج العمل اليومي - {$cityName}\n";
            $message .= "📅 التاريخ: {$dateFormatted}\n";
            $message .= "📊 عدد أوامر العمل: " . $programs->count() . "\n\n";
            
            $message .= "أوامر العمل المقررة:\n";
            foreach ($programs as $index => $program) {
                $message .= ($index + 1) . ". ";
                $message .= "أمر عمل: " . $program->workOrder->order_number;
                
                if ($program->start_time && $program->end_time) {
                    $startTime = \Carbon\Carbon::parse($program->start_time)->format('H:i');
                    $endTime = \Carbon\Carbon::parse($program->end_time)->format('H:i');
                    $message .= " ({$startTime} - {$endTime})";
                }
                
                if ($program->work_type) {
                    $message .= " - " . $program->work_type;
                }
                
                if ($program->location) {
                    $message .= " - " . $program->location;
                }
                
                $message .= "\n";
            }
            
            // إرسال الإشعار للمستخدمين المحددين
            $notificationCount = 0;
            $dailyProgramUrl = route('admin.work-orders.daily-program', [
                'project' => $project,
                'selected_date' => $selectedDate
            ]);
            
            foreach ($userIds as $userId) {
                \App\Models\Notification::create([
                    'user_id' => $userId,
                    'message' => $message,
                    'type' => 'daily_program',
                    'link' => $dailyProgramUrl,
                    'is_read' => false,
                ]);
                $notificationCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "تم إرسال برنامج العمل اليومي بنجاح إلى {$notificationCount} مستخدم"
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error sending daily program notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الإشعار: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث بيانات السلامة لأمر العمل
     */
    public function updateSafety(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('Safety update request received', [
                'workOrderId' => $workOrder->id,
                'hasPermitsImages' => $request->hasFile('permits_images'),
                'hasTeamImages' => $request->hasFile('team_images'),
                'hasEquipmentImages' => $request->hasFile('equipment_images'),
                'hasGeneralImages' => $request->hasFile('general_images'),
                'hasTbtImages' => $request->hasFile('tbt_images'),
                'allFiles' => array_keys($request->allFiles())
            ]);
            
            $validated = $request->validate([
                'safety_notes' => 'nullable|string',
                'safety_status' => 'nullable|string',
                'safety_officer' => 'nullable|string|max:255',
                'inspection_date' => 'nullable|date',
                'non_compliance_reasons' => 'required_if:safety_status,غير مطابق|nullable|string',
                'permits_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
                'team_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
                'equipment_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
                'general_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
                'tbt_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
                'non_compliance_attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:102400',
                // الملفات الجديدة (PDF + صور)
                'tbt_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:102400',
                'permits_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:102400',
                'team_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:102400',
                'equipment_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:102400',
                'general_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:102400',
            ]);

            // حفظ البيانات الحالية في سجل السلامة إذا تم تغيير أي منها
            $shouldSaveHistory = false;
            $historyData = [];

            if ($validated['safety_officer'] !== $workOrder->safety_officer) {
                $shouldSaveHistory = true;
                $historyData['safety_officer'] = $validated['safety_officer'];
            }

            if ($validated['safety_status'] !== $workOrder->safety_status) {
                $shouldSaveHistory = true;
                $historyData['safety_status'] = $validated['safety_status'];
            }

            if ($validated['safety_notes'] !== $workOrder->safety_notes) {
                $shouldSaveHistory = true;
                $historyData['safety_notes'] = $validated['safety_notes'];
            }

            if ($validated['non_compliance_reasons'] !== $workOrder->non_compliance_reasons) {
                $shouldSaveHistory = true;
                $historyData['non_compliance_reasons'] = $validated['non_compliance_reasons'];
            }

            // دائماً احفظ تاريخ التفتيش في السجل إذا تم إدخاله
            if (!empty($validated['inspection_date'])) {
                $shouldSaveHistory = true;
                $historyData['inspection_date'] = $validated['inspection_date'];
            }

            // حفظ السجل التاريخي إذا كان هناك تغيير
            if ($shouldSaveHistory) {
                \DB::table('work_order_safety_history')->insert([
                    'work_order_id' => $workOrder->id,
                    'safety_officer' => $validated['safety_officer'],
                    'safety_status' => $validated['safety_status'],
                    'safety_notes' => $validated['safety_notes'],
                    'non_compliance_reasons' => $validated['non_compliance_reasons'],
                    'inspection_date' => $validated['inspection_date'],
                    'updated_by' => auth()->user()->name ?? 'غير محدد',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // تحديث البيانات الأساسية (بدون تاريخ التفتيش)
            $workOrder->update([
                'safety_notes' => $validated['safety_notes'],
                'safety_status' => $validated['safety_status'],
                'safety_officer' => $validated['safety_officer'],
                // إزالة تحديث inspection_date لمنع الكتابة فوق القديم
                'non_compliance_reasons' => $validated['non_compliance_reasons'],
            ]);

            // حفظ تاريخ التفتيش الجديد في الجدول المخصص إذا تم إدخال تاريخ
            if (!empty($validated['inspection_date'])) {
                \Log::info('Saving inspection date', [
                    'work_order_id' => $workOrder->id,
                    'inspection_date' => $validated['inspection_date'],
                    'inspector_name' => $validated['safety_officer'] ?? 'غير محدد'
                ]);
                
                // حفظ التاريخ دائماً (حتى لو كان مكرراً) لحفظ سجل كامل
                try {
                    \DB::table('work_order_inspection_dates')->insert([
                        'work_order_id' => $workOrder->id,
                        'inspection_date' => $validated['inspection_date'],
                        'inspector_name' => $validated['safety_officer'] ?? 'غير محدد',
                        'notes' => $validated['safety_notes'],
                        'status' => 'completed',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    \Log::info('Inspection date saved successfully');
                } catch (\Exception $e) {
                    \Log::error('Failed to save inspection date: ' . $e->getMessage());
                }
            } else {
                \Log::info('No inspection date provided in request');
            }

            // رفع صور التصاريح
            if ($request->hasFile('permits_images')) {
                $this->uploadSafetyImages($request->file('permits_images'), $workOrder, 'permits');
            }

            // رفع صور فريق العمل
            if ($request->hasFile('team_images')) {
                $this->uploadSafetyImages($request->file('team_images'), $workOrder, 'team');
            }

            // رفع صور المعدات
            if ($request->hasFile('equipment_images')) {
                $this->uploadSafetyImages($request->file('equipment_images'), $workOrder, 'equipment');
            }

            // رفع الصور العامة
            if ($request->hasFile('general_images')) {
                $this->uploadSafetyImages($request->file('general_images'), $workOrder, 'general');
            }

            // رفع صور اجتماع ما قبل بدء العمل TBT
            if ($request->hasFile('tbt_images')) {
                $this->uploadSafetyImages($request->file('tbt_images'), $workOrder, 'tbt');
            }

            // رفع مرفقات عدم المطابقة
            if ($request->hasFile('non_compliance_attachments')) {
                $this->uploadNonComplianceAttachments($request->file('non_compliance_attachments'), $workOrder);
            }

            // رفع الملفات الجديدة (PDF + صور)
            if ($request->hasFile('tbt_files')) {
                $this->uploadSafetyFiles($request->file('tbt_files'), $workOrder, 'tbt_files');
            }

            if ($request->hasFile('permits_files')) {
                $this->uploadSafetyFiles($request->file('permits_files'), $workOrder, 'permits_files');
            }

            if ($request->hasFile('team_files')) {
                $this->uploadSafetyFiles($request->file('team_files'), $workOrder, 'team_files');
            }

            if ($request->hasFile('equipment_files')) {
                $this->uploadSafetyFiles($request->file('equipment_files'), $workOrder, 'equipment_files');
            }

            if ($request->hasFile('general_files')) {
                $this->uploadSafetyFiles($request->file('general_files'), $workOrder, 'general_files');
            }

            return redirect()->route('admin.work-orders.safety', $workOrder)
                ->with('success', 'تم تحديث بيانات السلامة بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error updating safety data: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات السلامة');
        }
    }

    /**
     * رفع صور السلامة
     */
    private function uploadSafetyImages($files, WorkOrder $workOrder, $category)
    {
        try {
            $uploadedImages = [];
            $fieldName = 'safety_' . $category . '_images';
            
            \Log::info('Starting uploadSafetyImages', [
                'category' => $category,
                'fieldName' => $fieldName,
                'workOrderId' => $workOrder->id,
                'filesCount' => count($files)
            ]);
            
            // الحصول على الصور الموجودة
            $existingImages = $workOrder->$fieldName ?? [];
            \Log::info('Existing images', ['count' => count($existingImages)]);

            foreach ($files as $index => $file) {
                \Log::info('Processing file', [
                    'index' => $index,
                    'originalName' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
                
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'work_orders/' . $workOrder->id . '/safety/' . $category;
                
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                $uploadedImages[] = $filePath;
                
                \Log::info('File uploaded successfully', [
                    'filename' => $filename,
                    'filePath' => $filePath
                ]);
            }

            // دمج الصور الجديدة مع الموجودة
            $allImages = array_merge($existingImages, $uploadedImages);
            
            \Log::info('Merging images', [
                'existingCount' => count($existingImages),
                'newCount' => count($uploadedImages),
                'totalCount' => count($allImages)
            ]);
            
            // تحديث قاعدة البيانات
            $updateResult = $workOrder->update([
                $fieldName => $allImages
            ]);
            
            \Log::info('Database update', [
                'fieldName' => $fieldName,
                'success' => $updateResult,
                'finalImageCount' => count($allImages)
            ]);
            
            // التأكد من الحفظ
            $workOrder->refresh();
            $savedImages = $workOrder->$fieldName ?? [];
            \Log::info('Verification after save', [
                'savedCount' => count($savedImages),
                'savedImages' => $savedImages
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in uploadSafetyImages', [
                'error' => $e->getMessage(),
                'category' => $category,
                'workOrderId' => $workOrder->id
            ]);
            throw $e;
        }
    }

    /**
     * رفع ملفات السلامة (PDF + صور)
     */
    private function uploadSafetyFiles($files, WorkOrder $workOrder, $category)
    {
        try {
            $uploadedFiles = [];
            $fieldName = 'safety_' . $category;
            
            \Log::info('Starting uploadSafetyFiles', [
                'category' => $category,
                'fieldName' => $fieldName,
                'workOrderId' => $workOrder->id,
                'filesCount' => count($files)
            ]);
            
            // الحصول على الملفات الموجودة
            $existingFiles = $workOrder->$fieldName ?? [];
            \Log::info('Existing files', ['count' => count($existingFiles)]);

            foreach ($files as $index => $file) {
                \Log::info('Processing file', [
                    'index' => $index,
                    'originalName' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mimeType' => $file->getMimeType()
                ]);
                
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'work_orders/' . $workOrder->id . '/safety/files/' . str_replace('_files', '', $category);
                
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                $uploadedFiles[] = $filePath;
                
                \Log::info('File uploaded successfully', [
                    'filename' => $filename,
                    'filePath' => $filePath
                ]);
            }

            // دمج الملفات الجديدة مع الموجودة
            $allFiles = array_merge($existingFiles, $uploadedFiles);
            
            \Log::info('Merging files', [
                'existingCount' => count($existingFiles),
                'newCount' => count($uploadedFiles),
                'totalCount' => count($allFiles)
            ]);
            
            // تحديث قاعدة البيانات
            $updateResult = $workOrder->update([
                $fieldName => $allFiles
            ]);
            
            \Log::info('Database update', [
                'fieldName' => $fieldName,
                'success' => $updateResult,
                'finalFileCount' => count($allFiles)
            ]);
            
            $workOrder->refresh();
            \Log::info('Work order refreshed', [
                'finalFileCount' => count($workOrder->$fieldName ?? [])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error uploading safety files: ' . $e->getMessage(), [
                'category' => $category,
                'workOrderId' => $workOrder->id
            ]);
            throw $e;
        }
    }

    /**
     * رفع مرفقات عدم المطابقة
     */
    private function uploadNonComplianceAttachments($files, WorkOrder $workOrder)
    {
        try {
            $uploadedAttachments = [];
            
            \Log::info('Starting uploadNonComplianceAttachments', [
                'workOrderId' => $workOrder->id,
                'filesCount' => count($files)
            ]);
            
            // الحصول على المرفقات الموجودة
            $existingAttachments = $workOrder->non_compliance_attachments ?? [];
            \Log::info('Existing attachments', ['count' => count($existingAttachments)]);

            foreach ($files as $index => $file) {
                \Log::info('Processing file', [
                    'index' => $index,
                    'originalName' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
                
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'work_orders/' . $workOrder->id . '/safety/non_compliance';
                
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }
                
                $filePath = $file->storeAs($path, $filename, 'public');
                $uploadedAttachments[] = $filePath;
                
                \Log::info('File uploaded successfully', [
                    'filename' => $filename,
                    'filePath' => $filePath
                ]);
            }

            // دمج المرفقات الجديدة مع الموجودة
            $allAttachments = array_merge($existingAttachments, $uploadedAttachments);
            
            \Log::info('Merging attachments', [
                'existingCount' => count($existingAttachments),
                'newCount' => count($uploadedAttachments),
                'totalCount' => count($allAttachments)
            ]);
            
            // تحديث قاعدة البيانات
            $updateResult = $workOrder->update([
                'non_compliance_attachments' => $allAttachments
            ]);
            
            \Log::info('Database update', [
                'success' => $updateResult,
                'finalAttachmentCount' => count($allAttachments)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in uploadNonComplianceAttachments', [
                'error' => $e->getMessage(),
                'workOrderId' => $workOrder->id
            ]);
            throw $e;
        }
    }

    /**
     * رفع ملفات السلامة
     */
    public function uploadSafety(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'safety_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
            ]);

            if ($request->hasFile('safety_files')) {
                foreach ($request->file('safety_files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/safety';
                    
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    
                    WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_name' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'safety_files'
                    ]);
                }
            }

            return redirect()->route('admin.work-orders.safety', $workOrder)
                ->with('success', 'تم رفع ملفات السلامة بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading safety files: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفع ملفات السلامة');
        }
    }

    /**
     * حذف صورة السلامة
     */
    public function deleteSafetyImage(WorkOrder $workOrder, $category, $index)
    {
        try {
            \Log::info('Delete safety image request received', [
                'workOrderId' => $workOrder->id,
                'category' => $category,
                'index' => $index
            ]);
            
            $fieldName = 'safety_' . $category . '_images';
            $images = $workOrder->$fieldName ?? [];

            if (!isset($images[$index])) {
                \Log::warning('Safety image not found', [
                    'fieldName' => $fieldName,
                    'index' => $index,
                    'imagesCount' => count($images)
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'الصورة غير موجودة'
                ], 404);
            }

            $imagePath = $images[$index];

            // حذف الصورة من التخزين
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // إزالة الصورة من المصفوفة
            unset($images[$index]);
            $images = array_values($images); // إعادة ترقيم المصفوفة

            // تحديث قاعدة البيانات
            $workOrder->update([
                $fieldName => $images
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting safety image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة'
            ], 500);
        }
    }

    /**
     * حذف ملف السلامة (PDF + صور)
     */
    public function deleteSafetyFile(WorkOrder $workOrder, $category, $index)
    {
        try {
            \Log::info('Delete safety file request received', [
                'workOrderId' => $workOrder->id,
                'category' => $category,
                'index' => $index
            ]);
            
            $fieldName = 'safety_' . $category;
            $files = $workOrder->$fieldName ?? [];

            if (!isset($files[$index])) {
                \Log::warning('Safety file not found', [
                    'fieldName' => $fieldName,
                    'index' => $index,
                    'filesCount' => count($files)
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود'
                ], 404);
            }

            $filePath = $files[$index];

            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                \Log::info('File deleted from storage', ['filePath' => $filePath]);
            } else {
                \Log::warning('File not found in storage', ['filePath' => $filePath]);
            }

            // إزالة الملف من المصفوفة
            unset($files[$index]);
            $files = array_values($files); // إعادة ترقيم المصفوفة

            // تحديث قاعدة البيانات
            $workOrder->update([
                $fieldName => $files
            ]);

            \Log::info('Safety file deleted successfully', [
                'remainingFiles' => count($files)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملف بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting safety file: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الملف'
            ], 500);
        }
    }

    /**
     * حذف مرفق عدم المطابقة
     */
    public function deleteNonComplianceAttachment(WorkOrder $workOrder, $index)
    {
        try {
            $attachments = $workOrder->non_compliance_attachments ?? [];

            if (!isset($attachments[$index])) {
                return response()->json([
                    'success' => false,
                    'message' => 'المرفق غير موجود'
                ], 404);
            }

            $attachmentPath = $attachments[$index];

            // حذف المرفق من التخزين
            if (Storage::disk('public')->exists($attachmentPath)) {
                Storage::disk('public')->delete($attachmentPath);
            }

            // إزالة المرفق من المصفوفة
            unset($attachments[$index]);
            $attachments = array_values($attachments); // إعادة ترقيم المصفوفة

            // تحديث قاعدة البيانات
            $workOrder->update([
                'non_compliance_attachments' => $attachments
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المرفق بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting non-compliance attachment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المرفق'
            ], 500);
        }
    }

    /**
     * إضافة مخالفة سلامة جديدة
     */
    public function addSafetyViolation(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'violation_amount' => 'required|numeric|min:0',
                'violator' => 'required|string|max:255',
                'violation_source' => 'required|in:internal,external',
                'violation_date' => 'required|date',
                'description' => 'required|string',
                'notes' => 'nullable|string',
                'violation_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
            ]);

            // رفع المرفقات إن وجدت
            $attachments = [];
            if ($request->hasFile('violation_attachments')) {
                foreach ($request->file('violation_attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/safety_violations';
                    
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    $attachments[] = $filePath;
                }
            }

            // إضافة المرفقات للبيانات المحققة
            $validated['attachments'] = $attachments;

            $workOrder->safetyViolations()->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المخالفة بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error adding safety violation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المخالفة'
            ], 500);
        }
    }

    /**
     * حذف مخالفة سلامة
     */
    public function deleteSafetyViolation($violationId)
    {
        try {
            $violation = \App\Models\SafetyViolation::findOrFail($violationId);
            
            // حذف المرفقات من التخزين
            if ($violation->attachments && is_array($violation->attachments)) {
                foreach ($violation->attachments as $attachment) {
                    if (Storage::disk('public')->exists($attachment)) {
                        Storage::disk('public')->delete($attachment);
                    }
                }
            }
            
            $violation->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المخالفة بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting safety violation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المخالفة'
            ], 500);
        }
    }

    /**
     * عرض صفحة إدارة الإيرادات
     */
    public function revenues(Request $request)
    {
        try {
            // التحقق من وجود المشروع
            $project = $request->get('project');

            // التحقق من الصلاحيات
            $user = auth()->user();
            $revenuesPermission = $project . '_manage_revenues';
            
            if (!$user->hasPermission($revenuesPermission) && !$user->isAdmin()) {
                return redirect()->back()->with('error', 'ليس لديك صلاحية للوصول إلى إدارة الإيرادات');
            }
            
            if (!$project || !in_array($project, ['riyadh', 'madinah'])) {
                return redirect()->route('project.selection');
            }

            // التحقق من الصلاحيات حسب المدينة
            $user = auth()->user();
            $requiredPermission = $project . '_manage_revenues';
            $viewPermission = $project . '_view_revenues';

            if (!$user->hasAnyPermission([$requiredPermission, $viewPermission]) && !$user->isAdmin()) {
                return redirect()->back()->with('error', 'ليس لديك صلاحية للوصول إلى إدارة الإيرادات');
            }
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            $projectName = $project === 'madinah' ? 'مشروع المدينة المنورة' : 'مشروع الرياض';
            
            // جلب الإيرادات المفلترة حسب المشروع
            $revenues = \App\Models\Revenue::where('project', $project)
                                          ->orderBy('created_at', 'desc')
                                          ->get();
            
            // حساب القيم الأساسية أولاً
            $totalNetExtractValue = $revenues->sum('net_extract_value') ?: 0;
            $totalPaymentValue = $revenues->where('extract_status', 'مدفوع')->sum('payment_value') ?: 0;
            $totalFirstPaymentTaxUnpaid = $revenues->where('extract_status', 'غير مدفوع')->sum('first_payment_tax') ?: 0;
            
            // إحصائيات سريعة شاملة
            $statistics = [
                'totalRevenues' => $revenues->count(),
                'totalExtractValue' => $revenues->sum('extract_value') ?: 0,
                'totalTaxValue' => $revenues->sum('tax_value') ?: 0,
                'totalPenalties' => $revenues->sum('penalties') ?: 0,
                'totalFirstPaymentTax' => $totalFirstPaymentTaxUnpaid,
                'totalNetExtractValue' => $totalNetExtractValue,
                // إجمالي المدفوعات = إجمالي قيمة الصرف للمستخلصات المدفوعة فقط
                'totalPaymentValue' => $totalPaymentValue,
                // المبلغ المتبقي عند العميل شامل الضريبة = إجمالي صافي قيمة المستخلصات - إجمالي المدفوعات
                'remainingAmount' => $totalNetExtractValue - $totalPaymentValue,
            ];
            
            \Log::info('Revenues page loaded', [
                'project' => $project,
                'city' => $city,
                'statistics' => $statistics
            ]);
            
            return view('admin.work_orders.revenues', compact('revenues', 'statistics', 'project', 'projectName'));
            
        } catch (\Exception $e) {
            \Log::error('Error loading revenues page: ' . $e->getMessage());
            
            // في حالة الخطأ، إرجاع مجموعة فارغة
            $revenues = collect();
            $statistics = [
                'totalRevenues' => 0,
                'totalExtractValue' => 0,
                'totalTaxValue' => 0,
                'totalPenalties' => 0,
                'totalFirstPaymentTax' => 0,
                'totalNetExtractValue' => 0,
                'totalPaymentValue' => 0,
                'remainingAmount' => 0,
            ];
            $project = $request->get('project', 'riyadh');
            $projectName = $project === 'madinah' ? 'مشروع المدينة المنورة' : 'مشروع الرياض';
            
            return view('admin.work_orders.revenues', compact('revenues', 'statistics', 'project', 'projectName'))
                ->with('error', 'حدث خطأ في تحميل البيانات: ' . $e->getMessage());
        }
    }

    /**
     * حفظ بيانات الإيرادات
     */
    public function saveRevenue(Request $request)
    {
        try {
            $data = $request->validate([
                'project' => 'nullable|string|in:riyadh,madinah',
                'city' => 'nullable|string|max:255',
                'client_name' => 'nullable|string|max:255',
                'project_area' => 'nullable|string|max:255',
                'contract_number' => 'nullable|string|max:255',
                'extract_number' => 'nullable|string|max:255',
                'office' => 'nullable|string|max:255',
                'extract_type' => 'nullable|string|max:255',
                'po_number' => 'nullable|string|max:255',
                'invoice_number' => 'nullable|string|max:255',
                'extract_value' => 'nullable|numeric',
                'tax_percentage' => 'nullable|string|max:255',
                'tax_value' => 'nullable|numeric',
                'penalties' => 'nullable|numeric',
                'first_payment_tax' => 'nullable|numeric',
                'net_extract_value' => 'nullable|numeric',
                'extract_date' => 'nullable|date',
                'year' => 'nullable|string|max:255',
                'payment_type' => 'nullable|string|max:255',
                'reference_number' => 'nullable|string|max:255',
                'payment_date' => 'nullable|date',
                'payment_value' => 'nullable|numeric',
                'extract_status' => 'nullable|string|max:255',
                'row_id' => 'nullable|string'
            ]);

            // إذا لم يتم إرسال المشروع، جلبه من الـ query parameter أو استخدام الافتراضي
            if (empty($data['project'])) {
                $data['project'] = $request->get('project', 'riyadh');
            }
            
            // تحديد المدينة بناءً على المشروع
            if (empty($data['city'])) {
                $data['city'] = $data['project'] === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            }

            // إزالة row_id من البيانات
            $rowId = $data['row_id'] ?? null;
            unset($data['row_id']);

            // البحث عن السجل الموجود أو إنشاء جديد
            if ($rowId && is_numeric($rowId)) {
                $revenue = \App\Models\Revenue::find($rowId);
                if ($revenue) {
                    $revenue->update($data);
                } else {
                    $revenue = \App\Models\Revenue::create($data);
                }
            } else {
                $revenue = \App\Models\Revenue::create($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ البيانات بنجاح',
                'revenue_id' => $revenue->id,
                'data' => $revenue
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ في حفظ الإيرادات: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف بيانات الإيرادات
     */
    public function deleteRevenue($id)
    {
        try {
            $revenue = \App\Models\Revenue::find($id);
            
            if (!$revenue) {
                return response()->json([
                    'success' => false,
                    'message' => 'السجل غير موجود'
                ], 404);
            }

            $revenue->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف السجل بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ في حذف الإيرادات: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف السجل'
            ], 500);
        }
    }

    public function uploadRevenueAttachment(\Illuminate\Http\Request $request)
    {
        try {
            $request->validate([
                'attachment' => 'required|file|max:10240', // Max 10MB
                'revenue_id' => 'nullable|exists:revenues,id',
                'row_id' => 'required'
            ]);

            $revenueId = $request->input('revenue_id');
            $rowId = $request->input('row_id');
            
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $rowId . '_' . $file->getClientOriginalName();
                
                // حفظ الملف في مجلد revenues_attachments
                $path = $file->storeAs('revenues_attachments', $fileName, 'public');
                
                // إذا كان هناك revenue_id، نحفظ المسار في قاعدة البيانات
                if ($revenueId) {
                    $revenue = \App\Models\Revenue::find($revenueId);
                    if ($revenue) {
                        $revenue->attachment_path = $path;
                        $revenue->save();
                    }
                }
                
                \Log::info('تم رفع مرفق إيرادات بنجاح: ' . $fileName);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع المرفق بنجاح',
                    'file_path' => $path,
                    'file_name' => $fileName
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'لم يتم اختيار ملف'
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المرسلة: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('خطأ في رفع مرفق الإيرادات: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع المرفق: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * استيراد الإيرادات من ملف Excel
     */
    public function importRevenues(Request $request)
    {
        try {
            Log::info('ImportRevenues method called', ['request_data' => $request->all()]);
            
            // التحقق من وجود المشروع
            $project = $request->get('project', 'riyadh');
            if (!in_array($project, ['riyadh', 'madinah'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'المشروع غير صحيح'
                ], 400);
            }
            
            // التحقق من وجود الملف
            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تحديد ملف للاستيراد'
                ], 400);
            }

            $file = $request->file('file');
            
            // التحقق من نوع الملف
            if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls', 'csv'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الملف غير مدعوم. الأنواع المدعومة هي: xlsx, xls, csv'
                ], 400);
            }

            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';

            // استخدام RevenuesImport للاستيراد مع تمرير المشروع والمدينة
            Log::info('Creating RevenuesImport instance', ['project' => $project, 'city' => $city]);
            $import = new RevenuesImport($project, $city);
            
            Log::info('Starting Excel import', ['file_name' => $file->getClientOriginalName()]);
            Excel::import($import, $file);
            
            Log::info('Excel import completed successfully');
            $errors = $import->getErrors();

            $importedCount = count($import->getImportedRevenues());
            $errorCount = count($errors);
            
            return response()->json([
                'success' => true,
                'message' => 'تم استيراد ' . $importedCount . ' سجل بنجاح لـ ' . ($project === 'madinah' ? 'مشروع المدينة المنورة' : 'مشروع الرياض') . ($errorCount > 0 ? ' مع ' . $errorCount . ' أخطاء' : ''),
                'imported_count' => $importedCount,
                'errors_count' => $errorCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing revenues: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد الإيرادات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض انتاجية التنفيذ
     */
    public function executionProductivity(Request $request)
    {
        // التحقق من وجود المشروع
        $project = $request->get('project');
        
        if (!$project || !in_array($project, ['riyadh', 'madinah'])) {
            return redirect()->route('project.selection');
        }
        
        // تحديد المدينة بناءً على المشروع
        $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
        $projectName = $project === 'madinah' ? 'مشروع المدينة المنورة' : 'مشروع الرياض';
        
        // بناء الاستعلام الأساسي - تخفيف الشروط للاختبار
        $query = WorkOrder::where('city', $city)
            ->where(function($q) {
                $q->where('execution_status', '>=', 2) // أوامر العمل المنفذة
                  ->orWhereNotNull('procedure_155_delivery_date') // أو التي لها تاريخ تسليم
                  ->orWhereNotNull('final_total_value'); // أو التي لها قيمة إجمالية
            });
        
        // ملاحظة: فلتر التاريخ تم نقله لمستوى بنود العمل (work_date) بدلاً من procedure_155_delivery_date
        
        // فلتر نوع العمل
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }
        
        // فلتر الحي
        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }
        
        // Debug: طباعة عدد النتائج
        $totalCount = $query->count();
        \Log::info("Execution Productivity Debug: Total work orders found: $totalCount for city: $city");
        
        // عرض سجل التنفيذ اليومي من جدول daily_work_executions (نفس البيانات اللي في صفحة execution)
        \Log::info("Execution Productivity: Searching for daily executions in city: $city");
        
        $dailyExecutions = \App\Models\DailyWorkExecution::whereHas('workOrder', function($q) use ($city, $request) {
            $q->where('city', $city);
            
            // فلتر رقم أمر العمل
            if ($request->filled('order_number')) {
                $q->where('order_number', 'like', '%' . $request->order_number . '%');
            }
        })
        // فلتر تاريخ التنفيذ - على مستوى التنفيذ اليومي
        ->when($request->filled('date_from'), function($query) use ($request) {
            $query->whereDate('work_date', '>=', $request->date_from);
        })
        ->when($request->filled('date_to'), function($query) use ($request) {
            $query->whereDate('work_date', '<=', $request->date_to);
        })
        ->when($request->filled('work_item_code'), function($query) use ($request) {
            $query->whereHas('workOrderItem.workItem', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->work_item_code . '%');
            });
        })
        ->when($request->filled('executed_by'), function($query) use ($request) {
            $query->whereHas('createdBy', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->executed_by . '%');
            });
        })
        ->with(['workOrder', 'workOrderItem.workItem', 'createdBy'])
        ->orderBy('work_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate($request->get('per_page', 20));
        
        // جلب ملاحظات التنفيذ اليومي لكل work_order_id في النتائج الحالية
        $workOrderIds = $dailyExecutions->pluck('work_order_id')->unique();
        $dailyNotes = \App\Models\DailyExecutionNote::whereIn('work_order_id', $workOrderIds)
            ->with('createdBy')
            ->get()
            ->groupBy('work_order_id');
        
        \Log::info("Execution Productivity: Found " . $dailyExecutions->count() . " daily executions");
        
        // إذا لم توجد بيانات في daily_work_executions، استخدم work_order_items كبديل
        if ($dailyExecutions->count() == 0) {
            \Log::info("No daily executions found, falling back to work_order_items");
            
            $workOrderItems = \App\Models\WorkOrderItem::whereHas('workOrder', function($q) use ($city, $request) {
                $q->where('city', $city)
                  ->where(function($subQ) {
                      $subQ->where('execution_status', '>=', 2)
                           ->orWhereNotNull('procedure_155_delivery_date')
                           ->orWhereNotNull('final_total_value');
                  });
                
                // فلتر رقم أمر العمل
                if ($request->filled('order_number')) {
                    $q->where('order_number', 'like', '%' . $request->order_number . '%');
                }
            })
            ->where('executed_quantity', '>', 0)
            ->with(['workOrder', 'workItem'])
            ->orderBy('updated_at', 'desc')
            ->paginate($request->get('per_page', 20));
            
            \Log::info("Found " . $workOrderItems->count() . " work order items as fallback");
            
            // استخدم work_order_items بدلاً من daily_executions
            $dailyExecutions = $workOrderItems;
            $usingFallbackData = true;
        }
        
        // حساب الإحصائيات بناءً على التنفيذ اليومي
        $totalWorkOrders = $query->count();
        $totalValue = $query->sum('final_total_value');
        $totalDailyExecutions = $dailyExecutions->total();
        $totalExecutedValue = $dailyExecutions->sum(function($execution) {
            // التحقق من نوع البيانات (daily_work_executions أو work_order_items)
            if (isset($execution->workOrderItem) && $execution->workOrderItem) {
                // من daily_work_executions
                return $execution->executed_quantity * ($execution->workOrderItem->unit_price ?? 0);
            } else {
                // من work_order_items مباشرة
                return $execution->executed_quantity * ($execution->unit_price ?? 0);
            }
        });
        
        // حساب عدد أوامر العمل الفريدة التي لها تنفيذ يومي
        $uniqueWorkOrdersWithExecution = $dailyExecutions->groupBy('work_order_id')->count();
        
        // لا نحتاج لقوائم الأنواع والأحياء بعد الآن
        
        // تعيين متغير افتراضي إذا لم يكن موجوداً
        $usingFallbackData = $usingFallbackData ?? false;
        
        return view('admin.work_orders.execution-productivity', compact(
            'dailyExecutions', 'project', 'projectName', 'city',
            'totalWorkOrders', 'totalValue', 'totalDailyExecutions', 'totalExecutedValue',
            'uniqueWorkOrdersWithExecution', 'usingFallbackData', 'dailyNotes'
        ));
    }
    
    /**
     * تحديث سجل التنفيذ اليومي
     */
    public function updateDailyExecution(Request $request, DailyWorkExecution $dailyExecution)
    {
        try {
            $request->validate([
                'executed_quantity' => 'required|numeric|min:0'
            ]);

            $oldQuantity = $dailyExecution->executed_quantity;
            $newQuantity = $request->executed_quantity;

            // تحديث سجل التنفيذ اليومي
            $dailyExecution->update([
                'executed_quantity' => $newQuantity,
                'updated_by' => auth()->id()
            ]);

            // تحديث إجمالي الكمية المنفذة في WorkOrderItem
            $workOrderItem = $dailyExecution->workOrderItem;
            if ($workOrderItem) {
                // حساب إجمالي الكمية المنفذة من جميع سجلات التنفيذ اليومي
                $totalExecuted = DailyWorkExecution::where('work_order_item_id', $workOrderItem->id)
                    ->sum('executed_quantity');
                
                $workOrderItem->update([
                    'executed_quantity' => $totalExecuted
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية المنفذة بنجاح',
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث سجل التنفيذ اليومي: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الكمية المنفذة'
            ], 500);
        }
    }

    /**
     * حذف سجل التنفيذ اليومي
     */
    public function deleteDailyExecution(DailyWorkExecution $dailyExecution)
    {
        try {
            $workOrderItem = $dailyExecution->workOrderItem;
            $deletedQuantity = $dailyExecution->executed_quantity;

            // حذف سجل التنفيذ اليومي
            $dailyExecution->delete();

            // تحديث إجمالي الكمية المنفذة في WorkOrderItem
            if ($workOrderItem) {
                // إعادة حساب إجمالي الكمية المنفذة من سجلات التنفيذ المتبقية
                $totalExecuted = DailyWorkExecution::where('work_order_item_id', $workOrderItem->id)
                    ->sum('executed_quantity');
                
                $workOrderItem->update([
                    'executed_quantity' => $totalExecuted
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حذف سجل التنفيذ بنجاح',
                'deleted_quantity' => $deletedQuantity
            ]);

        } catch (\Exception $e) {
            \Log::error('خطأ في حذف سجل التنفيذ اليومي: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف سجل التنفيذ'
            ], 500);
        }
    }

    /**
     * عرض صفحة إيرادات المشاريع الموحدة (مشرف النظام فقط)
     */
    public function allProjectsRevenues(Request $request)
    {
        try {
            // التحقق من صلاحيات مشرف النظام
            if (!auth()->user()->is_admin) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
            
            // جمع إحصائيات من جميع المصادر
            
            // Get date filters
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            // 1. إيرادات مشاريع الرياض والمدينة (من جدول revenues)
            $workOrdersRevenuesQuery = \App\Models\Revenue::query();
            
            // Apply date filters if provided
            if ($startDate) {
                $workOrdersRevenuesQuery->where('extract_date', '>=', $startDate);
            }
            if ($endDate) {
                $workOrdersRevenuesQuery->where('extract_date', '<=', $endDate);
            }
            
            $workOrdersRevenues = $workOrdersRevenuesQuery->get();
            
            // حساب المبلغ المتبقي للمستخلصات الغير مدفوعة فقط
            $riyadhRevenues = $workOrdersRevenues->where('project', 'riyadh');
            $riyadhUnpaid = $riyadhRevenues->where('extract_status', 'غير مدفوع');
            $riyadhPaid = $riyadhRevenues->where('extract_status', 'مدفوع');
            
            $madinahRevenues = $workOrdersRevenues->where('project', 'madinah');
            $madinahUnpaid = $madinahRevenues->where('extract_status', 'غير مدفوع');
            $madinahPaid = $madinahRevenues->where('extract_status', 'مدفوع');
            
            $workOrdersStats = [
                'riyadh' => [
                    'count' => $riyadhRevenues->count(),
                    'total_value' => $riyadhRevenues->sum('extract_value'),
                    'total_tax' => $riyadhRevenues->sum('tax_value'),
                    'total_penalties' => $riyadhRevenues->sum('penalties'),
                    'total_net_extract_value' => $riyadhRevenues->sum('net_extract_value'),
                    'total_payments' => $riyadhPaid->sum('payment_value'),
                    'first_payment_tax' => $riyadhUnpaid->sum('first_payment_tax'),
                    'unpaid_amount' => $riyadhRevenues->sum('net_extract_value') - $riyadhPaid->sum('payment_value'),
                ],
                'madinah' => [
                    'count' => $madinahRevenues->count(),
                    'total_value' => $madinahRevenues->sum('extract_value'),
                    'total_tax' => $madinahRevenues->sum('tax_value'),
                    'total_penalties' => $madinahRevenues->sum('penalties'),
                    'total_net_extract_value' => $madinahRevenues->sum('net_extract_value'),
                    'total_payments' => $madinahPaid->sum('payment_value'),
                    'first_payment_tax' => $madinahUnpaid->sum('first_payment_tax'),
                    'unpaid_amount' => $madinahRevenues->sum('net_extract_value') - $madinahPaid->sum('payment_value'),
                ]
            ];
            
            // 2. إيرادات مشاريع تسليم المفتاح (من جدول turnkey_revenues)
            // فقط السجلات المرتبطة بمشاريع (التي لها project_id)
            $turnkeyRevenuesQuery = \App\Models\TurnkeyRevenue::whereNotNull('project_id');
            
            // Apply date filters if provided (include null dates)
            if ($startDate && $endDate) {
                $turnkeyRevenuesQuery->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('extract_date', [$startDate, $endDate])
                      ->orWhereNull('extract_date');
                });
            } elseif ($startDate) {
                $turnkeyRevenuesQuery->where(function($q) use ($startDate) {
                    $q->where('extract_date', '>=', $startDate)
                      ->orWhereNull('extract_date');
                });
            } elseif ($endDate) {
                $turnkeyRevenuesQuery->where(function($q) use ($endDate) {
                    $q->where('extract_date', '<=', $endDate)
                      ->orWhereNull('extract_date');
                });
            }
            
            // جلب جميع الإيرادات (للإحصائيات الإجمالية - فقط المرتبطة بمشاريع)
            $turnkeyRevenues = $turnkeyRevenuesQuery->get();
            
            // جمع إحصائيات إجمالية لمشاريع تسليم المفتاح (من جميع السجلات)
            $turnkeyUnpaid = $turnkeyRevenues->where('payment_status', 'غير مدفوع');
            $turnkeyPaid = $turnkeyRevenues->where('payment_status', 'مدفوع');
            
            $turnkeyStats = [
                'count' => $turnkeyRevenues->count(),
                'total_value' => $turnkeyRevenues->sum('extract_value'),
                'total_tax' => $turnkeyRevenues->sum('tax_value'),
                'total_penalties' => $turnkeyRevenues->sum('penalties'),
                'total_net_value' => $turnkeyRevenues->sum('net_extract_value'),
                'total_payments' => $turnkeyPaid->sum('payment_value'),
                'first_payment_tax' => $turnkeyUnpaid->sum('first_payment_tax'),
                'unpaid_amount' => $turnkeyRevenues->sum('net_extract_value') - $turnkeyPaid->sum('payment_value'),
            ];
            
            // جمع المشاريع للإحصائيات التفصيلية
            $turnkeyProjects = \App\Models\Project::where('project_type', '!=', 'special')
                ->whereIn('project_type', ['OH33KV', 'UA33LW', 'SLS33KV', 'UG132KV'])
                ->get();
            
            $turnkeyProjectsStats = [];
            foreach ($turnkeyProjects as $project) {
                // البحث باستخدام project_id
                $projectRevenues = $turnkeyRevenues->where('project_id', $project->id);
                $projectUnpaid = $projectRevenues->where('payment_status', 'غير مدفوع');
                $projectPaid = $projectRevenues->where('payment_status', 'مدفوع');
                
                $turnkeyProjectsStats[] = [
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_type' => $project->project_type,
                    'contract_number' => $project->contract_number,
                    'count' => $projectRevenues->count(),
                    'total_value' => $projectRevenues->sum('extract_value'),
                    'total_tax' => $projectRevenues->sum('tax_value'),
                    'total_penalties' => $projectRevenues->sum('penalties'),
                    'total_net_value' => $projectRevenues->sum('net_extract_value'),
                    'total_payments' => $projectPaid->sum('payment_value'),
                    'first_payment_tax' => $projectUnpaid->sum('first_payment_tax'),
                    'unpaid_amount' => $projectRevenues->sum('net_extract_value') - $projectPaid->sum('payment_value'),
                ];
            }
            
            // 3. إيرادات المشاريع الخاصة (من جدول special_project_revenues)
            $specialRevenuesQuery = \App\Models\SpecialProjectRevenue::query();
            
            // Apply date filters if provided
            if ($startDate) {
                $specialRevenuesQuery->where('preparation_date', '>=', $startDate);
            }
            if ($endDate) {
                $specialRevenuesQuery->where('preparation_date', '<=', $endDate);
            }
            
            $specialRevenues = $specialRevenuesQuery->get();
            
            // جمع إحصائيات إجمالية للمشاريع الخاصة
            $specialUnpaid = $specialRevenues->where('payment_status', 'unpaid');
            $specialPaid = $specialRevenues->where('payment_status', 'paid');
            
            $specialStats = [
                'count' => $specialRevenues->count(),
                'total_value' => $specialRevenues->sum('total_value'),
                'total_tax' => $specialRevenues->sum('tax_value'),
                'total_penalties' => $specialRevenues->sum('penalties'),
                'total_net_value' => $specialRevenues->sum('net_value'),
                'total_payments' => $specialPaid->sum('payment_value'),
                'first_payment_tax' => $specialUnpaid->sum('advance_payment_tax'),
                'unpaid_amount' => $specialRevenues->sum('net_value') - $specialPaid->sum('payment_value'),
            ];
            
            // جمع إحصائيات لكل مشروع خاص على حدة
            $specialProjects = \App\Models\Project::where('project_type', 'special')->get();
            
            $specialProjectsStats = [];
            foreach ($specialProjects as $project) {
                $projectRevenues = $specialRevenues->where('project_id', $project->id);
                $projectUnpaid = $projectRevenues->where('payment_status', 'unpaid');
                $projectPaid = $projectRevenues->where('payment_status', 'paid');
                
                $specialProjectsStats[] = [
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'contract_number' => $project->contract_number,
                    'location' => $project->location,
                    'count' => $projectRevenues->count(),
                    'total_value' => $projectRevenues->sum('total_value'),
                    'total_tax' => $projectRevenues->sum('tax_value'),
                    'total_penalties' => $projectRevenues->sum('penalties'),
                    'total_net_value' => $projectRevenues->sum('net_value'),
                    'total_payments' => $projectPaid->sum('payment_value'),
                    'first_payment_tax' => $projectUnpaid->sum('advance_payment_tax'),
                    'unpaid_amount' => $projectRevenues->sum('net_value') - $projectPaid->sum('payment_value'),
                ];
            }
            
            // 4. إجمالي عام لكل المشاريع
            // حساب الإجمالي العام بطريقة صحيحة
            $totalCount = $workOrdersStats['riyadh']['count'] + $workOrdersStats['madinah']['count'] + 
                         $turnkeyStats['count'] + $specialStats['count'];
            
            $totalValue = $workOrdersStats['riyadh']['total_value'] + $workOrdersStats['madinah']['total_value'] + 
                         $turnkeyStats['total_value'] + $specialStats['total_value'];
            
            $totalTax = $workOrdersStats['riyadh']['total_tax'] + $workOrdersStats['madinah']['total_tax'] + 
                       $turnkeyStats['total_tax'] + $specialStats['total_tax'];
            
            $totalPenalties = $workOrdersStats['riyadh']['total_penalties'] + $workOrdersStats['madinah']['total_penalties'] + 
                             $turnkeyStats['total_penalties'] + $specialStats['total_penalties'];
            
            $totalNetExtractValue = $workOrdersStats['riyadh']['total_net_extract_value'] + 
                                   $workOrdersStats['madinah']['total_net_extract_value'] + 
                                   $turnkeyStats['total_net_value'] + 
                                   $specialStats['total_net_value'];
            
            $totalPayments = $workOrdersStats['riyadh']['total_payments'] + $workOrdersStats['madinah']['total_payments'] + 
                            $turnkeyStats['total_payments'] + $specialStats['total_payments'];
            
            $totalFirstPaymentTax = $workOrdersStats['riyadh']['first_payment_tax'] + 
                                   $workOrdersStats['madinah']['first_payment_tax'] + 
                                   $turnkeyStats['first_payment_tax'] + 
                                   $specialStats['first_payment_tax'];
            
            // المبلغ المتبقي = إجمالي صافي المستخلصات - إجمالي المدفوعات
            $totalUnpaidAmount = $totalNetExtractValue - $totalPayments;
            
            $grandTotal = [
                'count' => $totalCount,
                'total_value' => $totalValue,
                'total_tax' => $totalTax,
                'total_penalties' => $totalPenalties,
                'total_net_extract_value' => $totalNetExtractValue,
                'total_payments' => $totalPayments,
                'first_payment_tax' => $totalFirstPaymentTax,
                'unpaid_amount' => $totalUnpaidAmount,
            ];
            
            return view('admin.work_orders.all-projects-revenues', compact(
                'workOrdersStats',
                'turnkeyStats',
                'turnkeyProjectsStats',
                'specialStats',
                'specialProjectsStats',
                'grandTotal'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in allProjectsRevenues: ' . $e->getMessage());
            
            return back()
                ->with('error', 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage());
        }
    }

    /**
     * Export all projects revenues to Excel
     */
    public function exportAllProjectsRevenues(Request $request)
    {
        try {
            // التحقق من صلاحيات مشرف النظام
            if (!auth()->user()->is_admin) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
            
            // Get date filters
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            // جمع البيانات (نفس المنطق من allProjectsRevenues)
            
            // 1. إيرادات مشاريع الرياض والمدينة
            $workOrdersRevenuesQuery = \App\Models\Revenue::query();
            
            if ($startDate) {
                $workOrdersRevenuesQuery->where('extract_date', '>=', $startDate);
            }
            if ($endDate) {
                $workOrdersRevenuesQuery->where('extract_date', '<=', $endDate);
            }
            
            $workOrdersRevenues = $workOrdersRevenuesQuery->get();
            
            $riyadhRevenues = $workOrdersRevenues->where('project', 'riyadh');
            $riyadhUnpaid = $riyadhRevenues->where('extract_status', 'غير مدفوع');
            $riyadhPaid = $riyadhRevenues->where('extract_status', 'مدفوع');
            
            $madinahRevenues = $workOrdersRevenues->where('project', 'madinah');
            $madinahUnpaid = $madinahRevenues->where('extract_status', 'غير مدفوع');
            $madinahPaid = $madinahRevenues->where('extract_status', 'مدفوع');
            
            // 2. إيرادات مشاريع تسليم المفتاح
            $turnkeyRevenuesQuery = \App\Models\TurnkeyRevenue::whereNotNull('project_id');
            
            if ($startDate && $endDate) {
                $turnkeyRevenuesQuery->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('extract_date', [$startDate, $endDate])
                      ->orWhereNull('extract_date');
                });
            } elseif ($startDate) {
                $turnkeyRevenuesQuery->where(function($q) use ($startDate) {
                    $q->where('extract_date', '>=', $startDate)
                      ->orWhereNull('extract_date');
                });
            } elseif ($endDate) {
                $turnkeyRevenuesQuery->where(function($q) use ($endDate) {
                    $q->where('extract_date', '<=', $endDate)
                      ->orWhereNull('extract_date');
                });
            }
            
            $turnkeyRevenues = $turnkeyRevenuesQuery->get();
            
            // 3. إيرادات المشاريع الخاصة
            $specialRevenuesQuery = \App\Models\SpecialProjectRevenue::query();
            
            if ($startDate) {
                $specialRevenuesQuery->where('preparation_date', '>=', $startDate);
            }
            if ($endDate) {
                $specialRevenuesQuery->where('preparation_date', '<=', $endDate);
            }
            
            $specialRevenues = $specialRevenuesQuery->get();
            
            // تجهيز البيانات للتصدير
            $exportData = [];
            
            // مشروع الرياض
            $exportData[] = [
                'project_type' => 'عقد موحد - الرياض',
                'project_name' => 'مشروع الرياض',
                'contract_number' => 'عقد موحد',
                'location' => 'الرياض',
                'count' => $riyadhRevenues->count(),
                'total_value' => $riyadhRevenues->sum('extract_value'),
                'total_tax' => $riyadhRevenues->sum('tax_value'),
                'total_penalties' => $riyadhRevenues->sum('penalties'),
                'total_net_value' => $riyadhRevenues->sum('net_extract_value'),
                'total_payments' => $riyadhPaid->sum('net_extract_value'),
                'first_payment_tax' => $riyadhRevenues->sum('first_payment_tax'),
                'unpaid_amount' => $riyadhUnpaid->sum(function($revenue) {
                    return ($revenue->extract_value ?: 0) + ($revenue->tax_value ?: 0) - ($revenue->penalties ?: 0);
                }),
            ];
            
            // مشروع المدينة
            $exportData[] = [
                'project_type' => 'عقد موحد - المدينة',
                'project_name' => 'مشروع المدينة المنورة',
                'contract_number' => 'عقد موحد',
                'location' => 'المدينة المنورة',
                'count' => $madinahRevenues->count(),
                'total_value' => $madinahRevenues->sum('extract_value'),
                'total_tax' => $madinahRevenues->sum('tax_value'),
                'total_penalties' => $madinahRevenues->sum('penalties'),
                'total_net_value' => $madinahRevenues->sum('net_extract_value'),
                'total_payments' => $madinahPaid->sum('net_extract_value'),
                'first_payment_tax' => $madinahRevenues->sum('first_payment_tax'),
                'unpaid_amount' => $madinahUnpaid->sum(function($revenue) {
                    return ($revenue->extract_value ?: 0) + ($revenue->tax_value ?: 0) - ($revenue->penalties ?: 0);
                }),
            ];
            
            // مشاريع تسليم المفتاح
            $turnkeyProjects = \App\Models\Project::where('project_type', '!=', 'special')
                ->whereIn('project_type', ['OH33KV', 'UA33LW', 'SLS33KV', 'UG132KV'])
                ->get();
            
            foreach ($turnkeyProjects as $project) {
                $projectRevenues = $turnkeyRevenues->where('project_id', $project->id);
                $projectUnpaid = $projectRevenues->where('payment_status', 'غير مدفوع');
                $projectPaid = $projectRevenues->where('payment_status', 'مدفوع');
                
                // حساب صافي قيمة المستخلصات: extract_value + tax_value - penalties
                $totalNetValue = $projectRevenues->sum(function($revenue) {
                    return ($revenue->extract_value ?? 0) + ($revenue->tax_value ?? 0) - ($revenue->penalties ?? 0);
                });
                
                $totalPayments = $projectPaid->sum(function($revenue) {
                    return ($revenue->extract_value ?? 0) + ($revenue->tax_value ?? 0) - ($revenue->penalties ?? 0);
                });
                
                $unpaidAmount = $projectUnpaid->sum(function($revenue) {
                    return ($revenue->extract_value ?? 0) + ($revenue->tax_value ?? 0) - ($revenue->penalties ?? 0);
                });
                
                $exportData[] = [
                    'project_type' => 'تسليم مفتاح - ' . $project->project_type,
                    'project_name' => $project->name,
                    'contract_number' => $project->contract_number,
                    'location' => $project->location ?? '-',
                    'count' => $projectRevenues->count(),
                    'total_value' => $projectRevenues->sum('extract_value'),
                    'total_tax' => $projectRevenues->sum('tax_value'),
                    'total_penalties' => $projectRevenues->sum('penalties'),
                    'total_net_value' => $totalNetValue,
                    'total_payments' => $totalPayments,
                    'first_payment_tax' => $projectRevenues->sum('first_payment_tax'),
                    'unpaid_amount' => $unpaidAmount,
                ];
            }
            
            // المشاريع الخاصة
            $specialProjects = \App\Models\Project::where('project_type', 'special')->get();
            
            foreach ($specialProjects as $project) {
                $projectRevenues = $specialRevenues->where('project_id', $project->id);
                $projectUnpaid = $projectRevenues->where('payment_status', 'unpaid');
                $projectPaid = $projectRevenues->where('payment_status', 'paid');
                
                $exportData[] = [
                    'project_type' => 'مشروع خاص',
                    'project_name' => $project->name,
                    'contract_number' => $project->contract_number,
                    'location' => $project->location,
                    'count' => $projectRevenues->count(),
                    'total_value' => $projectRevenues->sum('total_value'),
                    'total_tax' => $projectRevenues->sum('tax_value'),
                    'total_penalties' => $projectRevenues->sum('penalties'),
                    'total_net_value' => $projectRevenues->sum('net_value'),
                    'total_payments' => $projectPaid->sum('net_value'),
                    'first_payment_tax' => $projectRevenues->sum('advance_payment_tax'),
                    'unpaid_amount' => $projectUnpaid->sum('net_value'),
                ];
            }
            
            // تصدير البيانات
            $fileName = 'اجمالي_ايرادات_المشاريع_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return \Excel::download(
                new \App\Exports\AllProjectsRevenuesExport($exportData),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error exporting all projects revenues: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * Get all notifications for a specific work order
     */
    public function getWorkOrderNotifications(WorkOrder $workOrder)
    {
        try {
            // Get all notifications related to this work order
            // Assuming notifications table has a work_order_id or link field
            $notifications = \App\Models\Notification::where(function($query) use ($workOrder) {
                $query->where('work_order_id', $workOrder->id)
                      ->orWhere('link', 'like', '%/work-orders/' . $workOrder->id . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title ?? 'إشعار',
                    'message' => $notification->message,
                    'is_read' => $notification->is_read ?? 0,
                    'created_at' => $notification->created_at,
                    'sender_name' => $notification->user ? $notification->user->name : 'النظام',
                ];
            });

            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching work order notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإشعارات'
            ], 500);
        }
    }

    /**
     * Update work order notes
     */
    public function updateNotes(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string'
            ]);
            
            // تحديث الملاحظات مباشرة بأبسط طريقة
            $workOrder->notes = $request->notes;
            $saved = $workOrder->save();
            
            if (!$saved) {
                throw new \Exception('فشل حفظ الملاحظات في قاعدة البيانات');
            }
            
            \Log::info('Work order notes updated successfully', [
                'work_order_id' => $workOrder->id,
                'notes_length' => mb_strlen($request->notes ?? ''),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملاحظات بنجاح',
                'updated_by' => auth()->user()->name ?? 'Unknown',
                'updated_at' => now()->format('Y-m-d H:i')
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error updating notes', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة'
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error updating work order notes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الملاحظات: ' . $e->getMessage()
            ], 500);
        }
    }
} 