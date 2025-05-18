<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\WorkOrder;
use App\Models\ReferenceMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info('Materials page accessed from MaterialsController');
        try {
            $workOrders = WorkOrder::where('execution_status', '!=', '5')->with('materials')->paginate(10);
            $materials = Material::with('workOrder')->latest()->paginate(20);
            return view('admin.work_orders.materials', compact('workOrders', 'materials'));
        } catch (\Exception $e) {
            \Log::error('Error in materials page: ' . $e->getMessage());
            \Log::error('Error stack trace: ' . $e->getTraceAsString());
            abort(500, 'خطأ في تحميل صفحة المواد');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $workOrders = WorkOrder::all();
        return view('admin.work_orders.create_material', compact('workOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_id' => 'nullable|exists:work_orders,id',
            'code' => 'required|string|max:255',
            'description' => 'required|string',
            'planned_quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:255',
            'line' => 'nullable|string|max:255',
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'actual_quantity' => 'nullable|numeric|min:0',
        ]);

        // التعامل مع الملفات
        $data = $request->except(['check_in_file', 'gate_pass_file', 'store_in_file', 'store_out_file']);
        
        // إذا كان الوصف فارغًا، نحاول جلب الوصف من جدول المواد المرجعية
        if (empty($data['description'])) {
            $referenceMaterial = ReferenceMaterial::where('code', $data['code'])->first();
            if ($referenceMaterial) {
                $data['description'] = $referenceMaterial->description;
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['description' => 'يرجى إدخال وصف المادة أو التأكد من وجود الكود في جدول المواد المرجعية']);
            }
        }
        
        // تحميل ملف check_in إذا وجد
        if ($request->hasFile('check_in_file')) {
            $file = $request->file('check_in_file');
            $path = $file->store('materials/check_in', 'public');
            $data['check_in_file'] = $path;
        }
        
        // تحميل ملف gate_pass إذا وجد
        if ($request->hasFile('gate_pass_file')) {
            $file = $request->file('gate_pass_file');
            $path = $file->store('materials/gate_pass', 'public');
            $data['gate_pass_file'] = $path;
        }

        // تحميل ملف store_in إذا وجد
        if ($request->hasFile('store_in_file')) {
            $file = $request->file('store_in_file');
            $path = $file->store('materials/store_in', 'public');
            $data['store_in_file'] = $path;
        }
        
        // تحميل ملف store_out إذا وجد
        if ($request->hasFile('store_out_file')) {
            $file = $request->file('store_out_file');
            $path = $file->store('materials/store_out', 'public');
            $data['store_out_file'] = $path;
        }

        // حساب الفرق بين الكمية المخططة والكمية الفعلية
        $planned = $data['planned_quantity'] ?? 0;
        $actual = $data['actual_quantity'] ?? 0;
        $data['difference'] = $planned - $actual;

        $material = Material::create($data);

        if ($request->has('save_and_continue')) {
            // إعادة المستخدم لنفس صفحة المواد مع رسالة نجاح
            return redirect()->back()->with('success', 'تم إضافة المادة بنجاح، يمكنك إضافة مادة أخرى.');
        } else {
            // إعادة المستخدم لجدول المواد
            return redirect()->route('admin.work-orders.materials')
                ->with('success', 'تم إضافة المادة بنجاح');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return view('admin.work_orders.show_material', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $workOrders = WorkOrder::all();
        return view('admin.work_orders.edit_material', compact('material', 'workOrders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'work_order_id' => 'nullable|exists:work_orders,id',
            'code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:255',
            'line' => 'nullable|string|max:255',
            'check_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'date_gatepass' => 'nullable|date',
            'gate_pass_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_in_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'store_out_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'actual_quantity' => 'nullable|numeric|min:0',
        ]);

        // التعامل مع الملفات
        $data = $request->except(['check_in_file', 'gate_pass_file', 'store_in_file', 'store_out_file']);
        
        // تحميل ملف check_in إذا وجد
        if ($request->hasFile('check_in_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->check_in_file) {
                Storage::disk('public')->delete($material->check_in_file);
            }
            
            $file = $request->file('check_in_file');
            $path = $file->store('materials/check_in', 'public');
            $data['check_in_file'] = $path;
        }
        
        // تحميل ملف gate_pass إذا وجد
        if ($request->hasFile('gate_pass_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->gate_pass_file) {
                Storage::disk('public')->delete($material->gate_pass_file);
            }
            
            $file = $request->file('gate_pass_file');
            $path = $file->store('materials/gate_pass', 'public');
            $data['gate_pass_file'] = $path;
        }

        // تحميل ملف store_in إذا وجد
        if ($request->hasFile('store_in_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->store_in_file) {
                Storage::disk('public')->delete($material->store_in_file);
            }
            
            $file = $request->file('store_in_file');
            $path = $file->store('materials/store_in', 'public');
            $data['store_in_file'] = $path;
        }
        
        // تحميل ملف store_out إذا وجد
        if ($request->hasFile('store_out_file')) {
            // حذف الملف القديم إذا وجد
            if ($material->store_out_file) {
                Storage::disk('public')->delete($material->store_out_file);
            }
            
            $file = $request->file('store_out_file');
            $path = $file->store('materials/store_out', 'public');
            $data['store_out_file'] = $path;
        }

        // حساب الفرق بين الكمية المخططة والكمية الفعلية
        $planned = $data['planned_quantity'] ?? 0;
        $actual = $data['actual_quantity'] ?? 0;
        $data['difference'] = $planned - $actual;

        $material->update($data);

        return redirect()->route('admin.work-orders.materials')
            ->with('success', 'تم تحديث المادة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        // حذف الملفات المرفقة
        if ($material->check_in_file) {
            Storage::disk('public')->delete($material->check_in_file);
        }
        
        if ($material->gate_pass_file) {
            Storage::disk('public')->delete($material->gate_pass_file);
        }
        
        if ($material->store_in_file) {
            Storage::disk('public')->delete($material->store_in_file);
        }
        
        if ($material->store_out_file) {
            Storage::disk('public')->delete($material->store_out_file);
        }
        
        $material->delete();

        return redirect()->route('admin.work-orders.materials')
            ->with('success', 'تم حذف المادة بنجاح');
    }
    
    /**
     * Get material description based on code.
     * 
     * @param string $code The material code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaterialDescription($code)
    {
        $materialsData = [
            "M001" => "أنابيب مياه بلاستيكية قطر 50 مم",
            "M002" => "أنابيب صرف صحي 100 مم",
            "M003" => "محابس مياه معدنية 3/4 بوصة",
            "M004" => "وصلات بلاستيكية T شكل",
            "M005" => "صمامات تحكم بالضغط",
        ];
        
        $description = isset($materialsData[$code]) ? $materialsData[$code] : '';
        
        return response()->json([
            'description' => $description,
            'success' => !empty($description)
        ]);
    }

    public function getDescriptionByCode($code)
    {
        $material = ReferenceMaterial::where('code', $code)->first();
        if ($material) {
            return response()->json(['description' => $material->description]);
        }
        return response()->json(['description' => ''], 404);
    }
}
