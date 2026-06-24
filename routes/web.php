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
use App\Http\Controllers\Dashboard\Admin\AdminOrderController;
use App\Http\Controllers\Dashboard\Admin\PharmacyApplicationController;
use App\Http\Controllers\Dashboard\Pharmacy\GoogleController;
use App\Http\Controllers\Dashboard\Pharmacy\PharmacyInventoryController;
use App\Http\Controllers\Dashboard\Pharmacy\PharmacyMainController;
use App\Http\Controllers\Dashboard\Pharmacy\PharmacyProfileController;

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

    // Notifications
    Route::get('/notifications/page', [\App\Http\Controllers\NotificationController::class, 'adminPage'])->name('admin.notifications.page');
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount']);
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/send', [\App\Http\Controllers\NotificationController::class, 'sendCustomNotification']);

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

    // Platform Orders (Admin View)
    Route::resource('orders', AdminOrderController::class)->only(['index']);

    // Pharmacy Wallets (Admin View)
    Route::get('wallets', [\App\Http\Controllers\Dashboard\Admin\AdminWalletController::class, 'index'])->name('admin.wallets.index');

    // Withdrawals (Admin View)
    Route::get('withdrawals', [\App\Http\Controllers\Dashboard\Admin\AdminWithdrawalController::class, 'index'])->name('admin.withdrawals.index');
    Route::patch('withdrawals/{withdrawal}/approve', [\App\Http\Controllers\Dashboard\Admin\AdminWithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
    Route::patch('withdrawals/{withdrawal}/reject', [\App\Http\Controllers\Dashboard\Admin\AdminWithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');
});


Route::prefix('pharmacy')->name('pharmacy.')->middleware(['auth', 'role:pharmacy', 'is_active'])->group(function () {

    // 1. مسارات متاحة لأي صيدلي (سواء معتمد أو لا يزال قيد المراجعة)
    // Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');
    // Route::get('/application', [PharmacyApplicationController::class, 'index'])->name('application.index');
    // Route::post('/apply', [PharmacyApplicationController::class, 'store'])->name('apply');

    // 2. مسارات محمية بـ Middleware (مخصصة فقط للصيدليات المعتمدة)
    Route::middleware(['approved_pharmacy'])->group(function () {

        // Orders Management
        Route::get('/orders', [\App\Http\Controllers\Dashboard\Pharmacy\PharmacyOrderController::class, 'index'])->name('orders');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Dashboard\Pharmacy\PharmacyOrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/wallet', [\App\Http\Controllers\Dashboard\Pharmacy\PharmacyWalletController::class, 'index'])->name('wallet');
        Route::post('/wallet/withdraw', [\App\Http\Controllers\Dashboard\Pharmacy\PharmacyWalletController::class, 'requestWithdrawal'])->name('wallet.withdraw');

        // Chat Routes
        Route::get('/chats', function () {
            return view('pharmacy.chat.index');
        })->name('chats');
        Route::get('/chats/sessions', [\App\Http\Controllers\Dashboard\Pharmacy\ChatController::class, 'getSessions']);
        Route::get('/chats/{session}/messages', [\App\Http\Controllers\Dashboard\Pharmacy\ChatController::class, 'getMessages']);
        Route::post('/chats/{session}/messages', [\App\Http\Controllers\Dashboard\Pharmacy\ChatController::class, 'sendMessage']);
        Route::post('/chats/{session}/read', [\App\Http\Controllers\Dashboard\Pharmacy\ChatController::class, 'markAsRead']);
        // لوحة التحكم الأساسية
        Route::get('/dashboard', [PharmacyMainController::class, 'index'])->name('dashboard');

        // Notifications
        Route::get('/notifications/page', [\App\Http\Controllers\NotificationController::class, 'pharmacyPage'])->name('notifications.page');
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount']);
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);

        // مسارات الأدوية والمخزون (الآن سيصبح اسمها تلقائياً: pharmacy.medicines.index)
        Route::resource('medicines', PharmacyInventoryController::class)->only(['index', 'store', 'update', 'destroy']);

        // صفحة الملف الشخصي (عرض البيانات)
    Route::get('/profile', [PharmacyProfileController::class, 'index'])->name('profile.index');

    // مسار تحديث البيانات (PUT)
    Route::put('/profile/update', [PharmacyProfileController::class, 'update'])->name('profile.update');
    });
});
Route::prefix('pharmacy')->name('pharmacy.')->middleware(['auth'])->group(function () {
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');
    Route::get('/application', [PharmacyApplicationController::class, 'index'])->name('application.index');
    Route::post('/apply', [PharmacyApplicationController::class, 'store'])->name('apply');
});
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

