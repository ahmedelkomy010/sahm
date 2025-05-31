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

// Lab Licenses Routes
Route::group(['prefix' => 'lab-licenses'], function () {
    Route::get('/', [LabLicenseController::class, 'index']);
    Route::post('/', [LabLicenseController::class, 'store']);
    Route::put('/{id}', [LabLicenseController::class, 'update']);
    Route::delete('/{id}', [LabLicenseController::class, 'destroy']);
}); 