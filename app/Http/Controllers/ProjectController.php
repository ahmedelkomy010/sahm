<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * عرض صفحة اختيار نوع المشروع
     */
    public function showProjectTypeSelection()
    {
        return view('projects.type-selection');
    }

    /**
     * تعيين نوع المشروع في الجلسة
     */
    public function setProjectType(Request $request)
    {
        $request->validate([
            'project_type' => 'required|in:civil,electrical,mixed'
        ]);

        session(['project_type' => $request->project_type]);

        return redirect()->route('projects.create')
            ->with('success', 'تم تحديد نوع المشروع بنجاح');
    }

    /**
     * الحصول على نوع المشروع الحالي من الجلسة
     */
    public function getCurrentProjectType()
    {
        return response()->json([
            'project_type' => session('project_type')
        ]);
    }

    /**
     * عرض قائمة المشاريع
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // البحث حسب الاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contract_number', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        // فلترة حسب نوع المشروع
        if ($request->filled('project_type')) {
            $query->where('project_type', $request->project_type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ترتيب المشاريع
        $query->orderBy('created_at', 'desc');

        // تقسيم النتائج إلى صفحات
        $projects = $query->paginate(9);

        // إحصائيات سريعة
        $stats = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
        ];

        return view('projects.index', compact('projects', 'stats'));
    }

    /**
     * عرض نموذج إنشاء مشروع جديد
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * حفظ مشروع جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_number' => 'required|string|max:255|unique:projects',
            'project_type' => 'required|in:OH33KV,UA33LW,SLS33KV,UG132KV',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'srgn_date' => 'nullable|date',
            'tcc_date' => 'nullable|date',
            'pac_date' => 'nullable|date',
            'fat_date' => 'nullable|date',
        ]);

        $project = Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'تم إنشاء المشروع بنجاح');
    }

    /**
     * عرض تفاصيل مشروع محدد
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * عرض نموذج تعديل مشروع
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * تحديث بيانات مشروع
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_number' => 'required|string|max:255|unique:projects,contract_number,' . $project->id,
            'project_type' => 'required|in:OH33KV,UA33LW,SLS33KV,UG132KV',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
            'srgn_date' => 'nullable|date',
            'tcc_date' => 'nullable|date',
            'pac_date' => 'nullable|date',
            'fat_date' => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'تم تحديث المشروع بنجاح');
    }

    /**
     * حذف مشروع
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }

    /**
     * صفحة التصميم
     */
    public function design(Project $project)
    {
        return view('projects.sections.design', compact('project'));
    }

    /**
     * صفحة التوريد
     */
    public function supplying(Project $project)
    {
        return view('projects.sections.supplying', compact('project'));
    }

    /**
     * صفحة التركيب
     */
    public function installation(Project $project)
    {
        return view('projects.sections.installation', compact('project'));
    }

    /**
     * صفحة الاختبارات
     */
    public function testing(Project $project)
    {
        return view('projects.sections.testing', compact('project'));
    }

    /**
     * صفحة ضمان الجودة
     */
    public function quality(Project $project)
    {
        return view('projects.sections.quality', compact('project'));
    }

    /**
     * صفحة السلامة
     */
    public function safety(Project $project)
    {
        return view('projects.sections.safety', compact('project'));
    }

    /**
     * صفحة حزمة العطاء
     */
    public function bidPackage(Project $project)
    {
        return view('projects.sections.bid-package', compact('project'));
    }

    /**
     * صفحة التقارير
     */
    public function reports(Project $project)
    {
        return view('projects.sections.reports', compact('project'));
    }

    /**
     * صفحة الوثائق
     */
    public function documents(Project $project)
    {
        return view('projects.sections.documents', compact('project'));
    }

    /**
     * صفحة الإيرادات
     */
    public function revenues(Project $project)
    {
        // جلب الإيرادات المرتبطة بهذا المشروع
        // إذا لم توجد إيرادات مرتبطة بالمشروع، سنعرض جميع الإيرادات مؤقتاً للاختبار
        $revenues = \App\Models\Revenue::where('project', $project->name)
                                      ->orWhere('project_area', $project->name)
                                      ->orWhere('project', 'like', '%' . $project->name . '%')
                                      ->orderBy('created_at', 'desc')
                                      ->get();
        
        // إذا لم توجد إيرادات، سنجلب جميع الإيرادات مؤقتاً
        if ($revenues->isEmpty()) {
            $revenues = \App\Models\Revenue::orderBy('created_at', 'desc')->get();
        }
        
        // إحصائيات سريعة
        $totalRevenues = $revenues->count();
        $totalValue = $revenues->sum('extract_value') ?: 0;
        $paidValue = $revenues->sum('payment_value') ?: 0;
        $pendingValue = $totalValue - $paidValue;
        
        \Log::info('Revenues loaded for project', [
            'project_id' => $project->id,
            'project_name' => $project->name,
            'revenues_count' => $totalRevenues,
            'total_value' => $totalValue
        ]);
        
        return view('projects.sections.revenues', compact(
            'project', 
            'revenues', 
            'totalRevenues', 
            'totalValue', 
            'paidValue', 
            'pendingValue'
        ));
    }
} 