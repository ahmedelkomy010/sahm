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
            $civilWorksTotal = $workOrder->dailyCivilWorks()
                ->whereDate('work_date', $today)
                ->sum('total_cost');

            Log::info('Civil works total:', ['total' => $civilWorksTotal]);

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
            \Log::info('Saving daily civil works data', [
                'work_order_id' => $workOrder->id,
                'request_data' => $request->all()
            ]);
            
            $dailyWork = $request->input('daily_work', []);
            $requestedWorkDate = $request->input('work_date'); // التاريخ المحدد للعمل
            
            // التحقق من وجود البيانات
            if (empty($dailyWork)) {
                \Log::warning('No data to save', ['work_order_id' => $workOrder->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد بيانات للحفظ'
                ], 400);
            }
            
            \Log::info('Received work date', [
                'work_order_id' => $workOrder->id,
                'requested_work_date' => $requestedWorkDate
            ]);
            
            // فلترة البيانات: حفظ العناصر التي تحتوي على طول وسعر/إجمالي فقط
            $originalCount = count($dailyWork);
            $validItems = [];
            $skippedItems = 0;
            
            foreach ($dailyWork as $index => $item) {
                if (!is_array($item)) {
                    \Log::warning('Skipping non-array item', [
                        'work_order_id' => $workOrder->id,
                        'index' => $index,
                        'type' => gettype($item)
                    ]);
                    $skippedItems++;
                    continue;
                }
                
                $length = (float) ($item['length'] ?? 0);
                $price = (float) ($item['price'] ?? 0);
                $total = (float) ($item['total'] ?? 0);
                
                // شرط الحفظ: يجب وجود طول > 0 و (سعر > 0 أو إجمالي > 0)
                if ($length > 0 && ($price > 0 || $total > 0)) {
                    // استخدام التاريخ المحدد في الطلب أو التاريخ المرسل مع العنصر أو التاريخ الحالي
                    $workDate = $requestedWorkDate ?? $item['work_date'] ?? now()->format('Y-m-d');
                    
                    $validItems[] = [
                        'excavation_type' => $item['excavation_type'] ?? 'غير محدد',
                        'cable_name' => $item['cable_name'] ?? 'كابل ' . ($index + 1),
                        'length' => $length,
                        'width' => (float) ($item['width'] ?? 0),
                        'depth' => (float) ($item['depth'] ?? 0),
                        'volume' => (float) ($item['volume'] ?? 0),
                        'price' => $price,
                        'total' => $total,
                        'work_date' => $workDate,
                        'work_time' => $item['work_time'] ?? now()->format('H:i:s'),
                        'category' => $item['category'] ?? 'excavation',
                        'id' => $item['id'] ?? 'item_' . time() . '_' . $index,
                        'created_at' => now()->toISOString(),
                        'source' => $item['source'] ?? 'user_input'
                    ];
                } else {
                    \Log::info('Skipping item without valid length and price/total', [
                        'work_order_id' => $workOrder->id,
                        'index' => $index,
                        'length' => $length,
                        'price' => $price,
                        'total' => $total
                    ]);
                    $skippedItems++;
                }
            }
            
            // تحديث البيانات للمعالجة
            $dailyWork = $validItems;
            
            \Log::info('Data filtering completed', [
                'work_order_id' => $workOrder->id,
                'original_items' => $originalCount,
                'valid_items' => count($dailyWork),
                'skipped_items' => $skippedItems
            ]);
            
            // التحقق من وجود عناصر صالحة بعد الفلترة
            if (empty($dailyWork)) {
                \Log::warning('No valid items after filtering', ['work_order_id' => $workOrder->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد عناصر صالحة للحفظ (يجب إدخال الطول والسعر أو الإجمالي)',
                    'original_items' => $originalCount,
                    'skipped_items' => $skippedItems
                ], 400);
            }
            
            // التأكد من أن البيانات الواردة array
            if (!is_array($dailyWork)) {
                \Log::error('Daily work data is not an array', [
                    'work_order_id' => $workOrder->id,
                    'data_type' => gettype($dailyWork)
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'تنسيق البيانات غير صحيح'
                ], 400);
            }

            // جلب البيانات الموجودة وتنظيفها
            $existingData = [];
            
            if ($workOrder->daily_civil_works_data) {
                if (is_string($workOrder->daily_civil_works_data)) {
                    $decodedData = json_decode($workOrder->daily_civil_works_data, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedData)) {
                        $existingData = $decodedData;
                        \Log::info('Successfully decoded existing data', [
                            'work_order_id' => $workOrder->id,
                            'existing_count' => count($existingData)
                        ]);
                    } else {
                        \Log::warning('Failed to decode existing JSON data, starting fresh', [
                            'work_order_id' => $workOrder->id,
                            'json_error' => json_last_error_msg()
                        ]);
                        $existingData = [];
                    }
                } elseif (is_array($workOrder->daily_civil_works_data)) {
                    $existingData = $workOrder->daily_civil_works_data;
                } else {
                    \Log::warning('Existing data type is not string or array', [
                        'work_order_id' => $workOrder->id,
                        'data_type' => gettype($workOrder->daily_civil_works_data)
                    ]);
                    $existingData = [];
                }
            }
            
            // التأكد من أن البيانات الموجودة array
            if (!is_array($existingData)) {
                \Log::warning('Existing data converted to empty array', ['work_order_id' => $workOrder->id]);
                $existingData = [];
            }

            // دمج البيانات بطريقة آمنة
            try {
                $allData = array_merge($existingData, $dailyWork);
                \Log::info('Successfully merged data', [
                    'work_order_id' => $workOrder->id,
                    'existing_count' => count($existingData),
                    'new_count' => count($dailyWork),
                    'total_count' => count($allData)
                ]);
            } catch (\Exception $mergeError) {
                \Log::error('Failed to merge arrays', [
                    'work_order_id' => $workOrder->id,
                    'error' => $mergeError->getMessage()
                ]);
                throw new \Exception('فشل في دمج البيانات: ' . $mergeError->getMessage());
            }

            // تحويل إلى JSON بطريقة آمنة
            $jsonData = json_encode($allData, JSON_UNESCAPED_UNICODE);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Failed to encode data to JSON', [
                    'work_order_id' => $workOrder->id,
                    'json_error' => json_last_error_msg()
                ]);
                throw new \Exception('فشل في تحويل البيانات إلى JSON: ' . json_last_error_msg());
            }

            // حفظ في قاعدة البيانات
            $updateData = [
                'daily_civil_works_data' => $jsonData,
                'daily_civil_works_last_update' => now()
            ];
            
            $updateResult = $workOrder->update($updateData);
            
            if (!$updateResult) {
                \Log::error('Failed to update database', ['work_order_id' => $workOrder->id]);
                throw new \Exception('فشل في حفظ البيانات في قاعدة البيانات');
            }
            
            \Log::info('Successfully saved daily civil works data', [
                'work_order_id' => $workOrder->id,
                'saved_items' => count($dailyWork),
                'total_items' => count($allData)
            ]);

            $message = 'تم حفظ بيانات العمل اليومي بنجاح';
            if ($skippedItems > 0) {
                $message .= " (تم حفظ " . count($dailyWork) . " من إجمالي " . $originalCount . " عنصر)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'saved_items' => count($dailyWork),
                'total_items' => count($allData),
                'skipped_items' => $skippedItems,
                'work_order_id' => $workOrder->id,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in saveDailyCivilWorks', [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage(),
                'work_order_id' => $workOrder->id,
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * جلب بيانات العمل اليومي المحفوظة للحفريات
     * دعم اختياري للاستعلام بالتاريخ
     */
    public function getDailyCivilWorks(Request $request, WorkOrder $workOrder)
    {
        try {
            $date = $request->query('date');
            \Log::info('Fetching daily civil works data', [
                'work_order_id' => $workOrder->id,
                'requested_date' => $date
            ]);
            
            $savedData = [];
            
            // محاولة جلب البيانات من daily_civil_works_data أولاً
            if ($workOrder->daily_civil_works_data) {
                if (is_string($workOrder->daily_civil_works_data)) {
                    $decodedData = json_decode($workOrder->daily_civil_works_data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $savedData = $decodedData;
                    } else {
                        \Log::warning('Failed to decode daily_civil_works_data JSON', [
                            'work_order_id' => $workOrder->id,
                            'json_error' => json_last_error_msg()
                        ]);
                    }
                } elseif (is_array($workOrder->daily_civil_works_data)) {
                    $savedData = $workOrder->daily_civil_works_data;
                }
            }
            
            // إذا لم توجد بيانات، جرب excavation_details_table
            if (empty($savedData) && $workOrder->excavation_details_table) {
                if (is_string($workOrder->excavation_details_table)) {
                    $decodedData = json_decode($workOrder->excavation_details_table, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $savedData = $decodedData;
                        \Log::info('Using excavation_details_table as fallback', ['work_order_id' => $workOrder->id]);
                    }
                } elseif (is_array($workOrder->excavation_details_table)) {
                    $savedData = $workOrder->excavation_details_table;
                }
            }
            
            // تأكد من أن البيانات array
            if (!is_array($savedData)) {
                $savedData = [];
            }

            // إذا تم طلب تاريخ محدد، قم بالفلترة
            if ($date) {
                $originalCount = count($savedData);
                $savedData = array_filter($savedData, function($item) use ($date) {
                    $itemDate = $item['work_date'] ?? $item['date'] ?? null;
                    if (!$itemDate && isset($item['created_at'])) {
                        $itemDate = substr($item['created_at'], 0, 10);
                    }
                    return $itemDate === $date;
                });
                // إعادة ترقيم المفاتيح
                $savedData = array_values($savedData);
                
                \Log::info('Filtered data by date', [
                    'work_order_id' => $workOrder->id,
                    'date' => $date,
                    'original_count' => $originalCount,
                    'filtered_count' => count($savedData)
                ]);
            }
            
            \Log::info('Successfully fetched daily civil works data', [
                'work_order_id' => $workOrder->id,
                'data_count' => count($savedData),
                'filtered_by_date' => $date !== null
            ]);

            return response()->json([
                'success' => true,
                'data' => $savedData,
                'count' => count($savedData),
                'work_order_id' => $workOrder->id,
                'date_filter' => $date,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching daily civil works data', [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage(),
                'data' => [],
                'work_order_id' => $workOrder->id,
                'timestamp' => now()->toISOString()
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
            'end_33kv' => 'نهاية 33kv',
            // إضافة المزيد من الترجمات
            'aluminum_500_3_13kv_end' => 'نهاية 3 × 500 ألومنيوم 13.8kv',
            'aluminum_300_3_13kv_end' => 'نهاية 3 × 300 ألومنيوم 13.8kv',
            'aluminum_185_3_13kv_end' => 'نهاية 3 × 185 ألومنيوم 13.8kv',
            'aluminum_95_3_13kv_end' => 'نهاية 3 × 95 ألومنيوم 13.8kv',
            'aluminum_35_3_13kv_end' => 'نهاية 3 × 35 ألومنيوم 13.8kv',
            'copper_500_3_13kv_end' => 'نهاية 3 × 500 نحاس 13.8kv',
            'copper_300_3_13kv_end' => 'نهاية 3 × 300 نحاس 13.8kv',
            'copper_185_3_13kv_end' => 'نهاية 3 × 185 نحاس 13.8kv',
            'copper_95_3_13kv_end' => 'نهاية 3 × 95 نحاس 13.8kv',
            'copper_35_3_13kv_end' => 'نهاية 3 × 35 نحاس 13.8kv',
            'aluminum_300_4_1kv_end' => 'نهاية 4 × 300 ألومنيوم 1kv',
            'aluminum_185_4_1kv_end' => 'نهاية 4 × 185 ألومنيوم 1kv',
            'aluminum_95_4_1kv_end' => 'نهاية 4 × 95 ألومنيوم 1kv',
            'aluminum_35_4_1kv_end' => 'نهاية 4 × 35 ألومنيوم 1kv'
        ];

        // إذا كان النوع موجود في الترجمات، استخدمه
        if (isset($translations[$type])) {
            return $translations[$type];
        }

        // إذا لم يكن موجود، حاول تحسين عرض النص
        $type = str_replace('_', ' ', $type);
        $type = ucwords($type);
        
        return $type;
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
            $civilWorksData = $workOrder->dailyCivilWorks()
                ->whereBetween('work_date', [$startDate, $endDate])
                ->orderBy('work_date', 'asc')
                ->get();

            $civilWorksReport = [
                'total_amount' => $civilWorksData->sum('total_cost'),
                'total_quantity' => $civilWorksData->sum('length'),
                'items_count' => $civilWorksData->count(),
                'details' => $civilWorksData->map(function($item) {
                    return [
                        'date' => $item->work_date->format('Y-m-d'),
                        'excavation_type' => $item->work_type,
                        'cable_type' => $item->cable_type,
                        'length' => $item->length,
                        'price' => $item->unit_price,
                        'total' => $item->total_cost
                        ];
                })->toArray()
            ];

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

    /**
     * جلب بيانات الأعمال المدنية لتاريخ محدد
     */
    public function getDailyCivilWorksByDate(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'date' => 'required|date'
            ]);

            $date = $request->input('date');
            
            \Log::info('Fetching civil works data for specific date', [
                'work_order_id' => $workOrder->id,
                'date' => $date
            ]);

            // استخدام الوظيفة الموجودة مع تمرير التاريخ
            $request->merge(['date' => $date]);
            return $this->getDailyCivilWorks($request, $workOrder);

        } catch (\Exception $e) {
            \Log::error('Error fetching civil works data by date', [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف بيانات الأعمال المدنية لتاريخ محدد
     */
    public function deleteDailyCivilWorksByDate(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'date' => 'required|date'
            ]);

            $date = $request->input('date');
            
            \Log::info('Deleting civil works data for specific date', [
                'work_order_id' => $workOrder->id,
                'date' => $date
            ]);

            DB::beginTransaction();

            // جلب البيانات الحالية
            $savedData = [];
            if ($workOrder->daily_civil_works_data) {
                if (is_string($workOrder->daily_civil_works_data)) {
                    $decodedData = json_decode($workOrder->daily_civil_works_data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $savedData = $decodedData;
                    }
                } elseif (is_array($workOrder->daily_civil_works_data)) {
                    $savedData = $workOrder->daily_civil_works_data;
                }
            }

            if (!is_array($savedData)) {
                $savedData = [];
            }

            $originalCount = count($savedData);

            // فلترة البيانات لإزالة البيانات للتاريخ المحدد
            $filteredData = array_filter($savedData, function($item) use ($date) {
                $itemDate = $item['work_date'] ?? $item['date'] ?? null;
                if (!$itemDate && isset($item['created_at'])) {
                    $itemDate = substr($item['created_at'], 0, 10);
                }
                return $itemDate !== $date;
            });

            // إعادة ترقيم المفاتيح
            $filteredData = array_values($filteredData);
            $deletedCount = $originalCount - count($filteredData);

            if ($deletedCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على بيانات للتاريخ المحدد'
                ], 404);
            }

            // تحويل إلى JSON وحفظ
            $jsonData = json_encode($filteredData, JSON_UNESCAPED_UNICODE);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('فشل في تحويل البيانات إلى JSON: ' . json_last_error_msg());
            }

            $workOrder->update([
                'daily_civil_works_data' => $jsonData,
                'daily_civil_works_last_update' => now()
            ]);

            DB::commit();

            \Log::info('Successfully deleted civil works data for date', [
                'work_order_id' => $workOrder->id,
                'date' => $date,
                'deleted_count' => $deletedCount,
                'remaining_count' => count($filteredData)
            ]);

            return response()->json([
                'success' => true,
                'message' => "تم حذف {$deletedCount} عنصر للتاريخ {$date}",
                'deleted_count' => $deletedCount,
                'remaining_count' => count($filteredData),
                'date' => $date
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error deleting civil works data by date', [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب إحصائيات الأعمال المدنية مجمعة حسب التاريخ
     */
    public function getDailyCivilWorksStatistics(WorkOrder $workOrder)
    {
        try {
            \Log::info('Fetching civil works statistics by date', [
                'work_order_id' => $workOrder->id
            ]);

            $savedData = [];
            
            // جلب البيانات
            if ($workOrder->daily_civil_works_data) {
                if (is_string($workOrder->daily_civil_works_data)) {
                    $decodedData = json_decode($workOrder->daily_civil_works_data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $savedData = $decodedData;
                    }
                } elseif (is_array($workOrder->daily_civil_works_data)) {
                    $savedData = $workOrder->daily_civil_works_data;
                }
            }

            if (!is_array($savedData)) {
                $savedData = [];
            }

            // تجميع البيانات حسب التاريخ
            $statistics = [];
            foreach ($savedData as $item) {
                $itemDate = $item['work_date'] ?? $item['date'] ?? null;
                if (!$itemDate && isset($item['created_at'])) {
                    $itemDate = substr($item['created_at'], 0, 10);
                }
                
                if (!$itemDate) {
                    $itemDate = 'غير محدد';
                }

                if (!isset($statistics[$itemDate])) {
                    $statistics[$itemDate] = [
                        'date' => $itemDate,
                        'count' => 0,
                        'total_length' => 0,
                        'total_amount' => 0,
                        'items' => []
                    ];
                }

                $statistics[$itemDate]['count']++;
                $statistics[$itemDate]['total_length'] += floatval($item['length'] ?? 0);
                $statistics[$itemDate]['total_amount'] += floatval($item['total'] ?? 0);
                
                // إضافة معلومات مختصرة عن كل عنصر
                $statistics[$itemDate]['items'][] = [
                    'excavation_type' => $item['excavation_type'] ?? 'غير محدد',
                    'cable_name' => $item['cable_name'] ?? 'غير محدد',
                    'length' => floatval($item['length'] ?? 0),
                    'total' => floatval($item['total'] ?? 0)
                ];
            }

            // ترتيب النتائج حسب التاريخ
            ksort($statistics);
            $statistics = array_values($statistics);

            \Log::info('Successfully fetched civil works statistics', [
                'work_order_id' => $workOrder->id,
                'dates_count' => count($statistics),
                'total_items' => count($savedData)
            ]);

            return response()->json([
                'success' => true,
                'statistics' => $statistics,
                'dates_count' => count($statistics),
                'total_items' => count($savedData),
                'work_order_id' => $workOrder->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching civil works statistics', [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * نسخ بيانات من تاريخ إلى تاريخ آخر
     */
    public function copyDailyCivilWorksDate(Request $request, WorkOrder $workOrder)
    {
        try {
            $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|different:from_date'
            ]);

            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            
            \Log::info('Copying civil works data between dates', [
                'work_order_id' => $workOrder->id,
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]);

            DB::beginTransaction();

            // جلب البيانات الحالية
            $savedData = [];
            if ($workOrder->daily_civil_works_data) {
                if (is_string($workOrder->daily_civil_works_data)) {
                    $decodedData = json_decode($workOrder->daily_civil_works_data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $savedData = $decodedData;
                    }
                } elseif (is_array($workOrder->daily_civil_works_data)) {
                    $savedData = $workOrder->daily_civil_works_data;
                }
            }

            if (!is_array($savedData)) {
                $savedData = [];
            }

            // البحث عن البيانات للتاريخ المصدر
            $sourceData = array_filter($savedData, function($item) use ($fromDate) {
                $itemDate = $item['work_date'] ?? $item['date'] ?? null;
                if (!$itemDate && isset($item['created_at'])) {
                    $itemDate = substr($item['created_at'], 0, 10);
                }
                return $itemDate === $fromDate;
            });

            if (empty($sourceData)) {
                return response()->json([
                    'success' => false,
                    'message' => "لا توجد بيانات للتاريخ {$fromDate} للنسخ"
                ], 404);
            }

            // إنشاء نسخة جديدة من البيانات للتاريخ الجديد
            $copiedData = [];
            foreach ($sourceData as $item) {
                $newItem = $item;
                $newItem['work_date'] = $toDate;
                $newItem['work_time'] = now()->format('H:i:s');
                $newItem['id'] = 'copied_' . time() . '_' . uniqid();
                $newItem['created_at'] = now()->toISOString();
                $copiedData[] = $newItem;
            }

            // إزالة أي بيانات موجودة للتاريخ المستهدف أولاً
            $filteredData = array_filter($savedData, function($item) use ($toDate) {
                $itemDate = $item['work_date'] ?? $item['date'] ?? null;
                if (!$itemDate && isset($item['created_at'])) {
                    $itemDate = substr($item['created_at'], 0, 10);
                }
                return $itemDate !== $toDate;
            });

            // دمج البيانات الجديدة
            $allData = array_merge(array_values($filteredData), $copiedData);

            // تحويل إلى JSON وحفظ
            $jsonData = json_encode($allData, JSON_UNESCAPED_UNICODE);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('فشل في تحويل البيانات إلى JSON: ' . json_last_error_msg());
            }

            $workOrder->update([
                'daily_civil_works_data' => $jsonData,
                'daily_civil_works_last_update' => now()
            ]);

            DB::commit();

            \Log::info('Successfully copied civil works data between dates', [
                'work_order_id' => $workOrder->id,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'copied_count' => count($copiedData)
            ]);

            return response()->json([
                'success' => true,
                'message' => "تم نسخ " . count($copiedData) . " عنصر من {$fromDate} إلى {$toDate}",
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'copied_count' => count($copiedData)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error copying civil works data between dates', [
                'work_order_id' => $workOrder->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء نسخ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }
} 