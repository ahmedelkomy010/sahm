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
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'srgn_date' => 'nullable|date',
            'kick_off_date' => 'nullable|date',
            'tcc_date' => 'nullable|date',
            'pac_date' => 'nullable|date',
            'fat_date' => 'nullable|date',
        ]);

        $project = Project::create($validated);

        return redirect()->route('project.type-selection')
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
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold',
            'srgn_date' => 'nullable|date',
            'kick_off_date' => 'nullable|date',
            'tcc_date' => 'nullable|date',
            'pac_date' => 'nullable|date',
            'fat_date' => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('project.type-selection')
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
        // جلب المجلدات والملفات
        $basePath = storage_path('app/public/projects/' . $project->id . '/supplying');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.supplying', compact('project', 'folders', 'files'));
    }

    /**
     * عرض محتويات مجلد Supplying
     */
    public function viewSupplyingFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/supplying/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()
                ->route('projects.supplying', $project)
                ->with('error', 'المجلد غير موجود');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/supplying/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.supplying-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * إنشاء مجلد Supplying جديد
     */
    public function createSupplyingFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);

        try {
            // تنظيف اسم المجلد - السماح بالعربي والإنجليزي والأرقام والمسافات والشرطات والـunderscores
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()
                    ->back()
                    ->with('error', 'اسم المجلد غير صالح');
            }
            
            // التأكد من وجود المسار الأساسي
            $basePath = storage_path('app/public/projects/' . $project->id . '/supplying');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            // التحقق من عدم وجود المجلد مسبقاً
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            // إنشاء المجلد
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            // حفظ الوصف إذا كان موجوداً
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            \Log::info('Supplying folder created successfully: ' . $folderPath);
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating supplying folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    /**
     * رفع ملفات إلى مجلد Supplying
     */
    public function uploadSupplyingFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/supplying/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading supplying files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
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
        // جلب المجلدات والملفات
        $basePath = storage_path('app/public/projects/' . $project->id . '/bid-package');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    // جلب وصف المجلد إن وجد
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    // حساب عدد الملفات في المجلد
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    // ملف في المجلد الرئيسي
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.bid-package', compact('project', 'folders', 'files'));
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
        // جلب المجلدات والملفات
        $basePath = storage_path('app/public/projects/' . $project->id . '/documents');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.documents', compact('project', 'folders', 'files'));
    }

    /**
     * عرض محتويات مجلد Documents
     */
    public function viewDocumentsFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/documents/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()
                ->route('projects.documents', $project)
                ->with('error', 'المجلد غير موجود');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/documents/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.documents-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * إنشاء مجلد Documents جديد
     */
    public function createDocumentsFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()
                    ->back()
                    ->with('error', 'اسم المجلد غير صالح');
            }
            
            $basePath = storage_path('app/public/projects/' . $project->id . '/documents');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            \Log::info('Documents folder created successfully: ' . $folderPath);
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating documents folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    /**
     * رفع ملفات إلى مجلد Documents
     */
    public function uploadDocumentsFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/documents/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading documents files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    /**
     * صفحة الإيرادات
     */
    public function revenues(Project $project)
    {
        // جلب إيرادات تسليم المفتاح
        $turnkeyRevenues = \App\Models\TurnkeyRevenue::where('project', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // جلب المجلدات والملفات
        $basePath = storage_path('app/public/projects/' . $project->id . '/revenues');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.revenues', compact('project', 'folders', 'files', 'turnkeyRevenues'));
    }

    /**
     * عرض محتويات مجلد Revenues
     */
    public function viewRevenuesFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/revenues/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()
                ->route('projects.revenues', $project)
                ->with('error', 'المجلد غير موجود');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/revenues/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.revenues-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * إنشاء مجلد Revenues جديد
     */
    public function createRevenuesFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()
                    ->back()
                    ->with('error', 'اسم المجلد غير صالح');
            }
            
            $basePath = storage_path('app/public/projects/' . $project->id . '/revenues');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            \Log::info('Revenues folder created successfully: ' . $folderPath);
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating revenues folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    /**
     * رفع ملفات إلى مجلد Revenues
     */
    public function uploadRevenuesFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/revenues/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading revenues files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    /**
     * عرض محتويات مجلد معين
     */
    public function viewBidPackageFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/bid-package/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()
                ->route('projects.bid-package', $project)
                ->with('error', 'المجلد غير موجود');
        }
        
        // جلب وصف المجلد
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        // جلب الملفات في المجلد
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/bid-package/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.bid-package-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * إنشاء مجلد جديد في bid package
     */
    public function createBidPackageFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:1000',
        ]);

        try {
            // تنظيف اسم المجلد - السماح بالحروف العربية والإنجليزية والأرقام والمسافات والشرطات
            $folderName = $validated['folder_name'];
            // إزالة الأحرف الخاصة فقط، والمحافظة على العربية
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $folderName);
            $folderName = trim($folderName);
            
            // إنشاء المسار الأساسي أولاً
            $basePath = storage_path('app/public/projects/' . $project->id . '/bid-package');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            // إنشاء المجلد الجديد
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            $created = mkdir($folderPath, 0777, true);
            
            if ($created) {
                chmod($folderPath, 0777);
                
                // إنشاء ملف وصف للمجلد
                if ($request->folder_description) {
                    file_put_contents($folderPath . '/description.txt', $request->folder_description);
                }
                
                \Log::info('Folder created successfully', [
            'project_id' => $project->id,
                    'folder_name' => $folderName,
                    'path' => $folderPath
                ]);
                
                return redirect()
                    ->back()
                    ->with('success', 'تم إنشاء المجلد "' . $folderName . '" بنجاح');
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'فشل إنشاء المجلد');
            }

        } catch (\Exception $e) {
            \Log::error('Error creating bid package folder: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'folder_name' => $request->folder_name,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    /**
     * رفع ملفات متعددة في bid package
     */
    public function uploadBidPackageFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200', // Max 50MB per file
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/bid-package/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];

                // يمكنك إضافة حفظ معلومات الملف في قاعدة البيانات هنا
                // مثلاً: حفظ في جدول bid_package_files
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading bid package files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    /**
     * صفحة Study
     */
    public function study(Project $project)
    {
        // جلب المجلدات والملفات
        $basePath = storage_path('app/public/projects/' . $project->id . '/study');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.study', compact('project', 'folders', 'files'));
    }

    /**
     * عرض محتويات مجلد Study
     */
    public function viewStudyFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/study/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()
                ->route('projects.study', $project)
                ->with('error', 'المجلد غير موجود');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/study/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.study-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * إنشاء مجلد جديد في Study
     */
    public function createStudyFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:1000',
        ]);

        try {
            $folderName = $validated['folder_name'];
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $folderName);
            $folderName = trim($folderName);
            
            $basePath = storage_path('app/public/projects/' . $project->id . '/study');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            $created = mkdir($folderPath, 0777, true);
            
            if ($created) {
                chmod($folderPath, 0777);
                
                if ($request->folder_description) {
                    file_put_contents($folderPath . '/description.txt', $request->folder_description);
                }
                
                return redirect()
                    ->back()
                    ->with('success', 'تم إنشاء المجلد "' . $folderName . '" بنجاح');
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'فشل إنشاء المجلد');
            }

        } catch (\Exception $e) {
            \Log::error('Error creating study folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    /**
     * رفع ملفات متعددة في Study
     */
    public function uploadStudyFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/study/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading study files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    /**
     * عرض صفحة Clarification
     */
    public function clarification(Project $project)
    {
        // جلب المجلدات والملفات
        $basePath = storage_path('app/public/projects/' . $project->id . '/clarification');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.clarification', compact('project', 'folders', 'files'));
    }

    /**
     * عرض محتويات مجلد Clarification
     */
    public function viewClarificationFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/clarification/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()
                ->route('projects.clarification', $project)
                ->with('error', 'المجلد غير موجود');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/clarification/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.clarification-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * إنشاء مجلد Clarification جديد
     */
    public function createClarificationFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);

        try {
            // تنظيف اسم المجلد - السماح بالعربي والإنجليزي والأرقام والمسافات والشرطات والـunderscores
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()
                    ->back()
                    ->with('error', 'اسم المجلد غير صالح');
            }
            
            // التأكد من وجود المسار الأساسي
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            // التحقق من عدم وجود المجلد مسبقاً
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            // إنشاء المجلد
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            // حفظ الوصف إذا كان موجوداً
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            \Log::info('Clarification folder created successfully: ' . $folderPath);
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating clarification folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    /**
     * رفع ملفات إلى مجلد Clarification
     */
    public function uploadClarificationFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/clarification/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading clarification files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    // ============================================
    // Study Sub-sections Methods
    // ============================================

    /**
     * Site Visit (PRD, PG) Section
     */
    public function studySiteVisit(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/study/site-visit');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                        'url' => asset('storage/projects/' . $project->id . '/study/site-visit/' . $item)
                    ];
                }
            }
        }
        
        return view('projects.sections.study-site-visit', compact('project', 'folders', 'files'));
    }

    public function createStudySiteVisitFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:500',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);

            $basePath = storage_path('app/public/projects/' . $project->id . '/study/site-visit');
            
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if ($request->folder_description) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating site-visit folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    public function uploadStudySiteVisitFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/study/site-visit/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading site-visit files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewStudySiteVisitFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/study/site-visit/' . $folderName);
        
        if (!file_exists($folderPath)) {
            abort(404);
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                    'url' => asset('storage/projects/' . $project->id . '/study/site-visit/' . $folderName . '/' . $item)
                ];
            }
        }
        
        return view('projects.sections.study-site-visit-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * Tech Files Section
     */
    public function studyTechFiles(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/study/tech-files');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                        'url' => asset('storage/projects/' . $project->id . '/study/tech-files/' . $item)
                    ];
                }
            }
        }
        
        return view('projects.sections.study-tech-files', compact('project', 'folders', 'files'));
    }

    public function createStudyTechFilesFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:500',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);

            $basePath = storage_path('app/public/projects/' . $project->id . '/study/tech-files');
            
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if ($request->folder_description) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating tech-files folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    public function uploadStudyTechFilesFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/study/tech-files/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading tech-files files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewStudyTechFilesFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/study/tech-files/' . $folderName);
        
        if (!file_exists($folderPath)) {
            abort(404);
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                    'url' => asset('storage/projects/' . $project->id . '/study/tech-files/' . $folderName . '/' . $item)
                ];
            }
        }
        
        return view('projects.sections.study-tech-files-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * Commercial Files Section
     */
    public function studyCommercialFiles(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/study/commercial-files');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                        'url' => asset('storage/projects/' . $project->id . '/study/commercial-files/' . $item)
                    ];
                }
            }
        }
        
        return view('projects.sections.study-commercial-files', compact('project', 'folders', 'files'));
    }

    public function createStudyCommercialFilesFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:500',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);

            $basePath = storage_path('app/public/projects/' . $project->id . '/study/commercial-files');
            
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if ($request->folder_description) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating commercial-files folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    public function uploadStudyCommercialFilesFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/study/commercial-files/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading commercial-files files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewStudyCommercialFilesFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/study/commercial-files/' . $folderName);
        
        if (!file_exists($folderPath)) {
            abort(404);
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                    'url' => asset('storage/projects/' . $project->id . '/study/commercial-files/' . $folderName . '/' . $item)
                ];
            }
        }
        
        return view('projects.sections.study-commercial-files-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * Sch C Section
     */
    public function studySchC(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/study/sch-c');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                        'url' => asset('storage/projects/' . $project->id . '/study/sch-c/' . $item)
                    ];
                }
            }
        }
        
        return view('projects.sections.study-sch-c', compact('project', 'folders', 'files'));
    }

    public function createStudySchCFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:500',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);

            $basePath = storage_path('app/public/projects/' . $project->id . '/study/sch-c');
            
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if ($request->folder_description) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating sch-c folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    public function uploadStudySchCFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/study/sch-c/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading sch-c files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewStudySchCFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/study/sch-c/' . $folderName);
        
        if (!file_exists($folderPath)) {
            abort(404);
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                    'url' => asset('storage/projects/' . $project->id . '/study/sch-c/' . $folderName . '/' . $item)
                ];
            }
        }
        
        return view('projects.sections.study-sch-c-folder', compact('project', 'folderName', 'description', 'files'));
    }

    /**
     * Documents Section (Study)
     */
    public function studyDocuments(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/study/study-documents');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                        'url' => asset('storage/projects/' . $project->id . '/study/study-documents/' . $item)
                    ];
                }
            }
        }
        
        return view('projects.sections.study-documents', compact('project', 'folders', 'files'));
    }

    public function createStudyDocumentsFolder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:500',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);

            $basePath = storage_path('app/public/projects/' . $project->id . '/study/study-documents');
            
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if ($request->folder_description) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating study-documents folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    public function uploadStudyDocumentsFiles(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/study/study-documents/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading study-documents files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewStudyDocumentsFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/study/study-documents/' . $folderName);
        
        if (!file_exists($folderPath)) {
            abort(404);
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                    'url' => asset('storage/projects/' . $project->id . '/study/study-documents/' . $folderName . '/' . $item)
                ];
            }
        }
        
        return view('projects.sections.study-documents-folder', compact('project', 'folderName', 'description', 'files'));
    }

    // ============================================
    // Bid Package Sub-sections Methods
    // ============================================

    /**
     * Vol 1 Section
     */
    public function bidPackageVol1(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/bid-package/vol1');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                        'url' => asset('storage/projects/' . $project->id . '/bid-package/vol1/' . $item)
                    ];
                }
            }
        }
        
        return view('projects.sections.bid-package-vol1', compact('project', 'folders', 'files'));
    }

    public function createBidPackageVol1Folder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string|max:500',
        ]);

        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);

            $basePath = storage_path('app/public/projects/' . $project->id . '/bid-package/vol1');
            
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()
                    ->back()
                    ->with('error', 'المجلد موجود بالفعل');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if ($request->folder_description) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()
                ->back()
                ->with('success', 'تم إنشاء المجلد بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error creating vol1 folder: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المجلد: ' . $e->getMessage());
        }
    }

    public function uploadBidPackageVol1Files(Request $request, Project $project)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string',
        ]);

        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            
            $storagePath = 'public/projects/' . $project->id . '/bid-package/vol1/' . $folderName;
            
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                
                $path = $file->storeAs($storagePath, $fileName);
                
                $uploadedFiles[] = [
                    'name' => $originalName,
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }

            return redirect()
                ->back()
                ->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error uploading vol1 files: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewBidPackageVol1Folder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/bid-package/vol1/' . $folderName);
        
        if (!file_exists($folderPath)) {
            abort(404);
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath)),
                    'url' => asset('storage/projects/' . $project->id . '/bid-package/vol1/' . $folderName . '/' . $item)
                ];
            }
        }
        
        return view('projects.sections.bid-package-vol1-folder', compact('project', 'folderName', 'description', 'files'));
    }

    // Vol 2 and Documents methods would follow similar pattern...
    // Adding just Vol2 and Documents main methods for brevity
    public function bidPackageVol2(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/bid-package/vol2');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $itemPath = $basePath . '/' . $item;
                if (is_dir($itemPath)) {
                    $description = file_exists($itemPath . '/description.txt') ? file_get_contents($itemPath . '/description.txt') : '';
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    $folders[] = ['name' => $item, 'description' => $description, 'file_count' => $fileCount, 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath))];
                } else {
                    $files[] = ['name' => $item, 'size' => filesize($itemPath), 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath)), 'url' => asset('storage/projects/' . $project->id . '/bid-package/vol2/' . $item)];
                }
            }
        }
        return view('projects.sections.bid-package-vol2', compact('project', 'folders', 'files'));
    }

    public function bidPackageDocuments(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/bid-package/documents');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $itemPath = $basePath . '/' . $item;
                if (is_dir($itemPath)) {
                    $description = file_exists($itemPath . '/description.txt') ? file_get_contents($itemPath . '/description.txt') : '';
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    $folders[] = ['name' => $item, 'description' => $description, 'file_count' => $fileCount, 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath))];
                } else {
                    $files[] = ['name' => $item, 'size' => filesize($itemPath), 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath)), 'url' => asset('storage/projects/' . $project->id . '/bid-package/documents/' . $item)];
                }
            }
        }
        return view('projects.sections.bid-package-documents', compact('project', 'folders', 'files'));
    }

    // Note: Remaining CRUD methods for Vol2 and Documents follow the same pattern as Vol1
    // They should be added following the same structure for create, upload, and view folder

    // ============================================
    // Supplying Sub-sections Methods
    // ============================================

    public function supplyingSupplying(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/supplying/supplying');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $itemPath = $basePath . '/' . $item;
                if (is_dir($itemPath)) {
                    $description = file_exists($itemPath . '/description.txt') ? file_get_contents($itemPath . '/description.txt') : '';
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    $folders[] = ['name' => $item, 'description' => $description, 'file_count' => $fileCount, 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath))];
                } else {
                    $files[] = ['name' => $item, 'size' => filesize($itemPath), 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath)), 'url' => asset('storage/projects/' . $project->id . '/supplying/supplying/' . $item)];
                }
            }
        }
        return view('projects.sections.supplying-supplying', compact('project', 'folders', 'files'));
    }

    public function createSupplyingSupplyingFolder(Request $request, Project $project)
    {
        $validated = $request->validate(['folder_name' => 'required|string|max:255', 'folder_description' => 'nullable|string|max:500']);
        try {
            $folderName = trim(preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']));
            $basePath = storage_path('app/public/projects/' . $project->id . '/supplying/supplying');
            if (!file_exists($basePath)) { mkdir($basePath, 0777, true); chmod($basePath, 0777); }
            $folderPath = $basePath . '/' . $folderName;
            if (file_exists($folderPath)) { return redirect()->back()->with('error', 'المجلد موجود بالفعل'); }
            mkdir($folderPath, 0777, true); chmod($folderPath, 0777);
            if ($request->folder_description) { file_put_contents($folderPath . '/description.txt', $validated['folder_description']); }
            return redirect()->back()->with('success', 'تم إنشاء المجلد بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error creating supplying folder: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المجلد');
        }
    }

    public function uploadSupplyingSupplyingFiles(Request $request, Project $project)
    {
        $validated = $request->validate(['files' => 'required|array|min:1', 'files.*' => 'required|file|max:51200', 'folder_id' => 'nullable|string']);
        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            $storagePath = 'public/projects/' . $project->id . '/supplying/supplying/' . $folderName;
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                $path = $file->storeAs($storagePath, $fileName);
                $uploadedFiles[] = ['name' => $originalName, 'path' => $path, 'size' => $file->getSize()];
            }
            return redirect()->back()->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error uploading supplying files: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewSupplyingSupplyingFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/supplying/supplying/' . $folderName);
        if (!file_exists($folderPath)) { abort(404); }
        $description = file_exists($folderPath . '/description.txt') ? file_get_contents($folderPath . '/description.txt') : '';
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = ['name' => $item, 'size' => filesize($itemPath), 'extension' => pathinfo($item, PATHINFO_EXTENSION), 'created_at' => date('Y-m-d H:i', filectime($itemPath)), 'url' => asset('storage/projects/' . $project->id . '/supplying/supplying/' . $folderName . '/' . $item)];
            }
        }
        return view('projects.sections.supplying-supplying-folder', compact('project', 'folderName', 'description', 'files'));
    }

    public function supplyingInstallation(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/supplying/installation');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $itemPath = $basePath . '/' . $item;
                if (is_dir($itemPath)) {
                    $description = file_exists($itemPath . '/description.txt') ? file_get_contents($itemPath . '/description.txt') : '';
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    $folders[] = ['name' => $item, 'description' => $description, 'file_count' => $fileCount, 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath))];
                } else {
                    $files[] = ['name' => $item, 'size' => filesize($itemPath), 'path' => $itemPath, 'created_at' => date('Y-m-d H:i', filectime($itemPath)), 'url' => asset('storage/projects/' . $project->id . '/supplying/installation/' . $item)];
                }
            }
        }
        return view('projects.sections.supplying-installation', compact('project', 'folders', 'files'));
    }

    public function createSupplyingInstallationFolder(Request $request, Project $project)
    {
        $validated = $request->validate(['folder_name' => 'required|string|max:255', 'folder_description' => 'nullable|string|max:500']);
        try {
            $folderName = trim(preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']));
            $basePath = storage_path('app/public/projects/' . $project->id . '/supplying/installation');
            if (!file_exists($basePath)) { mkdir($basePath, 0777, true); chmod($basePath, 0777); }
            $folderPath = $basePath . '/' . $folderName;
            if (file_exists($folderPath)) { return redirect()->back()->with('error', 'المجلد موجود بالفعل'); }
            mkdir($folderPath, 0777, true); chmod($folderPath, 0777);
            if ($request->folder_description) { file_put_contents($folderPath . '/description.txt', $validated['folder_description']); }
            return redirect()->back()->with('success', 'تم إنشاء المجلد بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error creating installation folder: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المجلد');
        }
    }

    public function uploadSupplyingInstallationFiles(Request $request, Project $project)
    {
        $validated = $request->validate(['files' => 'required|array|min:1', 'files.*' => 'required|file|max:51200', 'folder_id' => 'nullable|string']);
        try {
            $uploadedFiles = [];
            $folderName = $request->folder_id ?: 'main';
            $storagePath = 'public/projects/' . $project->id . '/supplying/installation/' . $folderName;
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                $path = $file->storeAs($storagePath, $fileName);
                $uploadedFiles[] = ['name' => $originalName, 'path' => $path, 'size' => $file->getSize()];
            }
            return redirect()->back()->with('success', 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error uploading installation files: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الملفات');
        }
    }

    public function viewSupplyingInstallationFolder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/supplying/installation/' . $folderName);
        if (!file_exists($folderPath)) { abort(404); }
        $description = file_exists($folderPath . '/description.txt') ? file_get_contents($folderPath . '/description.txt') : '';
        $files = [];
        $items = array_diff(scandir($folderPath), ['.', '..', 'description.txt']);
        foreach ($items as $item) {
            $itemPath = $folderPath . '/' . $item;
            if (is_file($itemPath)) {
                $files[] = ['name' => $item, 'size' => filesize($itemPath), 'extension' => pathinfo($item, PATHINFO_EXTENSION), 'created_at' => date('Y-m-d H:i', filectime($itemPath)), 'url' => asset('storage/projects/' . $project->id . '/supplying/installation/' . $folderName . '/' . $item)];
            }
        }
        return view('projects.sections.supplying-installation-folder', compact('project', 'folderName', 'description', 'files'));
    }
    
    // ============================================
    // CLA1 Methods
    // ============================================
    
    public function clarificationCla1(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla1');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.clarification-cla1', compact('project', 'folders', 'files'));
    }
    
    public function createClarificationCla1Folder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);
        
        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()->back()->with('error', 'Invalid folder name');
            }
            
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla1');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()->back()->with('error', 'Folder already exists');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()->back()->with('success', 'Folder created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create folder: ' . $e->getMessage());
        }
    }
    
    public function uploadClarificationCla1Files(Request $request, Project $project)
    {
        $request->validate([
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string'
        ]);
        
        try {
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla1');
            
            if ($request->folder_id) {
                $targetPath = $basePath . '/' . $request->folder_id;
            } else {
                $targetPath = $basePath;
            }
            
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
                chmod($targetPath, 0777);
            }
            
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->move($targetPath, $fileName);
                chmod($targetPath . '/' . $fileName, 0644);
            }
            
            return redirect()->back()->with('success', 'Files uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload files: ' . $e->getMessage());
        }
    }
    
    public function viewClarificationCla1Folder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/clarification/cla1/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()->route('projects.clarification.cla1', $project)->with('error', 'Folder not found');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/clarification/cla1/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.clarification-cla1-folder', compact('project', 'folderName', 'description', 'files'));
    }
    
    // ============================================
    // CLA2 Methods
    // ============================================
    
    public function clarificationCla2(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla2');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.clarification-cla2', compact('project', 'folders', 'files'));
    }
    
    public function createClarificationCla2Folder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);
        
        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()->back()->with('error', 'Invalid folder name');
            }
            
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla2');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()->back()->with('error', 'Folder already exists');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()->back()->with('success', 'Folder created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create folder: ' . $e->getMessage());
        }
    }
    
    public function uploadClarificationCla2Files(Request $request, Project $project)
    {
        $request->validate([
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string'
        ]);
        
        try {
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla2');
            
            if ($request->folder_id) {
                $targetPath = $basePath . '/' . $request->folder_id;
            } else {
                $targetPath = $basePath;
            }
            
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
                chmod($targetPath, 0777);
            }
            
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->move($targetPath, $fileName);
                chmod($targetPath . '/' . $fileName, 0644);
            }
            
            return redirect()->back()->with('success', 'Files uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload files: ' . $e->getMessage());
        }
    }
    
    public function viewClarificationCla2Folder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/clarification/cla2/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()->route('projects.clarification.cla2', $project)->with('error', 'Folder not found');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/clarification/cla2/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.clarification-cla2-folder', compact('project', 'folderName', 'description', 'files'));
    }
    
    // ============================================
    // CLA3 Methods
    // ============================================
    
    public function clarificationCla3(Project $project)
    {
        $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla3');
        $folders = [];
        $files = [];
        
        if (file_exists($basePath)) {
            $items = scandir($basePath);
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $itemPath = $basePath . '/' . $item;
                
                if (is_dir($itemPath)) {
                    $description = '';
                    $descFile = $itemPath . '/description.txt';
                    if (file_exists($descFile)) {
                        $description = file_get_contents($descFile);
                    }
                    
                    $fileCount = count(array_diff(scandir($itemPath), ['.', '..', 'description.txt']));
                    
                    $folders[] = [
                        'name' => $item,
                        'description' => $description,
                        'file_count' => $fileCount,
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'size' => filesize($itemPath),
                        'path' => $itemPath,
                        'created_at' => date('Y-m-d H:i', filectime($itemPath))
                    ];
                }
            }
        }
        
        return view('projects.sections.clarification-cla3', compact('project', 'folders', 'files'));
    }
    
    public function createClarificationCla3Folder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_description' => 'nullable|string',
        ]);
        
        try {
            $folderName = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s\-\_]/u', '', $validated['folder_name']);
            $folderName = trim($folderName);
            
            if (empty($folderName)) {
                return redirect()->back()->with('error', 'Invalid folder name');
            }
            
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla3');
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
                chmod($basePath, 0777);
            }
            
            $folderPath = $basePath . '/' . $folderName;
            
            if (file_exists($folderPath)) {
                return redirect()->back()->with('error', 'Folder already exists');
            }
            
            mkdir($folderPath, 0777, true);
            chmod($folderPath, 0777);
            
            if (!empty($validated['folder_description'])) {
                file_put_contents($folderPath . '/description.txt', $validated['folder_description']);
            }
            
            return redirect()->back()->with('success', 'Folder created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create folder: ' . $e->getMessage());
        }
    }
    
    public function uploadClarificationCla3Files(Request $request, Project $project)
    {
        $request->validate([
            'files.*' => 'required|file|max:51200',
            'folder_id' => 'nullable|string'
        ]);
        
        try {
            $basePath = storage_path('app/public/projects/' . $project->id . '/clarification/cla3');
            
            if ($request->folder_id) {
                $targetPath = $basePath . '/' . $request->folder_id;
            } else {
                $targetPath = $basePath;
            }
            
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
                chmod($targetPath, 0777);
            }
            
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->move($targetPath, $fileName);
                chmod($targetPath . '/' . $fileName, 0644);
            }
            
            return redirect()->back()->with('success', 'Files uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload files: ' . $e->getMessage());
        }
    }
    
    public function viewClarificationCla3Folder(Project $project, $folderName)
    {
        $folderPath = storage_path('app/public/projects/' . $project->id . '/clarification/cla3/' . $folderName);
        
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return redirect()->route('projects.clarification.cla3', $project)->with('error', 'Folder not found');
        }
        
        $description = '';
        $descFile = $folderPath . '/description.txt';
        if (file_exists($descFile)) {
            $description = file_get_contents($descFile);
        }
        
        $files = [];
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === 'description.txt') continue;
            
            $itemPath = $folderPath . '/' . $item;
            
            if (!is_dir($itemPath)) {
                $pathInfo = pathinfo($item);
                $files[] = [
                    'name' => $item,
                    'size' => filesize($itemPath),
                    'extension' => $pathInfo['extension'] ?? '',
                    'path' => $itemPath,
                    'url' => asset('storage/projects/' . $project->id . '/clarification/cla3/' . $folderName . '/' . $item),
                    'created_at' => date('Y-m-d H:i', filectime($itemPath))
                ];
            }
        }
        
        return view('projects.sections.clarification-cla3-folder', compact('project', 'folderName', 'description', 'files'));
    }
}
