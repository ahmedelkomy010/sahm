<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderFile;
use App\Models\WorkOrderInstallation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    public function installations(WorkOrder $workOrder)
    {
        // قائمة أنواع التركيبات المتاحة
        $installations = [
            'installation_1600' => 'تركيب لوحة منخفض 1600',
            'installation_3000' => 'تركيب لوحة منخفض 3000',
            'ground_installation' => 'تأريض عداد',
            'mini_pillar_installation' => 'تأريض ميني بلر',
            'multiple_installation' => 'تأريض متعدد',
            'aluminum_4x70_1kv' => 'أطراف ألومنيوم 4x70 1 kv',
            'copper_1x50_13kv' => 'نهاية نحاس 1x50 13.8 kv',
            'aluminum_3x70_13kv' => 'نهاية ألومنيوم 3x70 13.8 kv',
            'aluminum_500_3_13kv' => 'نهاية 3 × 500 ألومنيوم 13.8kv',
            'aluminum_500_3_connection_13kv' => 'وصلة 3 × 500 ألومنيوم 13.8kv',
            'aluminum_70_4_1kv' => 'نهاية 4 × 70 ألومنيوم 1kv',
            'aluminum_185_4_1kv' => 'نهاية 4 × 185 ألومنيوم 1kv',
            'aluminum_300_4_1kv' => 'نهاية 4 × 300 ألومنيوم 1kv',
            'connection_33kv' => 'وصلة 33kv',
            'end_33kv' => 'نهاية 33kv'
        ];

        // تحميل التركيبات الحالية
        $currentInstallations = WorkOrderInstallation::where('work_order_id', $workOrder->id)->get();

        return view('admin.work_orders.installations', compact('workOrder', 'installations', 'currentInstallations'));
    }

    public function saveInstallations(Request $request, WorkOrder $workOrder)
    {
        try {
            DB::beginTransaction();

            // Delete existing installations for this work order
            WorkOrderInstallation::where('work_order_id', $workOrder->id)->delete();

            // Save new installations
            foreach ($request->all() as $type => $data) {
                if ($data['price'] > 0 && $data['number'] > 0) {
                    WorkOrderInstallation::create([
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
            Log::error('Error saving installations: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ التركيبات'
            ], 500);
        }
    }

    public function getInstallations(WorkOrder $workOrder)
    {
        $installations = WorkOrderInstallation::where('work_order_id', $workOrder->id)->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $installations
        ]);
    }

    public function getDailyInstallationsSummary(Request $request, WorkOrder $workOrder)
    {
        try {
            $date = $request->query('date', now()->format('Y-m-d'));
            
            $installations = WorkOrderInstallation::where('work_order_id', $workOrder->id)
                ->whereDate('created_at', $date)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($installation) {
                    return [
                        'installation_type' => $installation->installation_type,
                        'quantity' => $installation->quantity,
                        'total' => $installation->total,
                        'created_at' => $installation->created_at
                    ];
                });
            
            return response()->json([
                'status' => 'success',
                'installations' => $installations
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching daily installations summary: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب بيانات التركيبات اليومية'
            ], 500);
        }
    }

    public function uploadInstallationsImages(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'electrical_works_images.*' => 'required|image|mimes:jpeg,png,jpg|max:30720' // 30MB max
            ]);

            $uploadedFiles = [];
            
            if ($request->hasFile('electrical_works_images')) {
                foreach ($request->file('electrical_works_images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('work_orders/' . $workOrder->id . '/installations', $filename, 'public');
                    
                    $file = WorkOrderFile::create([
                        'work_order_id' => $workOrder->id,
                        'file_path' => $path,
                        'original_filename' => $image->getClientOriginalName(),
                        'file_type' => $image->getMimeType(),
                        'file_size' => $image->getSize(),
                        'file_category' => 'installations'
                    ]);
                    
                    $uploadedFiles[] = $file;
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'تم رفع الصور بنجاح',
                'files' => $uploadedFiles
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error uploading installation images: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء رفع الصور'
            ], 500);
        }
    }

    public function deleteInstallationImage($imageId)
    {
        try {
            $file = WorkOrderFile::findOrFail($imageId);
            
            // Delete the physical file
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // Delete the database record
            $file->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الصورة بنجاح'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting installation image: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الصورة'
            ], 500);
        }
    }

    /**
     * حفظ بيانات العمل اليومي للحفريات
     */
    public function saveDailyCivilWorks(Request $request, WorkOrder $workOrder)
    {
        try {
            $dailyWork = $request->input('daily_work', []);
            
            if (empty($dailyWork)) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد بيانات للحفظ'
                ]);
            }

            // إضافة البيانات الجديدة للبيانات الموجودة (بدون حذف)
            $existingData = $workOrder->daily_civil_works_data ? json_decode($workOrder->daily_civil_works_data, true) : [];
            $allData = array_merge($existingData, $dailyWork);

            // حفظ البيانات
            $workOrder->update([
                'daily_civil_works_data' => json_encode($allData),
                'daily_civil_works_last_update' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ بيانات العمل اليومي بنجاح',
                'saved_items' => count($dailyWork)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب بيانات العمل اليومي المحفوظة للحفريات
     */
    public function getDailyCivilWorks(WorkOrder $workOrder)
    {
        try {
            $savedData = $workOrder->daily_civil_works_data ? json_decode($workOrder->daily_civil_works_data, true) : [];

            return response()->json([
                'success' => true,
                'data' => $savedData,
                'count' => count($savedData)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
} 