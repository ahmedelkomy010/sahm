<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LabLicenseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});


// Lab Licenses Routes
Route::get('/lab-licenses', [LabLicenseController::class, 'index']);
Route::post('/lab-licenses', [LabLicenseController::class, 'store']);
Route::put('/lab-licenses/{id}', [LabLicenseController::class, 'update']);
Route::delete('/lab-licenses/{id}', [LabLicenseController::class, 'destroy']);

// Work Orders API Routes
Route::middleware('web')->group(function () {
    // Receipts Dashboard
    Route::get('/work-orders/receipts', [\App\Http\Controllers\Api\WorkOrderController::class, 'receipts']);
    Route::get('/work-orders/receipts/export', [\App\Http\Controllers\Api\WorkOrderController::class, 'exportReceipts']);
    
    // Execution Dashboard
    Route::get('/work-orders/execution', [\App\Http\Controllers\Api\WorkOrderController::class, 'execution']);
    Route::get('/work-orders/execution/export', [\App\Http\Controllers\Api\WorkOrderController::class, 'exportExecution']);
    
    // In Progress Dashboard
    Route::get('/work-orders/inprogress', [\App\Http\Controllers\Api\WorkOrderController::class, 'inProgress']);
    Route::get('/work-orders/inprogress/export', [\App\Http\Controllers\Api\WorkOrderController::class, 'exportInProgress']);
    
    // Extracts Dashboard
    Route::get('/work-orders/extracts', [\App\Http\Controllers\Api\WorkOrderController::class, 'extracts']);
    Route::get('/work-orders/extracts/export', [\App\Http\Controllers\Api\WorkOrderController::class, 'exportExtracts']);
    
    // Completed Dashboard
    Route::get('/work-orders/completed', [\App\Http\Controllers\Api\WorkOrderController::class, 'completed']);
    Route::get('/work-orders/completed/export', [\App\Http\Controllers\Api\WorkOrderController::class, 'exportCompleted']);
}); 