<?php

use App\Http\Controllers\Dashboard\Admin\AdminAdController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Admin\AdminAuthController;
use App\Http\Controllers\Dashboard\Admin\AdminCategoryController;
use App\Http\Controllers\Dashboard\Admin\AdminCouponController;
use App\Http\Controllers\Dashboard\Admin\AdminDeliveryCompanyController;
use App\Http\Controllers\Dashboard\Admin\AdminMainController;
use App\Http\Controllers\Dashboard\Admin\AdminMedicineController;
use App\Http\Controllers\Dashboard\Admin\AdminPharmacyApplicationController;
use App\Http\Controllers\Dashboard\Admin\AdminPharmacyController;
use App\Http\Controllers\Dashboard\Admin\AdminProfileController;
use App\Http\Controllers\Dashboard\Admin\AdminUsersController;
use App\Http\Controllers\Dashboard\Admin\PharmacyApplicationController;
use App\Http\Controllers\Dashboard\Pharmacy\GoogleController;
use App\Http\Controllers\Dashboard\Pharmacy\PharmacyInventoryController;
use App\Http\Controllers\Dashboard\Pharmacy\PharmacyMainController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login')->middleware('guest');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminMainController::class, 'index'])->name('admin.dashboard');

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

    Route::post('pharmacies/{id}/toggle-big', [AdminPharmacyController::class, 'toggleBigPharmacy']);

    // Coupons Routes
    Route::resource('coupons', AdminCouponController::class)->except(['create', 'show', 'edit']);
    Route::post('coupons/{coupon}/toggle-status', [AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle-status');

    //ads
    Route::resource('ads', AdminAdController::class)->except(['create', 'show', 'edit']);
    Route::post('ads/{ad}/toggle-status', [AdminAdController::class, 'toggleStatus'])->name('admin.ads.toggle-status');

    //users
    Route::resource('users', AdminUsersController::class)->only(['index', 'update', 'destroy']);
    Route::post('users/{user}/toggle-status', [AdminUsersController::class, 'toggleStatus'])->name('users.toggle-status');

    //delivery
    Route::patch('delivery-companies/{deliveryCompany}/toggle-status', [AdminDeliveryCompanyController::class, 'toggleStatus'])->name('delivery_companies.toggle_status');
    Route::resource('delivery-companies', AdminDeliveryCompanyController::class)->except(['create', 'show', 'edit']);
});
Route::prefix('pharmacy')->name('pharmacy.')->middleware(['auth', 'role:pharmacy', 'is_active'])->group(function () {

    // 1. مسارات متاحة لأي صيدلي (سواء معتمد أو لا يزال قيد المراجعة)
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');
    Route::get('/application', [PharmacyApplicationController::class, 'index'])->name('application.index');
    Route::post('/apply', [PharmacyApplicationController::class, 'store'])->name('apply');

    // 2. مسارات محمية بـ Middleware (مخصصة فقط للصيدليات المعتمدة)
    Route::middleware(['approved_pharmacy'])->group(function () {

        // لوحة التحكم الأساسية
        Route::get('/dashboard', [PharmacyMainController::class, 'index'])->name('dashboard');

        // مسارات الأدوية والمخزون (الآن سيصبح اسمها تلقائياً: pharmacy.medicines.index)
        Route::resource('medicines', PharmacyInventoryController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
