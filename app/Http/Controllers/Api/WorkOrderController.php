<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WorkOrdersInProgressExport;
use App\Exports\WorkOrdersReceiptsExport;
use App\Exports\WorkOrdersCompletedExport;

class WorkOrderController extends Controller
{
    /**
     * جلب أوامر العمل المستلمة
     */
    public function receipts(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $perPage = $request->get('per_page', 25);
            $page = $request->get('page', 1);
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            \Log::info('Receipts API called', [
                'project' => $project,
                'city' => $city,
                'per_page' => $perPage,
                'page' => $page
            ]);
            
            // بناء الاستعلام الأساسي - جلب أوامر العمل المستلمة فقط
            $query = WorkOrder::where('city', $city)
                ->whereNotNull('order_number'); // أوامر العمل المستلمة لها رقم أمر عمل
            
            // تطبيق الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            // حساب الإحصائيات
            $totalReceivedOrders = $query->count();
            $totalValue = $query->sum('order_value_without_consultant');
            
            // حساب النسبة من إجمالي الأوامر
            $totalOrders = WorkOrder::where('city', $city)->count();
            $percentage = $totalOrders > 0 ? round(($totalReceivedOrders / $totalOrders) * 100, 1) : 0;
            
            // حساب متوسط قيمة الأمر
            $averageValue = $totalReceivedOrders > 0 ? $totalValue / $totalReceivedOrders : 0;
            
            // جلب البيانات مع الـ pagination
            $workOrders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            
            // تحضير البيانات للعرض
            $data = $workOrders->getCollection()->map(function ($workOrder) {
                return [
                    'id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'work_type' => $workOrder->work_type,
                    'work_description' => $this->getWorkTypeDescription($workOrder->work_type),
                    'subscriber_name' => $workOrder->subscriber_name,
                    'district' => $workOrder->district,
                    'office' => $workOrder->office,
                    'order_value_without_consultant' => $workOrder->order_value_without_consultant,
                    'received_at' => $workOrder->received_at ? $workOrder->received_at->format('Y-m-d') : null,
                    'created_at' => $workOrder->created_at ? $workOrder->created_at->format('Y-m-d') : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => [
                    'total_orders' => $totalReceivedOrders,
                    'total_value' => $totalValue,
                    'percentage' => $percentage,
                    'average_value' => $averageValue,
                ],
                'pagination' => [
                    'current_page' => $workOrders->currentPage(),
                    'per_page' => $workOrders->perPage(),
                    'total' => $workOrders->total(),
                    'total_pages' => $workOrders->lastPage(),
                    'from' => $workOrders->firstItem(),
                    'to' => $workOrders->lastItem(),
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
     * تصدير أوامر العمل المستلمة إلى Excel
     */
    public function exportReceipts(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام مع نفس الفلاتر
            $query = WorkOrder::where('city', $city);
            
            // تطبيق نفس الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            $workOrders = $query->orderBy('created_at', 'desc')->get();
            
            $fileName = 'work_orders_receipts_' . $project . '_' . date('Y-m-d') . '.xlsx';
            
            return Excel::download(new WorkOrdersReceiptsExport($workOrders), $fileName);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أوامر العمل المنفذة
     */
    public function execution(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $perPage = $request->get('per_page', 25);
            $page = $request->get('page', 1);
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            \Log::info('Execution API called', [
                'project' => $project,
                'city' => $city,
                'per_page' => $perPage,
                'page' => $page
            ]);
            
            // بناء الاستعلام الأساسي - فلترة أوامر العمل المنفذة (حالات التنفيذ المختلفة)
            $query = WorkOrder::where('city', $city)
                ->whereIn('execution_status', [2, 3, 4, 7]) // حالات التنفيذ المختلفة
                ->whereNotNull('actual_execution_value_without_consultant')
                ->where('actual_execution_value_without_consultant', '>', 0);
            
            // تطبيق الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            // حساب الإحصائيات
            $totalExecutedOrders = $query->count();
            $totalExecutedValue = $query->sum('actual_execution_value_without_consultant');
            $totalInitialValue = $query->sum('order_value_without_consultant');
            
            \Log::info('Execution stats', [
                'totalExecutedOrders' => $totalExecutedOrders,
                'totalExecutedValue' => $totalExecutedValue,
                'totalInitialValue' => $totalInitialValue
            ]);
            
            // حساب النسبة من إجمالي الأوامر
            $totalOrders = WorkOrder::where('city', $city)->count();
            $executionPercentage = $totalOrders > 0 ? round(($totalExecutedOrders / $totalOrders) * 100, 1) : 0;
            
            // حساب متوسط القيمة المنفذة
            $averageExecutedValue = $totalExecutedOrders > 0 ? $totalExecutedValue / $totalExecutedOrders : 0;
            
            // جلب البيانات مع الـ pagination
            $workOrders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            
            // تحضير البيانات للعرض
            $data = $workOrders->getCollection()->map(function ($workOrder) {
                // جلب بنود العمل المرتبطة بأمر العمل
                $workItems = $workOrder->workOrderItems()->with('workItem')->get()->map(function ($workOrderItem) {
                    $workItem = $workOrderItem->workItem;
                    $unitPrice = $workItem ? (float) $workItem->unit_price : 0;
                    $plannedQuantity = (float) ($workOrderItem->quantity ?? 0);
                    $executedQuantity = (float) ($workOrderItem->executed_quantity ?? 0);
                    $plannedAmount = $plannedQuantity * $unitPrice;
                    $executedAmount = $executedQuantity * $unitPrice;
                    
                    return [
                        'id' => $workOrderItem->id,
                        'code' => $workItem ? $workItem->code : '-',
                        'description' => $workItem ? $workItem->description : '-',
                        'unit' => $workOrderItem->unit ?? ($workItem ? $workItem->unit : 'عدد'),
                        'unit_price' => (float) $unitPrice,
                        'planned_quantity' => $plannedQuantity,
                        'executed_quantity' => $executedQuantity,
                        'planned_amount' => (float) $plannedAmount,
                        'executed_amount' => (float) $executedAmount,
                        'difference' => (float) ($plannedQuantity - $executedQuantity),
                    ];
                });

                return [
                    'id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'office' => $workOrder->office,
                    'initial_value' => $workOrder->order_value_without_consultant,
                    'executed_value' => $workOrder->actual_execution_value_without_consultant,
                    'execution_status' => $workOrder->execution_status,
                    'execution_status_text' => $this->getExecutionStatusText($workOrder->execution_status),
                    'work_items' => $workItems,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => [
                    'total_orders' => $totalExecutedOrders,
                    'total_executed_value' => $totalExecutedValue,
                    'execution_percentage' => $executionPercentage,
                    'average_executed_value' => $averageExecutedValue,
                ],
                'pagination' => [
                    'current_page' => $workOrders->currentPage(),
                    'per_page' => $workOrders->perPage(),
                    'total' => $workOrders->total(),
                    'total_pages' => $workOrders->lastPage(),
                    'from' => $workOrders->firstItem(),
                    'to' => $workOrders->lastItem(),
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
     * تصدير أوامر العمل المنفذة إلى Excel
     */
    public function exportExecution(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام مع نفس الفلاتر
            $query = WorkOrder::where('city', $city)
                ->whereIn('execution_status', [2, 3, 4]); // حالات التنفيذ المختلفة
            
            // تطبيق نفس الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            $workOrders = $query->orderBy('created_at', 'desc')->get();
            
            $fileName = 'work_orders_execution_' . $project . '_' . date('Y-m-d') . '.xlsx';
            
            return Excel::download(new WorkOrdersExecutionExport($workOrders), $fileName);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أوامر العمل في المستخلص
     */
    public function extracts(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $perPage = $request->get('per_page', 25);
            $page = $request->get('page', 1);
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام الأساسي - فلترة أوامر العمل في المستخلص
            $query = WorkOrder::where('city', $city)
                ->whereIn('execution_status', [5, 6]); // حالات المستخلص
            
            // تطبيق الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }

            if ($request->filled('extract_number')) {
                $query->where('extract_number', 'like', '%' . $request->extract_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            // حساب الإحصائيات
            $totalExtracts = $query->count();
            $totalOrders = $query->count();
            $totalValue = $query->sum('extract_value');
            
            // حساب النسبة من إجمالي الأوامر
            $allOrders = WorkOrder::where('city', $city)->count();
            $extractPercentage = $allOrders > 0 ? round(($totalOrders / $allOrders) * 100, 1) : 0;
            
            // حساب متوسط قيمة المستخلص
            $averageValue = $totalExtracts > 0 ? $totalValue / $totalExtracts : 0;
            
            // جلب البيانات مع الـ pagination
            $workOrders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            
            // تحضير البيانات للعرض
            $data = $workOrders->getCollection()->map(function ($workOrder) {
                return [
                    'id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'office' => $workOrder->office,
                    'initial_value' => $workOrder->order_value_without_consultant,
                    'extract_value' => $workOrder->extract_value,
                    'extract_number' => $workOrder->extract_number,
                    'extract_date' => $workOrder->extract_date ? $workOrder->extract_date->format('Y-m-d') : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => [
                    'total_extracts' => $totalExtracts,
                    'total_orders' => $totalOrders,
                    'total_extract_value' => $totalValue,
                    'extract_percentage' => $extractPercentage,
                    'average_extract_value' => $averageValue,
                ],
                'pagination' => [
                    'current_page' => $workOrders->currentPage(),
                    'per_page' => $workOrders->perPage(),
                    'total' => $workOrders->total(),
                    'total_pages' => $workOrders->lastPage(),
                    'from' => $workOrders->firstItem(),
                    'to' => $workOrders->lastItem(),
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
     * تصدير أوامر العمل في المستخلص إلى Excel
     */
    public function exportExtracts(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام مع نفس الفلاتر
            $query = WorkOrder::where('city', $city)
                ->whereIn('execution_status', [5, 6]); // حالات المستخلص
            
            // تطبيق نفس الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            $workOrders = $query->orderBy('created_at', 'desc')->get();
            
            $fileName = 'work_orders_extracts_' . $project . '_' . date('Y-m-d') . '.xlsx';
            
            return Excel::download(new WorkOrdersExtractsExport($workOrders), $fileName);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أوامر العمل المنتهية والمصروفة
     */
    public function completed(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $perPage = $request->get('per_page', 25);
            $page = $request->get('page', 1);
            
            \Log::info('Completed API called', [
                'project' => $project,
                'per_page' => $perPage,
                'page' => $page
            ]);
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            \Log::info('City determined', ['city' => $city]);
            
            // بناء الاستعلام الأساسي - فلترة أوامر العمل المنتهية والمصروفة
            $query = WorkOrder::where('city', $city)
                ->where('execution_status', 7); // منتهي تم الصرف
            
            // تطبيق الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }

            // فلتر التاريخ
            if ($request->filled('date_from')) {
                $query->whereDate('payment_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('payment_date', '<=', $request->date_to);
            }
            
            // حساب الإحصائيات
            $totalCompletedOrders = $query->count();
            $totalFinalValue = $query->sum('final_value');
            
            \Log::info('Completed stats calculated', [
                'totalCompletedOrders' => $totalCompletedOrders,
                'totalFinalValue' => $totalFinalValue
            ]);
            
            // حساب النسبة من إجمالي الأوامر
            $allOrders = WorkOrder::where('city', $city)->count();
            $completionPercentage = $allOrders > 0 ? round(($totalCompletedOrders / $allOrders) * 100, 1) : 0;
            
            // حساب متوسط القيمة النهائية
            $averageFinalValue = $totalCompletedOrders > 0 ? $totalFinalValue / $totalCompletedOrders : 0;
            
            // جلب البيانات مع الـ pagination
            $workOrders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            
            // تحضير البيانات للعرض
            $data = $workOrders->getCollection()->map(function ($workOrder) {
                return [
                    'id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'subscriber_name' => $workOrder->subscriber_name,
                    'office' => $workOrder->office,
                    'initial_value' => $workOrder->order_value_without_consultant,
                    'final_value' => $workOrder->final_value,
                    'payment_date' => $workOrder->payment_date ? $workOrder->payment_date->format('Y-m-d') : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => [
                    'total_orders' => $totalCompletedOrders,
                    'total_final_value' => $totalFinalValue,
                    'completion_percentage' => $completionPercentage,
                    'average_final_value' => $averageFinalValue,
                ],
                'pagination' => [
                    'current_page' => $workOrders->currentPage(),
                    'per_page' => $workOrders->perPage(),
                    'total' => $workOrders->total(),
                    'total_pages' => $workOrders->lastPage(),
                    'from' => $workOrders->firstItem(),
                    'to' => $workOrders->lastItem(),
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
     * تصدير أوامر العمل المنتهية والمصروفة إلى Excel
     */
    public function exportCompleted(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام مع نفس الفلاتر
            $query = WorkOrder::where('city', $city)
                ->where('execution_status', 7); // منتهي تم الصرف
            
            // تطبيق نفس الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            $workOrders = $query->orderBy('created_at', 'desc')->get();
            
            $fileName = 'work_orders_completed_' . $project . '_' . date('Y-m-d') . '.xlsx';
            
            return Excel::download(new WorkOrdersCompletedExport($workOrders), $fileName);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أوامر العمل في حالة جاري التنفيذ
     */
    public function inProgress(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $perPage = $request->get('per_page', 25);
            $page = $request->get('page', 1);
            
            // تحديد المدينة بناءً على المشروع
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام الأساسي - عرض أوامر العمل جاري العمل فقط
            $query = WorkOrder::where('city', $city)
                ->where('execution_status', 1) // 1 = جاري العمل
                ->whereNotNull('order_number');
            
            // تطبيق الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            // حساب الإحصائيات
            $totalInProgressOrders = $query->count();
            $totalValue = $query->sum('order_value_without_consultant');
            
            // حساب النسبة من إجمالي الأوامر
            $totalOrders = WorkOrder::where('city', $city)->count();
            $percentage = $totalOrders > 0 ? round(($totalInProgressOrders / $totalOrders) * 100, 1) : 0;
            
            // حساب متوسط قيمة الأمر
            $averageValue = $totalInProgressOrders > 0 ? $totalValue / $totalInProgressOrders : 0;
            
            // جلب البيانات مع الـ pagination
            $workOrders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            
            // تحضير البيانات للعرض
            $data = $workOrders->getCollection()->map(function ($workOrder) {
                return [
                    'id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'subscriber_name' => $workOrder->subscriber_name,
                    'office' => $workOrder->office,
                    'order_value_without_consultant' => $workOrder->order_value_without_consultant,
                    'execution_status' => $workOrder->execution_status,
                    'execution_status_text' => $this->getExecutionStatusText($workOrder->execution_status),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => [
                    'total_orders' => $totalInProgressOrders,
                    'total_value' => $totalValue,
                    'percentage' => $percentage,
                    'average_value' => $averageValue,
                ],
                'pagination' => [
                    'current_page' => $workOrders->currentPage(),
                    'per_page' => $workOrders->perPage(),
                    'total' => $workOrders->total(),
                    'total_pages' => $workOrders->lastPage(),
                    'from' => $workOrders->firstItem(),
                    'to' => $workOrders->lastItem(),
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
     * تصدير أوامر العمل جاري التنفيذ إلى Excel
     */
    public function exportInProgress(Request $request)
    {
        try {
            $project = $request->get('project', 'riyadh');
            $city = $project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
            
            // بناء الاستعلام مع نفس الفلاتر
            $query = WorkOrder::where('city', $city)
                ->where('execution_status', 1); // 1 = جاري العمل
            
            // تطبيق نفس الفلاتر المبسطة
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('office')) {
                $query->where('office', $request->office);
            }
            
            $workOrders = $query->orderBy('created_at', 'desc')->get();
            
            $fileName = 'work_orders_inprogress_' . $project . '_' . date('Y-m-d') . '.xlsx';
            
            return Excel::download(new WorkOrdersInProgressExport($workOrders), $fileName);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * الحصول على نص حالة التنفيذ
     */
    private function getExecutionStatusText($status)
    {
        $statuses = [
            1 => 'جاري العمل',
            2 => 'تم تسليم 155 ولم تصدر شهادة انجاز',
            3 => 'صدرت شهادة ولم تعتمد',
            4 => 'تم اعتماد شهادة الانجاز',
            5 => 'مؤكد ولم تدخل مستخلص',
            6 => 'دخلت مستخلص ولم تصرف',
            7 => 'منتهي تم الصرف',
        ];
        
        return $statuses[$status] ?? 'حالة غير محددة';
    }

    private function getWorkTypeDescription($workType)
    {
        $descriptions = [
            '409' => 'ازالة-نقل شبكة على المشترك',
            '408' => 'ازاله عداد على المشترك',
            '460' => 'استبدال شبكات',
            '901' => 'اضافة عداد طاقة شمسية',
            '440' => 'الرفع المساحي',
            '410' => 'انشاء محطة/محول لمشترك/مشتركين',
            '801' => 'تركيب عداد ايصال سريع',
            '804' => 'تركيب محطة ش ارضية VM ايصال سريع',
            '805' => 'تركيب محطة ش هوائية VM ايصال سريع',
            '480' => '(تسليم مفتاح) تمويل خارجي',
            '441' => 'تعزيز شبكة أرضية ومحطات',
            '442' => 'تعزيز شبكة هوائية ومحطات',
            '802' => 'شبكة ارضية VL ايصال سريع',
            '803' => 'شبكة هوائية VL ايصال سريع',
            '402' => 'توصيل عداد بحفرية شبكة ارضيه',
            '401' => '(عداد بدون حفرية) أرضي/هوائي',
            '404' => 'عداد بمحطة شبكة ارضية VM',
            '405' => 'توصيل عداد بمحطة شبكة هوائية VM',
            '430' => 'مخططات منح وزارة البلدية',
            '450' => 'مشاريع ربط محطات التحويل',
            '403' => 'توصيل عداد شبكة هوائية VL',
            '806' => 'ايصال وزارة الاسكان جهد منخفض',
            '444' => 'تحويل الشبكه من هوائي الي ارضي',
            '111' => 'Mv- طوارئ ضغط متوسط',
            '222' => 'Lv - طوارئ ض منخفض',
            '333' => 'Oh - طوارئ هوائي',
        ];
        
        return $descriptions[$workType] ?? 'نوع عمل غير محدد';
    }
}