<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\MaterialsController;
use Illuminate\Support\Facades\Gate;


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
    Route::patch('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
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
    Route::post('work-orders/materials', [WorkOrderController::class, 'storeMaterial'])->name('work-orders.materials.store');
    Route::get('work-orders/materials/{material}/edit', [WorkOrderController::class, 'editMaterial'])->name('work-orders.materials.edit');
    Route::put('work-orders/materials/{material}', [WorkOrderController::class, 'updateMaterial'])->name('work-orders.materials.update');
    Route::delete('work-orders/materials/{material}', [WorkOrderController::class, 'destroyMaterial'])->name('work-orders.materials.destroy');
    
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
    Route::get('work-orders/licenses/data', [\App\Http\Controllers\Admin\LicenseController::class, 'display'])->name('work-orders.licenses.data');
    Route::get('work-orders/{workOrder}/execution', [WorkOrderController::class, 'execution'])->name('work-orders.execution');
    Route::put('work-orders/{workOrder}/execution', [WorkOrderController::class, 'updateExecution'])->name('work-orders.update-execution');
    Route::delete('work-orders/{workOrder}/execution-file', [WorkOrderController::class, 'deleteExecutionFile'])->name('work-orders.delete-execution-file');
    
    Route::get('work-orders/{workOrder}/license', [WorkOrderController::class, 'license'])->name('work-orders.license');
    Route::put('work-orders/{workOrder}/license', [WorkOrderController::class, 'updateLicense'])->name('work-orders.update-license');

    Route::get('work-orders/{workOrder}/actions-execution', [WorkOrderController::class, 'actionsExecution'])->name('work-orders.actions-execution');

    // Civil Works Routes
    Route::get('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'civilWorks'])->name('work-orders.civil-works');
    Route::put('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'storeCivilWorks'])->name('work-orders.civil-works.store');
    Route::delete('work-orders/{workOrder}/civil-works/{file}', [WorkOrderController::class, 'deleteCivilWorksFile'])->name('work-orders.civil-works.delete-file');

    // Installations Routes
    Route::get('work-orders/{workOrder}/installations', [WorkOrderController::class, 'installations'])->name('work-orders.installations');
    Route::put('work-orders/{workOrder}/installations', [WorkOrderController::class, 'storeInstallations'])->name('work-orders.installations.store');
    Route::delete('work-orders/{workOrder}/installations/{file}', [WorkOrderController::class, 'deleteInstallationsFile'])->name('work-orders.installations.delete-file');

    // Electrical Works Routes
    Route::get('work-orders/{workOrder}/electrical-works', [WorkOrderController::class, 'electricalWorks'])->name('work-orders.electrical-works');
    Route::put('work-orders/{workOrder}/electrical-works', [WorkOrderController::class, 'storeElectricalWorks'])->name('work-orders.electrical-works.store');
    Route::delete('work-orders/{workOrder}/electrical-works/{file}', [WorkOrderController::class, 'deleteElectricalWorksFile'])->name('work-orders.electrical-works.delete-file');

    // حذف سجل العمليات المدخلة
    Route::delete('work-orders/logs/{log}', [\App\Http\Controllers\WorkOrderController::class, 'deleteLog'])->name('work-orders.delete-log');
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
