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
            'project_type' => 'required|in:civil,electrical,mixed',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
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
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
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
} 