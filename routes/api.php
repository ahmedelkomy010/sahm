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