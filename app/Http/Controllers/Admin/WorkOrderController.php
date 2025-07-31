<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrderRequest;
use App\Models\WorkOrder;
use App\Models\WorkOrderFile;
use App\Models\WorkItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    /**
     * عرض قائمة أوامر العمل
     */
    public function index(Request $request)
    {
        $project = $request->get('project', 'riyadh');
        
        // تحديد المدينة بناءً على المشروع
        $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
        
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
        $query->orderBy('created_at', 'desc');
        
        // عدد العناصر في الصفحة (يمكن تخصيصه من خلال الفلتر)
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 15;
        
        $workOrders = $query->paginate($perPage);
        
        return view('admin.work_orders.index', compact('workOrders', 'project'));
    }

    /**
     * عرض صفحة الإنتاجية العامة
     */
    public function generalProductivity(Request $request)
    {
        $project = $request->get('project', 'riyadh');
        
        return view('admin.work_orders.general-productivity', compact('project'));
    }

    /**
     * جلب بيانات الإنتاجية العامة
     */
    public function getGeneralProductivityData(Request $request)
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $project = $request->get('project', 'riyadh');
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // جلب أوامر العمل مع بنود العمل المنفذة
            $workOrders = WorkOrder::with(['workOrderItems.workItem'])
                ->where('city', $city)
                ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->get();
            
            // حساب الإحصائيات
            $totalWorkOrders = $workOrders->count();
            $totalItemsCount = 0;
            $totalExecutedQuantity = 0;
            $totalAmount = 0;
            
            $workOrdersData = [];
            
            foreach ($workOrders as $workOrder) {
                // جلب كل البنود المرتبطة بأمر العمل
                $allWorkItems = $workOrder->workOrderItems()
                    ->with('workItem')
                    ->get();
                
                $itemsCount = $allWorkItems->count();
                $executedItemsCount = $allWorkItems->where('executed_quantity', '>', 0)->count();
                $executedQuantity = $allWorkItems->sum('executed_quantity');
                $totalValue = $allWorkItems->sum(function($item) {
                    return $item->quantity * ($item->unit_price ?? 0);
                });
                
                // جمع تفاصيل بنود العمل
                $workItemsDetails = [];
                foreach ($allWorkItems as $workOrderItem) {
                    $workItemsDetails[] = [
                        'id' => $workOrderItem->id,
                        'work_item_code' => $workOrderItem->workItem->code ?? 'غير محدد',
                        'work_item_description' => $workOrderItem->workItem->description ?? 'غير محدد',
                        'planned_quantity' => $workOrderItem->quantity ?? 0,
                        'executed_quantity' => $workOrderItem->executed_quantity ?? 0,
                        'unit' => $workOrderItem->workItem->unit ?? 'غير محدد',
                        'unit_price' => $workOrderItem->unit_price ?? 0,
                        'planned_amount' => ($workOrderItem->quantity ?? 0) * ($workOrderItem->unit_price ?? 0),
                        'executed_amount' => ($workOrderItem->executed_quantity ?? 0) * ($workOrderItem->unit_price ?? 0),
                        'completion_percentage' => ($workOrderItem->quantity ?? 0) > 0 ? 
                            round((($workOrderItem->executed_quantity ?? 0) / $workOrderItem->quantity) * 100, 2) : 0,
                        'work_date' => $workOrderItem->work_date ? $workOrderItem->work_date->format('Y-m-d') : null,
                        'notes' => $workOrderItem->notes,
                    ];
                }
                
                $totalItemsCount += $itemsCount;
                $totalExecutedQuantity += $executedQuantity;
                $totalAmount += $totalValue;
                
                $workOrdersData[] = [
                    'id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'subscriber_name' => $workOrder->subscriber_name,
                    'work_type' => $workOrder->work_type,
                    'district' => $workOrder->district,
                    'items_count' => $itemsCount,
                    'executed_items_count' => $executedItemsCount,
                    'executed_quantity' => $executedQuantity,
                    'total_value' => $totalValue,
                    'completion_rate' => $itemsCount > 0 ? round(($executedItemsCount / $itemsCount) * 100, 2) : 0,
                    'created_at' => $workOrder->created_at->format('Y-m-d'),
                    'work_items' => $workItemsDetails,
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'work_orders' => $workOrdersData,
                    'summary' => [
                        'total_work_orders' => $totalWorkOrders,
                        'total_items_count' => $totalItemsCount,
                        'total_executed_quantity' => $totalExecutedQuantity,
                        'total_amount' => $totalAmount,
                    ],
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching general productivity data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات'
            ], 500);
        }
    }

    /**
     * رفع صور التنفيذ
     */
    public function uploadExecutionImages(Request $request, WorkOrder $workOrder)
    {
        try {
            Log::info('Starting image upload for work order: ' . $workOrder->id);
            
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
            ]);

            if (!$request->hasFile('images')) {
                Log::warning('No images found in request');
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم اختيار أي صور للرفع'
                ], 400);
            }

            Log::info('Found ' . count($request->file('images')) . ' images to upload');

            $uploadedImages = [];
            foreach ($request->file('images') as $index => $image) {
                Log::info("Processing image {$index}: " . $image->getClientOriginalName());
                
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $originalName = $image->getClientOriginalName();
                
                // حفظ الصورة
                $path = $image->storeAs('work_orders/' . $workOrder->id . '/execution', $filename, 'public');
                Log::info("Image stored at path: {$path}");
                
                // التحقق من وجود الملف
                if (!Storage::disk('public')->exists($path)) {
                    Log::error("File was not saved properly: {$path}");
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

                Log::info("Database record created with ID: {$file->id}");

                $uploadedImages[] = [
                    'id' => $file->id,
                    'name' => $originalName,
                    'path' => Storage::url($path),
                    'size' => $this->formatFileSize($file->file_size),
                    'created_at' => $file->created_at->format('Y-m-d H:i:s')
                ];
            }

            Log::info('Successfully uploaded ' . count($uploadedImages) . ' images');

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصور بنجاح',
                'images' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading execution images: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة تنفيذ
     */
    public function deleteExecutionImage(WorkOrder $workOrder, $image)
    {
        try {
            $file = $workOrder->files()->where('id', $image)->where('file_category', 'execution_images')->firstOrFail();
            
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
            Log::error('Error deleting execution image: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض صفحة التنفيذ
     */
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
            'workOrderMaterials.material'
        ]);

        // جلب صور التنفيذ
        $executionImages = $workOrder->files()
            ->where('file_category', 'execution_images')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter work order items by date - only show items that match the selected date
        if ($workDate) {
            $workOrder->workOrderItems = $workOrder->workOrderItems->filter(function($item) use ($workDate) {
                // إذا كان البند له تاريخ محفوظ، اعرضه فقط إذا كان يطابق التاريخ المحدد
                if ($item->work_date) {
                    return $item->work_date->format('Y-m-d') === $workDate;
                }
                // إذا لم يكن للبند تاريخ محفوظ، اعرضه فقط في تاريخ اليوم
                return $workDate === now()->format('Y-m-d');
            });
        }
        
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
            'grandTotal',
            'workDate',
            'workItems',
            'project',
            'executionImages'
        ));
    }

    /**
     * عرض تقرير إنتاجية الرياض
     */
    public function riyadhProductivity()
    {
        return view('admin.work_orders.general-productivity', ['project' => 'riyadh']);
    }

    /**
     * عرض تقرير إنتاجية المدينة
     */
    public function madinahProductivity()
    {
        return view('admin.work_orders.general-productivity', ['project' => 'madinah']);
    }

    /**
     * تنسيق حجم الملف
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
} 