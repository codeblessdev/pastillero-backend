<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DependentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\AdminController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);
Route::apiResource('pharmacies', PharmacyController::class)->middleware('auth:sanctum');
Route::get('admin/dashboard', [AdminController::class, 'index'])->middleware('role:admin');
Route::get('preferences', [PreferenceController::class, 'index'])->middleware('auth:sanctum');
Route::post('preferences', [PreferenceController::class, 'store'])->middleware('auth:sanctum');





Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('dependents', DependentController::class);
    Route::get('dependents/caregiver/{caregiverId}', [DependentController::class, 'getByCaregiverId']);
    Route::get('/treatments/active', [TreatmentController::class, 'activeTreatments']);
    Route::get('/treatments/history', [TreatmentController::class, 'treatmentHistory']);
    Route::post('/treatments', [TreatmentController::class, 'store']);
    Route::put('/treatments/{treatment}', [TreatmentController::class, 'update']);
    Route::delete('/treatments/{treatment}', [TreatmentController::class, 'destroy']);
    Route::patch('/treatments/{treatment}/stock', [TreatmentController::class, 'updateStock']);
});

Route::middleware('auth:sanctum')->prefix('reports')->group(function () {
    Route::get('active-treatments', [ReportController::class, 'activeTreatmentsReport']);
    Route::get('history', [ReportController::class, 'historyReport']);
    Route::get('stock', [ReportController::class, 'stockReport']);
});

