<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\CustomerApiController;
use App\Http\Controllers\Api\Mobile\TechnicianApiController;
use App\Http\Controllers\Api\Mobile\DeliveryApiController;
use App\Http\Controllers\DeliveryTrackingController;

// ─── Customer Mobile App API ───
Route::group(['prefix' => 'v1/customer'], function () {
    // Public
    Route::post('/login', [CustomerApiController::class, 'login']);
    Route::post('/register', [CustomerApiController::class, 'register']);

    // Auth required
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [CustomerApiController::class, 'profile']);
        Route::put('/profile', [CustomerApiController::class, 'updateProfile']);
        Route::post('/logout', [CustomerApiController::class, 'logout']);

        // Products
        Route::get('/products', [CustomerApiController::class, 'products']);
        Route::get('/products/{id}', [CustomerApiController::class, 'productShow']);

        // Cart
        Route::get('/cart', [CustomerApiController::class, 'cart']);
        Route::post('/cart/add/{id}', [CustomerApiController::class, 'cartAdd']);
        Route::delete('/cart/remove/{id}', [CustomerApiController::class, 'cartRemove']);

        // Orders
        Route::get('/orders', [CustomerApiController::class, 'orders']);
        Route::get('/orders/{id}', [CustomerApiController::class, 'orderShow']);
        Route::post('/checkout', [CustomerApiController::class, 'checkout']);

        // Service Requests
        Route::get('/services', [CustomerApiController::class, 'services']);
        Route::post('/services/book', [CustomerApiController::class, 'bookService']);
        Route::get('/services/{id}', [CustomerApiController::class, 'serviceShow']);

        // Warranty
        Route::get('/warranty', [CustomerApiController::class, 'warranty']);
        Route::post('/warranty/{id}/claim', [CustomerApiController::class, 'submitClaim']);

        // Notifications
        Route::get('/notifications', [CustomerApiController::class, 'notifications']);
        Route::post('/notifications/read', [CustomerApiController::class, 'markRead']);

        // Support / Chat
        Route::get('/support/messages', [CustomerApiController::class, 'supportMessages']);
        Route::post('/support/message', [CustomerApiController::class, 'sendSupportMessage']);
    });
});

// ─── Technician Mobile App API ───
Route::group(['prefix' => 'v1/technician'], function () {
    Route::post('/login', [TechnicianApiController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/jobs', [TechnicianApiController::class, 'assignedJobs']);
        Route::get('/jobs/{id}', [TechnicianApiController::class, 'jobDetails']);
        Route::post('/jobs/{id}/status', [TechnicianApiController::class, 'updateStatus']);
        Route::post('/jobs/{id}/photos', [TechnicianApiController::class, 'uploadPhotos']);
        Route::post('/jobs/{id}/notes', [TechnicianApiController::class, 'addNotes']);
        Route::get('/parts', [TechnicianApiController::class, 'getParts']);
        Route::post('/jobs/{id}/parts', [TechnicianApiController::class, 'addPart']);
        Route::post('/ai-diagnose', [TechnicianApiController::class, 'aiDiagnose']);
    });
});

// ─── Delivery Agent Mobile App API ───
Route::group(['prefix' => 'v1/delivery'], function () {
    Route::post('/login', [DeliveryApiController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/tasks', [DeliveryApiController::class, 'assignedTasks']);
        Route::post('/tasks/{id}/status', [DeliveryApiController::class, 'updateStatus']);
        Route::post('/tasks/{id}/pickup', [DeliveryApiController::class, 'markPickedUp']);
        Route::post('/tasks/{id}/deliver', [DeliveryApiController::class, 'markDelivered']);
    });
});

// ─── Public: Customer Delivery Tracking Poll ───
// No auth required — customers poll this to get delivery status
Route::get('/tracking/{orderId}/status', [DeliveryTrackingController::class, 'getDeliveryStatus']);

// ─── Delivery Partner: Location Push ───
// Called every few seconds by the delivery partner's browser/app
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/delivery/location/update', [DeliveryTrackingController::class, 'updatePartnerLocation']);
    Route::post('/delivery/location/offline', [DeliveryTrackingController::class, 'setOffline']);
});

// Web-auth version for browser-based delivery dashboard (uses session)
Route::middleware('auth')->group(function () {
    Route::post('/delivery/location/update', [DeliveryTrackingController::class, 'updatePartnerLocation'])->withoutMiddleware('auth:sanctum');
    Route::post('/delivery/location/offline', [DeliveryTrackingController::class, 'setOffline'])->withoutMiddleware('auth:sanctum');
    Route::get('/admin/delivery/map-data', [DeliveryTrackingController::class, 'adminMapData']);
});
