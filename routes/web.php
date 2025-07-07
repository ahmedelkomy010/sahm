<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\MaterialsController;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\OrderEntryController;
use App\Http\Controllers\LabLicenseWebController;
use App\Http\Controllers\CableRecordController;
use App\Http\Controllers\LicenseViolationController;


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
    // صفحة المواد ووظائفها مرتبطة بأمر العمل
            Route::get('work-orders/{workOrder}/materials', [MaterialsController::class, 'index'])->name('work-orders.materials.index');
        Route::get('work-orders/{workOrder}/materials/create', [MaterialsController::class, 'create'])->name('work-orders.materials.create');
        Route::post('work-orders/{workOrder}/materials', [MaterialsController::class, 'store'])->name('work-orders.materials.store');
        Route::get('work-orders/{workOrder}/materials/{material}', [MaterialsController::class, 'show'])->name('work-orders.materials.show');
        Route::get('work-orders/{workOrder}/materials/{material}/edit', [MaterialsController::class, 'edit'])->name('work-orders.materials.edit');
        Route::put('work-orders/{workOrder}/materials/{material}', [MaterialsController::class, 'update'])->name('work-orders.materials.update');
        Route::delete('work-orders/{workOrder}/materials/{material}', [MaterialsController::class, 'destroy'])->name('work-orders.materials.destroy');
        Route::get('work-orders/{workOrder}/materials/export/excel', [MaterialsController::class, 'exportExcel'])->name('work-orders.materials.export.excel');
        Route::get('materials/description/{code}', [MaterialsController::class, 'getDescriptionByCode'])->name('materials.description');
        
        // روتات الملفات المرفقة للمواد
        Route::post('work-orders/{workOrder}/materials/upload-files', [MaterialsController::class, 'uploadFiles'])->name('work-orders.materials.upload-files');
        Route::delete('work-orders/{workOrder}/materials/{material}/delete-file', [MaterialsController::class, 'deleteFile'])->name('work-orders.materials.delete-file');
    
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
    
    // Work Items Management Routes
    Route::post('work-orders/add-work-item', [WorkOrderController::class, 'addWorkItem'])->name('work-orders.add-work-item');
    Route::post('work-orders/update-work-item/{workOrderItem}', [WorkOrderController::class, 'updateWorkItem'])->name('work-orders.update-work-item');
    Route::delete('work-orders/delete-work-item/{workOrderItem}', [WorkOrderController::class, 'deleteWorkItem'])->name('work-orders.delete-work-item');
    
    Route::get('work-orders/{workOrder}/license', [WorkOrderController::class, 'license'])->name('work-orders.license');
    Route::put('work-orders/{workOrder}/license', [WorkOrderController::class, 'updateLicense'])->name('work-orders.update-license');
    Route::post('work-orders/{workOrder}/upload-license', [WorkOrderController::class, 'uploadLicense'])->name('work-orders.upload-license');
    Route::delete('work-orders/license-files/{fileId}', [WorkOrderController::class, 'deleteLicenseFile'])->name('work-orders.delete-license-file');

    Route::get('work-orders/{workOrder}/actions-execution', [WorkOrderController::class, 'actionsExecution'])->name('work-orders.actions-execution');
    Route::post('work-orders/{workOrderId}/upload-post-execution-file', [WorkOrderController::class, 'uploadPostExecutionFile'])->name('work-orders.upload-post-execution-file');

    // Cable Records Routes - تم تعطيلها مؤقتاً
    // Route::get('work-orders/{workOrder}/cable-records', [CableRecordController::class, 'index'])->name('work-orders.cable-records');
    // Route::post('work-orders/{workOrder}/cable-records', [CableRecordController::class, 'store'])->name('work-orders.cable-records.store');
    // Route::post('work-orders/{workOrder}/cable-records/bulk-store', [CableRecordController::class, 'bulkStore'])->name('work-orders.cable-records.bulk-store');
    // Route::put('work-orders/{workOrder}/cable-records/{cableRecord}', [CableRecordController::class, 'update'])->name('work-orders.cable-records.update');
    // Route::delete('work-orders/{workOrder}/cable-records/{cableRecord}', [CableRecordController::class, 'destroy'])->name('work-orders.cable-records.destroy');
    // Route::get('work-orders/{workOrder}/cable-records/daily-details', [CableRecordController::class, 'dailyDetails'])->name('work-orders.cable-records.daily-details');

    // Survey Routes
    Route::get('work-orders/{workOrder}/survey', [WorkOrderController::class, 'survey'])->name('work-orders.survey');
    Route::post('work-orders/{workOrder}/survey', [WorkOrderController::class, 'storeSurvey'])->name('work-orders.survey.store');
    Route::get('work-orders/survey/{survey}/edit', [WorkOrderController::class, 'editSurvey'])->name('work-orders.survey.edit');

    // Installations Routes
    Route::get('work-orders/{workOrder}/installations', [WorkOrderController::class, 'installations'])->name('work-orders.installations');
    Route::put('work-orders/{workOrder}/installations', [WorkOrderController::class, 'storeInstallations'])->name('work-orders.installations.store');
    Route::delete('work-orders/{workOrder}/installations/{file}', [WorkOrderController::class, 'deleteInstallationsFile'])->name('work-orders.installations.delete-file');

    // Civil Works Routes
    Route::get('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'civilWorks'])->name('work-orders.civil-works');
    Route::post('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'civilWorks'])->name('work-orders.civil-works.store');
    Route::put('work-orders/{workOrder}/civil-works', [WorkOrderController::class, 'civilWorks'])->name('work-orders.civil-works.store');
    Route::get('work-orders/{workOrder}/civil-works/excavation-details', [WorkOrderController::class, 'getExcavationDetails'])->name('work-orders.civil-works.excavation-details');
    Route::post('work-orders/{workOrder}/civil-works/images', [WorkOrderController::class, 'saveCivilWorksImages'])->name('work-orders.civil-works.images');
    Route::post('work-orders/{workOrder}/civil-works/attachments', [WorkOrderController::class, 'saveCivilWorksAttachments'])->name('work-orders.civil-works.attachments');
    Route::post('work-orders/{workOrder}/civil-works/lock', [WorkOrderController::class, 'lockCivilWorksImages'])->name('work-orders.civil-works.lock');
    Route::post('work-orders/{workOrder}/civil-works/save-daily-data', [WorkOrderController::class, 'saveDailyData'])->name('work-orders.civil-works.save-daily-data');
    Route::post('/work-orders/{workOrder}/civil-works/save-excavation', [WorkOrderController::class, 'saveExcavationDetails'])
        ->name('work-orders.civil-works.save-excavation');
    Route::get('/work-orders/{workOrder}/civil-works/today-excavations', [WorkOrderController::class, 'getTodayExcavations'])
        ->name('work-orders.civil-works.today-excavations');

    // Electrical Works Routes
    Route::get('work-orders/{workOrder}/electrical-works', [App\Http\Controllers\ElectricalWorksController::class, 'index'])->name('work-orders.electrical-works');
    Route::put('work-orders/{workOrder}/electrical-works/store', [App\Http\Controllers\ElectricalWorksController::class, 'store'])->name('work-orders.electrical-works.store');
    Route::post('work-orders/{workOrder}/electrical-works/store', [App\Http\Controllers\ElectricalWorksController::class, 'store'])->name('work-orders.electrical-works.store.post');


    Route::post('work-orders/{workOrder}/electrical-works/images', [App\Http\Controllers\ElectricalWorksController::class, 'storeImages'])->name('work-orders.electrical-works.images');
    Route::delete('work-orders/{workOrder}/electrical-works/images/{image}', [App\Http\Controllers\ElectricalWorksController::class, 'deleteImage'])->name('work-orders.electrical-works.delete-image');

    // حذف سجل العمليات المدخلة
    Route::delete('work-orders/logs/{log}', [\App\Http\Controllers\WorkOrderController::class, 'deleteLog'])->name('work-orders.delete-log');

    // Order Entries
    Route::post('/admin/work-orders/{work_order}/order-entries', [OrderEntryController::class, 'store'])->name('order-entries.store');
    Route::delete('/admin/order-entries/{id}', [OrderEntryController::class, 'destroy'])->name('order-entries.destroy');


    
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
    // مسار مؤقت لحل مشكلة save-section1 - يجب حذفه لاحقاً
    Route::post('licenses/save-section1', [\App\Http\Controllers\Admin\LicenseController::class, 'saveSection'])->name('licenses.save-section1');
    Route::delete('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'destroy'])->name('licenses.destroy');
    Route::get('licenses/export/excel', [\App\Http\Controllers\Admin\LicenseController::class, 'exportExcel'])->name('licenses.export.excel');
    Route::put('licenses/{license}/update', [\App\Http\Controllers\Admin\LicenseController::class, 'update'])->name('licenses.update-inline');
    
    // مسارات عرض وتعديل الرخص
    Route::get('licenses/{license}/edit', [\App\Http\Controllers\Admin\LicenseController::class, 'edit'])->name('licenses.edit');
    Route::put('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'update'])->name('licenses.update');
    Route::get('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'show'])->name('licenses.show');
            Route::get('licenses/{license}/pdf', [\App\Http\Controllers\Admin\LicenseController::class, 'exportPdf'])->name('licenses.pdf');
        Route::post('licenses/{license}/remove-evacuation-file', [\App\Http\Controllers\Admin\LicenseController::class, 'removeEvacuationFile'])->name('licenses.remove-evacuation-file');

    // تحديث بيانات الفسح للإخلاء
    Route::post('licenses/update-evac-streets/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'updateEvacStreets'])->name('licenses.update-evac-streets');
    
    // بيانات الإخلاءات التفصيلية
    Route::post('licenses/save-evacuation-data', [\App\Http\Controllers\Admin\LicenseController::class, 'saveEvacuationData'])->name('licenses.save-evacuation-data');
    Route::get('licenses/get-evacuation-data/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'getEvacuationData'])->name('licenses.get-evacuation-data');

    // مسارات التمديدات
    Route::get('licenses/extensions/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'getExtensionsByWorkOrder'])->name('licenses.extensions.by-work-order');
    Route::get('licenses/extensions/by-license/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'getExtensionsByLicense'])->name('licenses.extensions.by-license');

    // مسارات الاختبارات المعملية الديناميكية
    Route::post('licenses/lab-test/save-status', [\App\Http\Controllers\Admin\LicenseController::class, 'saveLabTestStatus'])->name('licenses.lab-test.save-status');
    Route::post('licenses/lab-test/upload-file', [\App\Http\Controllers\Admin\LicenseController::class, 'uploadLabTestFile'])->name('licenses.lab-test.upload-file');
    Route::post('licenses/lab-test/delete-file', [\App\Http\Controllers\Admin\LicenseController::class, 'deleteLabTestFile'])->name('licenses.lab-test.delete-file');

    // License Violations routes
    Route::get('licenses/{license}/violations', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'index'])->name('license-violations.index');
    Route::get('violations/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'getByWorkOrder'])->name('violations.by-work-order');
    Route::get('licenses/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'getByWorkOrder'])->name('licenses.by-work-order');
    Route::get('licenses-list/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'getLicensesByWorkOrder'])->name('licenses-list.by-work-order');
    Route::post('license-violations', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'store'])->name('license-violations.store');
    Route::get('license-violations/{violation}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'show'])->name('license-violations.show');
    Route::put('license-violations/{violation}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'update'])->name('license-violations.update');
    Route::delete('license-violations/{violation}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'destroy'])->name('license-violations.destroy');
    Route::patch('license-violations/{violation}/status', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'updateStatus'])->name('license-violations.update-status');
    Route::delete('license-violations/bulk-destroy', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'bulkDestroy'])->name('license-violations.bulk-destroy');

    // Streets routes - تم تعطينها مؤقتاً
    // Route::post('streets', [\App\Http\Controllers\Admin\StreetController::class, 'store'])->name('streets.store');
    // Route::put('streets/{street}', [\App\Http\Controllers\Admin\StreetController::class, 'update'])->name('streets.update');
    // Route::delete('streets/{street}', [\App\Http\Controllers\Admin\StreetController::class, 'destroy'])->name('streets.destroy');



    Route::post('work-orders/{workOrder}/installations/images', [App\Http\Controllers\WorkOrderController::class, 'uploadInstallationsImages'])->name('work-orders.installations.images');
    Route::delete('work-orders/installations/images/{imageId}', [App\Http\Controllers\WorkOrderController::class, 'deleteInstallationImage'])->name('work-orders.installations.images.delete');
    Route::post('work-orders/{workOrder}/electrical-works/images', [App\Http\Controllers\WorkOrderController::class, 'uploadElectricalWorksImages'])->name('work-orders.electrical-works.images');



    // مرفقات الفاتورة
    Route::post('work-orders/{workOrder}/invoice-attachments', [\App\Http\Controllers\Admin\InvoiceAttachmentController::class, 'store'])
        ->name('work-orders.invoice-attachments.store');
    Route::delete('work-orders/invoice-attachments/{invoiceAttachment}', [\App\Http\Controllers\Admin\InvoiceAttachmentController::class, 'destroy'])
        ->name('work-orders.invoice-attachments.destroy');
});

// Project Selection Route
Route::get('/project-selection', function () {
    return view('project-selection', ['hideNavbar' => false]);
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

// مسار مؤقت لإنشاء أرقام شهادات التنسيق
Route::get('/generate-coordination-numbers', function () {
    $licenses = \App\Models\License::whereNull('coordination_certificate_number')
                                  ->orWhere('coordination_certificate_number', '')
                                  ->get();

    $count = 0;
    $results = [];

    foreach ($licenses as $license) {
        $year = $license->license_date ? $license->license_date->format('Y') : date('Y');
        $workOrderId = str_pad($license->work_order_id, 4, '0', STR_PAD_LEFT);
        $licenseId = str_pad($license->id, 4, '0', STR_PAD_LEFT);
        
        $coordinationNumber = "TC-{$year}-{$workOrderId}-{$licenseId}";
        
        $license->update([
            'coordination_certificate_number' => $coordinationNumber
        ]);
        
        $count++;
        $results[] = "تم إنشاء رقم شهادة التنسيق للرخصة #{$license->id}: {$coordinationNumber}";
    }

    $output = implode('<br>', $results);
    $output .= "<br><br><strong>تم إنشاء أرقام شهادات التنسيق لـ {$count} رخصة بنجاح!</strong>";
    
    return $output;
})->middleware('auth');


// User Dashboard (redirect to admin dashboard)
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Lab Licenses Web Routes
Route::get('/lab-licenses', [LabLicenseWebController::class, 'index'])->name('lab-licenses.index');
Route::post('/lab-licenses', [LabLicenseWebController::class, 'store'])->name('lab-licenses.store');
Route::put('/lab-licenses/{id}', [LabLicenseWebController::class, 'update'])->name('lab-licenses.update');
Route::delete('/lab-licenses/{id}', [LabLicenseWebController::class, 'destroy'])->name('lab-licenses.destroy');

// مسار بديل للوصول للملفات في حالة فشل storage link
Route::get('files/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('files.serve');

// مسارات المخالفات
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/violations', [App\Http\Controllers\Admin\ViolationController::class, 'index'])->name('violations.index');
    Route::post('/violations', [App\Http\Controllers\Admin\ViolationController::class, 'store'])->name('violations.store');
    Route::get('/violations/{violation}', [App\Http\Controllers\Admin\ViolationController::class, 'show'])->name('violations.show');
    Route::put('/violations/{violation}', [App\Http\Controllers\Admin\ViolationController::class, 'update'])->name('violations.update');
    Route::delete('/violations/{violation}', [App\Http\Controllers\Admin\ViolationController::class, 'destroy'])->name('violations.destroy');
});

// مسار مؤقت لتشغيل الهجرة وإصلاح جدول المخالفات
Route::get('/run-migration', function () {
    try {
        \Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_06_23_060459_add_new_fields_to_license_violations_table.php'
        ]);
        
        return '<h2>✓ تم تشغيل الهجرة بنجاح!</h2><p>الآن يمكنك إضافة المخالفات بدون مشاكل.</p>';
    } catch (\Exception $e) {
        return '<h2>❌ خطأ في تشغيل الهجرة:</h2><p>' . $e->getMessage() . '</p>';
    }
});

// مسار مؤقت لإصلاح جدول المخالفات
Route::get('/fix-violations-table', function () {
    $output = "<h2>إصلاح جدول المخالفات</h2>";
    
    try {
        // استخدام SQL مباشر بدلاً من Schema Builder
        $output .= "<p>1. جعل license_number اختيارياً...</p>";
        \DB::statement('ALTER TABLE license_violations MODIFY license_number VARCHAR(255) NULL');
        $output .= "<p style='color: green;'>✓ تم بنجاح</p>";
        
        // إضافة الحقول بـ SQL مباشر
        $fields = [
            'violation_type' => 'VARCHAR(255) NULL',
            'payment_status' => 'INT NULL',
            'violation_value' => 'DECIMAL(10,2) NULL',
            'due_date' => 'DATE NULL',
            'work_order_id' => 'BIGINT UNSIGNED NULL'
        ];
        
        foreach ($fields as $fieldName => $fieldType) {
            try {
                // تحقق من وجود الحقل
                $exists = \DB::select("SHOW COLUMNS FROM license_violations LIKE '{$fieldName}'");
                
                if (empty($exists)) {
                    $output .= "<p>2. إضافة حقل {$fieldName}...</p>";
                    \DB::statement("ALTER TABLE license_violations ADD COLUMN {$fieldName} {$fieldType}");
                    $output .= "<p style='color: green;'>✓ تم بنجاح</p>";
                } else {
                    $output .= "<p style='color: blue;'>- حقل {$fieldName} موجود بالفعل</p>";
                }
            } catch (\Exception $e) {
                $output .= "<p style='color: orange;'>⚠️ تحذير للحقل {$fieldName}: " . $e->getMessage() . "</p>";
            }
        }
        
        // إضافة العلاقة الخارجية
        try {
            $foreignKeyExists = \DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'license_violations' 
                AND COLUMN_NAME = 'work_order_id' 
                AND CONSTRAINT_NAME LIKE '%foreign%'
            ");
            
            if (empty($foreignKeyExists)) {
                $output .= "<p>3. إضافة العلاقة الخارجية...</p>";
                \DB::statement("ALTER TABLE license_violations ADD CONSTRAINT license_violations_work_order_id_foreign FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE");
                $output .= "<p style='color: green;'>✓ تم بنجاح</p>";
            } else {
                $output .= "<p style='color: blue;'>- العلاقة الخارجية موجودة بالفعل</p>";
            }
        } catch (\Exception $e) {
            $output .= "<p style='color: orange;'>⚠️ تحذير للعلاقة الخارجية: " . $e->getMessage() . "</p>";
        }
        
        // عرض هيكل الجدول
        $output .= "<h3>هيكل جدول license_violations الحالي:</h3>";
        $columns = \DB::select("DESCRIBE license_violations");
        
        $output .= "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        $output .= "<tr style='background-color: #f0f0f0;'><th>الحقل</th><th>النوع</th><th>Null</th><th>مفتاح</th><th>افتراضي</th></tr>";
        
        foreach ($columns as $column) {
            $output .= "<tr>";
            $output .= "<td>{$column->Field}</td>";
            $output .= "<td>{$column->Type}</td>";
            $output .= "<td>{$column->Null}</td>";
            $output .= "<td>{$column->Key}</td>";
            $output .= "<td>{$column->Default}</td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        
        $output .= "<h2 style='color: green;'>🎉 تم إصلاح قاعدة البيانات بنجاح!</h2>";
        $output .= "<p><strong>يمكنك الآن إضافة المخالفات بدون مشاكل.</strong></p>";
        $output .= "<p><a href='javascript:history.back()'>العودة للصفحة السابقة</a></p>";
        
        return $output;
        
    } catch (\Exception $e) {
        return "<h2 style='color: red;'>❌ حدث خطأ:</h2><p>" . $e->getMessage() . "</p><p>تفاصيل الخطأ:</p><pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// مسار مؤقت لاختبار إنشاء المخالفات
Route::get('/test-violation', function () {
    try {
        // إنشاء أو العثور على work order
        $workOrder = \App\Models\WorkOrder::first();
        if (!$workOrder) {
            return 'No work orders found in database';
        }

        // إنشاء أو العثور على license
        $license = \App\Models\License::firstOrCreate(
            ['work_order_id' => $workOrder->id],
            ['work_order_id' => $workOrder->id]
        );

        // إنشاء مخالفة تجريبية
        $violation = \App\Models\LicenseViolation::create([
            'license_id' => $license->id,
            'work_order_id' => $workOrder->id,
            'violation_number' => 'TEST-' . time(),
            'violation_date' => now(),
            'violation_type' => 'Test Violation',
            'responsible_party' => 'Test Party',
            'payment_status' => 1,
            'violation_amount' => 100.50,
            'payment_due_date' => now()->addDays(30),
        ]);

        return 'Violation created successfully with ID: ' . $violation->id;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>Trace: ' . $e->getTraceAsString();
    }
})->middleware('auth');
