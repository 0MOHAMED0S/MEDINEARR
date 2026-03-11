<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Admin\AdminAuthController;
use App\Http\Controllers\Dashboard\Admin\AdminCategoryController;
use App\Http\Controllers\Dashboard\Admin\AdminMedicineController;
use App\Http\Controllers\Dashboard\Admin\AdminPharmacyController;
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
    Route::get('/dashboard', function () {return view('dashboard.index');})->name('admin.dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    //categories routes
    Route::resource('categories', AdminCategoryController::class);
    Route::patch('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    //medicines routes
    Route::resource('medicines', AdminMedicineController::class);
    Route::patch('/medicines/{medicine}/toggle-status', [AdminMedicineController::class, 'toggleStatus'])->name('medicines.toggle-status');

    Route::resource('pharmacies', AdminPharmacyController::class);

});
Route::prefix('pharmacy')->middleware(['auth', 'role:pharmacy'])->group(function () {
    Route::post('/logout', [GoogleController::class, 'logout'])->name('pharmacy.logout');
    Route::get('/pharmacyApplication', [PharmacyApplicationController::class, 'index'])->name('pharmacy.Application.index');
    Route::post('/pharmacy/apply', [PharmacyApplicationController::class, 'store'])->name('pharmacy.apply');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
