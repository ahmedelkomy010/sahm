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
        // تحديث البيانات من قاعدة البيانات
        $workOrder = $workOrder->fresh();
        
        // تسجيل البيانات المسترجعة للتشخيص
        \Log::info('Retrieved electrical works data for work order ' . $workOrder->id);
        \Log::info('Electrical works data:', ['data' => $workOrder->electrical_works]);
        
        return view('admin.work_orders.electrical_works', [
            'workOrder' => $workOrder,
            'electricalItems' => $this->electricalItems
        ]);
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        try {
            // حفظ بيانات الأعمال الكهربائية
            $electricalWorksData = $request->input('electrical_works', []);
            \Log::info('Saving electrical works data:', ['data' => $electricalWorksData]);
            \Log::info('Full request data:', ['data' => $request->all()]);
            
            // تنظيف البيانات وضمان وجود quantity مع الحفاظ على القيم الرقمية
            foreach ($electricalWorksData as $key => $data) {
                // التأكد من وجود status
                if (!isset($data['status'])) {
                    $electricalWorksData[$key]['status'] = '';
                }
                
                // التأكد من وجود quantity والحفاظ على القيم الرقمية
                if (!isset($data['quantity'])) {
                    $electricalWorksData[$key]['quantity'] = '';
                } else {
                    // تحويل القيمة إلى رقم إذا كانت رقمية، وإلا الاحتفاظ بها كما هي
                    $quantity = trim($data['quantity']);
                    if (is_numeric($quantity) && $quantity !== '') {
                        $electricalWorksData[$key]['quantity'] = (string) intval($quantity);
                    } else {
                        $electricalWorksData[$key]['quantity'] = '';
                    }
                }
                
                \Log::info("Electrical work item $key:", ['data' => $electricalWorksData[$key]]);
            }
            
            $workOrder->update([
                'electrical_works' => $electricalWorksData
            ]);
            
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

    public function deleteImage($workOrder, $image)
    {
        $file = $workOrder->files()->findOrFail($image);
        
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        $file->delete();

        return redirect()->back()->with('success', 'تم حذف الصورة بنجاح');
    }
} 