<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InvoiceSettingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UsedLaptopController;
use App\Http\Controllers\Admin\CustomerOrderController;
use App\Http\Controllers\Admin\WarrantyTicketController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\CRMController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\AIAssistantController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\TrackingController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ServiceController as CustomerServiceController;
use App\Http\Controllers\Customer\ProductOrderController as CustomerProductOrderController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\WarrantyController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryTrackingController;

/*
|--------------------------------------------------------------------------
| Public-facing Shop & Customer Tracking
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('shop.index');
});

// Amazon-style Inventory Showcase
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');

// Customer-facing Device Tracking (Packing, Shipping, Delivered)
Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
Route::get('/track/{job_id}', [TrackingController::class, 'show'])->name('tracking.show');
Route::post('/track/{job_id}/specs', [TrackingController::class, 'updateDeviceSpecs'])->name('tracking.updateSpecs');

/*
|--------------------------------------------------------------------------
| Delivery Partner Portal Routes  (/delivery/*)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'delivery_partner'], 'prefix' => 'delivery', 'as' => 'delivery.'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Delivery\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/{id}', [\App\Http\Controllers\Delivery\DashboardController::class, 'show'])->name('show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Delivery\DashboardController::class, 'updateStatus'])->name('status');
    Route::post('/orders/{id}/otp/send', [\App\Http\Controllers\Delivery\DashboardController::class, 'sendOtp'])->name('otp.send');
    Route::post('/orders/{id}/otp/verify', [\App\Http\Controllers\Delivery\DashboardController::class, 'verifyOtp'])->name('otp.verify');
});

// Legacy single-route alias (preserved for backward compat)
Route::get('/delivery-partner', [\App\Http\Controllers\Delivery\DashboardController::class, 'index'])
    ->middleware(['auth', 'delivery_partner'])
    ->name('delivery.legacy');


/*
|--------------------------------------------------------------------------
| Customer Portal Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login']);

        Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register']);

        Route::get('/forgot-password', [CustomerAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [CustomerAuthController::class, 'sendResetLink'])->name('password.email');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::post('/notifications/read', [CustomerDashboardController::class, 'markNotificationsRead'])->name('notifications.read');
        Route::get('/invoice/{id}/download', [CustomerDashboardController::class, 'downloadInvoice'])->name('invoice.download');

        // Book Service
        Route::get('/service/book', [CustomerServiceController::class, 'create'])->name('service.book');
        Route::post('/service/book', [CustomerServiceController::class, 'store'])->name('service.store');
        
        // New Service Modules
        Route::get('/service/custom-build', [CustomerServiceController::class, 'customBuild'])->name('service.custom-build');
        Route::get('/service/cctv', [CustomerServiceController::class, 'cctv'])->name('service.cctv');
        Route::get('/shop/laptops', [CustomerServiceController::class, 'laptops'])->name('shop.laptops');

        // Order Product (legacy single-item quick buy)
        Route::post('/shop/order/{id}', [CustomerProductOrderController::class, 'store'])->name('product.order');
        Route::get('/orders', [CustomerProductOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}/track', [CustomerProductOrderController::class, 'track'])->name('orders.track');

        // 🛒 Shopping Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/cart/checkout', [CartController::class, 'placeOrder'])->name('cart.placeOrder');

        // 🛡️ Warranty
        Route::get('/warranty', [WarrantyController::class, 'index'])->name('warranty.index');
        Route::get('/warranty/{id}', [WarrantyController::class, 'show'])->name('warranty.show');
        Route::post('/warranty/{id}/claim', [WarrantyController::class, 'claim'])->name('warranty.claim');

        // 📍 Delivery Location & Live Tracking
        Route::post('/location/save', [DeliveryTrackingController::class, 'saveCustomerLocation'])->name('location.save');
        Route::get('/orders/{id}/live-track', [DeliveryTrackingController::class, 'customerTrackPage'])->name('orders.live-track');

        // ⚙️ Customer Settings Profile
        Route::view('/settings', 'customer.settings.index')->name('settings');

        // 🤖 AI Chat Bot
        Route::get('/chat', [\App\Http\Controllers\Customer\ChatController::class, 'index'])->name('chat');
        Route::post('/chat', [\App\Http\Controllers\Customer\ChatController::class, 'message'])->name('chat.message');

        // 🎮 Tech Learning & Hardware Builder Game
        Route::get('/learning', [\App\Http\Controllers\Customer\LearningController::class, 'index'])->name('learning.index');
        Route::get('/learning/hardware-builder', [\App\Http\Controllers\Customer\LearningController::class, 'game'])->name('learning.hardware-builder');

        // 🔬 THAMBU TECH LAB (Advanced 3D Academy)
        Route::group(['prefix' => 'tech-lab', 'as' => 'tech-lab.'], function () {
            Route::get('/', [\App\Http\Controllers\Customer\LearningController::class, 'labDashboard'])->name('dashboard');
            Route::get('/3d-pc-builder', [\App\Http\Controllers\Customer\LearningController::class, 'builder3d'])->name('builder');
            Route::get('/laptop-repair-sim', [\App\Http\Controllers\Customer\LearningController::class, 'repairSim'])->name('repair');
            Route::get('/troubleshooting-ai', [\App\Http\Controllers\Customer\LearningController::class, 'troubleshoot'])->name('troubleshoot');
            Route::get('/virus-smash', [\App\Http\Controllers\Customer\LearningController::class, 'virusSmash'])->name('virus-smash');
            Route::get('/repair-tycoon', [\App\Http\Controllers\Customer\LearningController::class, 'repairTycoon'])->name('tycoon');
            Route::get('/device-scanner', [\App\Http\Controllers\Customer\LearningController::class, 'deviceScanner'])->name('scanner');
            Route::post('/save-score', [\App\Http\Controllers\Customer\LearningController::class, 'saveScore'])->name('save-score');
            Route::post('/save-tycoon', [\App\Http\Controllers\Customer\LearningController::class, 'saveTycoon'])->name('save-tycoon');
            Route::post('/analyze-device', [\App\Http\Controllers\Customer\LearningController::class, 'analyzeDevice'])->name('analyze-device');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Admin & Technician Routes (Luxury Management)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    
    // Core Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🗺️ Live Delivery Map
    Route::get('/delivery/live-map', [DeliveryTrackingController::class, 'adminDeliveryMap'])->name('delivery.live-map');

    // Service Management
    // Note: Route::resource automatically creates 'admin.services.show'
    Route::resource('services', ServiceOrderController::class);
    
    // FIX for the RouteNotFoundException: Add an alias for 'view' if your Blade uses it
    Route::get('/services/{service}/view', [ServiceOrderController::class, 'show'])->name('services.view');
    Route::post('/services/{service}/assign-technician', [ServiceOrderController::class, 'assignTechnician'])->name('services.assignTechnician');
    Route::post('/services/{service}/assign-delivery', [ServiceOrderController::class, 'assignDelivery'])->name('services.assignDelivery');
    Route::post('/services/{service}/use-part', [ServiceOrderController::class, 'usePart'])->name('services.usePart');
    Route::delete('/services/{service}/remove-part/{partId}', [ServiceOrderController::class, 'removePart'])->name('services.removePart');
    
    // Status & Logistics (Packing -> Shipping -> Delivered) 
    Route::post('/services/{id}/update-status', [ServiceOrderController::class, 'updateStatus'])->name('services.updateStatus');
    
    // Damage Photo Uploads 
    Route::post('/services/{id}/upload-photos', [ServiceOrderController::class, 'uploadDamagePhotos'])->name('services.uploadPhotos');
    
    // Device & Spare Parts Management
    Route::resource('devices', DeviceController::class);
    Route::resource('parts', SparePartController::class);
    Route::resource('part-categories', \App\Http\Controllers\Admin\PartCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('laptops', UsedLaptopController::class);
    
    // Custom View Endpoints
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{order}', [CustomerOrderController::class, 'update'])->name('orders.update');
    Route::get('/warranty', [WarrantyTicketController::class, 'index'])->name('warranty.index');
    
    // Amazon-style Inventory Control (Stock Updates) 
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/update-stock/{id}', [InventoryController::class, 'updateStock'])->name('inventory.updateStock');

    // Payment Portal & Invoices 
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/process/{orderId}', [PaymentController::class, 'process'])->name('payments.process');
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::post('/invoices/{invoice}/convert', [InvoiceController::class, 'convert'])->name('invoices.convert');
    
    // Invoice Settings
    Route::get('/invoice-settings', [InvoiceSettingController::class, 'index'])->name('invoice-settings.index');
    Route::post('/invoice-settings', [InvoiceSettingController::class, 'update'])->name('invoice-settings.update');

    // Billings & Purchase Orders
    Route::resource('billings', \App\Http\Controllers\Admin\BillingController::class);
    Route::resource('purchase-orders', \App\Http\Controllers\Admin\PurchaseOrderController::class);

    // Offers & Promotions
    Route::resource('offers', \App\Http\Controllers\Admin\OfferController::class);

    // Employee & Payroll Database
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
    
    // ZKTeco Attendance
    Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/sync', [\App\Http\Controllers\Admin\AttendanceController::class, 'sync'])->name('attendance.sync');
    Route::post('attendance/clear', [\App\Http\Controllers\Admin\AttendanceController::class, 'clearDevice'])->name('attendance.clear');

    Route::resource('delivery-partners', \App\Http\Controllers\Admin\DeliveryPartnerController::class);

    // Warranty Management
    Route::get('/warranty-claims', [WarrantyTicketController::class, 'index'])->name('warranty.claims');
    Route::get('/warranty-claims/{id}', [WarrantyTicketController::class, 'show'])->name('warranty.claims.show');
    Route::post('/warranty-claims/{id}/update', [WarrantyTicketController::class, 'update'])->name('warranty.claims.update');
    Route::get('/warranty-certificates', [WarrantyTicketController::class, 'certificates'])->name('warranty.certificates');
    Route::post('/warranty-certificates', [WarrantyTicketController::class, 'storeCertificate'])->name('warranty.certificates.store');

    // ───── Finance Module ─────
    Route::group(['prefix' => 'finance', 'as' => 'finance.'], function () {
        Route::get('/dashboard', [FinanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/expenses', [FinanceController::class, 'expenses'])->name('expenses');
        Route::post('/expenses', [FinanceController::class, 'storeExpense'])->name('expenses.store');
        Route::delete('/expenses/{id}', [FinanceController::class, 'destroyExpense'])->name('expenses.destroy');
        Route::get('/reports', [FinanceController::class, 'reports'])->name('reports');
    });

    // ───── CRM Module ─────
    Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {
        Route::get('/', [CRMController::class, 'index'])->name('index');
        Route::get('/{id}', [CRMController::class, 'show'])->name('show');
        Route::post('/{id}/followup', [CRMController::class, 'addFollowup'])->name('followup');
    });

    // ───── Analytics Module ─────
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');

    // ───── Branch Management ─────
    Route::resource('branches', BranchController::class);

    // ───── Marketing Module ─────
    Route::group(['prefix' => 'marketing', 'as' => 'marketing.'], function () {
        Route::get('/', [MarketingController::class, 'index'])->name('index');
        Route::get('/campaigns/create', [MarketingController::class, 'create'])->name('create');
        Route::post('/campaigns', [MarketingController::class, 'store'])->name('store');
        Route::get('/campaigns/{id}/edit', [MarketingController::class, 'edit'])->name('edit');
        Route::put('/campaigns/{id}', [MarketingController::class, 'update'])->name('update');
        Route::delete('/campaigns/{id}', [MarketingController::class, 'destroy'])->name('destroy');
        Route::post('/campaigns/{id}/toggle', [MarketingController::class, 'toggle'])->name('toggle');
    });

    // ───── HRM Payroll ─────
    Route::group(['prefix' => 'hrm', 'as' => 'hrm.'], function () {
        Route::get('/payroll', [SalaryController::class, 'index'])->name('payroll');
        Route::post('/payroll/generate', [SalaryController::class, 'generate'])->name('payroll.generate');
        Route::post('/payroll/{id}/pay', [SalaryController::class, 'markPaid'])->name('payroll.pay');
    });

    // ───── AI Assistant ─────
    Route::group(['prefix' => 'ai', 'as' => 'ai.'], function () {
        Route::get('/', [AIAssistantController::class, 'index'])->name('index');
        Route::post('/diagnose', [AIAssistantController::class, 'diagnose'])->name('diagnose');
        Route::post('/inventory-forecast', [AIAssistantController::class, 'inventoryForecast'])->name('inventory.forecast');
    });
    // ───── Customization / Settings / Theme ─────
    Route::controller(\App\Http\Controllers\Admin\CustomizationController::class)->group(function () {
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'index')->name('customization.index');
            Route::post('/save-settings', 'updateSettings')->name('customization.settings');
            Route::post('/save-toggles', 'updateToggles')->name('customization.toggles');
        });
    });

    // ───── Notification Templates ─────
    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'toggle'])->name('toggle');
    });

    // ───── Dynamic Forms Builder ─────
    Route::group(['prefix' => 'forms', 'as' => 'forms.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\DynamicFormController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\DynamicFormController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Admin\DynamicFormController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\DynamicFormController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/fields', [\App\Http\Controllers\Admin\DynamicFormController::class, 'addField'])->name('addField');
        Route::delete('/{id}/fields/{field_id}', [\App\Http\Controllers\Admin\DynamicFormController::class, 'destroyField'])->name('destroyField');
    });

    // ───── Role Permission Management ─────
    Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
        Route::put('/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('destroy');
    });

    // ───── Dynamic Pages Builder ─────
    Route::resource('pages', \App\Http\Controllers\Admin\DynamicPageController::class)->except(['show'])->names('pages');

    // ───── Dealer Management ─────
    Route::resource('dealers', \App\Http\Controllers\Admin\DealerController::class);

    // ───── ERP: Inventory Audit & Management ─────
    Route::get('inventory/logs', function () {
        return view('admin.inventory.logs');
    })->name('inventory.logs');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    // ───── ERP: Dealer Orders ─────
    Route::get('orders', [\App\Http\Controllers\Admin\DealerOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\DealerOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [\App\Http\Controllers\Admin\DealerOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // ───── ERP: Logistics & Store Visits ─────
    Route::get('logistics', [\App\Http\Controllers\Admin\LogisticsController::class, 'index'])->name('logistics.index');
    Route::get('orders/{order}/ship', [\App\Http\Controllers\Admin\LogisticsController::class, 'createShipment'])->name('logistics.createShipment');
    Route::post('orders/{order}/ship', [\App\Http\Controllers\Admin\LogisticsController::class, 'storeShipment'])->name('logistics.storeShipment');
    Route::get('visits', [\App\Http\Controllers\Admin\LogisticsController::class, 'visits'])->name('logistics.visits');
    Route::get('visits/create', [\App\Http\Controllers\Admin\LogisticsController::class, 'createVisit'])->name('logistics.createVisit');
    Route::post('visits', [\App\Http\Controllers\Admin\LogisticsController::class, 'storeVisit'])->name('logistics.storeVisit');

});

/*
|--------------------------------------------------------------------------
| Dealer Portal Routes
|--------------------------------------------------------------------------
*/
// removed so routes/auth.php handles dealer/login natively

Route::group(['middleware' => ['auth', 'dealer'], 'prefix' => 'dealer', 'as' => 'dealer.'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Dealer\DashboardController::class, 'index'])->name('dashboard');
    
    // Services / Bookings
    Route::get('/services', [\App\Http\Controllers\Dealer\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [\App\Http\Controllers\Dealer\ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [\App\Http\Controllers\Dealer\ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}', [\App\Http\Controllers\Dealer\ServiceController::class, 'show'])->name('services.show');
    
    // Invoices
    Route::get('/invoices', [\App\Http\Controllers\Dealer\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [\App\Http\Controllers\Dealer\InvoiceController::class, 'show'])->name('invoices.show');

    // ───── ERP: Dealer Ordering ─────
    Route::get('/shop', [\App\Http\Controllers\Dealer\OrderController::class, 'index'])->name('orders.create');
    Route::post('/shop', [\App\Http\Controllers\Dealer\OrderController::class, 'store'])->name('orders.store');
    Route::get('/order-history', [\App\Http\Controllers\Dealer\OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [\App\Http\Controllers\Dealer\OrderController::class, 'show'])->name('orders.show');

    // ───── ERP: My Inventory ─────
    Route::get('/inventory', [\App\Http\Controllers\Dealer\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/logs', [\App\Http\Controllers\Dealer\InventoryController::class, 'logs'])->name('inventory.logs');
});

/*
|--------------------------------------------------------------------------
| Technician Dedicated Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'technician'], 'prefix' => 'technician', 'as' => 'technician.'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Technician\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/services/{id}/update-status', [\App\Http\Controllers\Technician\DashboardController::class, 'updateStatus'])->name('services.updateStatus');
    Route::post('/services/{service}/use-part', [\App\Http\Controllers\Technician\DashboardController::class, 'usePart'])->name('services.usePart');
    Route::delete('/services/{service}/remove-part/{partId}', [\App\Http\Controllers\Technician\DashboardController::class, 'removePart'])->name('services.removePart');
    Route::post('/visits/{id}/update', [\App\Http\Controllers\Technician\DashboardController::class, 'updateVisit'])->name('visits.update');
    
    // Inspection Photos
    Route::post('/services/{id}/photos', [\App\Http\Controllers\Technician\InspectionPhotoController::class, 'store'])->name('services.photos.store');
    Route::delete('/photos/{id}', [\App\Http\Controllers\Technician\InspectionPhotoController::class, 'destroy'])->name('services.photos.destroy');
});

// Authentication Routes
require __DIR__.'/auth.php';