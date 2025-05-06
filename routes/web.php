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
    return view('welcome');
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
    
    // Work Orders
    Route::resource('work-orders', App\Http\Controllers\WorkOrderController::class)->parameters([
        'work-orders' => 'workOrder'
    ]);
    Route::delete('work-orders/files/{id}', [App\Http\Controllers\WorkOrderController::class, 'deleteFile'])->name('work-orders.files.delete');
    Route::get('work-orders/descriptions/{workType}', [App\Http\Controllers\WorkOrderController::class, 'getWorkDescription'])->name('work-orders.descriptions');
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
    // Work Orders Routes - These routes are already defined by the resource route above
    // Commenting out to avoid route conflicts
    /*
    Route::get('/admin/work-orders', [WorkOrderController::class, 'index'])->name('admin.work-orders.index');
    Route::get('/admin/work-orders/create', [WorkOrderController::class, 'create'])->name('admin.work-orders.create');
    Route::post('/admin/work-orders', [WorkOrderController::class, 'store'])->name('admin.work-orders.store');
    Route::get('/admin/work-orders/{workOrder}', [WorkOrderController::class, 'show'])->name('admin.work-orders.show');
    Route::get('/admin/work-orders/{workOrder}/edit', [WorkOrderController::class, 'edit'])->name('admin.work-orders.edit');
    Route::put('/admin/work-orders/{workOrder}', [WorkOrderController::class, 'update'])->name('admin.work-orders.update');
    Route::delete('/admin/work-orders/{workOrder}', [WorkOrderController::class, 'destroy'])->name('admin.work-orders.destroy');
    */
    
    // Materials Route (Direct Access)
    Route::get('/materials', [MaterialsController::class, 'index'])->name('materials.index'); 
    Route::resource('materials', MaterialsController::class)->except(['index']);
    Route::get('/materials/descriptions/{code}', [MaterialsController::class, 'getMaterialDescription'])->name('materials.description');
    
    // Survey Routes
    Route::get('/admin/work-orders/{workOrder}/survey', [WorkOrderController::class, 'survey'])->name('admin.work-orders.survey');
    Route::post('/admin/work-orders/{workOrder}/survey', [WorkOrderController::class, 'storeSurvey'])->name('admin.work-orders.survey.store');
    Route::get('/admin/work-orders/survey/{survey}/edit', [WorkOrderController::class, 'editSurvey'])->name('admin.work-orders.survey.edit');
    
    // Work Order Sections Routes
    Route::get('/admin/work-orders/materials', [WorkOrderController::class, 'materials'])->name('admin.work-orders.materials');
    Route::post('/admin/work-orders/materials', [WorkOrderController::class, 'storeMaterial'])->name('admin.work-orders.materials.store');
    Route::get('/admin/work-orders/materials/{material}/edit', [WorkOrderController::class, 'editMaterial'])->name('admin.work-orders.materials.edit');
    Route::put('/admin/work-orders/materials/{material}', [WorkOrderController::class, 'updateMaterial'])->name('admin.work-orders.materials.update');
    Route::delete('/admin/work-orders/materials/{material}', [WorkOrderController::class, 'destroyMaterial'])->name('admin.work-orders.materials.destroy');
    
    Route::get('/admin/work-orders/licenses', [WorkOrderController::class, 'licenses'])->name('admin.work-orders.licenses');
    
    // Work Order Execution Routes
    Route::get('/admin/work-orders/execution', [WorkOrderController::class, 'execution'])->name('admin.work-orders.execution');
    Route::get('/admin/work-orders/post-execution', [WorkOrderController::class, 'postExecution'])->name('admin.work-orders.post-execution');
    
    // License Routes
    Route::get('/admin/work-orders/{workOrder}/license', [WorkOrderController::class, 'license'])->name('admin.work-orders.license');
    Route::put('/admin/work-orders/{workOrder}/license', [WorkOrderController::class, 'updateLicense'])->name('admin.work-orders.update-license');
});
