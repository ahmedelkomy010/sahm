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
            $electricalWorksImages = collect(); // مبسط للآن
            
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
            throw $e;
        }
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        \Log::info('ElectricalWorksController store method called');
        \Log::info('Work Order ID: ' . $workOrder->id);
        
        try {
            // حفظ بيانات الأعمال الكهربائية
            $electricalWorksData = $request->input('electrical_works', []);
            \Log::info('Raw electrical works data:', $electricalWorksData);
            
            // تنظيف البيانات وضمان وجود الحقول الجديدة
            $cleanedData = [];
            foreach ($electricalWorksData as $key => $data) {
                if (isset($data['length']) || isset($data['price']) || isset($data['total'])) {
                    $cleanedData[$key] = [
                        'length' => isset($data['length']) ? (string) floatval($data['length']) : '0',
                        'price' => isset($data['price']) ? (string) floatval($data['price']) : '0',
                        'total' => isset($data['total']) ? (string) floatval($data['total']) : '0'
                    ];
                }
            }
            
            \Log::info('Cleaned electrical works data:', $cleanedData);
            
            $workOrder->update([
                'electrical_works' => $cleanedData
            ]);
            
            \Log::info('Work order updated successfully');
            
            // التحقق من الحفظ
            $workOrder = $workOrder->fresh();
            \Log::info('Saved electrical works data verified:', ['data' => $workOrder->electrical_works]);

            // إذا كان الطلب AJAX، إرجاع استجابة JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حفظ بيانات الأعمال الكهربائية بنجاح'
                ]);
            }

            return redirect()->back()->with('success', 'تم حفظ بيانات الأعمال الكهربائية بنجاح');
                
        } catch (\Exception $e) {
            \Log::error('Error saving electrical works: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ البيانات');
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
                    'category' => 'electrical_works'
                ]);
            }
        }

        return redirect()->back()->with('success', 'تم رفع الصور بنجاح');
    }

    public function deleteImage(WorkOrder $workOrder, $image)
    {
        $file = $workOrder->electricalWorksFiles()
            ->where('id', $image)
            ->firstOrFail();
        
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        $file->delete();

        return redirect()->back()->with('success', 'تم حذف صورة الأعمال الكهربائية بنجاح');
    }
} 