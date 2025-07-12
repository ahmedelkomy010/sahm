<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderFile;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Models\License;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    // عرض قائمة أوامر العمل
    public function index()
    {
        $workOrders = WorkOrder::latest()->paginate(10);
        return view('admin.work_orders.index', compact('workOrders'));
    }

    // عرض نموذج إنشاء أمر عمل جديد
    public function create()
    {
        $workItems = \App\Models\WorkItem::orderBy('code')->get();
        $referenceMaterials = \App\Models\ReferenceMaterial::orderBy('code')->get();
        return view('admin.work_orders.create', compact('workItems', 'referenceMaterials'));
    }

    // حفظ أمر عمل جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:work_orders',
            'work_type' => 'required|string|max:999',
            'work_description' => 'required|string',
            'approval_date' => 'required|date',
            'subscriber_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'order_value_with_consultant' => 'required|numeric|min:0',
            'order_value_without_consultant' => 'required|numeric|min:0',
            'execution_status' => 'required|in:1,2,3,4,5,6,7',
            'municipality' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'station_number' => 'nullable|string|max:255',
            'consultant_name' => 'nullable|string|max:255',
            // بنود العمل
            'work_items' => 'nullable|array',
            'work_items.*.work_item_id' => 'required_with:work_items|exists:work_items,id',
            'work_items.*.planned_quantity' => 'required_with:work_items|numeric|min:0',
            'work_items.*.unit_price' => 'nullable|numeric|min:0',
            'work_items.*.notes' => 'nullable|string',
            // المواد
            'materials' => 'nullable|array',
            'materials.*.material_code' => 'required_with:materials|string|max:255',
            'materials.*.material_description' => 'required_with:materials|string|max:255',
            'materials.*.planned_quantity' => 'required_with:materials|numeric|min:0',
            'materials.*.unit' => 'required_with:materials|string|max:50',
            'materials.*.unit_price' => 'nullable|numeric|min:0',
            'materials.*.notes' => 'nullable|string',
            // المرفقات
            'files.license_estimate' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'files.daily_measurement' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'files.backup_1' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
        ]);
        
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
                        'notes' => $workItem['notes'] ?? null,
                    ]);
                }
            }
        }
        
        // حفظ المواد
        if ($request->has('materials') && is_array($request->materials)) {
            foreach ($request->materials as $material) {
                if (!empty($material['material_code']) && !empty($material['material_description'])) {
                    // إنشاء أو العثور على المادة في جدول materials
                    $materialRecord = Material::firstOrCreate(
                        ['code' => $material['material_code']],
                        [
                            'name' => $material['material_description'],
                            'description' => $material['material_description'],
                            'unit' => $material['unit'] ?? 'عدد',
                            'unit_price' => $material['unit_price'] ?? 0,
                            'is_active' => true,
                        ]
                    );
                    
                    // ربط المادة بأمر العمل في جدول work_order_materials
                    $workOrder->workOrderMaterials()->create([
                        'material_id' => $materialRecord->id,
                        'quantity' => $material['planned_quantity'] ?? 0,
                        'unit_price' => $material['unit_price'] ?? 0,
                        'notes' => $material['notes'] ?? null,
                    ]);
                }
            }
        }
        
        // حفظ المرفقات الأساسية
        if ($request->hasFile('files')) {
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
        }
        
        return redirect()->route('admin.work-orders.index')->with('success', 'تم إنشاء أمر العمل بنجاح');
    }

    // عرض أمر عمل محدد
    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['files', 'basicAttachments', 'invoiceAttachments', 'licenses.violations']);
        
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
        
        return view('admin.work_orders.show', compact('workOrder', 'licensesTotals'));
    }

    /**
     * عرض صفحة الرخص لأمر العمل
     */
    public function license(WorkOrder $workOrder)
    {
        $workOrder->load(['licenses.violations', 'licenses.attachments']);
        return view('admin.work_orders.license', compact('workOrder'));
    }

    /**
     * عرض صفحة المسح لأمر العمل
     */
    public function survey(WorkOrder $workOrder)
    {
        $workOrder->load(['surveys.files']);
        return view('admin.work_orders.survey', compact('workOrder'));
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
                'site_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:30720', // 30MB max
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
                            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
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
        $workOrder->load('files');
        return view('admin.work_orders.edit', compact('workOrder'));
    }

    // تحديث أمر عمل
    public function update(Request $request, WorkOrder $workOrder)
    {
        // دعم التحديث الجزئي لحقول رقم أمر الشراء وصحيفة الإدخال ورقم المستخلص والحقول الجديدة
        if ($request->input('_section') === 'extract_number_group') {
            $workOrder->update($request->only([
                'purchase_order_number',
                'entry_sheet',
                'extract_number',
                'actual_execution_value_consultant',
                'actual_execution_value_without_consultant',
                'first_partial_payment_without_tax',
                'second_partial_payment_with_tax',
                'tax_value',
                'procedure_155_delivery_date',
                'final_total_value',
                'execution_status',
                'pre_operation_tests',
            ]));
            return redirect()->route('admin.work-orders.actions-execution', $workOrder->id)->with('success', 'تم تحديث البيانات بنجاح');
        }
        
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:work_orders,order_number,' . $workOrder->id,
            'work_type' => 'required|string|max:999',
            'work_description' => 'required|string',
            'approval_date' => 'required|date',
            'subscriber_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'order_value_with_consultant' => 'required|numeric|min:0',
            'order_value_without_consultant' => 'required|numeric|min:0',
            'execution_status' => 'required|in:1,2,3,4,5,6,7',
            'municipality' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'station_number' => 'nullable|string|max:255',
            'consultant_name' => 'nullable|string|max:255',
        ]);
        $workOrder->update($validated);
        return redirect()->route('admin.work-orders.index')->with('success', 'تم تحديث أمر العمل بنجاح');
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
        
        $workOrder->load([
            'files', 
            'basicAttachments', 
            'civilWorksFiles', 
            'civilWorksAttachments',
            'installationsFiles',
            'electricalWorksFiles',
            'workOrderItems.workItem', 
            'workOrderMaterials.material'
        ]);
        
        $hasWorkItems = $workOrder->workOrderItems()->count() > 0;
        $hasMaterials = $workOrder->workOrderMaterials()->count() > 0;
        
        $totalWorkItemsValue = $workOrder->workOrderItems()->sum(DB::raw('quantity * unit_price'));
        $totalMaterialsValue = $workOrder->workOrderMaterials()->sum(DB::raw('quantity * unit_price'));
        
        $grandTotal = $totalWorkItemsValue + $totalMaterialsValue;
        
        return view('admin.work_orders.execution', compact(
            'workOrder', 
            'hasWorkItems', 
            'hasMaterials', 
            'totalWorkItemsValue', 
            'totalMaterialsValue', 
            'grandTotal'
        ));
    }

    public function actionsExecution(WorkOrder $workOrder)
    {
        // تحديث البيانات من قاعدة البيانات
        $workOrder = $workOrder->fresh();
        
        $workOrder->load([
            'files', 
            'basicAttachments', 
            'civilWorksFiles', 
            'civilWorksAttachments',
            'installationsFiles',
            'electricalWorksFiles',
            'workOrderItems.workItem', 
            'workOrderMaterials.material',
            'invoiceAttachments'
        ]);
        
        // جلب صور التنفيذ من جميع أنواع الملفات
        $executionImages = collect();
        
        // إضافة صور الأعمال المدنية
        if ($workOrder->civilWorksFiles) {
            $executionImages = $executionImages->merge($workOrder->civilWorksFiles);
        }
        
        // إضافة صور التركيبات
        if ($workOrder->installationsFiles) {
            $executionImages = $executionImages->merge($workOrder->installationsFiles);
        }
        
        // إضافة صور الأعمال الكهربائية
        if ($workOrder->electricalWorksFiles) {
            $executionImages = $executionImages->merge($workOrder->electricalWorksFiles);
        }
        
        $hasWorkItems = $workOrder->workOrderItems()->count() > 0;
        $hasMaterials = $workOrder->workOrderMaterials()->count() > 0;
        
        $totalWorkItemsValue = $workOrder->workOrderItems()->sum(DB::raw('quantity * unit_price'));
        $totalMaterialsValue = $workOrder->workOrderMaterials()->sum(DB::raw('quantity * unit_price'));
        
        $grandTotal = $totalWorkItemsValue + $totalMaterialsValue;
        
        return view('admin.work_orders.actions-execution', compact(
            'workOrder', 
            'hasWorkItems', 
            'hasMaterials', 
            'totalWorkItemsValue', 
            'totalMaterialsValue', 
            'grandTotal',
            'executionImages'
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
                'work_item_code' => 'required|string|max:255',
                'work_item_description' => 'required|string|max:500',
                'unit' => 'required|string|max:50',
                'unit_price' => 'required|numeric|min:0',
                'planned_quantity' => 'required|numeric|min:0',
                'actual_quantity' => 'nullable|numeric|min:0',
            ]);

            // إنشاء أو العثور على بند العمل
            $workItem = \App\Models\WorkItem::firstOrCreate([
                'code' => $request->work_item_code,
            ], [
                'description' => $request->work_item_description,
                'unit' => $request->unit,
                'unit_price' => $request->unit_price,
            ]);

            // إنشاء ربط بين أمر العمل وبند العمل
            $workOrderItem = \App\Models\WorkOrderItem::create([
                'work_order_id' => $request->work_order_id,
                'work_item_id' => $workItem->id,
                'quantity' => $request->planned_quantity,
                'unit_price' => $request->unit_price,
                'executed_quantity' => $request->actual_quantity ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة بند العمل بنجاح',
                'data' => $workOrderItem
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة بند العمل: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث بند عمل
     */
    public function updateWorkItem(Request $request, \App\Models\WorkOrderItem $workOrderItem)
    {
        try {
            $request->validate([
                'quantity' => 'required|numeric|min:0',
                'executed_quantity' => 'nullable|numeric|min:0',
            ]);

            $workOrderItem->update([
                'quantity' => $request->quantity,
                'executed_quantity' => $request->executed_quantity ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بند العمل بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بند العمل: ' . $e->getMessage()
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
            $data = $request->validate([
                'installations' => 'required|array'
            ]);

            // حفظ البيانات في قاعدة البيانات
            $workOrder->update([
                'installations_data' => $data['installations']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حفظ بيانات التركيبات بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
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

    public function civilWorks(Request $request, WorkOrder $workOrder)
    {
        // إذا كان الطلب POST أو PUT، احفظ البيانات
        if ($request->isMethod('put') || $request->isMethod('post')) {
            // تحديث بيانات الحفريات
            $updateData = [];
            
            // حفظ بيانات الحفريات التربة الترابية غير مسفلتة
            if ($request->has('excavation_unsurfaced_soil')) {
                $updateData['excavation_unsurfaced_soil'] = $request->input('excavation_unsurfaced_soil');
            }
            
            // حفظ بيانات الحفريات التربة الترابية مسفلتة
            if ($request->has('excavation_surfaced_soil')) {
                $updateData['excavation_surfaced_soil'] = $request->input('excavation_surfaced_soil');
            }
            
            // حفظ بيانات الحفريات التربة الصخرية غير مسفلتة
            if ($request->has('excavation_unsurfaced_rock')) {
                $updateData['excavation_unsurfaced_rock'] = $request->input('excavation_unsurfaced_rock');
            }
            
            // حفظ بيانات الحفريات التربة الصخرية مسفلتة
            if ($request->has('excavation_surfaced_rock')) {
                $updateData['excavation_surfaced_rock'] = $request->input('excavation_surfaced_rock');
            }
            
            // حفظ بيانات الحفر المفتوح للحفريات التربة الترابية غير مسفلتة
            if ($request->has('excavation_unsurfaced_soil_open')) {
                $updateData['excavation_unsurfaced_soil_open'] = $request->input('excavation_unsurfaced_soil_open');
            }
            
            // حفظ بيانات الحفر المفتوح للحفريات التربة الترابية مسفلتة
            if ($request->has('excavation_surfaced_soil_open')) {
                $updateData['excavation_surfaced_soil_open'] = $request->input('excavation_surfaced_soil_open');
            }
            
            // حفظ بيانات الحفر المفتوح للحفريات التربة الصخرية غير مسفلتة
            if ($request->has('excavation_unsurfaced_rock_open')) {
                $updateData['excavation_unsurfaced_rock_open'] = $request->input('excavation_unsurfaced_rock_open');
            }
            
            // حفظ بيانات الحفر المفتوح للحفريات التربة الصخرية مسفلتة
            if ($request->has('excavation_surfaced_rock_open')) {
                $updateData['excavation_surfaced_rock_open'] = $request->input('excavation_surfaced_rock_open');
            }
            
            // حفظ بيانات الأعمال الكهربائية
            if ($request->has('electrical_items')) {
                $updateData['electrical_works'] = $request->input('electrical_items');
            }
            
            // حفظ بيانات أعمال الأسفلت والحفر المفتوح
            if ($request->has('open_excavation')) {
                $updateData['open_excavation'] = $request->input('open_excavation');
            }
            
            // حفظ بيانات الجدول التفصيلي
            if ($request->has('excavation_details_table')) {
                $updateData['excavation_details_table'] = $request->input('excavation_details_table');
            }
            
            // تحديث البيانات في قاعدة البيانات
            if (!empty($updateData)) {
                $workOrder->update($updateData);
            }
            
            // إذا كان الطلب AJAX، أرجع JSON response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حفظ بيانات الأعمال المدنية بنجاح'
                ]);
            }
            
            return redirect()->route('admin.work-orders.civil-works', $workOrder)
                ->with('success', 'تم حفظ بيانات الأعمال المدنية بنجاح');
        }
        
        // عرض الصفحة
        $workOrder->load(['civilWorksFiles', 'civilWorksAttachments']);
        
        // تحضير البيانات المحفوظة للملخص اليومي
        $savedDailyData = $workOrder->daily_civil_works_data ?? $workOrder->excavation_details_table ?? [];
        
        return view('admin.work_orders.civil_works', compact('workOrder', 'savedDailyData'));
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
            Log::info('File details', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
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

            $import = new \App\Imports\WorkItemsImport(0);
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
                'message' => 'تم استيراد ' . count($importedItems) . ' عنصر بنجاح',
                'imported_count' => count($importedItems),
                'errors_count' => count($errors),
                'errors' => $errors,
                'imported_items' => $importedItems
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
        ini_set('memory_limit', '2G');
        
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:10240'
            ]);

            $file = $request->file('file');
            Log::info('Materials import file details', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);

            // التحقق من أن مكتبة Excel متاحة
            if (!class_exists('\Maatwebsite\Excel\Facades\Excel')) {
                return response()->json([
                    'success' => false,
                    'message' => 'مكتبة Excel غير متاحة'
                ], 500);
            }

            // التحقق من أن كلاس WorkOrderMaterialsImport يعمل
            if (!class_exists('\App\Imports\WorkOrderMaterialsImport')) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلاس استيراد المواد غير متاح'
                ], 500);
            }

            $import = new \App\Imports\WorkOrderMaterialsImport();
            Log::info('Materials import class created successfully');

            \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            Log::info('Materials Excel import completed');

            $importedMaterials = $import->getFormattedMaterials();
            $errors = $import->errors();

            Log::info('Materials import results', [
                'imported_count' => count($importedMaterials),
                'errors_count' => count($errors)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد ' . count($importedMaterials) . ' مادة بنجاح',
                'imported_count' => count($importedMaterials),
                'errors_count' => count($errors),
                'errors' => $errors,
                'imported_materials' => $importedMaterials
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في تحقق الملف: ' . implode(', ', $e->errors()['file'] ?? ['ملف غير صالح'])
            ], 422);
        } catch (\Exception $e) {
            Log::error('Import materials error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد ملف المواد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get work items with search functionality
     */
    public function getWorkItems(Request $request)
    {
        $query = \App\Models\WorkItem::query();

        // البحث في كل الأعمدة
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('code', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('unit', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('notes', 'LIKE', "%{$searchTerm}%");
            });
        }

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
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('name', 'LIKE', "%{$searchTerm}%");
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
            Log::info('Saving civil works images', [
                'work_order_id' => $workOrder->id,
                'user_id' => auth()->id()
            ]);

            $request->validate([
                'civil_works_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff|max:10240', // 10MB max per image
            ]);

            $savedImages = [];

            if ($request->hasFile('civil_works_images')) {
                $files = $request->file('civil_works_images');

                foreach ($files as $file) {
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
                    
                    $savedImages[] = $savedFile;
                    
                    Log::info('Image saved successfully', [
                        'filename' => $filename,
                        'path' => $filePath,
                        'size' => $file->getSize()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ ' . count($savedImages) . ' صورة بنجاح',
                'images_count' => count($savedImages),
                'images' => collect($savedImages)->map(function($img) {
                    return [
                        'id' => $img->id,
                        'filename' => $img->filename,
                        'original_filename' => $img->original_filename,
                        'url' => asset('storage/' . $img->file_path)
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving civil works images', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'work_order_id' => $workOrder->id
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
                    $file = $request->file($fieldName);
                    
                    // التحقق من صحة الملف
                    $request->validate([
                        $fieldName => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480'
                    ]);
                    
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
                    
                    $uploadedFiles[] = $description;
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
                'site_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:30720', // 30MB max
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
                            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
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
} 