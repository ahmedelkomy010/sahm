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
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExcavationDetailController;


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

// المسار الرئيسي
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('login');

// روت تسجيل الدخول
Route::post('/', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

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

// مسار لجعل المستخدم مشرفاً
Route::get('/make-me-admin', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    $user->is_admin = 1;
    $user->save();
    
    return redirect()->back()->with('success', 'تم تعيينك كمشرف بنجاح!');
})->middleware('auth');

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Special Projects Routes
    Route::get('/special-projects', [App\Http\Controllers\Admin\SpecialProjectController::class, 'index'])->name('special-projects.index');
    Route::get('/special-projects/create', [App\Http\Controllers\Admin\SpecialProjectController::class, 'create'])->name('special-projects.create');
    Route::post('/special-projects', [App\Http\Controllers\Admin\SpecialProjectController::class, 'store'])->name('special-projects.store');
    Route::get('/special-projects/{project}', [App\Http\Controllers\Admin\SpecialProjectController::class, 'show'])->name('special-projects.show');
    Route::get('/special-projects/{project}/revenues/export', [App\Http\Controllers\Admin\SpecialProjectController::class, 'exportRevenues'])->name('special-projects.revenues.export');
    Route::get('/special-projects/{project}/revenues', [App\Http\Controllers\Admin\SpecialProjectController::class, 'revenues'])->name('special-projects.revenues');
    Route::post('/special-projects/{project}/revenues', [App\Http\Controllers\Admin\SpecialProjectController::class, 'storeRevenue'])->name('special-projects.revenues.store');
    Route::put('/special-projects/{project}/revenues/{revenue}', [App\Http\Controllers\Admin\SpecialProjectController::class, 'updateRevenue'])->name('special-projects.revenues.update');
    Route::delete('/special-projects/{project}/revenues/{revenue}', [App\Http\Controllers\Admin\SpecialProjectController::class, 'destroyRevenue'])->name('special-projects.revenues.destroy');
    Route::post('/special-projects/{project}/revenues/{revenue}/attachment', [App\Http\Controllers\Admin\SpecialProjectController::class, 'uploadRevenueAttachment'])->name('special-projects.revenues.upload-attachment');
    Route::get('/special-projects/{project}/edit', [App\Http\Controllers\Admin\SpecialProjectController::class, 'edit'])->name('special-projects.edit');
    Route::put('/special-projects/{project}', [App\Http\Controllers\Admin\SpecialProjectController::class, 'update'])->name('special-projects.update');
    Route::delete('/special-projects/{project}', [App\Http\Controllers\Admin\SpecialProjectController::class, 'destroy'])->name('special-projects.destroy');
    
    Route::delete('work-orders/{workOrder}', [App\Http\Controllers\Admin\WorkOrderDeleteController::class, '__invoke'])->name('work-orders.destroy');
    Route::post('work-orders/{workOrder}/send-note', [App\Http\Controllers\Admin\WorkOrderController::class, 'sendNote'])->name('work-orders.send-note');
    
    // Notifications routes
    Route::get('notifications', [App\Http\Controllers\Admin\WorkOrderController::class, 'getNotifications'])->name('notifications.index');
    Route::post('notifications/{notification}/mark-read', [App\Http\Controllers\Admin\WorkOrderController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\Admin\WorkOrderController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
    Route::post('notifications/reply', [App\Http\Controllers\Admin\WorkOrderController::class, 'replyToNotification'])->name('notifications.reply');
    
    // Turnkey Revenues routes
    Route::post('turnkey-revenues/store', [App\Http\Controllers\Admin\TurnkeyRevenueController::class, 'store'])->name('turnkey-revenues.store');
    Route::post('turnkey-revenues/{id}/update', [App\Http\Controllers\Admin\TurnkeyRevenueController::class, 'update'])->name('turnkey-revenues.update');
    Route::delete('turnkey-revenues/{id}/delete', [App\Http\Controllers\Admin\TurnkeyRevenueController::class, 'destroy'])->name('turnkey-revenues.delete');
    Route::post('turnkey-revenues/upload-attachment', [App\Http\Controllers\Admin\TurnkeyRevenueController::class, 'uploadAttachment'])->name('turnkey-revenues.upload-attachment');
    Route::post('work-orders/{workOrder}/save-materials-notes', [MaterialsController::class, 'saveMaterialsNotes'])->name('work-orders.save-materials-notes');
    
    // التقارير العامة
    Route::get('reports/unified', [App\Http\Controllers\Admin\ReportsController::class, 'unified'])->name('reports.unified');
    
    // Work Orders Routes
    Route::get('work-orders', [App\Http\Controllers\WorkOrderController::class, 'index'])->name('work-orders.index');
    Route::get('work-orders/create', [App\Http\Controllers\WorkOrderController::class, 'create'])->name('work-orders.create');
    Route::get('work-orders/execution-productivity', [App\Http\Controllers\WorkOrderController::class, 'executionProductivity'])->name('work-orders.execution-productivity');
    
    // صفحة إيرادات المشاريع الموحدة (مشرف النظام فقط)
    Route::get('all-projects-revenues', [App\Http\Controllers\WorkOrderController::class, 'allProjectsRevenues'])
        ->name('all-projects-revenues');
    Route::get('all-projects-revenues/export', [App\Http\Controllers\WorkOrderController::class, 'exportAllProjectsRevenues'])
        ->name('all-projects-revenues.export');
    
    Route::get('work-orders/revenues', [App\Http\Controllers\WorkOrderController::class, 'revenues'])->name('work-orders.revenues');
    Route::post('work-orders/revenues/save', [App\Http\Controllers\WorkOrderController::class, 'saveRevenue'])->name('work-orders.revenues.save');
    Route::post('work-orders/revenues/import', [App\Http\Controllers\WorkOrderController::class, 'importRevenues'])->name('work-orders.revenues.import');
    Route::post('work-orders/revenues/upload-attachment', [App\Http\Controllers\WorkOrderController::class, 'uploadRevenueAttachment'])->name('work-orders.revenues.upload-attachment');
    
    // برنامج العمل اليومي
    Route::get('work-orders/daily-program', [App\Http\Controllers\WorkOrderController::class, 'dailyProgram'])->name('work-orders.daily-program');
    Route::post('work-orders/daily-program', [App\Http\Controllers\WorkOrderController::class, 'storeDailyProgram'])->name('work-orders.daily-program.store');
    Route::put('work-orders/daily-program/{dailyWorkProgram}', [App\Http\Controllers\WorkOrderController::class, 'updateDailyProgram'])->name('work-orders.daily-program.update');
    Route::delete('work-orders/daily-program/{dailyWorkProgram}', [App\Http\Controllers\WorkOrderController::class, 'destroyDailyProgram'])->name('work-orders.daily-program.destroy');
    Route::get('work-orders/daily-program/get-users', [App\Http\Controllers\WorkOrderController::class, 'getDailyProgramUsers'])->name('work-orders.daily-program.get-users');
    Route::post('work-orders/daily-program/send-notification', [App\Http\Controllers\WorkOrderController::class, 'sendDailyProgramNotification'])->name('work-orders.daily-program.send-notification');
    Route::get('work-orders/{workOrder}/notifications', [App\Http\Controllers\WorkOrderController::class, 'getWorkOrderNotifications'])->name('work-orders.notifications');
    Route::post('work-orders/{workOrder}/update-notes', [App\Http\Controllers\WorkOrderController::class, 'updateNotes'])->name('work-orders.update-notes');
    Route::post('work-orders/{workOrder}/update-status-note', [App\Http\Controllers\WorkOrderController::class, 'updateStatusNote'])->name('work-orders.update-status-note');
    Route::delete('work-orders/revenues/{id}', [App\Http\Controllers\WorkOrderController::class, 'deleteRevenue'])->name('work-orders.revenues.delete');
    Route::get('work-orders/export/excel', [App\Http\Controllers\WorkOrderController::class, 'exportExcel'])->name('work-orders.export.excel');
    Route::post('work-orders', [App\Http\Controllers\WorkOrderController::class, 'store'])->name('work-orders.store');
    Route::get('work-orders/{workOrder}', [App\Http\Controllers\WorkOrderController::class, 'show'])->name('work-orders.show');
    Route::get('work-orders/{workOrder}/edit', [App\Http\Controllers\WorkOrderController::class, 'edit'])->name('work-orders.edit');
    Route::put('work-orders/{workOrder}', [App\Http\Controllers\WorkOrderController::class, 'update'])->name('work-orders.update');
    // Route::delete('work-orders/{workOrder}', [App\Http\Controllers\Admin\WorkOrderDeleteController::class, '__invoke'])->name('admin.work-orders.destroy');
    
    // تصدير أوامر العمل لإكسل
    Route::get('work-orders/export/excel', [WorkOrderController::class, 'exportExcel'])->name('work-orders.export.excel');
    
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
    Route::get('work-orders/{workOrder}/materials/export', [MaterialsController::class, 'exportExcel'])->name('work-orders.materials.export');
    Route::post('work-orders/{workOrder}/materials', [MaterialsController::class, 'store'])->name('work-orders.materials.store');
    Route::post('work-orders/{workOrder}/materials/add-all-from-work-order', [MaterialsController::class, 'addAllFromWorkOrderMaterials'])->name('work-orders.materials.add-all-from-work-order');
    Route::post('work-orders/{workOrder}/materials/update-quantity', [MaterialsController::class, 'updateQuantity'])->name('work-orders.materials.update-quantity');
    Route::post('work-orders/{workOrder}/materials/update-notes', [MaterialsController::class, 'updateNotes'])->name('work-orders.materials.update-notes');
    Route::get('work-orders/{workOrder}/materials/{material}', [MaterialsController::class, 'show'])->name('work-orders.materials.show');
    
    // Post-execution routes
    Route::get('work-orders/{workOrder}/actions-execution', [WorkOrderController::class, 'actionsExecution'])->name('work-orders.actions-execution');
    Route::post('work-orders/{workOrder}/upload-post-execution-file', [WorkOrderController::class, 'uploadPostExecutionFile'])->name('work-orders.upload-post-execution-file');
    Route::get('work-orders/{workOrder}/get-files', [WorkOrderController::class, 'getFiles'])->name('work-orders.get-files');
    
    // Daily execution management routes
    Route::put('work-orders/daily-executions/{dailyExecution}', [WorkOrderController::class, 'updateDailyExecution'])->name('work-orders.daily-executions.update');
    Route::delete('work-orders/daily-executions/{dailyExecution}', [WorkOrderController::class, 'deleteDailyExecution'])->name('work-orders.daily-executions.delete');
    Route::put('work-orders/{workOrder}/materials/{material}', [MaterialsController::class, 'update'])->name('work-orders.materials.update');
    Route::delete('work-orders/{workOrder}/materials/{material}', [MaterialsController::class, 'destroy'])->name('work-orders.materials.destroy');
    Route::delete('work-orders/materials/work-order-material/{workOrderMaterial}', [MaterialsController::class, 'destroyWorkOrderMaterial'])->name('work-orders.materials.destroy-work-order-material');
    Route::get('materials/description/{code}', [MaterialsController::class, 'getDescriptionByCode'])->name('materials.description');
        
    // مسارات Excel للمواد - moved to admin group
        
    // روتات الملفات المرفقة للمواد
    Route::post('work-orders/{workOrder}/materials/upload-files', [MaterialsController::class, 'uploadFiles'])->name('work-orders.materials.upload-files');
    Route::delete('work-orders/{workOrder}/materials/{material}/delete-file', [MaterialsController::class, 'deleteFile'])->name('work-orders.materials.delete-file');
    
    // مسارات مواد الإزالة والسكراب
    Route::post('work-orders/{workOrder}/removal-scrap-materials', [MaterialsController::class, 'addRemovalScrapMaterial'])->name('work-orders.add-removal-scrap-material');
    Route::delete('work-orders/{workOrder}/removal-scrap-materials/{index}', [MaterialsController::class, 'deleteRemovalScrapMaterial'])->name('work-orders.delete-removal-scrap-material');
    
    // وظائف أوامر العمل الأخرى
    Route::delete('work-orders/files/{file}', [WorkOrderController::class, 'deleteFile'])->name('work-orders.files.delete');
    Route::get('work-orders/descriptions/{workType}', [WorkOrderController::class, 'getWorkDescription'])->name('work-orders.descriptions');
    Route::get('work-orders/search/{orderNumber}', [App\Http\Controllers\Admin\WorkOrderController::class, 'searchByOrderNumber'])->name('work-orders.search');
    
    // تسجيل Resource بعد تسجيل المسارات المخصصة
    Route::resource('work-orders', WorkOrderController::class)->parameters([
        'work-orders' => 'workOrder'
    ]);
    
    // صفحات المسح (Survey)
    Route::get('work-orders/{workOrder}/survey', [WorkOrderController::class, 'survey'])->name('work-orders.survey');
    Route::post('work-orders/{workOrder}/survey', [WorkOrderController::class, 'storeSurvey'])->name('work-orders.survey.store');
    Route::post('work-orders/{workOrder}/survey/completion-files', [WorkOrderController::class, 'uploadCompletionFiles'])->name('work-orders.survey.completion-files');
    Route::get('work-orders/survey/{survey}/edit', [WorkOrderController::class, 'editSurvey'])->name('work-orders.survey.edit');
    Route::put('work-orders/survey/{survey}', [WorkOrderController::class, 'updateSurvey'])->name('work-orders.survey.update');
    Route::delete('work-orders/survey/files/{file}', [WorkOrderController::class, 'deleteSurveyFile'])->name('work-orders.survey.files.delete');
    Route::get('work-orders/get-license-data', [WorkOrderController::class, 'getLicenseData'])->name('work-orders.get-license-data');
    Route::delete('work-orders/survey/{workOrder}/{survey}', [WorkOrderController::class, 'destroySurvey'])->name('work-orders.survey.destroy');
    
    // صفحات الرخص والتنفيذ
    Route::get('work-orders/licenses', [WorkOrderController::class, 'licenses'])->name('work-orders.licenses');
    Route::get('work-orders/{workOrder}/execution', [App\Http\Controllers\Admin\WorkOrderController::class, 'execution'])->name('work-orders.execution');
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
    
    // صفحة السلامة
    Route::get('work-orders/{workOrder}/safety', [WorkOrderController::class, 'safety'])->name('work-orders.safety');
    Route::put('work-orders/{workOrder}/safety', [WorkOrderController::class, 'updateSafety'])->name('work-orders.update-safety');
    Route::post('work-orders/{workOrder}/upload-safety', [WorkOrderController::class, 'uploadSafety'])->name('work-orders.upload-safety');
    Route::delete('work-orders/{workOrder}/safety-image/{category}/{index}', [WorkOrderController::class, 'deleteSafetyImage'])->name('work-orders.delete-safety-image');
    Route::delete('work-orders/{workOrder}/safety-file/{category}/{index}', [WorkOrderController::class, 'deleteSafetyFile'])->name('work-orders.delete-safety-file');
    Route::delete('work-orders/{workOrder}/non-compliance-attachment/{index}', [WorkOrderController::class, 'deleteNonComplianceAttachment'])->name('work-orders.delete-non-compliance-attachment');
    Route::post('work-orders/{workOrder}/safety-violation', [WorkOrderController::class, 'addSafetyViolation'])->name('work-orders.add-safety-violation');
    Route::delete('work-orders/safety-violation/{violationId}', [WorkOrderController::class, 'deleteSafetyViolation'])->name('work-orders.delete-safety-violation');



    
    // Survey Routes
    Route::get('work-orders/{workOrder}/survey', [WorkOrderController::class, 'survey'])->name('work-orders.survey');
    Route::post('work-orders/{workOrder}/survey', [WorkOrderController::class, 'storeSurvey'])->name('work-orders.survey.store');
    Route::get('work-orders/survey/{survey}/edit', [WorkOrderController::class, 'editSurvey'])->name('work-orders.survey.edit');
    Route::put('work-orders/survey/{survey}', [WorkOrderController::class, 'updateSurvey'])->name('work-orders.survey.update');



    // حذف سجل العمليات المدخلة
    Route::delete('work-orders/logs/{log}', [\App\Http\Controllers\WorkOrderController::class, 'deleteLog'])->name('work-orders.delete-log');

    // Order Entries
    Route::post('/admin/work-orders/{work_order}/order-entries', [OrderEntryController::class, 'store'])->name('order-entries.store');
    Route::delete('/admin/order-entries/{id}', [OrderEntryController::class, 'destroy'])->name('order-entries.destroy');


    
    // مسارات Excel لبنود العمل - moved to admin group
    
    // مسار البحث في المواد المرجعية
    Route::get('work-orders/search-materials', [WorkOrderController::class, 'searchReferenceMaterials'])
        ->name('work-orders.search-materials');
        


    // مسارات إدارة الرخص
    Route::get('licenses', [\App\Http\Controllers\Admin\LicenseController::class, 'display'])->name('licenses.index');
    Route::get('licenses/display', [\App\Http\Controllers\Admin\LicenseController::class, 'display'])->name('licenses.display');
    Route::get('work-orders/licenses', [WorkOrderController::class, 'licenses'])->name('work-orders.licenses');
    Route::get('work-orders/licenses/data', [\App\Http\Controllers\Admin\LicenseController::class, 'display'])->name('work-orders.licenses.data');
    Route::post('licenses', [\App\Http\Controllers\Admin\LicenseController::class, 'store'])->name('licenses.store');
    Route::post('licenses/save-section', [\App\Http\Controllers\Admin\LicenseController::class, 'saveSection'])->name('licenses.save-section');
    // مسار مؤقت لحل مشكلة save-section1 - يجب حذفه لاحقاً
    Route::post('licenses/save-section1', [\App\Http\Controllers\Admin\LicenseController::class, 'saveSection'])->name('licenses.save-section1');
    Route::delete('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'destroy'])->name('licenses.destroy');
    Route::post('licenses/{license}/toggle-status', [\App\Http\Controllers\Admin\LicenseController::class, 'toggleStatus'])->name('licenses.toggle-status');
    Route::get('licenses/export/excel', [\App\Http\Controllers\Admin\LicenseController::class, 'exportExcel'])->name('licenses.export.excel');
    Route::put('licenses/{license}/update', [\App\Http\Controllers\Admin\LicenseController::class, 'update'])->name('licenses.update-inline');
    
    // مسارات عرض الرخص
    Route::get('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'show'])->name('licenses.show');
            Route::get('licenses/{license}/pdf', [\App\Http\Controllers\Admin\LicenseController::class, 'exportPdf'])->name('licenses.export-pdf');
        Route::post('licenses/{license}/remove-evacuation-file', [\App\Http\Controllers\Admin\LicenseController::class, 'removeEvacuationFile'])->name('licenses.remove-evacuation-file');

    // تحديث بيانات الفسح للإخلاء
    Route::post('licenses/update-evac-streets/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'updateEvacStreets'])->name('licenses.update-evac-streets');
    
    // بيانات الإخلاءات التفصيلية
    Route::post('licenses/save-evacuation-data', [\App\Http\Controllers\Admin\LicenseController::class, 'saveEvacuationData'])->name('licenses.save-evacuation-data');
Route::post('licenses/save-evacuation-data-simple', [\App\Http\Controllers\Admin\LicenseController::class, 'saveEvacuationDataSimple'])->name('licenses.save-evacuation-data-simple');
                Route::get('licenses/get-evacuation-data/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'getEvacuationData'])->name('licenses.get-evacuation-data');

    // مسارات التمديدات
    Route::get('licenses/extensions/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'getExtensionsByWorkOrder'])->name('licenses.extensions.by-work-order');
    Route::get('licenses/extensions/by-license/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'getExtensionsByLicense'])->name('licenses.extensions.by-license');
    Route::delete('license-extensions/{extension}', [\App\Http\Controllers\Admin\LicenseController::class, 'deleteExtension'])->name('license-extensions.destroy');
    
    // Evacuation attachments routes
    Route::post('licenses/evacuation-attachments/upload', [\App\Http\Controllers\Admin\LicenseController::class, 'uploadEvacuationAttachments'])->name('licenses.evacuation-attachments.upload');
    Route::get('licenses/{license}/evacuation-attachments', [\App\Http\Controllers\Admin\LicenseController::class, 'getEvacuationAttachments'])->name('licenses.evacuation-attachments.get');
    Route::delete('licenses/{license}/evacuation-attachments/{index}', [\App\Http\Controllers\Admin\LicenseController::class, 'deleteEvacuationAttachment'])->name('licenses.evacuation-attachments.delete');

    // مسارات الاختبارات المعملية الديناميكية
    Route::post('licenses/lab-test/save-status', [\App\Http\Controllers\Admin\LicenseController::class, 'saveLabTestStatus'])->name('licenses.lab-test.save-status');
    Route::post('licenses/lab-test/upload-file', [\App\Http\Controllers\Admin\LicenseController::class, 'uploadLabTestFile'])->name('licenses.lab-test.upload-file');
    Route::post('licenses/lab-test/delete-file', [\App\Http\Controllers\Admin\LicenseController::class, 'deleteLabTestFile'])->name('licenses.lab-test.delete-file');

    // License Violations routes
    Route::get('licenses/{license}/violations', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'index'])->name('license-violations.index');
    Route::get('violations/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'getByWorkOrder'])->name('violations.by-work-order');
    Route::get('licenses/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'getByWorkOrder'])->name('licenses.by-work-order');
    Route::get('licenses/all-by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseController::class, 'getAllByWorkOrder'])->name('licenses.all-by-work-order');
    Route::post('licenses/search-by-number', [\App\Http\Controllers\Admin\LicenseController::class, 'searchByNumber'])->name('licenses.search-by-number');
    Route::put('licenses/{license}', [\App\Http\Controllers\Admin\LicenseController::class, 'update'])->name('licenses.update');
    Route::get('licenses-list/by-work-order/{workOrder}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'getLicensesByWorkOrder'])->name('licenses-list.by-work-order');
    Route::post('license-violations', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'store'])->name('license-violations.store');
    Route::get('license-violations/{violation}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'show'])->name('license-violations.show');
    Route::put('license-violations/{violation}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'update'])->name('license-violations.update');
    Route::delete('license-violations/{violation}', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'destroy'])->name('license-violations.destroy');
    Route::patch('license-violations/{violation}/status', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'updateStatus'])->name('license-violations.update-status');
    Route::delete('license-violations/bulk-destroy', [\App\Http\Controllers\Admin\LicenseViolationController::class, 'bulkDestroy'])->name('license-violations.bulk-destroy');






    // مرفقات الفاتورة
    Route::post('work-orders/{workOrder}/invoice-attachments', [\App\Http\Controllers\Admin\InvoiceAttachmentController::class, 'store'])
        ->name('work-orders.invoice-attachments.store');
    Route::delete('work-orders/invoice-attachments/{invoiceAttachment}', [\App\Http\Controllers\Admin\InvoiceAttachmentController::class, 'destroy'])
        ->name('work-orders.invoice-attachments.destroy');

    // Excavation Details Routes
    Route::post('licenses/{license}/excavation-details', [ExcavationDetailController::class, 'store'])->name('excavation-details.store');
    Route::put('excavation-details/{excavationDetail}', [ExcavationDetailController::class, 'update'])->name('excavation-details.update');
    Route::delete('excavation-details/{excavationDetail}', [ExcavationDetailController::class, 'destroy'])->name('excavation-details.destroy');
    
    // General Productivity Routes
    Route::get('work-orders/general-productivity', [App\Http\Controllers\Admin\WorkOrderController::class, 'generalProductivity'])->name('work-orders.general-productivity');
    Route::get('work-orders/general-productivity-data', [App\Http\Controllers\Admin\WorkOrderController::class, 'getGeneralProductivityData'])->name('work-orders.general-productivity-data');
    
    // عرض أوامر العمل حسب الحالة - منفصل لكل مدينة
    Route::get('work-orders/status/inprogress/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusInProgressRiyadh'])->name('work-orders.status.inprogress.riyadh');
    Route::get('work-orders/status/inprogress/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusInProgressMadinah'])->name('work-orders.status.inprogress.madinah');
    Route::get('work-orders/status/executed/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusExecutedRiyadh'])->name('work-orders.status.executed.riyadh');
    Route::get('work-orders/status/executed/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusExecutedMadinah'])->name('work-orders.status.executed.madinah');
    Route::get('work-orders/status/delivery155/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusDelivery155Riyadh'])->name('work-orders.status.delivery155.riyadh');
    Route::get('work-orders/status/delivery155/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusDelivery155Madinah'])->name('work-orders.status.delivery155.madinah');
    Route::get('work-orders/status/firstextract/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusFirstExtractRiyadh'])->name('work-orders.status.firstextract.riyadh');
    Route::get('work-orders/status/firstextract/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusFirstExtractMadinah'])->name('work-orders.status.firstextract.madinah');
    Route::get('work-orders/status/paidfirst/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusPaidFirstRiyadh'])->name('work-orders.status.paidfirst.riyadh');
    Route::get('work-orders/status/paidfirst/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusPaidFirstMadinah'])->name('work-orders.status.paidfirst.madinah');
    Route::get('work-orders/status/secondextract/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusSecondExtractRiyadh'])->name('work-orders.status.secondextract.riyadh');
    Route::get('work-orders/status/secondextract/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusSecondExtractMadinah'])->name('work-orders.status.secondextract.madinah');
    Route::get('work-orders/status/totalextract/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusTotalExtractRiyadh'])->name('work-orders.status.totalextract.riyadh');
    Route::get('work-orders/status/totalextract/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusTotalExtractMadinah'])->name('work-orders.status.totalextract.madinah');
    Route::get('work-orders/status/certificate/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusCertificateRiyadh'])->name('work-orders.status.certificate.riyadh');
    Route::get('work-orders/status/certificate/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusCertificateMadinah'])->name('work-orders.status.certificate.madinah');
    Route::get('work-orders/status/completed/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusCompletedRiyadh'])->name('work-orders.status.completed.riyadh');
    Route::get('work-orders/status/completed/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'statusCompletedMadinah'])->name('work-orders.status.completed.madinah');
    Route::get('work-orders/survey-results/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'surveyResultsRiyadh'])->name('work-orders.survey.riyadh');
    Route::get('work-orders/survey-results/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'surveyResultsMadinah'])->name('work-orders.survey.madinah');
    Route::get('work-orders/survey-results/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportSurveyNeeds'])->name('work-orders.survey.export');
    Route::get('work-orders/completion-files/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'completionFilesRiyadh'])->name('work-orders.completion.riyadh');
    Route::get('work-orders/completion-files/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'completionFilesMadinah'])->name('work-orders.completion.madinah');
    Route::get('work-orders/completion-files/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportCompletionFiles'])->name('work-orders.completion.export');
    Route::get('work-orders/{id}/completion-files', [App\Http\Controllers\Admin\WorkOrderController::class, 'getCompletionFiles'])->name('work-orders.completion.files');
    Route::post('work-orders/{id}/mark-surveyed', [App\Http\Controllers\Admin\WorkOrderController::class, 'markAsSurveyed'])->name('work-orders.mark-surveyed');
    
    // Quality Reports Routes
    Route::get('quality/violations/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'violationsRiyadh'])->name('quality.violations.riyadh');
    Route::get('quality/violations/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'violationsMadinah'])->name('quality.violations.madinah');
    Route::get('quality/violations/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportViolations'])->name('quality.violations.export');
    Route::get('quality/safety-violations/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'safetyViolationsRiyadh'])->name('quality.safety-violations.riyadh');
    Route::get('quality/safety-violations/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'safetyViolationsMadinah'])->name('quality.safety-violations.madinah');
    Route::get('quality/safety-violations/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportSafetyViolations'])->name('quality.safety-violations.export');
Route::get('quality/extensions/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'extensionsRiyadh'])->name('quality.extensions.riyadh');
Route::get('quality/extensions/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'extensionsMadinah'])->name('quality.extensions.madinah');
Route::get('quality/extensions/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportExtensions'])->name('quality.extensions.export');
    Route::get('quality/inspections/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'inspectionsRiyadh'])->name('quality.inspections.riyadh');
    Route::get('quality/inspections/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'inspectionsMadinah'])->name('quality.inspections.madinah');
    Route::get('quality/inspections/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportInspections'])->name('quality.inspections.export');
    Route::get('quality/inspections-reports/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'inspectionsReportsRiyadh'])->name('quality.inspections-reports.riyadh');
    Route::get('quality/inspections-reports/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'inspectionsReportsMadinah'])->name('quality.inspections-reports.madinah');
    Route::get('quality/evacuations/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'evacuationsRiyadh'])->name('quality.evacuations.riyadh');
    Route::get('quality/evacuations/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'evacuationsMadinah'])->name('quality.evacuations.madinah');
    Route::get('quality/evacuations/{city}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportEvacuations'])->name('quality.evacuations.export');
    
    // All Licenses Routes (جميع الرخص)
    Route::get('licenses/all/riyadh', [App\Http\Controllers\Admin\LicenseController::class, 'allLicensesRiyadh'])->name('licenses.all.riyadh');
    Route::get('licenses/all/madinah', [App\Http\Controllers\Admin\LicenseController::class, 'allLicensesMadinah'])->name('licenses.all.madinah');
    Route::get('licenses/all/{city}/export', [App\Http\Controllers\Admin\LicenseController::class, 'exportAllLicenses'])->name('licenses.all.export');
    
    // Time Management Routes (تجاوز المدة الزمنية والغير منفذه)
    Route::get('time-management/overdue/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'overdueOrdersRiyadh'])->name('time-management.overdue.riyadh');
    Route::get('time-management/overdue/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'overdueOrdersMadinah'])->name('time-management.overdue.madinah');
    Route::get('time-management/overdue/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportOverdueOrders'])->name('time-management.export-overdue');
    Route::get('work-orders/status/inprogress/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportInProgressOrders'])->name('work-orders.status.export-inprogress');
    Route::get('work-orders/status/executed/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportExecutedOrders'])->name('work-orders.status.export-executed');
    Route::get('work-orders/status/delivery155/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportDelivery155Orders'])->name('work-orders.status.export-delivery155');
    Route::get('work-orders/status/firstextract/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportFirstExtractOrders'])->name('work-orders.status.export-firstextract');
    Route::get('work-orders/status/paidfirst/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportPaidFirstOrders'])->name('work-orders.status.export-paidfirst');
    Route::get('work-orders/status/certificate/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportCertificateOrders'])->name('work-orders.status.export-certificate');
    Route::get('work-orders/status/secondextract/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportSecondExtractOrders'])->name('work-orders.status.export-secondextract');
    Route::get('work-orders/status/totalextract/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportTotalExtractOrders'])->name('work-orders.status.export-totalextract');
    Route::get('work-orders/status/completed/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportCompletedOrders'])->name('work-orders.status.export-completed');
    Route::get('time-management/unexecuted/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'unexecutedOrdersRiyadh'])->name('time-management.unexecuted.riyadh');
    Route::get('time-management/unexecuted/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'unexecutedOrdersMadinah'])->name('time-management.unexecuted.madinah');
    Route::get('time-management/unexecuted/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportUnexecutedOrders'])->name('time-management.export-unexecuted');
    Route::get('time-management/detailed-report/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'detailedReportRiyadh'])->name('time-management.detailed-report.riyadh');
    Route::get('time-management/detailed-report/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'detailedReportMadinah'])->name('time-management.detailed-report.madinah');
    Route::get('time-management/obstacles/{project}/export', [App\Http\Controllers\Admin\WorkOrderController::class, 'exportObstacles'])->name('time-management.export-obstacles');
    
    // Materials and Warehouses Status Routes
    Route::get('materials/executed-disbursed/riyadh', [App\Http\Controllers\Admin\MaterialController::class, 'executedDisbursedRiyadh'])->name('materials.executed-disbursed.riyadh');
    Route::get('materials/executed-disbursed/madinah', [App\Http\Controllers\Admin\MaterialController::class, 'executedDisbursedMadinah'])->name('materials.executed-disbursed.madinah');
    Route::get('materials/to-return/riyadh', [App\Http\Controllers\Admin\MaterialController::class, 'toReturnRiyadh'])->name('materials.to-return.riyadh');
    Route::get('materials/to-return/madinah', [App\Http\Controllers\Admin\MaterialController::class, 'toReturnMadinah'])->name('materials.to-return.madinah');
    Route::get('materials/to-disburse/riyadh', [App\Http\Controllers\Admin\MaterialController::class, 'toDisburseRiyadh'])->name('materials.to-disburse.riyadh');
    Route::get('materials/to-disburse/madinah', [App\Http\Controllers\Admin\MaterialController::class, 'toDisburseMadinah'])->name('materials.to-disburse.madinah');
    
    // تقارير الإنتاجية حسب المدينة
    Route::get('work-orders/productivity/riyadh', [App\Http\Controllers\Admin\WorkOrderController::class, 'riyadhProductivity'])->name('work-orders.productivity.riyadh');
    Route::get('work-orders/productivity/madinah', [App\Http\Controllers\Admin\WorkOrderController::class, 'madinahProductivity'])->name('work-orders.productivity.madinah');
    
    // صور التنفيذ
    Route::post('work-orders/{workOrder}/execution/images', [App\Http\Controllers\Admin\WorkOrderController::class, 'uploadExecutionImages'])->name('work-orders.execution.upload-images');
    Route::delete('work-orders/{workOrder}/execution/images/{image}', [App\Http\Controllers\Admin\WorkOrderController::class, 'deleteExecutionImage'])->name('work-orders.execution.delete-image');
    Route::get('work-orders/{workOrder}/daily-totals', [App\Http\Controllers\Admin\WorkOrderController::class, 'dailyTotals'])->name('work-orders.daily-totals');
    
    // ملاحظات التنفيذ اليومية
    Route::post('work-orders/{workOrder}/execution/notes', [App\Http\Controllers\Admin\WorkOrderController::class, 'saveDailyExecutionNote'])->name('work-orders.execution.save-note');
    Route::get('work-orders/{workOrder}/execution/notes', [App\Http\Controllers\Admin\WorkOrderController::class, 'getDailyExecutionNote'])->name('work-orders.execution.get-note');
    
    
    // Excel Import Routes
    Route::post('work-orders/import-work-items', [WorkOrderController::class, 'importWorkItems'])->name('work-orders.import-work-items');
    Route::post('work-orders/import-materials', [WorkOrderController::class, 'importWorkOrderMaterials'])->name('work-orders.import-materials');
    Route::get('work-orders/download-materials-template', [WorkOrderController::class, 'downloadMaterialsTemplate'])->name('work-orders.download-materials-template');
    Route::get('work-orders/work-items', [WorkOrderController::class, 'getWorkItems'])->name('work-orders.work-items');
});

// General Productivity Routes (accessible without admin prefix)
Route::middleware(['auth'])->group(function () {
    Route::get('work-orders/general-productivity', [App\Http\Controllers\Admin\WorkOrderController::class, 'generalProductivity'])->name('general-productivity');
    Route::get('work-orders/general-productivity-data', [App\Http\Controllers\Admin\WorkOrderController::class, 'getGeneralProductivityData'])->name('general-productivity-data');
});

// Project Selection Routes
Route::middleware(['auth'])->group(function () {
    // Changed from closure to controller method
    Route::get('/project/type-selection', [App\Http\Controllers\ProjectController::class, 'showProjectTypeSelection'])
        ->middleware(\App\Http\Middleware\ProjectPermissionMiddleware::class . ':turnkey_projects')
        ->name('project.type-selection');
    
    Route::post('/project/set-type', [App\Http\Controllers\ProjectController::class, 'setProjectType'])
        ->name('project.set-type');
    
    Route::get('/project/current-type', [App\Http\Controllers\ProjectController::class, 'getCurrentProjectType'])
        ->name('project.current-type');

    Route::get('/projects/create', [App\Http\Controllers\ProjectController::class, 'create'])
        ->name('projects.create');
    Route::post('/projects', [App\Http\Controllers\ProjectController::class, 'store'])
        ->name('projects.store');
    Route::get('/projects/{project}', [App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects/{project}/upload', [App\Http\Controllers\ProjectController::class, 'upload'])->name('projects.upload');
});

// Project Selection Route
Route::get('/project-selection', function () {
    return view('projects.project-selection', ['hideNavbar' => false]);
})->middleware(['auth', \App\Http\Middleware\ProjectPermissionMiddleware::class . ':unified_contracts'])->name('project.selection');

// مسار مؤقت لتعيين المستخدم الحالي كمسؤول - يجب حذفه بعد الاستخدام





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

     

        return 'Violation created successfully with ID: ' . $violation->id;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>Trace: ' . $e->getTraceAsString();
    }
})->middleware('auth');

// Project Type Selection Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/project/type-selection', [ProjectController::class, 'showProjectTypeSelection'])
        ->name('project.type-selection');
    
    Route::post('/project/set-type', [ProjectController::class, 'setProjectType'])
        ->name('project.set-type');
    
    Route::get('/project/current-type', [ProjectController::class, 'getCurrentProjectType'])
        ->name('project.current-type');
});





    // مسارات ملفات الرخص
    Route::get('licenses/evacuation-file/{license}/{index}', [\App\Http\Controllers\Admin\LicenseController::class, 'showEvacuationFile'])->name('licenses.evacuation-file');
    Route::get('licenses/{license}/download/{type}', [\App\Http\Controllers\Admin\LicenseController::class, 'downloadFile'])->name('admin.licenses.download');


// مسارات البحث والحفظ التلقائي للمواد
Route::get('admin/materials/search', [MaterialsController::class, 'search'])->name('admin.materials.search');
Route::post('admin/materials/save', [MaterialsController::class, 'saveReference'])->name('admin.materials.save');

// صفحات تفاصيل المواد العامة للمدن
Route::get('admin/materials/riyadh-overview', [MaterialsController::class, 'riyadhOverview'])->name('admin.materials.riyadh-overview');
Route::get('admin/materials/madinah-overview', [MaterialsController::class, 'madinahOverview'])->name('admin.materials.madinah-overview');
Route::get('admin/materials/{city}-overview', function(\Illuminate\Http\Request $request, $city) {
    return app(MaterialsController::class)->cityOverview($request, $city);
})->name('admin.materials.city-overview');

// Debug route to check database content
Route::get('admin/materials/debug-projects', function() {
    $projects = \App\Models\WorkOrder::select('id', 'order_number', 'project_name', 'project_description', 'city')
        ->take(20)
        ->get();
    
    $materialsCount = \App\Models\WorkOrderMaterial::count();
    
    return response()->json([
        'total_work_orders' => \App\Models\WorkOrder::count(),
        'total_materials' => $materialsCount,
        'sample_projects' => $projects->toArray()
    ]);
})->name('admin.materials.debug');

// Project Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/upload', [ProjectController::class, 'upload'])->name('projects.upload');
    
    // Project Management Routes
    Route::get('/projects/{project}/design', [ProjectController::class, 'design'])->name('projects.design');
    Route::get('/projects/{project}/supplying', [ProjectController::class, 'supplying'])->name('projects.supplying');
    Route::get('/projects/{project}/installation', [ProjectController::class, 'installation'])->name('projects.installation');
    Route::get('/projects/{project}/testing', [ProjectController::class, 'testing'])->name('projects.testing');
    Route::get('/projects/{project}/quality', [ProjectController::class, 'quality'])->name('projects.quality');
    Route::get('/projects/{project}/safety', [ProjectController::class, 'safety'])->name('projects.safety');
    Route::get('/projects/{project}/bid-package', [ProjectController::class, 'bidPackage'])->name('projects.bid-package');
    Route::get('/projects/{project}/bid-package/folder/{folderName}', [ProjectController::class, 'viewBidPackageFolder'])->name('projects.bid-package.folder');
    Route::post('/projects/{project}/bid-package/create-folder', [ProjectController::class, 'createBidPackageFolder'])->name('projects.bid-package.create-folder');
    Route::post('/projects/{project}/bid-package/upload-files', [ProjectController::class, 'uploadBidPackageFiles'])->name('projects.bid-package.upload-files');
    
    // Bid Package Sub-sections Routes
    Route::get('/projects/{project}/bid-package/vol1', [ProjectController::class, 'bidPackageVol1'])->name('projects.bid-package.vol1');
    Route::post('/projects/{project}/bid-package/vol1/create-folder', [ProjectController::class, 'createBidPackageVol1Folder'])->name('projects.bid-package.vol1.create-folder');
    Route::post('/projects/{project}/bid-package/vol1/upload-files', [ProjectController::class, 'uploadBidPackageVol1Files'])->name('projects.bid-package.vol1.upload-files');
    Route::get('/projects/{project}/bid-package/vol1/folder/{folderName}', [ProjectController::class, 'viewBidPackageVol1Folder'])->name('projects.bid-package.vol1.folder');
    
    Route::get('/projects/{project}/bid-package/vol2', [ProjectController::class, 'bidPackageVol2'])->name('projects.bid-package.vol2');
    Route::post('/projects/{project}/bid-package/vol2/create-folder', [ProjectController::class, 'createBidPackageVol2Folder'])->name('projects.bid-package.vol2.create-folder');
    Route::post('/projects/{project}/bid-package/vol2/upload-files', [ProjectController::class, 'uploadBidPackageVol2Files'])->name('projects.bid-package.vol2.upload-files');
    Route::get('/projects/{project}/bid-package/vol2/folder/{folderName}', [ProjectController::class, 'viewBidPackageVol2Folder'])->name('projects.bid-package.vol2.folder');
    
    Route::get('/projects/{project}/bid-package/documents', [ProjectController::class, 'bidPackageDocuments'])->name('projects.bid-package.documents');
    Route::post('/projects/{project}/bid-package/documents/create-folder', [ProjectController::class, 'createBidPackageDocumentsFolder'])->name('projects.bid-package.documents.create-folder');
    Route::post('/projects/{project}/bid-package/documents/upload-files', [ProjectController::class, 'uploadBidPackageDocumentsFiles'])->name('projects.bid-package.documents.upload-files');
    Route::get('/projects/{project}/bid-package/documents/folder/{folderName}', [ProjectController::class, 'viewBidPackageDocumentsFolder'])->name('projects.bid-package.documents.folder');
    
    // Study Routes
    Route::get('/projects/{project}/study', [ProjectController::class, 'study'])->name('projects.study');
    Route::get('/projects/{project}/study/folder/{folderName}', [ProjectController::class, 'viewStudyFolder'])->name('projects.study.folder');
    Route::post('/projects/{project}/study/create-folder', [ProjectController::class, 'createStudyFolder'])->name('projects.study.create-folder');
    Route::post('/projects/{project}/study/upload-files', [ProjectController::class, 'uploadStudyFiles'])->name('projects.study.upload-files');
    
    // Study Sub-sections Routes
    Route::get('/projects/{project}/study/site-visit', [ProjectController::class, 'studySiteVisit'])->name('projects.study.site-visit');
    Route::post('/projects/{project}/study/site-visit/create-folder', [ProjectController::class, 'createStudySiteVisitFolder'])->name('projects.study.site-visit.create-folder');
    Route::post('/projects/{project}/study/site-visit/upload-files', [ProjectController::class, 'uploadStudySiteVisitFiles'])->name('projects.study.site-visit.upload-files');
    Route::get('/projects/{project}/study/site-visit/folder/{folderName}', [ProjectController::class, 'viewStudySiteVisitFolder'])->name('projects.study.site-visit.folder');
    
    Route::get('/projects/{project}/study/tech-files', [ProjectController::class, 'studyTechFiles'])->name('projects.study.tech-files');
    Route::post('/projects/{project}/study/tech-files/create-folder', [ProjectController::class, 'createStudyTechFilesFolder'])->name('projects.study.tech-files.create-folder');
    Route::post('/projects/{project}/study/tech-files/upload-files', [ProjectController::class, 'uploadStudyTechFilesFiles'])->name('projects.study.tech-files.upload-files');
    Route::get('/projects/{project}/study/tech-files/folder/{folderName}', [ProjectController::class, 'viewStudyTechFilesFolder'])->name('projects.study.tech-files.folder');
    
    Route::get('/projects/{project}/study/commercial-files', [ProjectController::class, 'studyCommercialFiles'])->name('projects.study.commercial-files');
    Route::post('/projects/{project}/study/commercial-files/create-folder', [ProjectController::class, 'createStudyCommercialFilesFolder'])->name('projects.study.commercial-files.create-folder');
    Route::post('/projects/{project}/study/commercial-files/upload-files', [ProjectController::class, 'uploadStudyCommercialFilesFiles'])->name('projects.study.commercial-files.upload-files');
    Route::get('/projects/{project}/study/commercial-files/folder/{folderName}', [ProjectController::class, 'viewStudyCommercialFilesFolder'])->name('projects.study.commercial-files.folder');
    
    Route::get('/projects/{project}/study/sch-c', [ProjectController::class, 'studySchC'])->name('projects.study.sch-c');
    Route::post('/projects/{project}/study/sch-c/create-folder', [ProjectController::class, 'createStudySchCFolder'])->name('projects.study.sch-c.create-folder');
    Route::post('/projects/{project}/study/sch-c/upload-files', [ProjectController::class, 'uploadStudySchCFiles'])->name('projects.study.sch-c.upload-files');
    Route::get('/projects/{project}/study/sch-c/folder/{folderName}', [ProjectController::class, 'viewStudySchCFolder'])->name('projects.study.sch-c.folder');
    
    Route::get('/projects/{project}/study/documents', [ProjectController::class, 'studyDocuments'])->name('projects.study.documents');
    Route::post('/projects/{project}/study/documents/create-folder', [ProjectController::class, 'createStudyDocumentsFolder'])->name('projects.study.documents.create-folder');
    Route::post('/projects/{project}/study/documents/upload-files', [ProjectController::class, 'uploadStudyDocumentsFiles'])->name('projects.study.documents.upload-files');
    Route::get('/projects/{project}/study/documents/folder/{folderName}', [ProjectController::class, 'viewStudyDocumentsFolder'])->name('projects.study.documents.folder');
    
    // Clarification Routes
    Route::get('/projects/{project}/clarification', [ProjectController::class, 'clarification'])->name('projects.clarification');
    Route::get('/projects/{project}/clarification/folder/{folderName}', [ProjectController::class, 'viewClarificationFolder'])->name('projects.clarification.folder');
    Route::post('/projects/{project}/clarification/create-folder', [ProjectController::class, 'createClarificationFolder'])->name('projects.clarification.create-folder');
    Route::post('/projects/{project}/clarification/upload-files', [ProjectController::class, 'uploadClarificationFiles'])->name('projects.clarification.upload-files');
    
    // Clarification Sub-sections Routes
    // CLA1
    Route::get('/projects/{project}/clarification/cla1', [ProjectController::class, 'clarificationCla1'])->name('projects.clarification.cla1');
    Route::post('/projects/{project}/clarification/cla1/create-folder', [ProjectController::class, 'createClarificationCla1Folder'])->name('projects.clarification.cla1.create-folder');
    Route::post('/projects/{project}/clarification/cla1/upload-files', [ProjectController::class, 'uploadClarificationCla1Files'])->name('projects.clarification.cla1.upload-files');
    Route::get('/projects/{project}/clarification/cla1/folder/{folderName}', [ProjectController::class, 'viewClarificationCla1Folder'])->name('projects.clarification.cla1.folder');
    
    // CLA2
    Route::get('/projects/{project}/clarification/cla2', [ProjectController::class, 'clarificationCla2'])->name('projects.clarification.cla2');
    Route::post('/projects/{project}/clarification/cla2/create-folder', [ProjectController::class, 'createClarificationCla2Folder'])->name('projects.clarification.cla2.create-folder');
    Route::post('/projects/{project}/clarification/cla2/upload-files', [ProjectController::class, 'uploadClarificationCla2Files'])->name('projects.clarification.cla2.upload-files');
    Route::get('/projects/{project}/clarification/cla2/folder/{folderName}', [ProjectController::class, 'viewClarificationCla2Folder'])->name('projects.clarification.cla2.folder');
    
    // CLA3
    Route::get('/projects/{project}/clarification/cla3', [ProjectController::class, 'clarificationCla3'])->name('projects.clarification.cla3');
    Route::post('/projects/{project}/clarification/cla3/create-folder', [ProjectController::class, 'createClarificationCla3Folder'])->name('projects.clarification.cla3.create-folder');
    Route::post('/projects/{project}/clarification/cla3/upload-files', [ProjectController::class, 'uploadClarificationCla3Files'])->name('projects.clarification.cla3.upload-files');
    Route::get('/projects/{project}/clarification/cla3/folder/{folderName}', [ProjectController::class, 'viewClarificationCla3Folder'])->name('projects.clarification.cla3.folder');
    
    Route::get('/projects/{project}/supplying', [ProjectController::class, 'supplying'])->name('projects.supplying');
    Route::get('/projects/{project}/supplying/folder/{folderName}', [ProjectController::class, 'viewSupplyingFolder'])->name('projects.supplying.folder');
    Route::post('/projects/{project}/supplying/create-folder', [ProjectController::class, 'createSupplyingFolder'])->name('projects.supplying.create-folder');
    Route::post('/projects/{project}/supplying/upload-files', [ProjectController::class, 'uploadSupplyingFiles'])->name('projects.supplying.upload-files');
    
    // Supplying Sub-sections Routes
    Route::get('/projects/{project}/supplying/supplying', [ProjectController::class, 'supplyingSupplying'])->name('projects.supplying.supplying');
    Route::post('/projects/{project}/supplying/supplying/create-folder', [ProjectController::class, 'createSupplyingSupplyingFolder'])->name('projects.supplying.supplying.create-folder');
    Route::post('/projects/{project}/supplying/supplying/upload-files', [ProjectController::class, 'uploadSupplyingSupplyingFiles'])->name('projects.supplying.supplying.upload-files');
    Route::get('/projects/{project}/supplying/supplying/folder/{folderName}', [ProjectController::class, 'viewSupplyingSupplyingFolder'])->name('projects.supplying.supplying.folder');
    
    Route::get('/projects/{project}/supplying/installation', [ProjectController::class, 'supplyingInstallation'])->name('projects.supplying.installation');
    Route::post('/projects/{project}/supplying/installation/create-folder', [ProjectController::class, 'createSupplyingInstallationFolder'])->name('projects.supplying.installation.create-folder');
    Route::post('/projects/{project}/supplying/installation/upload-files', [ProjectController::class, 'uploadSupplyingInstallationFiles'])->name('projects.supplying.installation.upload-files');
    Route::get('/projects/{project}/supplying/installation/folder/{folderName}', [ProjectController::class, 'viewSupplyingInstallationFolder'])->name('projects.supplying.installation.folder');
    
    Route::get('/projects/{project}/reports', [ProjectController::class, 'reports'])->name('projects.reports');
    
    Route::get('/projects/{project}/documents', [ProjectController::class, 'documents'])->name('projects.documents');
    Route::get('/projects/{project}/documents/folder/{folderName}', [ProjectController::class, 'viewDocumentsFolder'])->name('projects.documents.folder');
    Route::post('/projects/{project}/documents/create-folder', [ProjectController::class, 'createDocumentsFolder'])->name('projects.documents.create-folder');
    Route::post('/projects/{project}/documents/upload-files', [ProjectController::class, 'uploadDocumentsFiles'])->name('projects.documents.upload-files');
    
    Route::get('/projects/{project}/revenues', [ProjectController::class, 'revenues'])->name('projects.revenues');
    Route::get('/projects/{project}/revenues/folder/{folderName}', [ProjectController::class, 'viewRevenuesFolder'])->name('projects.revenues.folder');
    Route::post('/projects/{project}/revenues/create-folder', [ProjectController::class, 'createRevenuesFolder'])->name('projects.revenues.create-folder');
    Route::post('/projects/{project}/revenues/upload-files', [ProjectController::class, 'uploadRevenuesFiles'])->name('projects.revenues.upload-files');
    
    // Revenue API Routes
    Route::get('/projects/{project}/revenues-data', [App\Http\Controllers\RevenueController::class, 'index'])->name('revenues.index');
    Route::post('/projects/{project}/revenues', [App\Http\Controllers\RevenueController::class, 'store'])->name('revenues.store');
    Route::post('/projects/{project}/revenues/import', [App\Http\Controllers\RevenueController::class, 'import'])->name('revenues.import');
    Route::put('/projects/{project}/revenues/{revenue}', [App\Http\Controllers\RevenueController::class, 'update'])->name('revenues.update');
    Route::delete('/projects/{project}/revenues/{revenue}', [App\Http\Controllers\RevenueController::class, 'destroy'])->name('revenues.destroy');
    
    // Design Files Routes
    Route::post('/projects/{project}/design/upload', [App\Http\Controllers\DesignFileController::class, 'upload'])->name('design.files.upload');
    Route::get('/projects/{project}/design/{section}/files', [App\Http\Controllers\DesignFileController::class, 'getFiles'])->name('design.files.get');
    Route::get('/projects/{project}/design/{section}/{filename}/download', [App\Http\Controllers\DesignFileController::class, 'download'])->name('design.files.download');
    Route::get('/projects/{project}/design/{section}/{filename}/view', [App\Http\Controllers\DesignFileController::class, 'view'])->name('design.files.view');
    Route::delete('/projects/{project}/design/{section}/{filename}', [App\Http\Controllers\DesignFileController::class, 'delete'])->name('design.files.delete');
    
    // Supplying Files Routes
    Route::post('/projects/{project}/supplying/upload', [App\Http\Controllers\DesignFileController::class, 'uploadSupplying'])->name('supplying.files.upload');
    Route::get('/projects/{project}/supplying/{section}/files', [App\Http\Controllers\DesignFileController::class, 'getSupplyingFiles'])->name('supplying.files.get');
    Route::get('/projects/{project}/supplying/{section}/{filename}/download', [App\Http\Controllers\DesignFileController::class, 'downloadSupplying'])->name('supplying.files.download');
    Route::get('/projects/{project}/supplying/{section}/{filename}/view', [App\Http\Controllers\DesignFileController::class, 'viewSupplying'])->name('supplying.files.view');
    Route::delete('/projects/{project}/supplying/{section}/{filename}', [App\Http\Controllers\DesignFileController::class, 'deleteSupplying'])->name('supplying.files.delete');
    
    // Installation Files Routes
    Route::post('/projects/{project}/installation/upload', [App\Http\Controllers\DesignFileController::class, 'uploadInstallation'])->name('installation.files.upload');
    Route::get('/projects/{project}/installation/{section}/files', [App\Http\Controllers\DesignFileController::class, 'getInstallationFiles'])->name('installation.files.get');
    Route::get('/projects/{project}/installation/{section}/{filename}/download', [App\Http\Controllers\DesignFileController::class, 'downloadInstallation'])->name('installation.files.download');
    Route::get('/projects/{project}/installation/{section}/{filename}/view', [App\Http\Controllers\DesignFileController::class, 'viewInstallation'])->name('installation.files.view');
    Route::delete('/projects/{project}/installation/{section}/{filename}', [App\Http\Controllers\DesignFileController::class, 'deleteInstallation'])->name('installation.files.delete');
    
    // Quality Files Routes
    Route::post('/projects/{project}/quality/upload', [App\Http\Controllers\DesignFileController::class, 'uploadQuality'])->name('quality.files.upload');
    Route::get('/projects/{project}/quality/{section}/files', [App\Http\Controllers\DesignFileController::class, 'getQualityFiles'])->name('quality.files.get');
    Route::get('/projects/{project}/quality/{section}/{filename}/download', [App\Http\Controllers\DesignFileController::class, 'downloadQuality'])->name('quality.files.download');
    Route::get('/projects/{project}/quality/{section}/{filename}/view', [App\Http\Controllers\DesignFileController::class, 'viewQuality'])->name('quality.files.view');
    Route::delete('/projects/{project}/quality/{section}/{filename}', [App\Http\Controllers\DesignFileController::class, 'deleteQuality'])->name('quality.files.delete');
    
// Reports Files Routes
Route::post('/projects/{project}/reports/upload', [App\Http\Controllers\DesignFileController::class, 'uploadReports'])->name('reports.files.upload');
Route::get('/projects/{project}/reports/{section}/files', [App\Http\Controllers\DesignFileController::class, 'getReportsFiles'])->name('reports.files.get');
Route::get('/projects/{project}/reports/{section}/{filename}/download', [App\Http\Controllers\DesignFileController::class, 'downloadReports'])->name('reports.files.download');
Route::get('/projects/{project}/reports/{section}/{filename}/view', [App\Http\Controllers\DesignFileController::class, 'viewReports'])->name('reports.files.view');
Route::delete('/projects/{project}/reports/{section}/{filename}', [App\Http\Controllers\DesignFileController::class, 'deleteReports'])->name('reports.files.delete');

});
