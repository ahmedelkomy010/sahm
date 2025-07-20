<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderFile;
use App\Models\WorkOrderInstallation;
use App\Models\DailyInstallation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

            // Get the installations data from the request
            $installationsData = $request->input('installations', []);
            $installationDate = $request->input('installation_date');
            
            // Log the received data for debugging
            Log::info('Received installations data:', [
                'data' => $installationsData,
                'date' => $installationDate
            ]);

            // Check if we have valid data
            $hasValidData = false;
            $processedData = [];
            
            foreach ($installationsData as $type => $data) {
                // Skip non-installation data and ensure it's array with required fields
                if (!is_array($data) || !isset($data['price']) || !isset($data['quantity']) || !isset($data['total'])) {
                    continue;
                }

                $price = (float) $data['price'];
                $quantity = (float) $data['quantity'];
                $total = (float) $data['total'];

                if ($price > 0 && $quantity > 0) {
                    $hasValidData = true;
                    $processedData[$type] = [
                        'price' => $price,
                        'quantity' => $quantity,
                        'total' => $total,
                        'installation_date' => $installationDate
                    ];
                }
            }

            if (!$hasValidData) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد بيانات صحيحة للحفظ'
                ], 400);
            }

            // Try to save to work_order_installations table
            try {
                // Delete existing installations for this work order and date
                if ($installationDate) {
                    DB::table('work_order_installations')
                        ->where('work_order_id', $workOrder->id)
                        ->where('installation_date', $installationDate)
                        ->delete();
                }

                // Save new installations
                foreach ($processedData as $type => $data) {
                    DB::table('work_order_installations')->insert([
                        'work_order_id' => $workOrder->id,
                        'installation_type' => $type,
                        'price' => $data['price'],
                        'quantity' => $data['quantity'],
                        'total' => $data['total'],
                        'installation_date' => $data['installation_date'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                Log::info("Saved installations to work_order_installations table");
                
            } catch (\Exception $e) {
                Log::warning("Error saving to work_order_installations table: " . $e->getMessage());
                throw $e;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ التركيبات بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving installations: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ' . json_encode($request->all()));
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ التركيبات: ' . $e->getMessage()
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

    public function getInstallationsByDate(Request $request, WorkOrder $workOrder)
    {
        try {
            $date = $request->query('date', now()->format('Y-m-d'));
            
            $installations = WorkOrderInstallation::where('work_order_id', $workOrder->id)
                ->where('installation_date', $date)
                ->get();
            
            return response()->json([
                'success' => true,
                'installations' => $installations
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching installations by date: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات'
            ], 500);
        }
    }

    public function getDailyTotals(WorkOrder $workOrder)
    {
        try {
            $today = now()->toDateString();
            Log::info('Calculating daily totals for date: ' . $today);

            // حساب إجمالي الأعمال المدنية
            $civilWorksData = $workOrder->daily_civil_works_data ?? [];
            if (is_string($civilWorksData)) {
                $civilWorksData = json_decode($civilWorksData, true) ?: [];
            }
            
            Log::info('Civil works data:', ['data' => $civilWorksData]);
            
            $civilWorksTotal = 0;
            if (is_array($civilWorksData)) {
                foreach ($civilWorksData as $item) {
                    // التحقق من التاريخ بطرق مختلفة
                    $itemDate = null;
                    if (isset($item['date'])) {
                        $itemDate = substr($item['date'], 0, 10);
                    } elseif (isset($item['work_date'])) {
                        $itemDate = substr($item['work_date'], 0, 10);
                    } elseif (isset($item['created_at'])) {
                        $itemDate = substr($item['created_at'], 0, 10);
                    }
                    
                    Log::info('Processing civil works item:', [
                        'item' => $item,
                        'itemDate' => $itemDate,
                        'today' => $today
                    ]);
                    
                    if ($itemDate === $today) {
                        $total = 0;
                        if (isset($item['total'])) {
                            $total = floatval($item['total']);
                        } elseif (isset($item['quantity']) && isset($item['price'])) {
                            $total = floatval($item['quantity']) * floatval($item['price']);
                        }
                        $civilWorksTotal += $total;
                        
                        Log::info('Added to civil works total:', [
                            'item_total' => $total,
                            'running_total' => $civilWorksTotal
                        ]);
                    }
                }
            }

            // حساب إجمالي التركيبات
            $installationsTotal = $workOrder->installations()
                ->whereDate('installation_date', $today)
                ->sum('total');

            Log::info('Installations total:', ['total' => $installationsTotal]);

            // حساب إجمالي الأعمال الكهربائية
            $electricalWorksData = $workOrder->daily_electrical_works_data ?? $workOrder->electrical_works_data ?? $workOrder->electrical_works ?? [];
            if (is_string($electricalWorksData)) {
                $electricalWorksData = json_decode($electricalWorksData, true) ?: [];
            }
            
            Log::info('Electrical works data:', ['data' => $electricalWorksData]);
            
            $electricalWorksTotal = 0;
            if (is_array($electricalWorksData)) {
                foreach ($electricalWorksData as $item) {
                    // التحقق من التاريخ بطرق مختلفة
                    $itemDate = null;
                    if (isset($item['work_date'])) {
                        $itemDate = substr($item['work_date'], 0, 10);
                    } elseif (isset($item['date'])) {
                        $itemDate = substr($item['date'], 0, 10);
                    } elseif (isset($item['created_at'])) {
                        $itemDate = substr($item['created_at'], 0, 10);
                    }
                    
                    Log::info('Processing electrical works item:', [
                        'item' => $item,
                        'itemDate' => $itemDate,
                        'today' => $today
                    ]);
                    
                    if ($itemDate === $today) {
                        if (isset($item['total'])) {
                            $electricalWorksTotal += floatval($item['total']);
                        } elseif (isset($item['length']) && isset($item['price'])) {
                            $electricalWorksTotal += floatval($item['length']) * floatval($item['price']);
                        }
                        
                        Log::info('Added to electrical works total:', [
                            'item_total' => isset($item['total']) ? floatval($item['total']) : (floatval($item['length'] ?? 0) * floatval($item['price'] ?? 0)),
                            'running_total' => $electricalWorksTotal
                        ]);
                    }
                }
            }

            // حساب الإجمالي الكلي
            $grandTotal = $civilWorksTotal + $installationsTotal + $electricalWorksTotal;

            Log::info('Final totals:', [
                'civil_works_total' => $civilWorksTotal,
                'installations_total' => $installationsTotal,
                'electrical_works_total' => $electricalWorksTotal,
                'grand_total' => $grandTotal
            ]);

            return response()->json([
                'success' => true,
                'civil_works_total' => $civilWorksTotal,
                'installations_total' => $installationsTotal,
                'electrical_works_total' => $electricalWorksTotal,
                'grand_total' => $grandTotal,
                'date' => $today
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating daily totals: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حساب الإجماليات اليومية'
            ], 500);
        }
    }

    public function getDailyInstallationsSummary(Request $request, WorkOrder $workOrder)
    {
        try {
            $date = $request->query('date', now()->format('Y-m-d'));
            
            // البحث عن البيانات اليومية أولاً
            $dailyInstallation = DailyInstallation::where('work_order_id', $workOrder->id)
                ->where('work_date', $date)
                ->first();
            
            if ($dailyInstallation) {
                // إذا وجدت بيانات يومية، اعرضها
                $installations = collect($dailyInstallation->installation_data)->map(function ($installation) {
                    return [
                        'installation_type' => $installation['installation_type'] ?? 'غير محدد',
                        'quantity' => $installation['quantity'] ?? 0,
                        'total' => $installation['total'] ?? 0,
                        'created_at' => $dailyInstallation->created_at
                    ];
                });
            } else {
                // إذا لم توجد بيانات يومية، اعرض البيانات من الجدول القديم
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
            }
            
            return response()->json([
                'status' => 'success',
                'installations' => $installations,
                'has_daily_data' => $dailyInstallation !== null
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching daily installations summary: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب بيانات التركيبات اليومية'
            ], 500);
        }
    }

    /**
     * حفظ بيانات التركيبات اليومية
     */
    public function saveDailyInstallations(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'work_date' => 'required|date',
                'installation_data' => 'required|array',
                'notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            // حساب الإجماليات
            $totalAmount = 0;
            $totalItems = 0;
            $installationData = [];

            foreach ($request->installation_data as $type => $data) {
                if (isset($data['quantity']) && $data['quantity'] > 0) {
                    $installationData[] = [
                        'installation_type' => $type,
                        'quantity' => floatval($data['quantity']),
                        'price' => floatval($data['price'] ?? 0),
                        'total' => floatval($data['total'] ?? 0)
                    ];
                    $totalAmount += floatval($data['total'] ?? 0);
                    $totalItems += intval($data['quantity'] ?? 0);
                }
            }

            // حفظ أو تحديث البيانات اليومية
            $dailyInstallation = DailyInstallation::updateOrCreate(
                [
                    'work_order_id' => $workOrder->id,
                    'work_date' => $request->work_date
                ],
                [
                    'installation_data' => $installationData,
                    'total_amount' => $totalAmount,
                    'total_items' => $totalItems,
                    'user_id' => Auth::id(),
                    'notes' => $request->notes
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حفظ البيانات اليومية بنجاح',
                'data' => [
                    'id' => $dailyInstallation->id,
                    'work_date' => $dailyInstallation->work_date->format('Y-m-d'),
                    'total_amount' => $dailyInstallation->total_amount,
                    'total_items' => $dailyInstallation->total_items
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving daily installations: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب بيانات التركيبات اليومية
     */
    public function getDailyInstallations(Request $request, WorkOrder $workOrder)
    {
        try {
            $date = $request->query('date', now()->format('Y-m-d'));
            
            $dailyInstallation = DailyInstallation::where('work_order_id', $workOrder->id)
                ->where('work_date', $date)
                ->first();
            
            if ($dailyInstallation) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'id' => $dailyInstallation->id,
                        'work_date' => $dailyInstallation->work_date->format('Y-m-d'),
                        'installation_data' => $dailyInstallation->installation_data,
                        'total_amount' => $dailyInstallation->total_amount,
                        'total_items' => $dailyInstallation->total_items,
                        'notes' => $dailyInstallation->notes,
                        'created_at' => $dailyInstallation->created_at->format('Y-m-d H:i:s')
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'data' => null,
                    'message' => 'لا توجد بيانات لهذا التاريخ'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching daily installations: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب البيانات'
            ], 500);
        }
    }

    /**
     * حذف بيانات التركيبات اليومية
     */
    public function deleteDailyInstallation($id)
    {
        try {
            $dailyInstallation = DailyInstallation::findOrFail($id);
            $dailyInstallation->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف البيانات اليومية بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting daily installation: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف البيانات'
            ], 500);
        }
    }

    /**
     * جلب جميع البيانات اليومية لأمر عمل معين
     */
    public function getAllDailyInstallations(WorkOrder $workOrder)
    {
        try {
            $dailyInstallations = DailyInstallation::where('work_order_id', $workOrder->id)
                ->orderBy('work_date', 'desc')
                ->get()
                ->map(function ($installation) {
                    return [
                        'id' => $installation->id,
                        'work_date' => $installation->work_date->format('Y-m-d'),
                        'formatted_date' => $installation->work_date->format('d-m-Y'),
                        'total_amount' => $installation->total_amount,
                        'total_items' => $installation->total_items,
                        'notes' => $installation->notes,
                        'created_at' => $installation->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $dailyInstallations
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching all daily installations: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب البيانات'
            ], 500);
        }
    }

    public function uploadInstallationsImages(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:30720' // 30MB max
            ]);

            if (!$request->hasFile('images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم اختيار أي صور للرفع'
                ], 400);
            }

            $uploadedImages = [];
            $currentImages = $workOrder->installations_images ?? [];

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('work_orders/' . $workOrder->id . '/installations', $filename, 'public');
                $uploadedImages[] = $path;
            }

            // Update work order with new images
            $workOrder->update([
                'installations_images' => array_merge($currentImages, $uploadedImages)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصور بنجاح',
                'images' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading installation images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الصور: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteInstallationImage(Request $request, WorkOrder $workOrder, $index)
    {
        try {
            $images = $workOrder->installations_images ?? [];

            if (!isset($images[$index])) {
                return response()->json([
                    'success' => false,
                    'message' => 'الصورة غير موجودة'
                ], 404);
            }

            $imagePath = $images[$index];

            // Delete the physical file
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Remove the image from the array
            unset($images[$index]);
            $images = array_values($images); // Reindex array

            // Update work order with remaining images
            $workOrder->update([
                'installations_images' => $images
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting installation image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInstallationImages(WorkOrder $workOrder)
    {
        try {
            $images = $workOrder->installations_images ?? [];
            
            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting installation images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الصور'
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

    /**
     * عرض صفحة الإنتاجية
     */
    public function productivity(WorkOrder $workOrder)
    {
        return view('admin.work_orders.productivity', compact('workOrder'));
    }

    /**
     * حساب الإنتاجية لفترة محددة
     */
    private function getArabicInstallationType($type) {
        $translations = [
            'installation_1600' => 'تركيب 1600',
            'installation_3000' => 'تركيب 3000',
            'aluminum_500_3_connection_13kv' => 'تركيب الومنيوم 500×3 جهد 13ك.ف',
            'copper_500_3_connection_13kv' => 'تركيب نحاس 500×3 جهد 13ك.ف',
            'copper_300_3_connection_13kv' => 'تركيب نحاس 300×3 جهد 13ك.ف',
            'copper_185_3_connection_13kv' => 'تركيب نحاس 185×3 جهد 13ك.ف',
            'copper_95_3_connection_13kv' => 'تركيب نحاس 95×3 جهد 13ك.ف',
            'copper_35_3_connection_13kv' => 'تركيب نحاس 35×3 جهد 13ك.ف',
            'aluminum_300_3_connection_13kv' => 'تركيب الومنيوم 300×3 جهد 13ك.ف',
            'aluminum_185_3_connection_13kv' => 'تركيب الومنيوم 185×3 جهد 13ك.ف',
            'aluminum_95_3_connection_13kv' => 'تركيب الومنيوم 95×3 جهد 13ك.ف',
            'aluminum_35_3_connection_13kv' => 'تركيب الومنيوم 35×3 جهد 13ك.ف'
        ];

        return $translations[$type] ?? $type;
    }

    private function getArabicExcavationType($type) {
        $translations = [
            'unsurfaced_soil' => 'حفرية ترابية غير مسفلتة',
            'surfaced_soil' => 'حفرية ترابية مسفلتة',
            'unsurfaced_rock' => 'حفرية صخرية غير مسفلتة',
            'surfaced_rock' => 'حفرية صخرية مسفلتة',
            'manual_excavation' => 'حفرية يدوية',
            'mechanical_excavation' => 'حفرية ميكانيكية'
        ];

        return $translations[$type] ?? $type;
    }

    public function getProductivityReport(Request $request, WorkOrder $workOrder)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تحديد تاريخ البداية والنهاية'
                ], 400);
            }

            // حساب الأعمال المدنية
            $civilWorksData = $workOrder->daily_civil_works_data ?? [];
            if (is_string($civilWorksData)) {
                $civilWorksData = json_decode($civilWorksData, true) ?: [];
            }

            $civilWorksReport = [
                'total_amount' => 0,
                'total_quantity' => 0,
                'items_count' => 0,
                'details' => []
            ];

            if (is_array($civilWorksData)) {
                foreach ($civilWorksData as $item) {
                    $itemDate = null;
                    if (isset($item['date'])) {
                        $itemDate = substr($item['date'], 0, 10);
                    } elseif (isset($item['work_date'])) {
                        $itemDate = substr($item['work_date'], 0, 10);
                    } elseif (isset($item['created_at'])) {
                        $itemDate = substr($item['created_at'], 0, 10);
                    }

                    if ($itemDate && $itemDate >= $startDate && $itemDate <= $endDate) {
                        $total = floatval($item['total'] ?? 0);
                        $length = floatval($item['length'] ?? 0);

                        $civilWorksReport['total_amount'] += $total;
                        $civilWorksReport['total_quantity'] += $length;
                        $civilWorksReport['items_count']++;
                        
                        $civilWorksReport['details'][] = [
                            'date' => $itemDate,
                            'excavation_type' => $this->getArabicExcavationType($item['excavation_type'] ?? $item['work_type'] ?? ''),
                            'cable_type' => $item['work_type'] ?? $item['title'] ?? $item['cable_type'] ?? 'غير محدد',
                            'length' => $length,
                            'price' => floatval($item['price'] ?? 0),
                            'total' => $total
                        ];
                    }
                }
            }

            // حساب التركيبات
            $installationsReport = [
                'total_amount' => 0,
                'total_quantity' => 0,
                'items_count' => 0,
                'details' => []
            ];

            $installations = $workOrder->installations()
                ->whereBetween('installation_date', [$startDate, $endDate])
                ->get();

            foreach ($installations as $installation) {
                $installationsReport['total_amount'] += $installation->total;
                $installationsReport['total_quantity'] += $installation->quantity;
                $installationsReport['items_count']++;
                
                $installationsReport['details'][] = [
                    'date' => $installation->installation_date,
                    'type' => $this->getArabicInstallationType($installation->installation_type),
                    'quantity' => $installation->quantity,
                    'price' => $installation->price,
                    'total' => $installation->total
                ];
            }

            // حساب الأعمال الكهربائية
            $electricalWorksData = $workOrder->daily_electrical_works_data ?? $workOrder->electrical_works_data ?? $workOrder->electrical_works ?? [];
            if (is_string($electricalWorksData)) {
                $electricalWorksData = json_decode($electricalWorksData, true) ?: [];
            }

            $electricalWorksReport = [
                'total_amount' => 0,
                'total_length' => 0,
                'items_count' => 0,
                'details' => []
            ];

            if (is_array($electricalWorksData)) {
                foreach ($electricalWorksData as $item) {
                    $itemDate = null;
                    if (isset($item['work_date'])) {
                        $itemDate = substr($item['work_date'], 0, 10);
                    } elseif (isset($item['date'])) {
                        $itemDate = substr($item['date'], 0, 10);
                    } elseif (isset($item['created_at'])) {
                        $itemDate = substr($item['created_at'], 0, 10);
                    }

                    if ($itemDate && $itemDate >= $startDate && $itemDate <= $endDate) {
                        $total = floatval($item['total'] ?? 0);
                        $length = floatval($item['length'] ?? 0);

                        $electricalWorksReport['total_amount'] += $total;
                        $electricalWorksReport['total_length'] += $length;
                        $electricalWorksReport['items_count']++;

                        $electricalWorksReport['details'][] = [
                            'date' => $itemDate,
                            'item_name' => $item['item_name'] ?? $item['work_type'] ?? 'غير محدد',
                            'length' => $length,
                            'price' => floatval($item['price'] ?? 0),
                            'total' => $total
                        ];
                    }
                }
            }

            // حساب الإجمالي العام
            $grandTotal = $civilWorksReport['total_amount'] + $installationsReport['total_amount'] + $electricalWorksReport['total_amount'];

            return response()->json([
                'success' => true,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'civil_works' => $civilWorksReport,
                'installations' => $installationsReport,
                'electrical_works' => $electricalWorksReport,
                'grand_total' => $grandTotal
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating productivity report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء تقرير الإنتاجية'
            ], 500);
        }
    }
} 