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
        
        // فلتر حالة التنفيذ - يدعم اختيار متعدد
        if ($request->filled('execution_status')) {
            $executionStatuses = $request->execution_status;
            if (is_array($executionStatuses)) {
                $query->whereIn('execution_status', $executionStatuses);
            } else {
                $query->where('execution_status', $executionStatuses);
            }
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
     * عرض أوامر العمل بحالة "جاري العمل بالموقع" - الرياض
     */
    public function statusInProgressRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "جاري العمل بالموقع" (status = 1) - الرياض
        $workOrders = WorkOrder::where('execution_status', 1)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 1)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-inprogress-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "جاري العمل بالموقع" - المدينة المنورة
     */
    public function statusInProgressMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "جاري العمل بالموقع" (status = 1) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 1)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 1)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-inprogress-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم التنفيذ بالموقع" - الرياض
     */
    public function statusExecutedRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم التنفيذ بالموقع" (status = 2) - الرياض
        $workOrders = WorkOrder::where('execution_status', 2)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 2)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-executed-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم التنفيذ بالموقع" - المدينة المنورة
     */
    public function statusExecutedMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم التنفيذ بالموقع" (status = 2) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 2)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 2)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-executed-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم تسليم 155 - جاري إصدار شهادة الإنجاز" - الرياض
     */
    public function statusDelivery155Riyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم تسليم 155 - جاري إصدار شهادة الإنجاز" (status = 3) - الرياض
        $workOrders = WorkOrder::where('execution_status', 3)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 3)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-delivery155-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم تسليم 155 - جاري إصدار شهادة الإنجاز" - المدينة المنورة
     */
    public function statusDelivery155Madinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم تسليم 155 - جاري إصدار شهادة الإنجاز" (status = 3) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 3)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 3)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-delivery155-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الأولى وجاري الصرف" - الرياض
     */
    public function statusFirstExtractRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الأولى وجاري الصرف" (status = 4) - الرياض
        $workOrders = WorkOrder::where('execution_status', 4)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 4)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-firstextract-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الأولى وجاري الصرف" - المدينة المنورة
     */
    public function statusFirstExtractMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الأولى وجاري الصرف" (status = 4) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 4)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 4)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-firstextract-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم صرف مستخلص الدفعة الجزئية الأولى" - الرياض
     */
    public function statusPaidFirstRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم صرف مستخلص الدفعة الجزئية الأولى" (status = 5) - الرياض
        $workOrders = WorkOrder::where('execution_status', 5)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 5)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-paidfirst-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم صرف مستخلص الدفعة الجزئية الأولى" - المدينة المنورة
     */
    public function statusPaidFirstMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم صرف مستخلص الدفعة الجزئية الأولى" (status = 5) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 5)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 5)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-paidfirst-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم إصدار شهادة الإنجاز" - الرياض
     */
    public function statusCertificateRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم إصدار شهادة الإنجاز" (status = 8) - الرياض
        $workOrders = WorkOrder::where('execution_status', 8)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 8)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-certificate-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم إصدار شهادة الإنجاز" - المدينة المنورة
     */
    public function statusCertificateMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم إصدار شهادة الإنجاز" (status = 8) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 8)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 8)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-certificate-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف" - الرياض
     */
    public function statusSecondExtractRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف" (status = 6) - الرياض
        $workOrders = WorkOrder::where('execution_status', 6)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 6)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-secondextract-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف" - المدينة المنورة
     */
    public function statusSecondExtractMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "إعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف" (status = 6) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 6)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 6)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-secondextract-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم إعداد المستخلص الكلي وجاري الصرف" - الرياض
     */
    public function statusTotalExtractRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم إعداد المستخلص الكلي وجاري الصرف" (status = 10) - الرياض
        $workOrders = WorkOrder::where('execution_status', 10)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 10)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-totalextract-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "تم إعداد المستخلص الكلي وجاري الصرف" - المدينة المنورة
     */
    public function statusTotalExtractMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "تم إعداد المستخلص الكلي وجاري الصرف" (status = 10) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 10)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 10)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-totalextract-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "منتهي تم الصرف" - الرياض
     */
    public function statusCompletedRiyadh(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "منتهي تم الصرف" (status = 7) - الرياض
        $workOrders = WorkOrder::where('execution_status', 7)
            ->where('city', 'الرياض')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 7)
            ->where('city', 'الرياض')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-completed-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل بحالة "منتهي تم الصرف" - المدينة المنورة
     */
    public function statusCompletedMadinah(Request $request)
    {
        // جلب عدد النتائج من الطلب
        $perPage = $request->input('per_page', 20);
        
        // جلب أوامر العمل بحالة "منتهي تم الصرف" (status = 7) - المدينة المنورة
        $workOrders = WorkOrder::where('execution_status', 7)
            ->where('city', 'المدينة المنورة')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('execution_status', 7)
            ->where('city', 'المدينة المنورة')
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.status-completed-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض نتائج المسح - الرياض (الأوامر التي تحتاج للمسح)
     */
    public function surveyResultsRiyadh()
    {
        // جلب أوامر العمل التي لم يتم مسحها - الرياض
        // فقط الأوامر التي ليس لديها surveys أو surveys بدون ملفات
        $workOrders = WorkOrder::where('city', 'الرياض')
            ->where(function($query) {
                $query->whereDoesntHave('surveys')
                      ->orWhereHas('surveys', function($q) {
                          $q->whereDoesntHave('files');
                      });
            })
            ->with(['surveys.files'])  // تحميل العلاقات مسبقاً لتحسين الأداء
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('city', 'الرياض')
            ->where(function($query) {
                $query->whereDoesntHave('surveys')
                      ->orWhereHas('surveys', function($q) {
                          $q->whereDoesntHave('files');
                      });
            })
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.survey-results-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض نتائج المسح - المدينة المنورة (الأوامر التي تحتاج للمسح)
     */
    public function surveyResultsMadinah()
    {
        // جلب أوامر العمل التي لم يتم مسحها - المدينة المنورة
        // فقط الأوامر التي ليس لديها surveys أو surveys بدون ملفات
        $workOrders = WorkOrder::where('city', 'المدينة المنورة')
            ->where(function($query) {
                $query->whereDoesntHave('surveys')
                      ->orWhereHas('surveys', function($q) {
                          $q->whereDoesntHave('files');
                      });
            })
            ->with(['surveys.files'])  // تحميل العلاقات مسبقاً لتحسين الأداء
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('city', 'المدينة المنورة')
            ->where(function($query) {
                $query->whereDoesntHave('surveys')
                      ->orWhereHas('surveys', function($q) {
                          $q->whereDoesntHave('files');
                      });
            })
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.survey-results-madinah', compact('workOrders', 'totalValue'));
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
                'images.*' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200', // 10MB max
            ]);

            if (!$request->hasFile('images')) {
                Log::warning('No files found in request');
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم اختيار أي ملفات للرفع'
                ], 400);
            }

            Log::info('Found ' . count($request->file('images')) . ' files to upload');

            $uploadedImages = [];
            foreach ($request->file('images') as $index => $image) {
                Log::info("Processing file {$index}: " . $image->getClientOriginalName());
                
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $originalName = $image->getClientOriginalName();
                
                // حفظ الملف
                $path = $image->storeAs('work_orders/' . $workOrder->id . '/execution', $filename, 'public');
                Log::info("File stored at path: {$path}");
                
                // التحقق من وجود الملف
                if (!Storage::disk('public')->exists($path)) {
                    Log::error("File was not saved properly: {$path}");
                    continue;
                }
                
                // إنشاء سجل للملف
                $file = $workOrder->files()->create([
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'file_path' => $path,
                    'file_type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'file_category' => 'execution_images',
                ]);

                Log::info("Database record created with ID: {$file->id}");

                $isPdf = strtolower($image->getClientOriginalExtension()) === 'pdf';
                
                $uploadedImages[] = [
                    'id' => $file->id,
                    'name' => $originalName,
                    'path' => Storage::url($path),
                    'size' => $this->formatFileSize($file->file_size),
                    'created_at' => $file->created_at->format('Y-m-d H:i:s'),
                    'is_pdf' => $isPdf
                ];
            }

            Log::info('Successfully uploaded ' . count($uploadedImages) . ' files');

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الملفات بنجاح',
                'images' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading execution files: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage()
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

    /**
     * حفظ أو تحديث ملاحظات التنفيذ اليومية
     */
    public function saveDailyExecutionNote(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'execution_date' => 'required|date',
                'notes' => 'required|string|max:2000'
            ]);

            $executionDate = $request->execution_date;
            $notes = $request->notes;

            // حفظ أو تحديث الملاحظة
            $dailyNote = \App\Models\DailyExecutionNote::updateOrCreate(
                [
                    'work_order_id' => $workOrder->id,
                    'execution_date' => $executionDate
                ],
                [
                    'notes' => $notes,
                    'created_by' => auth()->id()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ ملاحظات التنفيذ بنجاح',
                'note_id' => $dailyNote->id,
                'execution_date' => $executionDate
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الملاحظات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحميل ملاحظات التنفيذ لتاريخ محدد
     */
    public function getDailyExecutionNote(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'execution_date' => 'required|date'
            ]);

            $executionDate = $request->execution_date;
            $dailyNote = $workOrder->getDailyExecutionNoteForDate($executionDate);

            return response()->json([
                'success' => true,
                'note' => $dailyNote ? $dailyNote->notes : '',
                'execution_date' => $executionDate,
                'has_note' => (bool) $dailyNote
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل الملاحظات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث أمر العمل
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        try {
            $validated = $request->validate([
                'order_number' => 'required|string|max:255',
                'work_type' => 'required|string|max:999',
                'work_description' => 'required|string',
                'approval_date' => 'required|date',
                'subscriber_name' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'municipality' => 'nullable|string|max:255',
                'office' => 'nullable|string|max:255',
                'station_number' => 'nullable|string|max:255',
                'consultant_name' => 'nullable|string|max:255',
                'execution_status' => 'required|in:1,2,3,4,5,6,7,8,9,10',
                'manual_days' => 'required|integer|min:0',
            ]);

            // إذا تم تغيير حالة التنفيذ، احفظ تاريخ التغيير
            if (isset($validated['execution_status']) && $validated['execution_status'] != $workOrder->execution_status) {
                $validated['execution_status_date'] = now();
            }
            
            $workOrder->update($validated);

            return redirect()->route('admin.work-orders.show', $workOrder)
                ->with('success', 'تم تحديث أمر العمل بنجاح');

        } catch (\Exception $e) {
            Log::error('Error updating work order: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث أمر العمل');
        }
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

    /**
     * الحصول على الإجماليات اليومية
     */
    public function dailyTotals(WorkOrder $workOrder)
    {
        try {
            // حساب إجماليات بنود العمل المنفذة
            $totalExecutedAmount = $workOrder->workOrderItems()
                ->join('work_items', 'work_order_items.work_item_id', '=', 'work_items.id')
                ->sum(\DB::raw('work_order_items.executed_quantity * work_items.unit_price'));

            return response()->json([
                'success' => true,
                'civil_works_total' => 0,
                'installations_total' => 0,
                'electrical_works_total' => 0,
                'grand_total' => $totalExecutedAmount ?: 0
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in dailyTotals: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حساب الإجماليات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث عن أمر العمل بالرقم وإرجاع البيانات
     */
    public function searchByOrderNumber($orderNumber)
    {
        try {
            $workOrder = WorkOrder::where('order_number', $orderNumber)->first();
            
            if (!$workOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على أمر عمل بهذا الرقم'
                ]);
            }

            // إرجاع البيانات المطلوبة
            return response()->json([
                'success' => true,
                'data' => [
                    'work_type' => $workOrder->work_type,
                    'work_description' => $workOrder->work_description,
                    'approval_date' => $workOrder->approval_date ? $workOrder->approval_date->format('Y-m-d') : '',
                    'manual_days' => $workOrder->manual_days,
                    'subscriber_name' => $workOrder->subscriber_name,
                    'district' => $workOrder->district,
                    'municipality' => $workOrder->municipality,
                    'station_number' => $workOrder->station_number,
                    'consultant_name' => $workOrder->consultant_name,
                    'office' => $workOrder->office,
                    'order_value_with_consultant' => $workOrder->order_value_with_consultant,
                    'order_value_without_consultant' => $workOrder->order_value_without_consultant,
                    'execution_status' => $workOrder->execution_status,
                    'city' => $workOrder->city
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in searchByOrderNumber: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * إرسال ملاحظة لمستخدم عن أمر عمل
     */
    public function sendNote(Request $request, WorkOrder $workOrder)
    {
        try {
            // التحقق من صلاحية المشرف
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بهذا الإجراء'
                ], 403);
            }
            
            // التحقق من البيانات المدخلة
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'note' => 'required|string|max:500',
                'send_email' => 'boolean'
            ]);
            
            // الحصول على المستخدم المستهدف
            $targetUser = \App\Models\User::findOrFail($validated['user_id']);
            
            // حفظ الإشعار في قاعدة البيانات
            $notification = \App\Models\Notification::create([
                'user_id' => $targetUser->id,
                'from_user_id' => auth()->id(),
                'work_order_id' => $workOrder->id,
                'type' => 'note',
                'title' => "ملاحظة على أمر العمل رقم {$workOrder->order_number}",
                'message' => $validated['note'],
                'is_read' => false,
            ]);
            
            // تسجيل في الـ Log
            \Log::info('Note sent from admin', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'target_user_id' => $targetUser->id,
                'target_user_name' => $targetUser->name,
                'work_order_id' => $workOrder->id,
                'work_order_number' => $workOrder->order_number,
                'note' => $validated['note'],
                'notification_id' => $notification->id,
                'send_email' => $validated['send_email'] ?? false,
                'timestamp' => now()
            ]);
            
            // إرسال إشعار بالبريد الإلكتروني (اختياري)
            if ($validated['send_email'] ?? false) {
                try {
                    $adminName = auth()->user()->name;
                    \Mail::raw(
                        "السلام عليكم {$targetUser->name},\n\n" .
                        "لديك ملاحظة جديدة من مشرف النظام ({$adminName}) بخصوص أمر العمل رقم: {$workOrder->order_number}\n\n" .
                        "الملاحظة:\n{$validated['note']}\n\n" .
                        "رابط أمر العمل: " . url("/admin/work-orders/{$workOrder->id}") . "\n\n" .
                        "تحياتنا،\nفريق نظام سهم",
                        function ($message) use ($targetUser, $workOrder) {
                            $message->to($targetUser->email)
                                    ->subject("ملاحظة جديدة - أمر العمل رقم {$workOrder->order_number}");
                        }
                    );
                } catch (\Exception $e) {
                    \Log::warning('Failed to send email notification: ' . $e->getMessage());
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الملاحظة بنجاح'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in sendNote: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الملاحظة: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * جلب إشعارات المستخدم الحالي
     */
    public function getNotifications(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $unreadOnly = $request->get('unread_only', false);
            
            $query = \App\Models\Notification::where('user_id', auth()->id())
                ->with(['fromUser', 'workOrder'])
                ->orderBy('created_at', 'desc');
            
            if ($unreadOnly) {
                $query->unread();
            }
            
            $notifications = $query->limit($limit)->get();
            
            // عدد الإشعارات غير المقروءة
            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                ->unread()
                ->count();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'from_user' => $notification->fromUser ? $notification->fromUser->name : 'النظام',
                        'work_order' => $notification->workOrder ? [
                            'id' => $notification->workOrder->id,
                            'order_number' => $notification->workOrder->order_number
                        ] : null
                    ];
                }),
                'unread_count' => $unreadCount
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getNotifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإشعارات'
            ], 500);
        }
    }
    
    /**
     * تعليم إشعار كمقروء
     */
    public function markNotificationAsRead($notificationId)
    {
        try {
            $notification = \App\Models\Notification::where('id', $notificationId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            
            $notification->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'تم تعليم الإشعار كمقروء'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in markNotificationAsRead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الإشعار'
            ], 500);
        }
    }
    
    /**
     * تعليم جميع الإشعارات كمقروءة
     */
    public function markAllNotificationsAsRead()
    {
        try {
            \App\Models\Notification::where('user_id', auth()->id())
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم تعليم جميع الإشعارات كمقروءة'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in markAllNotificationsAsRead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الإشعارات'
            ], 500);
        }
    }

    /**
     * عرض أوامر العمل التي لم يتم رفع ملفات انتهاء العمل - الرياض
     */
    public function completionFilesRiyadh()
    {
        // جلب أوامر العمل التي لا يوجد عليها completion files
        $workOrders = WorkOrder::where('city', 'الرياض')
            ->whereDoesntHave('files', function($q) {
                $q->where('file_category', 'completion_files');
            })
            ->withCount(['files as completion_files_count' => function($q) {
                $q->where('file_category', 'completion_files');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('city', 'الرياض')
            ->whereDoesntHave('files', function($q) {
                $q->where('file_category', 'completion_files');
            })
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.completion-files-riyadh', compact('workOrders', 'totalValue'));
    }

    /**
     * عرض أوامر العمل التي لم يتم رفع ملفات انتهاء العمل - المدينة المنورة
     */
    public function completionFilesMadinah()
    {
        // جلب أوامر العمل التي لا يوجد عليها completion files
        $workOrders = WorkOrder::where('city', 'المدينة المنورة')
            ->whereDoesntHave('files', function($q) {
                $q->where('file_category', 'completion_files');
            })
            ->withCount(['files as completion_files_count' => function($q) {
                $q->where('file_category', 'completion_files');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // حساب إجمالي القيمة المبدئية
        $totalValue = WorkOrder::where('city', 'المدينة المنورة')
            ->whereDoesntHave('files', function($q) {
                $q->where('file_category', 'completion_files');
            })
            ->sum('order_value_without_consultant');
        
        return view('admin.work_orders.completion-files-madinah', compact('workOrders', 'totalValue'));
    }

    /**
     * تصدير أوامر العمل التي تحتاج لرفع ملفات إلى Excel
     */
    public function exportCompletionFiles($city)
    {
        try {
            // زيادة الـ Memory Limit
            ini_set('memory_limit', '2048M');
            set_time_limit(600);
            
            // تحديد اسم المدينة بالعربي
            $cityName = $city === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            // جلب أوامر العمل التي لا يوجد عليها completion files
            $workOrders = \App\Models\WorkOrder::where('city', $cityName)
                ->whereDoesntHave('files', function($q) {
                    $q->where('file_category', 'completion_files');
                })
                ->withCount(['files as completion_files_count' => function($q) {
                    $q->where('file_category', 'completion_files');
                }])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // إنشاء ملف Excel
            $fileName = 'completion_files_needs_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\CompletionFilesExport($workOrders, $cityName),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in exportCompletionFiles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * جلب ملفات انتهاء العمل لأمر معين (API)
     */
    public function getCompletionFiles($id)
    {
        try {
            $workOrder = WorkOrder::findOrFail($id);
            
            $files = WorkOrderFile::where('work_order_id', $id)
                ->where('file_category', 'completion_files')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'files' => $files
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تصدير أوامر العمل التي تحتاج للمسح إلى Excel
     */
    public function exportSurveyNeeds($city)
    {
        try {
            // زيادة الـ Memory Limit
            ini_set('memory_limit', '2048M');
            set_time_limit(600); // 10 minutes
            
            // تحديد اسم المدينة بالعربي
            $cityName = $city === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            \Log::info('Export Survey Needs started', ['city' => $city, 'cityName' => $cityName]);
            
            // جلب أوامر العمل التي تحتاج للمسح (بدون eager loading)
            $workOrders = WorkOrder::where('city', $cityName)
                ->where(function($query) {
                    $query->whereDoesntHave('surveys')
                          ->orWhereHas('surveys', function($q) {
                              $q->whereDoesntHave('files');
                          });
                })
                ->select('id', 'order_number', 'work_type', 'approval_date', 'city', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Load relations بشكل منفصل وأخف
            $workOrders->load(['surveys:id,work_order_id', 'surveys.files:id,survey_id']);
            
            \Log::info('Work orders fetched', ['count' => $workOrders->count()]);
            
            // إنشاء ملف Excel
            $fileName = 'survey_needs_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            \Log::info('Creating Excel export', ['fileName' => $fileName]);
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\SurveyNeedsExport($workOrders, $cityName),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in exportSurveyNeeds: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'city' => $city ?? null
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة المخالفات للرياض
     */
    public function violationsRiyadh()
    {
        $violations = \App\Models\LicenseViolation::with('workOrder')
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'الرياض');
            })
            ->orWhereHas('workOrder', function($q) {
                $q->where('city', 'riyadh');
            })
            ->orderBy('violation_date', 'desc')
            ->paginate(20);

        $violationsCount = \App\Models\LicenseViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->count();

        $totalAmount = \App\Models\LicenseViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->sum('violation_amount');

        return view('admin.quality.violations-riyadh', compact('violations', 'violationsCount', 'totalAmount'));
    }

    /**
     * عرض صفحة المخالفات للمدينة المنورة
     */
    public function violationsMadinah()
    {
        $violations = \App\Models\LicenseViolation::with('workOrder')
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'المدينة المنورة');
            })
            ->orWhereHas('workOrder', function($q) {
                $q->where('city', 'madinah');
            })
            ->orderBy('violation_date', 'desc')
            ->paginate(20);

        $violationsCount = \App\Models\LicenseViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->count();

        $totalAmount = \App\Models\LicenseViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->sum('violation_amount');

        return view('admin.quality.violations-madinah', compact('violations', 'violationsCount', 'totalAmount'));
    }

    /**
     * عرض صفحة التمديدات للرياض
     */
    public function extensionsRiyadh()
    {
        $licenses = \App\Models\License::with(['workOrder', 'extensions'])
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $licensesCount = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->count();

        $extendedCount = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->has('extensions')->count();

        return view('admin.quality.extensions-riyadh', compact('licenses', 'licensesCount', 'extendedCount'));
    }

    /**
     * عرض صفحة التمديدات للمدينة المنورة
     */
    public function extensionsMadinah()
    {
        $licenses = \App\Models\License::with(['workOrder', 'extensions'])
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $licensesCount = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->count();

        $extendedCount = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->has('extensions')->count();

        return view('admin.quality.extensions-madinah', compact('licenses', 'licensesCount', 'extendedCount'));
    }

    /**
     * تصدير التمديدات لمدينة محددة
     */
    public function exportExtensions($city)
    {
        try {
            // زيادة الـ Memory Limit
            ini_set('memory_limit', '2048M');
            set_time_limit(600);
            
            // تحديد اسم المدينة بالعربي
            $cityName = $city === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            // جلب رخص الحفر
            $licenses = \App\Models\License::with(['workOrder', 'extensions'])
                ->whereHas('workOrder', function($q) use ($cityName, $city) {
                    $q->where('city', $cityName)->orWhere('city', $city);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            // إنشاء ملف Excel
            $fileName = 'extensions_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\ExtensionsExport($licenses, $cityName),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in exportExtensions: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة الاختبارات للرياض
     */
    public function inspectionsRiyadh()
    {
        $workOrders = WorkOrder::with(['licenses'])
            ->where(function($q) {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalOrders = WorkOrder::where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->count();

        $ordersWithTests = WorkOrder::where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->whereHas('licenses', function($q) {
            $q->where(function($query) {
                $query->where('total_tests_count', '>', 0)
                      ->orWhereNotNull('lab_tests_data');
            });
        })->count();

        return view('admin.quality.inspections-riyadh', compact('workOrders', 'totalOrders', 'ordersWithTests'));
    }

    /**
     * عرض صفحة الاختبارات للمدينة المنورة
     */
    public function inspectionsMadinah()
    {
        $workOrders = WorkOrder::with(['licenses'])
            ->where(function($q) {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalOrders = WorkOrder::where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->count();

        $ordersWithTests = WorkOrder::where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->whereHas('licenses', function($q) {
            $q->where(function($query) {
                $query->where('total_tests_count', '>', 0)
                      ->orWhereNotNull('lab_tests_data');
            });
        })->count();

        return view('admin.quality.inspections-madinah', compact('workOrders', 'totalOrders', 'ordersWithTests'));
    }

    /**
     * تصدير الاختبارات لمدينة محددة
     */
    public function exportInspections($city)
    {
        try {
            // زيادة الـ Memory Limit
            ini_set('memory_limit', '2048M');
            set_time_limit(600);
            
            // تحديد اسم المدينة بالعربي
            $cityName = $city === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            // جلب أوامر العمل مع الاختبارات
            $workOrders = WorkOrder::with(['licenses'])
                ->where(function($q) use ($cityName, $city) {
                    $q->where('city', $cityName)->orWhere('city', $city);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            // إنشاء ملف Excel
            $fileName = 'inspections_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\InspectionsExport($workOrders, $cityName),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in exportInspections: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * عرض تقارير الاختبارات للرياض
     */
    public function inspectionsReportsRiyadh()
    {
        $licenses = \App\Models\License::with(['workOrder'])
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            })
            ->where(function($q) {
                $q->where('total_tests_count', '>', 0)
                  ->orWhereNotNull('lab_tests_data');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // حساب الإحصائيات
        $totalTests = $licenses->sum('total_tests_count');
        $successfulTests = $licenses->sum('successful_tests_count');
        $failedTests = $licenses->sum('failed_tests_count');

        return view('admin.quality.inspections-reports-riyadh', compact('licenses', 'totalTests', 'successfulTests', 'failedTests'));
    }

    /**
     * عرض تقارير الاختبارات للمدينة المنورة
     */
    public function inspectionsReportsMadinah()
    {
        $licenses = \App\Models\License::with(['workOrder'])
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            })
            ->where(function($q) {
                $q->where('total_tests_count', '>', 0)
                  ->orWhereNotNull('lab_tests_data');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // حساب الإحصائيات
        $totalTests = $licenses->sum('total_tests_count');
        $successfulTests = $licenses->sum('successful_tests_count');
        $failedTests = $licenses->sum('failed_tests_count');

        return view('admin.quality.inspections-reports-madinah', compact('licenses', 'totalTests', 'successfulTests', 'failedTests'));
    }

    /**
     * عرض صفحة الإخلاءات (الرخص التي ليس عليها إخلاء) للرياض
     */
    public function evacuationsRiyadh()
    {
        $licenses = \App\Models\License::with(['workOrder'])
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $licensesCount = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->count();

        $licensesWithEvacuation = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->whereNotNull('evacuation_date')->count();

        return view('admin.quality.evacuations-riyadh', compact('licenses', 'licensesCount', 'licensesWithEvacuation'));
    }

    /**
     * عرض صفحة الإخلاءات (الرخص التي ليس عليها إخلاء) للمدينة المنورة
     */
    public function evacuationsMadinah()
    {
        $licenses = \App\Models\License::with(['workOrder'])
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $licensesCount = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->count();

        $licensesWithEvacuation = \App\Models\License::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->whereNotNull('evacuation_date')->count();

        return view('admin.quality.evacuations-madinah', compact('licenses', 'licensesCount', 'licensesWithEvacuation'));
    }

    /**
     * تصدير بيانات الإخلاءات إلى Excel
     */
    public function exportEvacuations($city)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(600);

        $cityCondition = $city === 'madinah' ? 
            function($q) { $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah'); } :
            function($q) { $q->where('city', 'الرياض')->orWhere('city', 'riyadh'); };

        $licenses = \App\Models\License::with(['workOrder:id,order_number,work_type,city'])
            ->whereHas('workOrder', $cityCondition)
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $city === 'madinah' ? 'المدينة المنورة' : 'الرياض';
        $fileName = 'evacuations_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return \Excel::download(
            new \App\Exports\EvacuationsExport($licenses, $cityName),
            $fileName
        );
    }

    /**
     * عرض صفحة مخالفات السلامة للرياض
     */
    public function safetyViolationsRiyadh()
    {
        $violations = \App\Models\SafetyViolation::with('workOrder')
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            })
            ->orderBy('violation_date', 'desc')
            ->paginate(20);

        $violationsCount = \App\Models\SafetyViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->count();

        $totalAmount = \App\Models\SafetyViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })->sum('violation_amount');

        return view('admin.quality.safety-violations-riyadh', compact('violations', 'violationsCount', 'totalAmount'));
    }

    /**
     * عرض صفحة مخالفات السلامة للمدينة المنورة
     */
    public function safetyViolationsMadinah()
    {
        $violations = \App\Models\SafetyViolation::with('workOrder')
            ->whereHas('workOrder', function($q) {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            })
            ->orderBy('violation_date', 'desc')
            ->paginate(20);

        $violationsCount = \App\Models\SafetyViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->count();

        $totalAmount = \App\Models\SafetyViolation::whereHas('workOrder', function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })->sum('violation_amount');

        return view('admin.quality.safety-violations-madinah', compact('violations', 'violationsCount', 'totalAmount'));
    }

    /**
     * تصدير مخالفات السلامة إلى Excel
     */
    public function exportSafetyViolations($city)
    {
        try {
            // زيادة الـ Memory Limit
            ini_set('memory_limit', '2048M');
            set_time_limit(600);
            
            // تحديد اسم المدينة بالعربي
            $cityName = $city === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            // جلب مخالفات السلامة
            $violations = \App\Models\SafetyViolation::with('workOrder')
                ->whereHas('workOrder', function($q) use ($cityName, $city) {
                    $q->where('city', $cityName)->orWhere('city', $city);
                })
                ->orderBy('violation_date', 'desc')
                ->get();
            
            // إنشاء ملف Excel
            $fileName = 'safety_violations_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\SafetyViolationsExport($violations, $cityName),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in exportSafetyViolations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * تصدير المخالفات العامة إلى Excel
     */
    public function exportViolations($city)
    {
        try {
            // زيادة الـ Memory Limit
            ini_set('memory_limit', '2048M');
            set_time_limit(600);
            
            // تحديد اسم المدينة بالعربي
            $cityName = $city === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
            
            // جلب المخالفات العامة
            $violations = \App\Models\LicenseViolation::with('workOrder')
                ->whereHas('workOrder', function($q) use ($cityName, $city) {
                    $q->where('city', $cityName)->orWhere('city', $city);
                })
                ->orderBy('violation_date', 'desc')
                ->get();
            
            // إنشاء ملف Excel
            $fileName = 'violations_' . $city . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\ViolationsExport($violations, $cityName),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in exportViolations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * عرض أوامر العمل المتأخرة (تجاوزت المدة الزمنية) - الرياض
     */
    public function overdueOrdersRiyadh()
    {
        $workOrders = WorkOrder::where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })
        ->where('execution_status', '!=', 7) // ليست منتهية ومصروفة
        ->whereNotNull('approval_date')
        ->whereRaw('DATEDIFF(NOW(), approval_date) > 30') // تجاوزت 30 يوم مثلاً
        ->orderBy('approval_date', 'asc')
        ->paginate(20);

        $ordersCount = WorkOrder::where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })
        ->where('execution_status', '!=', 7)
        ->whereNotNull('approval_date')
        ->whereRaw('DATEDIFF(NOW(), approval_date) > 30')
        ->count();

        return view('admin.time-management.overdue-riyadh', compact('workOrders', 'ordersCount'));
    }

    /**
     * عرض أوامر العمل المتأخرة (تجاوزت المدة الزمنية) - المدينة
     */
    public function overdueOrdersMadinah()
    {
        $workOrders = WorkOrder::where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })
        ->where('execution_status', '!=', 7)
        ->whereNotNull('approval_date')
        ->whereRaw('DATEDIFF(NOW(), approval_date) > 30')
        ->orderBy('approval_date', 'asc')
        ->paginate(20);

        $ordersCount = WorkOrder::where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })
        ->where('execution_status', '!=', 7)
        ->whereNotNull('approval_date')
        ->whereRaw('DATEDIFF(NOW(), approval_date) > 30')
        ->count();

        return view('admin.time-management.overdue-madinah', compact('workOrders', 'ordersCount'));
    }

    /**
     * تصدير أوامر العمل المتأخرة إلى Excel
     */
    public function exportOverdueOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', '!=', 7)
            ->whereNotNull('approval_date')
            ->whereRaw('DATEDIFF(NOW(), approval_date) > 30')
            ->orderBy('approval_date', 'asc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'overdue-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\OverdueOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل الغير منفذة إلى Excel
     */
    public function exportUnexecutedOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::with(['licenses', 'dailyExecutionNotes'])
            ->where($cityCondition)
            ->where('execution_status', 1) // جاري العمل بالموقع
            ->orderBy('approval_date', 'asc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'unexecuted-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\UnexecutedOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل جاري العمل بالموقع إلى Excel
     */
    public function exportInProgressOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 1) // جاري العمل بالموقع
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'inprogress-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\InProgressOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل تم التنفيذ بالموقع إلى Excel
     */
    public function exportExecutedOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 2) // تم التنفيذ بالموقع
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'executed-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\ExecutedOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل تم تسليم 155 إلى Excel
     */
    public function exportDelivery155Orders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 3) // تم تسليم 155
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'delivery155-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\Delivery155OrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل إعداد مستخلص الدفعة الأولى إلى Excel
     */
    public function exportFirstExtractOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 4) // إعداد مستخلص الدفعة الأولى
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'firstextract-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\FirstExtractOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل تم صرف الدفعة الأولى إلى Excel
     */
    public function exportPaidFirstOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 5) // تم صرف الدفعة الأولى
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'paidfirst-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\PaidFirstOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل شهادة الإنجاز إلى Excel
     */
    public function exportCertificateOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 8) // تم إصدار شهادة الإنجاز
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'certificate-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\CertificateOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل مستخلص الدفعة الثانية إلى Excel
     */
    public function exportSecondExtractOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 6) // إعداد مستخلص الدفعة الثانية
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'secondextract-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\SecondExtractOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل المستخلص الكلي إلى Excel
     */
    public function exportTotalExtractOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 10) // تم إعداد المستخلص الكلي وجاري الصرف
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'totalextract-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\TotalExtractOrdersExport($workOrders), $fileName);
    }

    /**
     * تصدير أوامر العمل منتهي تم الصرف إلى Excel
     */
    public function exportCompletedOrders($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::where($cityCondition)
            ->where('execution_status', 7) // منتهي تم الصرف
            ->orderBy('created_at', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'completed-orders-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\CompletedOrdersExport($workOrders), $fileName);
    }

    /**
     * عرض أوامر العمل الغير منفذة - الرياض
     */
    public function unexecutedOrdersRiyadh()
    {
        $workOrders = WorkOrder::with(['licenses', 'dailyExecutionNotes'])
        ->where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })
        ->where('execution_status', 1) // جاري العمل بالموقع فقط
        ->orderBy('approval_date', 'asc')
        ->paginate(20);

        $ordersCount = WorkOrder::where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })
        ->where('execution_status', 1)
        ->count();

        $totalValue = WorkOrder::where(function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        })
        ->where('execution_status', 1)
        ->sum('order_value_without_consultant');

        return view('admin.time-management.unexecuted-riyadh', compact('workOrders', 'ordersCount', 'totalValue'));
    }

    /**
     * عرض أوامر العمل الغير منفذة - المدينة
     */
    public function unexecutedOrdersMadinah()
    {
        $workOrders = WorkOrder::with(['licenses', 'dailyExecutionNotes'])
        ->where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })
        ->where('execution_status', 1)
        ->orderBy('approval_date', 'asc')
        ->paginate(20);

        $ordersCount = WorkOrder::where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })
        ->where('execution_status', 1)
        ->count();

        $totalValue = WorkOrder::where(function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        })
        ->where('execution_status', 1)
        ->sum('order_value_without_consultant');

        return view('admin.time-management.unexecuted-madinah', compact('workOrders', 'ordersCount', 'totalValue'));
    }

    /**
     * عرض التقرير المفصل - الرياض
     */
    public function detailedReportRiyadh(Request $request)
    {
        $cityCondition = function($q) {
            $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
        };

        // Base query for filtering
        $query = WorkOrder::query()->where($cityCondition);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('approval_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('approval_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Statistics (using filtered query)
        $statsQuery = clone $query;
        
        $overdueCount = (clone $query)
            ->where('execution_status', '!=', 7)
            ->whereNotNull('approval_date')
            ->whereRaw('DATEDIFF(NOW(), approval_date) > 30')
            ->count();

        $unexecutedCount = (clone $query)
            ->where('execution_status', 1)
            ->count();

        $completedCount = (clone $query)
            ->where('execution_status', 7)
            ->count();

        $totalOrders = (clone $query)->count();

        $obstaclesCount = (clone $query)
            ->whereHas('survey', function($q) {
                $q->where('has_obstacles', true);
            })
            ->count();

        // Get paginated results
        $perPage = $request->input('per_page', 20);
        $workOrders = $query->with('survey')
            ->orderBy('approval_date', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        return view('admin.time-management.detailed-report-riyadh', compact(
            'overdueCount', 
            'unexecutedCount', 
            'completedCount', 
            'totalOrders',
            'obstaclesCount',
            'workOrders'
        ));
    }

    /**
     * عرض التقرير المفصل - المدينة
     */
    public function detailedReportMadinah(Request $request)
    {
        $cityCondition = function($q) {
            $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
        };

        // Base query for filtering
        $query = WorkOrder::query()->where($cityCondition);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('approval_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('approval_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Statistics (using filtered query)
        $statsQuery = clone $query;
        
        $overdueCount = (clone $query)
            ->where('execution_status', '!=', 7)
            ->whereNotNull('approval_date')
            ->whereRaw('DATEDIFF(NOW(), approval_date) > 30')
            ->count();

        $unexecutedCount = (clone $query)
            ->where('execution_status', 1)
            ->count();

        $completedCount = (clone $query)
            ->where('execution_status', 7)
            ->count();

        $totalOrders = (clone $query)->count();

        $obstaclesCount = (clone $query)
            ->whereHas('survey', function($q) {
                $q->where('has_obstacles', true);
            })
            ->count();

        // Get paginated results
        $perPage = $request->input('per_page', 20);
        $workOrders = $query->with('survey')
            ->orderBy('approval_date', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        return view('admin.time-management.detailed-report-madinah', compact(
            'overdueCount', 
            'unexecutedCount', 
            'completedCount', 
            'totalOrders',
            'obstaclesCount',
            'workOrders'
        ));
    }

    /**
     * تصدير أوامر العمل التي عليها معوقات إلى Excel
     */
    public function exportObstacles($project)
    {
        $cityCondition = function($q) use ($project) {
            if ($project === 'riyadh') {
                $q->where('city', 'الرياض')->orWhere('city', 'riyadh');
            } else {
                $q->where('city', 'المدينة المنورة')->orWhere('city', 'madinah');
            }
        };

        $workOrders = WorkOrder::with('survey')
            ->where($cityCondition)
            ->orderBy('approval_date', 'desc')
            ->get();

        $cityName = $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة';
        $fileName = 'obstacles-report-' . $project . '-' . now()->format('Y-m-d') . '.xlsx';

        return \Excel::download(new \App\Exports\ObstaclesReportExport($workOrders), $fileName);
    }
} 
