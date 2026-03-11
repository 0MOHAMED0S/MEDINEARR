<?php

use App\Http\Controllers\Api\Auth\AuthApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\FacebookApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\Auth\SocialLogoutController;
use Illuminate\Http\Request;
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
