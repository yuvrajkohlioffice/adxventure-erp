<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CrmController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/late-reason', [AuthController::class, 'late_reason']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile',[AuthController::class, 'user_profile']);
    Route::get('/dashboard',[AuthController::class, 'dashboard']);
  
    Route::controller(CrmController::class)->prefix('crm')->name('crm.')->group(function () {
        Route::get('leads','leads');
        Route::get('lead-profile/{id}','lead_profile');
        Route::post('followup','followup');
    });
});