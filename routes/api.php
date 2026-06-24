<?php

use App\Http\Controllers\Api\Ads\AdController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\FacebookApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\Auth\SocialLogoutController;
use App\Http\Controllers\Api\Categories\CategoryController;
use App\Http\Controllers\Api\DataAnalysis\DataAnalysisController;
use App\Http\Controllers\Api\Medicines\MedicineController;
use App\Http\Controllers\Api\Orders\CheckoutController;
use App\Http\Controllers\Api\Pharmacies\PacketItemController;
use App\Http\Controllers\Api\Pharmacies\PharmacyController;
use App\Http\Controllers\Api\Pharmacies\NearMedicinesController;
use App\Http\Controllers\Api\Pharmacies\NearPharmaciesController;
use App\Http\Controllers\Api\Pharmacies\PacketController;
use App\Http\Controllers\Api\Pharmacies\PharmacySearchController;
use App\Http\Controllers\Api\Pharmacies\SavePharmaciesController;
use App\Http\Controllers\Api\Pharmacies\SaveMedicinesController;
use App\Http\Controllers\Api\Pharmacies\SaveCartController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

// 1. Authentication Routes (مسارات تسجيل الدخول لا تحتاج لتوكن)
Route::post('/auth/google/login', [GoogleApiController::class, 'loginWithGoogle']);
Route::post('/auth/facebook/login', [FacebookApiController::class, 'loginWithFacebook']);

// 2. Protected Routes (المسارات المحمية)
Route::middleware(['auth:sanctum'])->group(function () {
    // Profile Management
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    // Logout
    Route::post('/logout', [SocialLogoutController::class, 'logout']);
    Route::post('/logout-all', [SocialLogoutController::class, 'logoutAllDevices']);
    //location
    Route::post('/profile/location', [AuthController::class, 'updateLocation']);

    // Chat Routes
    Route::get('/chat/sessions', [\App\Http\Controllers\Api\ChatController::class, 'getSessions']);
    Route::post('/chat/sessions', [\App\Http\Controllers\Api\ChatController::class, 'startSession']);
    Route::get('/chat/{session}/messages', [\App\Http\Controllers\Api\ChatController::class, 'getMessages']);
    Route::post('/chat/{session}/messages', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage']);
    Route::post('/chat/{session}/read', [\App\Http\Controllers\Api\ChatController::class, 'markAsRead']);

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount']);
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
});

Route::prefix('pharmacy')->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/pharmacies', [PharmacyController::class, 'index']);
        Route::get('/ads', [AdController::class, 'index']);

        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}/medicines', [CategoryController::class, 'getCategoryMedicines']);
        Route::get('/categories/{id}/medicines/details', [CategoryController::class, 'getMedicineDetails']);

        Route::get('/medicines', [MedicineController::class, 'index']);
        Route::post('/medicine-details', [MedicineController::class, 'show']);
        Route::get('/near-pharmacies', [NearPharmaciesController::class, 'index']);
        Route::get('/near-medicines', [NearMedicinesController::class, 'index']);
        Route::get('/{id}/inventory', [PharmacyController::class, 'getInventory']);
        Route::get('/search', [PharmacySearchController::class, 'index']);
        Route::get('/search/recent', [PharmacySearchController::class, 'recentSearches']);
        Route::post('/save/medicine', [SaveMedicinesController::class, 'toggleMedicine']);
        Route::get('/medicine/saved', [SaveMedicinesController::class, 'index']);
        Route::post('/save/pharmacy', [SavePharmaciesController::class, 'togglePharmacy']);
        Route::get('/pharmacy/saved', [SavePharmaciesController::class, 'index']);
        Route::post('/save/cart', [SaveCartController::class, 'toggleItem']);
        Route::put('/save/cart/update-quantity', [SaveCartController::class, 'updateQuantity']);
        Route::get('/save/cart/pharmacies', [SaveCartController::class, 'CartPharmacies']);
        Route::post('/save/cart/items', [SaveCartController::class, 'PharmacyCartItems']);
        Route::apiResource('packets', PacketController::class);
        // 1. عرض ملخص الطلب (السعر، التوصيل، الإجمالي)
        Route::post('/cart/checkout', [CheckoutController::class, 'summary']);
        // 2. التحقق من الكوبون وتطبيقه
        Route::post('/cart/apply-coupon', [CheckoutController::class, 'applyCoupon']);
        // 3. إتمام الطلب (الدفع)
        Route::post('/cart/place-order', [CheckoutController::class, 'placeOrder']);

        // 4. جلب الطلبات السابقة للمستخدم
        Route::get('/orders', [\App\Http\Controllers\Api\Orders\OrderController::class, 'index']);
        Route::get('/orders/pharmacy/{id}', [\App\Http\Controllers\Api\Orders\OrderController::class, 'pharmacyOrders']);


        // 📄 CRUD الخاص بالعناصر داخل الحقيبة
        Route::prefix('packets/{packet_id}/items')->group(function () {
            Route::get('/', [PacketItemController::class, 'index']);
            Route::post('/', [PacketItemController::class, 'store']);
            Route::post('/{item_id}', [PacketItemController::class, 'update']); // نستخدم POST للـ Update بسبب ملفات الـ Image
            Route::delete('/{item_id}', [PacketItemController::class, 'destroy']);
        });
    });

Route::prefix('data-analysis')
    ->middleware('api.key')
    ->group(function () {
        Route::get('/users', [DataAnalysisController::class, 'users']);
        Route::get('/pharmacies', [DataAnalysisController::class, 'pharmacies']);
        Route::get('/medicines', [DataAnalysisController::class, 'medicines']);
        Route::get('/categories', [DataAnalysisController::class, 'categories']);
        Route::get('/searchHistory', [DataAnalysisController::class, 'searchHistory']);
        Route::get('/pharmacy-inventory', [DataAnalysisController::class, 'pharmacyInventory']);
        Route::get('/orders', [DataAnalysisController::class, 'orders']);
        Route::get('/order-items', [DataAnalysisController::class, 'orderItems']);
    });

// 3. Webhook Route for Paymob (يجب أن يكون بدون حماية ليتمكن Paymob من الوصول إليه)
Route::post('/paymob/callback', [\App\Http\Controllers\Api\Orders\PaymobController::class, 'callback']);
Route::get('/paymob/response', [\App\Http\Controllers\Api\Orders\PaymobController::class, 'responseCallback']);
