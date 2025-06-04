<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\MaterialsController;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\OrderEntryController;
use App\Http\Controllers\LabLicenseWebController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Use Laravel Breeze Routes
require __DIR__.'/auth.php';

// مسار اختبار لفحص صلاحيات المشرف
Route::get('/test-admin', function () {
    if (!auth()->check()) {
        return 'الرجاء تسجيل الدخول أولاً';
    }
    
    $user = auth()->user();
    $data = [
        'name' => $user->name,
        'email' => $user->email,
        'is_admin_value' => $user->is_admin ? 'نعم' : 'لا',
        'is_admin_type' => gettype($user->is_admin),
        'gate_admin_check' => Gate::allows('admin') ? 'صلاحية المشرف متاحة' : 'صلاحية المشرف غير متاحة',
    ];
    
    return response()->json($data);
})->middleware('auth');

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // إزالة وسيط التحقق للاختبار - جميع المسارات متاحة للمستخدمين المسجلين
    // Users Management - all actions
    Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    
    // Users Management - read-only access
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    
    // Admin features - إزالة وسيط التحقق للاختبار
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // وحدات التحكم في أوامر العمل والمواد
    // صفحة المواد ووظائفها (سنضعها قبل resource لأهميتها)
    Route::get('work-orders/materials', [WorkOrderController::class, 'materials'])->name('work-orders.materials');
    Route::post('work-orders/materials', [MaterialsController::class, 'store'])->name('work-orders.materials.store');
    Route::get('work-orders/materials/{material}/edit', [MaterialsController::class, 'edit'])->name('work-orders.materials.edit');
    Route::put('work-orders/materials/{material}', [MaterialsController::class, 'update'])->name('work-orders.materials.update');
    Route::delete('work-orders/materials/{material}', [MaterialsController::class, 'destroy'])->name('work-orders.materials.destroy');
    Route::get('work-orders/materials/export/excel', [MaterialsController::class, 'exportExcel'])->name('work-orders.materials.export.excel');
    Route::get('materials/description/{code}', [MaterialsController::class, 'getDescriptionByCode'])->name('materials.description');
    
    // وظائف أوامر العمل الأخرى
    Route::delete('work-orders/files/{id}', [WorkOrderController::class, 'deleteFile'])->name('work-orders.files.delete');
    Route::get('work-orders/descriptions/{workType}', [WorkOrderController::class, 'getWorkDescription'])->name('work-orders.descriptions');
    
    // تسجيل Resource بعد تسجيل المسارات المخصصة
    Route::resource('work-orders', WorkOrderController::class)->parameters([
        'work-orders' => 'workOrder'
    ]);
    
    // صفحات المسح (Survey)
    Route::get('work-orders/{workOrder}/survey', [WorkOrderController::class, 'survey'])->name('work-orders.survey');
    Route::post('work-orders/{workOrder}/survey', [WorkOrderController::class, 'storeSurvey'])->name('work-orders.survey.store');
    Route::get('work-orders/survey/{survey}/edit', [WorkOrderController::class, 'editSurvey'])->name('work-orders.survey.edit');
    
    // صفحات الرخص والتنفيذ
    Route::get('work-orders/licenses', [WorkOrderController::class, 'licenses'])->name('work-orders.licenses');
    Route::get('work-orders/{workOrder}/execution', [WorkOrderController::class, 'execution'])->name('work-orders.execution');
    Route::put('work-orders/{workOrder}/execution', [WorkOrderController::class, 'updateExecution'])->name('work-orders.update-execution');
    Route::delete('work-orders/{workOrder}/execution-file', [WorkOrderController::class, 'deleteExecutionFile'])->name('work-orders.delete-execution-file');
    
    Route::get('work-orders/{workOrder}/license', [WorkOrderController::class, 'license'])->name('work-orders.license');
    Route::put('work-orders/{workOrder}/license', [WorkOrderController::class, 'updateLicense'])->name('work-orders.update-license');
    Route::post('work-orders/{workOrder}/upload-license', [WorkOrderController::class, 'uploadLicense'])->name('work-orders.upload-license');
    Route::delete('work-orders/license-files/{fileId}', [WorkOrderController::class, 'deleteLicenseFile'])->name('work-orders.delete-license-file');

    Route::get('work-orders/{workOrder}/actions-execution', [WorkOrderController::class, 'actionsExecution'])->name('work-orders.actions-execution');
    Route::post('work-orders/{workOrderId}/upload-post-execution-file', [WorkOrderController::class, 'uploadPostExecutionFile'])->name('work-orders.upload-post-execution-file');

    // Civil Works Routes
    Route::get('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'civilWorks'])->name('work-orders.civil-works');
    Route::put('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'civilWorks'])->name('work-orders.civil-works.store');
    Route::delete('work-orders/{workOrder}/civil-works/{file}', [WorkOrderController::class, 'deleteCivilWorksFile'])->name('work-orders.civil-works.delete-file');

    // Survey Routes
    Route::get('work-orders/{workOrder}/survey', [WorkOrderController::class, 'survey'])->name('work-orders.survey');
    Route::post('work-orders/{workOrder}/survey', [WorkOrderController::class, 'storeSurvey'])->name('work-orders.survey.store');
    Route::get('work-orders/survey/{survey}/edit', [WorkOrderController::class, 'editSurvey'])->name('work-orders.survey.edit');

    // Installations Routes
    Route::get('work-orders/{workOrder}/installations', [WorkOrderController::class, 'installations'])->name('work-orders.installations');
    Route::put('work-orders/{workOrder}/installations', [WorkOrderController::class, 'storeInstallations'])->name('work-orders.installations.store');
    Route::delete('work-orders/{workOrder}/installations/{file}', [WorkOrderController::class, 'deleteInstallationsFile'])->name('work-orders.installations.delete-file');

    // Electrical Works Routes
    Route::get('work-orders/{workOrder}/electrical-works', [App\Http\Controllers\ElectricalWorksController::class, 'index'])->name('work-orders.electrical-works');
    Route::put('work-orders/{workOrder}/electrical-works/store', [App\Http\Controllers\ElectricalWorksController::class, 'store'])->name('work-orders.electrical-works.store');
    Route::post('work-orders/{workOrder}/electrical-works/images', [App\Http\Controllers\ElectricalWorksController::class, 'storeImages'])->name('work-orders.electrical-works.images');
    Route::delete('work-orders/{workOrder}/electrical-works/images/{image}', [App\Http\Controllers\ElectricalWorksController::class, 'deleteImage'])->name('work-orders.electrical-works.delete-image');

    // حذف سجل العمليات المدخلة
    Route::delete('work-orders/logs/{log}', [\App\Http\Controllers\WorkOrderController::class, 'deleteLog'])->name('work-orders.delete-log');

    // Order Entries
    Route::post('/admin/work-orders/{work_order}/order-entries', [OrderEntryController::class, 'store'])->name('order-entries.store');
    Route::delete('/admin/order-entries/{id}', [OrderEntryController::class, 'destroy'])->name('order-entries.destroy');

    // مسار جلب وصف المادة
    Route::get('work-orders/materials/get-description/{code}', [MaterialsController::class, 'getDescriptionByCode'])
        ->name('work-orders.materials.get-description');
    
    // مسارات Excel لبنود العمل
    Route::post('work-orders/import-work-items', [WorkOrderController::class, 'importWorkItems'])
        ->name('work-orders.import-work-items');
    Route::get('work-orders/work-items', [WorkOrderController::class, 'getWorkItems'])
        ->name('work-orders.work-items');

    // مسارات إدارة الرخص
    Route::get('work-orders/licenses', [WorkOrderController::class, 'licenses'])->name('work-orders.licenses');
    Route::get('work-orders/licenses/data', [\App\Http\Controllers\Admin\LicenseController::class, 'display'])->name('work-orders.licenses.data');
    Route::post('licenses', [\App\Http\Controllers\Admin\LicenseController::class, 'store'])->name('licenses.store');
    Route::post('licenses/save-section', [\App\Http\Controllers\Admin\LicenseController::class, 'saveSection'])->name('licenses.save-section');
    Route::delete('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'destroy'])->name('licenses.destroy');
    Route::get('licenses/export/excel', [\App\Http\Controllers\Admin\LicenseController::class, 'exportExcel'])->name('licenses.export.excel');
    Route::put('licenses/{license}/update', [\App\Http\Controllers\Admin\LicenseController::class, 'update'])->name('licenses.update-inline');
    
    // مسارات عرض وتعديل الرخص
    Route::get('licenses/{license}/edit', [\App\Http\Controllers\Admin\LicenseController::class, 'edit'])->name('licenses.edit');
    Route::put('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'update'])->name('licenses.update');
    Route::get('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'show'])->name('licenses.show');

    Route::post('work-orders/{workOrder}/installations/images', [App\Http\Controllers\WorkOrderController::class, 'uploadInstallationsImages'])->name('work-orders.installations.images');
    Route::delete('work-orders/installations/images/{imageId}', [App\Http\Controllers\WorkOrderController::class, 'deleteInstallationImage'])->name('work-orders.installations.images.delete');
    Route::post('work-orders/{workOrder}/electrical-works/images', [App\Http\Controllers\WorkOrderController::class, 'uploadElectricalWorksImages'])->name('work-orders.electrical-works.images');

    Route::get('materials/description/{code}', [App\Http\Controllers\WorkOrderController::class, 'getMaterialDescription'])->name('materials.description');

    // مرفقات الفاتورة
    Route::post('work-orders/{workOrder}/invoice-attachments', [\App\Http\Controllers\Admin\InvoiceAttachmentController::class, 'store'])
        ->name('work-orders.invoice-attachments.store');
    Route::delete('work-orders/invoice-attachments/{invoiceAttachment}', [\App\Http\Controllers\Admin\InvoiceAttachmentController::class, 'destroy'])
        ->name('work-orders.invoice-attachments.destroy');
});

// Project Selection Route
Route::get('/project-selection', function () {
    return view('project-selection');
})->middleware('auth')->name('project.selection');

// مسار مؤقت لتعيين المستخدم الحالي كمسؤول - يجب حذفه بعد الاستخدام
Route::get('/make-me-admin', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $user->is_admin = 1; // استخدام القيمة 1 الصريحة للتأكد
        $user->save();
        return redirect()->route('admin.users.index')
            ->with('success', 'تم تعيينك كمسؤول بنجاح!');
    }
    return redirect()->route('login');
});

// مسار مؤقت لتعيين مستخدم معين كمسؤول - سيتم إزالته لاحقاً
Route::get('/make-elkomy-admin', function () {
    $user = \App\Models\User::where('email', 'like', '%ahmedelkomy%')
                         ->orWhere('email', 'like', '%ahmedprog877@gmail.com%')  
                         ->orWhere('name', 'like', '%ahmedelkomy%')
                         ->first();
    
    if ($user) {
        $user->is_admin = true;
        $user->save();
        return redirect()->route('admin.users.index')
            ->with('success', 'تم تعيين المستخدم أحمد الكومي كمسؤول بنجاح!');
    } else {
        // إنشاء مستخدم جديد إذا لم يكن موجوداً
        $newUser = \App\Models\User::create([
            'name' => 'احمد الكومي',
            'email' => 'ahmedprog877@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'is_admin' => true,
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء مستخدم احمد الكومي كمسؤول بنجاح! البريد: ahmedprog877@gmail.com، كلمة المرور: 123456');
    }
});

// User Dashboard (redirect to admin dashboard)
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // المسارات المتعلقة بالملف الشخصي ومسارات أخرى...
    
    // نحذف مسارات المواد القديمة التي قد تتداخل مع المسارات الجديدة
    // ونبقي فقط على مسار وصف المواد الذي لا يتعارض
    Route::get('/materials/descriptions/{code}', [MaterialsController::class, 'getMaterialDescription'])->name('materials.description');
});

// Lab Licenses Web Routes
Route::get('/lab-licenses', [LabLicenseWebController::class, 'index'])->name('lab-licenses.index');
Route::post('/lab-licenses', [LabLicenseWebController::class, 'store'])->name('lab-licenses.store');
Route::put('/lab-licenses/{id}', [LabLicenseWebController::class, 'update'])->name('lab-licenses.update');
Route::delete('/lab-licenses/{id}', [LabLicenseWebController::class, 'destroy'])->name('lab-licenses.destroy');
