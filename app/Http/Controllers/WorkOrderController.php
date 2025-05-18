<?php

namespace App\Http\Controllers;

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
    // عرض قائمة أوامر العمل
    public function index()
    {
        $workOrders = WorkOrder::latest()->paginate(10);
        return view('admin.work_orders.index', compact('workOrders'));
    }

    // عرض نموذج إنشاء أمر عمل جديد
    public function create()
    {
        return view('admin.work_orders.create');
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
            'execution_status' => 'required|in:1,2,3,4,5,6',
            'municipality' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'station_number' => 'nullable|string|max:255',
            'consultant_name' => 'nullable|string|max:255',
        ]);
        $validated['user_id'] = Auth::id();
        $workOrder = WorkOrder::create($validated);
        return redirect()->route('admin.work-orders.index')->with('success', 'تم إنشاء أمر العمل بنجاح');
    }

    // عرض أمر عمل محدد
    public function show(WorkOrder $workOrder)
    {
        $workOrder->load('files');
        return view('admin.work_orders.show', compact('workOrder'));
    }

    // عرض نموذج تعديل أمر عمل
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
            'execution_status' => 'required|in:1,2,3,4,5,6',
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
        foreach ($workOrder->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }
        $workOrder->delete();
        return redirect()->route('admin.work-orders.index')->with('success', 'تم حذف أمر العمل بنجاح');
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
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
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
        // جلب السجلات من جدول work_order_logs
        $logs = \DB::table('work_order_logs')
            ->where('work_order_id', $workOrder->id)
            ->orderByDesc('created_at')
            ->get();
        return view('admin.work_orders.execution', compact('workOrder', 'logs'));
    }

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

        // Get all installation images
        $installationImages = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
            ->where('file_category', 'installations')
            ->where('file_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.work_orders.installations', compact('workOrder', 'installations', 'installationImages'));
    }

    public function civilWorks(WorkOrder $workOrder)
    {
        $workOrder->load('civilWorksFiles');
        return view('admin.work_orders.civil_works', compact('workOrder'));
    }

    public function uploadInstallationsImages(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'installations_images.*' => 'nullable|image|max:30720', // 30MB max per image
            ]);

            $newFiles = [];
            if ($request->hasFile('installations_images')) {
                $files = $request->file('installations_images');
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
                    $path = 'work_orders/' . $workOrder->id . '/installations';
                    if (!\Storage::disk('public')->exists($path)) {
                        \Storage::disk('public')->makeDirectory($path);
                    }
                    $filePath = $file->storeAs($path, $filename, 'public');
                    $newFile = \App\Models\WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'installations'
                    ]);
                    $newFiles[] = $newFile;
                }
            }

            // جلب جميع الصور المحدثة
            $allFiles = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
                ->where('file_category', 'installations')
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
            \Log::error('Error uploading installations images: ' . $e->getMessage());
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

    public function storeInstallations(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'installations.*.status' => 'required|in:yes,no,na',
            'installations.*.quantity' => 'nullable|integer|min:0',
        ]);

        // حفظ بيانات التركيبات في جدول work_orders أو جدول منفصل حسب الحاجة
        $workOrder->update([
            'installations_data' => json_encode($request->input('installations'), JSON_UNESCAPED_UNICODE)
        ]);

        return redirect()->route('admin.work-orders.installations', $workOrder)
            ->with('success', 'تم حفظ بيانات التركيبات بنجاح');
    }

    public function actionsExecution(WorkOrder $workOrder)
    {
        $workOrder = WorkOrder::with('files')->find($workOrder->id);
        $executionImages = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
            ->whereIn('file_category', ['civil_works', 'installations', 'electrical_works'])
            ->where('file_type', 'like', 'image/%')
            ->get();
        return view('admin.work_orders.actions-execution', compact('workOrder', 'executionImages'));
    }

    // عرض صفحة الرخص
    public function license(WorkOrder $workOrder)
    {
        // جلب ملفات الرخص المرتبطة بأمر العمل
        $licenseFiles = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
            ->where('file_category', 'license')
            ->orderBy('created_at', 'desc')
            ->get();

        // جلب معلومات الرخص من جدول الرخص إذا كان موجوداً
        $license = \App\Models\License::where('work_order_id', $workOrder->id)->first();

        return view('admin.work_orders.license', compact('workOrder', 'licenseFiles', 'license'));
    }

    // عرض صفحة المسح
    public function survey(WorkOrder $workOrder)
    {
        // جلب بيانات المسح إذا كانت موجودة
        $survey = \App\Models\Survey::where('work_order_id', $workOrder->id)->first();
        
        // جلب ملفات المسح المرتبطة بأمر العمل
        $surveyFiles = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
            ->where('file_category', 'survey')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.work_orders.survey', compact('workOrder', 'survey', 'surveyFiles'));
    }

    // حفظ بيانات المسح
    public function storeSurvey(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'survey_date' => 'required|date',
            'survey_type' => 'required|string|max:255',
            'survey_notes' => 'nullable|string',
            'survey_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // حفظ بيانات المسح
        $survey = \App\Models\Survey::updateOrCreate(
            ['work_order_id' => $workOrder->id],
            [
                'survey_date' => $validated['survey_date'],
                'survey_type' => $validated['survey_type'],
                'notes' => $validated['survey_notes']
            ]
        );

        // رفع ملف المسح إذا تم اختياره
        if ($request->hasFile('survey_file')) {
            $file = $request->file('survey_file');
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'work_orders/' . $workOrder->id . '/survey';
            
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }
            
            $filePath = $file->storeAs($path, $filename, 'public');
            
            \App\Models\WorkOrderFile::create([
                'work_order_id' => $workOrder->id,
                'filename' => $filename,
                'original_filename' => $originalName,
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'file_category' => 'survey'
            ]);
        }

        return redirect()->route('admin.work-orders.survey', $workOrder)
            ->with('success', 'تم حفظ بيانات المسح بنجاح');
    }

    // تعديل بيانات المسح
    public function editSurvey(Survey $survey)
    {
        $workOrder = $survey->workOrder;
        $surveyFiles = \App\Models\WorkOrderFile::where('work_order_id', $workOrder->id)
            ->where('file_category', 'survey')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.work_orders.edit_survey', compact('workOrder', 'survey', 'surveyFiles'));
    }

    // رفع ملف رخصة
    public function uploadLicense(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'license_number' => 'required|string|max:255',
                'license_date' => 'required|date',
                'license_type' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'has_restriction' => 'required|in:0,1',
                'restriction_agency' => 'nullable|string|max:255',
                'license_start_date' => 'nullable|date',
                'license_end_date' => 'nullable|date',
                'extension_start_date' => 'nullable|date',
                'extension_end_date' => 'nullable|date',
                // حقول الاختبارات
                'has_depth_test' => 'required|in:0,1',
                'has_soil_compaction_test' => 'required|in:0,1',
                'has_rc1_mc1_test' => 'required|in:0,1',
                'has_asphalt_test' => 'required|in:0,1',
                'has_soil_test' => 'required|in:0,1',
                'has_interlock_test' => 'required|in:0,1',
                // الملفات
                'coordination_certificates.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'letters_undertakings.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'payment_invoices.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'payment_proof.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'license_activation.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'extension_invoice.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'soil_test_images.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'test_results_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'final_inspection_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'license_closure_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            ]);

            // تحديث أو إنشاء سجل الرخصة
            $license = \App\Models\License::updateOrCreate(
                ['work_order_id' => $workOrder->id],
                [
                    'license_number' => $request->license_number,
                    'license_date' => $request->license_date,
                    'license_type' => $request->license_type,
                    'notes' => $request->notes,
                    'has_restriction' => $request->has_restriction,
                    'restriction_authority' => $request->restriction_agency,
                    'license_start_date' => $request->license_start_date,
                    'license_end_date' => $request->license_end_date,
                    'license_extension_start_date' => $request->extension_start_date,
                    'license_extension_end_date' => $request->extension_end_date,
                    // حقول الاختبارات
                    'has_depth_test' => $request->has_depth_test,
                    'has_soil_compaction_test' => $request->has_soil_compaction_test,
                    'has_rc1_mc1_test' => $request->has_rc1_mc1_test,
                    'has_asphalt_test' => $request->has_asphalt_test,
                    'has_soil_test' => $request->has_soil_test,
                    'has_interlock_test' => $request->has_interlock_test,
                ]
            );

            // رفع الملفات وحفظ المسارات
            $fileFields = [
                'coordination_certificates' => 'coordination_certificate_path',
                'letters_undertakings' => 'letters_and_commitments_path',
                'payment_invoices' => 'payment_invoices_path',
                'payment_proof' => 'payment_proof_path',
                'license_activation' => 'activation_file_path',
                'extension_invoice' => 'invoice_extension_file_path',
                'soil_test_images' => 'soil_test_images_path',
            ];

            foreach ($fileFields as $inputName => $dbField) {
                if ($request->hasFile($inputName)) {
                    $files = $request->file($inputName);
                    $paths = [];
                    foreach ((array)$files as $file) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = 'work_orders/' . $workOrder->id . '/license/' . $inputName;
                        if (!\Storage::disk('public')->exists($path)) {
                            \Storage::disk('public')->makeDirectory($path);
                        }
                        $filePath = $file->storeAs($path, $filename, 'public');
                        $paths[] = $filePath;
                    }
                    $license->$dbField = count($paths) > 1 ? json_encode($paths, JSON_UNESCAPED_UNICODE) : ($paths[0] ?? null);
                }
            }

            // ملفات مفردة
            $singleFiles = [
                'test_results_file' => 'test_results_file_path',
                'final_inspection_file' => 'final_inspection_file_path',
                'license_closure_file' => 'license_closure_file_path',
            ];

            foreach ($singleFiles as $inputName => $dbField) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/license/' . $inputName;
                    if (!\Storage::disk('public')->exists($path)) {
                        \Storage::disk('public')->makeDirectory($path);
                    }
                    $filePath = $file->storeAs($path, $filename, 'public');
                    $license->$dbField = $filePath;
                }
            }

            $license->save();

            // إعادة توجيه إلى صفحة عرض تفاصيل الرخصة
            return redirect()->route('admin.licenses.show', $license->id)
                ->with('success', 'تم حفظ بيانات الرخصة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error saving license: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ بيانات الرخصة: ' . $e->getMessage())
                ->withInput();
        }
    }

    // حذف ملف رخصة
    public function deleteLicenseFile($fileId)
    {
        try {
            $file = \App\Models\WorkOrderFile::findOrFail($fileId);
            
            if ($file->file_category !== 'license') {
                return back()->with('error', 'هذا الملف ليس من ملفات الرخص');
            }

            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // حذف السجل من قاعدة البيانات
            $file->delete();

            return back()->with('success', 'تم حذف ملف الرخصة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting license file: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف ملف الرخصة');
        }
    }

    // رفع مرفقات إجراءات ما بعد التنفيذ
    public function uploadPostExecutionFile(Request $request, $workOrderId)
    {
        try {
            \Log::info('Starting file upload process for work order: ' . $workOrderId);
            \Log::info('Request data:', $request->all());
            
            $workOrder = WorkOrder::findOrFail($workOrderId);
            
            // Validate the request
            $request->validate([
                'final_total_value' => 'nullable|numeric|min:0',
                'quantities_statement_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'final_materials_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'final_measurement_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'soil_tests_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'site_drawing_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'modified_estimate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'completion_certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'form_200_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'form_190_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'pre_operation_tests_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'first_payment_extract_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'second_payment_extract_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'total_extract_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'invoice_images.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            ]);

            \Log::info('Validation passed');

            // Update final total value if provided
            if ($request->has('final_total_value')) {
                $workOrder->final_total_value = $request->final_total_value;
                \Log::info('Updated final total value: ' . $request->final_total_value);
            }

            // Define all file fields that need to be processed
            $fileFields = [
                'quantities_statement_file',
                'final_materials_file',
                'final_measurement_file',
                'soil_tests_file',
                'site_drawing_file',
                'modified_estimate_file',
                'completion_certificate_file',
                'form_200_file',
                'form_190_file',
                'pre_operation_tests_file',
                'first_payment_extract_file',
                'second_payment_extract_file',
                'total_extract_file'
            ];

            // Process each file field
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    \Log::info('Processing file field: ' . $field);
                    $file = $request->file($field);
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/post_execution';
                    
                    \Log::info('File details:', [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'path' => $path,
                        'field' => $field
                    ]);
                    
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path);
                        \Log::info('Created directory: ' . $path);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    \Log::info('File stored at: ' . $filePath);
                    
                    // Create file record in database
                    $workOrderFile = WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'post_execution',
                        'attachment_type' => $field,
                    ]);
                    
                    \Log::info('Created work order file record:', [
                        'id' => $workOrderFile->id,
                        'file_path' => $filePath,
                        'field' => $field
                    ]);
                }
            }

            // Handle invoice images
            if ($request->hasFile('invoice_images')) {
                \Log::info('Processing invoice images');
                foreach ($request->file('invoice_images') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'work_orders/' . $workOrder->id . '/invoice_images';
                    
                    \Log::info('Invoice image details:', [
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'path' => $path
                    ]);
                    
                    if (!Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->makeDirectory($path);
                        \Log::info('Created directory for invoice images: ' . $path);
                    }
                    
                    $filePath = $file->storeAs($path, $filename, 'public');
                    \Log::info('Invoice image stored at: ' . $filePath);
                    
                    // Create file record in database
                    $workOrderFile = WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'filename' => $filename,
                        'original_filename' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_category' => 'invoice'
                    ]);
                    
                    \Log::info('Created invoice file record:', [
                        'id' => $workOrderFile->id,
                        'file_path' => $filePath
                    ]);
                }
            }

            // Save work order changes
            $workOrder->save();
            \Log::info('Work order updated successfully');

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'تم رفع الملفات بنجاح']);
            }
            return redirect()->route('admin.work-orders.actions-execution', $workOrder->id)
                ->with('success', 'تم تحديث البيانات ورفع الملفات بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error in uploadPostExecutionFile: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage());
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
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
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
            \Log::error('Error uploading electrical works images: ' . $e->getMessage());
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

    // حذف صورة من صور التركيبات
    public function deleteInstallationImage($imageId)
    {
        try {
            $image = \App\Models\WorkOrderFile::findOrFail($imageId);
            
            // التحقق من أن الصورة تنتمي إلى فئة التركيبات
            if ($image->file_category !== 'installations') {
                return back()->with('error', 'هذه الصورة ليست من صور التركيبات');
            }

            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($image->file_path)) {
                Storage::disk('public')->delete($image->file_path);
            }

            // حذف السجل من قاعدة البيانات
            $image->delete();

            return back()->with('success', 'تم حذف الصورة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting installation image: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الصورة');
        }
    }

    public function materials()
    {
        $workOrders = \App\Models\WorkOrder::all();
        $materials = \App\Models\Material::with('workOrder')->latest()->paginate(15);
        return view('admin.work_orders.materials', compact('workOrders', 'materials'));
    }

    public function getMaterialDescription($code)
    {
        $material = \App\Models\Material::where('code', $code)->first();
        return response()->json([
            'description' => $material ? $material->description : ''
        ]);
    }
} 