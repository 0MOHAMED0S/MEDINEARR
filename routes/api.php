<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\FacebookApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\Auth\SocialLogoutController;
use App\Http\Controllers\Api\DataAnalysis\DataAnalysisController;
use Illuminate\Support\Facades\Route;


// 1. Authentication Routes (مسارات تسجيل الدخول لا تحتاج لتوكن)
Route::post('/auth/google/login', [GoogleApiController::class, 'loginWithGoogle']);
Route::post('/auth/facebook/login', [FacebookApiController::class, 'loginWithFacebook']);

// 2. Protected Routes (المسارات المحمية)
Route::middleware('auth:sanctum')->group(function () {

    // Profile Management
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    // Logout
    Route::post('/logout', [SocialLogoutController::class, 'logout']);
    Route::post('/logout-all', [SocialLogoutController::class, 'logoutAllDevices']);

    //location
    Route::post('/profile/location', [AuthController::class, 'updateLocation']);
});

Route::prefix('data-analysis')
    ->middleware('api.key')
    ->group(function () {
        Route::get('/users', [DataAnalysisController::class, 'users']);
        Route::get('/pharmacies', [DataAnalysisController::class, 'pharmacies']);
        Route::get('/medicines', [DataAnalysisController::class, 'medicines']);
        Route::get('/categories', [DataAnalysisController::class, 'categories']);
    });
