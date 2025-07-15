<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElectricalWorksController extends Controller
{
    protected $electricalItems = [
        'al_500_joint' => 'وصلة 3 × 500 ألمونيوم 13.8kv',
        'al_500_end' => 'نهاية 3 500 ألمونيوم 13.8kv',
        'end_4x70_1kv' => 'نهاية 4 * 70 ألمونيوم 1kv',
        'end_4x185_1kv' => 'نهاية 4 * 185 ألمونيوم 1kv',
        'end_4x300_1kv' => 'نهاية 4 * 300 ألمونيوم 1kv',
        'joint_33kv' => 'وصلة 33kv',
        'end_33kv' => 'نهاية 33kv',
        'bi_metallic_joint_13_8kv' => 'وصلة باي ميتالك 13,8 kv',
        'bi_metallic_joint_1kv' => 'وصلة باي ميتالك 1 kv',
        'lugs_4x70_1kv' => 'لقزات 4x70 ألمونيوم 1 kv',
        'end_1x50_cu_13_8kv' => 'نهاية 1x50 نحاس 13.8 kv',
        'end_3x70_al_13_8kv' => 'نهاية 3x70 ألمونيوم 13,8 kv',
        'tariff_meter' => 'تأريض عداد',
        'tariff_minipillar' => 'تأريض ميني بلر',
        'tariff_equipment' => 'تأريض معدة',
        'tariff_ring' => 'تأريض رنج',
        'tariff_transformer' => 'تأريض محول'
    ];

    public function index(WorkOrder $workOrder)
    {
        try {
            \Log::info('ElectricalWorksController index method called for work order: ' . $workOrder->id);
            
            // تحديث البيانات من قاعدة البيانات
            $workOrder = $workOrder->fresh();
            
            // استرجاع صور الأعمال الكهربائية
            $electricalWorksImages = $workOrder->files()
                ->where('file_category', 'electrical_works')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // تسجيل البيانات المسترجعة للتشخيص
            \Log::info('Retrieved electrical works data for work order ' . $workOrder->id);
            \Log::info('Electrical works data:', ['data' => $workOrder->electrical_works]);
            
            return view('admin.work_orders.electrical_works', [
                'workOrder' => $workOrder,
                'electricalItems' => $this->electricalItems,
                'electricalWorksImages' => $electricalWorksImages,
                'savedElectricalData' => $workOrder->electrical_works // إضافة البيانات المحفوظة
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in ElectricalWorksController index method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في تحميل الصفحة: ' . $e->getMessage());
        }
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        try {
            \Log::info('ElectricalWorksController store method called', [
                'work_order_id' => $workOrder->id,
                'request_data' => $request->all()
            ]);

            // التحقق من صحة البيانات
            $validatedData = $request->validate([
                'electrical_works' => 'nullable|array',
                'electrical_works.*' => 'nullable|array',
                'electrical_works.*.length' => 'nullable|numeric|min:0',
                'electrical_works.*.price' => 'nullable|numeric|min:0',
                'electrical_works.*.total' => 'nullable|numeric|min:0',
            ]);

            // حفظ البيانات في قاعدة البيانات
            $workOrder->update([
                'electrical_works' => $validatedData['electrical_works'] ?? [],
                'electrical_works_last_update' => now()
            ]);

            \Log::info('Electrical works data saved successfully', [
                'work_order_id' => $workOrder->id,
                'data' => $validatedData['electrical_works'] ?? []
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حفظ البيانات بنجاح'
                ]);
            }

            return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');
            
        } catch (\Exception $e) {
            \Log::error('Error saving electrical works data: ' . $e->getMessage(), [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ البيانات');
        }
    }

    public function getDailyData(WorkOrder $workOrder)
    {
        try {
            $dailyData = $workOrder->daily_electrical_works_data ?? [];
            return response()->json([
                'success' => true,
                'data' => $dailyData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات اليومية: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearDailyData(WorkOrder $workOrder)
    {
        try {
            $workOrder->update([
                'daily_electrical_works_data' => [],
                'daily_electrical_works_last_update' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم مسح البيانات اليومية بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء مسح البيانات اليومية: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeImages(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'electrical_works_images.*' => 'required|image|max:30720', // 30MB max
            'electrical_works_images' => 'max:70' // max 70 files
        ]);

        if ($request->hasFile('electrical_works_images')) {
            foreach ($request->file('electrical_works_images') as $image) {
                $path = $image->store('electrical-works', 'public');
                
                $workOrder->files()->create([
                    'file_path' => $path,
                    'file_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                    'original_filename' => $image->getClientOriginalName(),
                    'file_category' => 'electrical_works'
                ]);
            }
        }

        return redirect()->back()->with('success', 'تم رفع الصور بنجاح');
    }

    public function deleteImage(WorkOrder $workOrder, $image)
    {
        $file = $workOrder->files()
            ->where('id', $image)
            ->where('file_category', 'electrical_works')
            ->firstOrFail();
        
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        $file->delete();

        return redirect()->back()->with('success', 'تم حذف صورة الأعمال الكهربائية بنجاح');
    }

    /**
     * حفظ البيانات اليومية للأعمال الكهربائية
     */
    public function saveDailyElectricalWorks(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'electrical_works' => 'required|array',
                'electrical_works.*.item_name' => 'required|string',
                'electrical_works.*.length' => 'required|numeric|min:0',
                'electrical_works.*.price' => 'required|numeric|min:0',
                'electrical_works.*.total' => 'required|numeric|min:0',
                'work_date' => 'required|date'
            ]);

            $workDate = $request->input('work_date');
            $electricalWorks = $request->input('electrical_works');

            // حفظ البيانات في daily_electrical_works_data
            $dailyElectricalWorks = $workOrder->daily_electrical_works_data ?? [];
            
            // إزالة البيانات القديمة لنفس التاريخ
            $dailyElectricalWorks = collect($dailyElectricalWorks)->filter(function ($item) use ($workDate) {
                return $item['work_date'] !== $workDate;
            })->toArray();

            // إضافة البيانات الجديدة
            foreach ($electricalWorks as $work) {
                $dailyElectricalWorks[] = [
                    'item_name' => $work['item_name'],
                    'length' => (float) $work['length'],
                    'price' => (float) $work['price'],
                    'total' => (float) $work['total'],
                    'work_date' => $workDate,
                    'created_at' => now()->toDateTimeString()
                ];
            }

            // حفظ البيانات في قاعدة البيانات
            $workOrder->update([
                'daily_electrical_works_data' => $dailyElectricalWorks,
                'daily_electrical_works_last_update' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ البيانات اليومية بنجاح',
                'data' => $electricalWorks
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving daily electrical works: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على الملخص اليومي للأعمال الكهربائية
     */
    public function getDailySummary(Request $request, WorkOrder $workOrder)
    {
        try {
            $date = $request->query('date', now()->format('Y-m-d'));
            
            // استرجاع البيانات من daily_electrical_works_data
            $dailyElectricalWorks = $workOrder->daily_electrical_works_data ?? [];
            
            // تصفية البيانات حسب التاريخ
            $filteredWorks = collect($dailyElectricalWorks)->filter(function ($item) use ($date) {
                return isset($item['work_date']) && $item['work_date'] === $date;
            })->values();

            return response()->json([
                'success' => true,
                'electrical_works' => $filteredWorks,
                'date' => $date
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting daily electrical works summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الملخص اليومي: ' . $e->getMessage()
            ], 500);
        }
    }
} 