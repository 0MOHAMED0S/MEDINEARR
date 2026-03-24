<?php

use App\Http\Controllers\Api\Ads\AdController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\FacebookApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\Auth\SocialLogoutController;
use App\Http\Controllers\Api\Categories\CategoryController;
use App\Http\Controllers\Api\DataAnalysis\DataAnalysisController;
use App\Http\Controllers\Api\Medicines\MedicineController;
use App\Http\Controllers\Api\Pharmacies\PharmacyController;
use App\Http\Controllers\Api\Pharmacies\PharmacySearchController;
use App\Http\Controllers\Api\Pharmacies\NearMedicinesController;
use Illuminate\Support\Facades\Route;

// 1. Authentication Routes (مسارات تسجيل الدخول لا تحتاج لتوكن)
Route::post('/auth/google/login', [GoogleApiController::class, 'loginWithGoogle']);
Route::post('/auth/facebook/login', [FacebookApiController::class, 'loginWithFacebook']);

// 2. Protected Routes (المسارات المحمية)
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    // Profile Management
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    // Logout
    Route::post('/logout', [SocialLogoutController::class, 'logout']);
    Route::post('/logout-all', [SocialLogoutController::class, 'logoutAllDevices']);
    //location
    Route::post('/profile/location', [AuthController::class, 'updateLocation']);
});

Route::prefix('pharmacy')->middleware(['auth:sanctum', 'role:user'])
    ->group(function () {
        Route::get('/pharmacies', [PharmacyController::class, 'index']);
        Route::get('/ads', [AdController::class, 'index']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/medicines', [MedicineController::class, 'index']);
        Route::get('/near-pharmacies', [PharmacySearchController::class, 'index']);
        Route::get('/near-medicines', [NearMedicinesController::class, 'index']);
        Route::get('/{id}/inventory', [PharmacyController::class, 'getInventory']);
    });

Route::prefix('data-analysis')
    ->middleware('api.key')
    ->group(function () {
        Route::get('/users', [DataAnalysisController::class, 'users']);
        Route::get('/pharmacies', [DataAnalysisController::class, 'pharmacies']);
        Route::get('/medicines', [DataAnalysisController::class, 'medicines']);
        Route::get('/categories', [DataAnalysisController::class, 'categories']);
        Route::get('/pharmacy-inventory', [DataAnalysisController::class, 'pharmacyInventory']);
    });
