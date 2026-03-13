<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Admin\AdminAuthController;
use App\Http\Controllers\Dashboard\Admin\AdminCategoryController;
use App\Http\Controllers\Dashboard\Admin\AdminMedicineController;
use App\Http\Controllers\Dashboard\Admin\AdminPharmacyApplicationController;
use App\Http\Controllers\Dashboard\Admin\AdminPharmacyController;
use App\Http\Controllers\Dashboard\Admin\AdminProfileController;
use App\Http\Controllers\Dashboard\Admin\PharmacyApplicationController;
use App\Http\Controllers\Dashboard\Pharmacy\GoogleController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login')->middleware('guest');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('admin.dashboard');


    // Admin Profile Routes
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::put('/profile/info', [AdminProfileController::class, 'updateInfo'])->name('admin.profile.info');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    //categories routes
    Route::resource('categories', AdminCategoryController::class);
    Route::patch('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    //medicines routes
    Route::resource('medicines', AdminMedicineController::class);
    Route::patch('/medicines/{medicine}/toggle-status', [AdminMedicineController::class, 'toggleStatus'])->name('medicines.toggle-status');

    //pharmacies application routes
    Route::resource('pharmaciesApplications', AdminPharmacyApplicationController::class);
    Route::put('/pharmaciesApplications/{id}/status', [AdminPharmacyApplicationController::class, 'updateStatus'])->name('admin.pharmaciesApplications.status');

    //pharmacies routes
    Route::resource('pharmacies', AdminPharmacyController::class);
    Route::post('/pharmacies/{id}/toggle-status', [AdminPharmacyController::class, 'toggleStatus'])->name('admin.pharmacies.toggle');
});

Route::prefix('pharmacy')->middleware(['auth', 'role:pharmacy'])->group(function () {
    Route::post('/logout', [GoogleController::class, 'logout'])->name('pharmacy.logout');
    Route::get('/pharmacyApplication', [PharmacyApplicationController::class, 'index'])->name('pharmacy.Application.index');
    Route::post('/pharmacy/apply', [PharmacyApplicationController::class, 'store'])->name('pharmacy.apply');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
