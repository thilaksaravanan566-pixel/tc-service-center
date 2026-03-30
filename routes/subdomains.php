<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Subdomain Routing — thambucomputers.com
|--------------------------------------------------------------------------
|
| These routes mirror the prefix-based routes but bind to subdomains.
| Both path-based (/admin/...) and subdomain-based (admin.domain.com/...)
| work simultaneously during the transition period.
|
| Subdomains:
|   admin.thambucomputers.com    → Admin ERP Panel
|   dealer.thambucomputers.com   → Dealer B2B Portal
|   customer.thambucomputers.com → Customer Self-Service
|
*/

$domain = config('app.base_domain', '127.0.0.1');

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN SUBDOMAIN → admin.thambucomputers.com
// ─────────────────────────────────────────────────────────────────────────────
Route::domain('admin.' . $domain)
    ->middleware(['auth', 'admin'])
    ->name('admin.subdomain.')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('services', App\Http\Controllers\Admin\ServiceOrderController::class);
        Route::post('/services/{service}/assign-technician', [App\Http\Controllers\Admin\ServiceOrderController::class, 'assignTechnician'])->name('services.assignTechnician');
        Route::post('/services/{id}/update-status', [App\Http\Controllers\Admin\ServiceOrderController::class, 'updateStatus'])->name('services.updateStatus');
        Route::resource('devices', App\Http\Controllers\Admin\DeviceController::class);
        Route::resource('parts', App\Http\Controllers\Admin\SparePartController::class);
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::resource('dealers', App\Http\Controllers\Admin\DealerController::class);
        Route::resource('invoices', App\Http\Controllers\Admin\InvoiceController::class);
        Route::get('/invoices/{invoice}/download', [App\Http\Controllers\Admin\InvoiceController::class, 'download'])->name('invoices.download');
        Route::get('/invoices/{invoice}/print', [App\Http\Controllers\Admin\InvoiceController::class, 'print'])->name('invoices.print');
        Route::post('/invoices/{invoice}/convert', [App\Http\Controllers\Admin\InvoiceController::class, 'convert'])->name('invoices.convert');
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory/update-stock/{id}', [App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.updateStock');
        Route::resource('branches', App\Http\Controllers\Admin\BranchController::class);
        Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
        Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::group(['prefix' => 'finance', 'as' => 'finance.'], function () {
            Route::get('/dashboard', [App\Http\Controllers\Admin\FinanceController::class, 'dashboard'])->name('dashboard');
            Route::get('/expenses', [App\Http\Controllers\Admin\FinanceController::class, 'expenses'])->name('expenses');
            Route::post('/expenses', [App\Http\Controllers\Admin\FinanceController::class, 'storeExpense'])->name('expenses.store');
            Route::get('/reports', [App\Http\Controllers\Admin\FinanceController::class, 'reports'])->name('reports');
        });
        Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {
            Route::get('/', [App\Http\Controllers\Admin\CRMController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\Admin\CRMController::class, 'show'])->name('show');
            Route::post('/{id}/followup', [App\Http\Controllers\Admin\CRMController::class, 'addFollowup'])->name('followup');
        });
        Route::group(['prefix' => 'hrm', 'as' => 'hrm.'], function () {
            Route::get('/payroll', [App\Http\Controllers\Admin\SalaryController::class, 'index'])->name('payroll');
            Route::post('/payroll/generate', [App\Http\Controllers\Admin\SalaryController::class, 'generate'])->name('payroll.generate');
            Route::post('/payroll/{id}/pay', [App\Http\Controllers\Admin\SalaryController::class, 'markPaid'])->name('payroll.pay');
        });
        Route::group(['prefix' => 'settings', 'as' => 'customization.'], function () {
            Route::get('/', [App\Http\Controllers\Admin\CustomizationController::class, 'index'])->name('index');
            Route::post('/save-settings', [App\Http\Controllers\Admin\CustomizationController::class, 'updateSettings'])->name('settings');
            Route::post('/save-toggles', [App\Http\Controllers\Admin\CustomizationController::class, 'updateToggles'])->name('toggles');
        });
    });

// ─────────────────────────────────────────────────────────────────────────────
// DEALER SUBDOMAIN → dealer.thambucomputers.com
// ─────────────────────────────────────────────────────────────────────────────
Route::domain('dealer.' . $domain)
    ->middleware(['auth', 'dealer'])
    ->name('dealer.subdomain.')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('dealer.dashboard');
        });
        Route::get('/login', function () {
            return redirect()->route('login.dealer');
        });
        Route::get('/dashboard', [App\Http\Controllers\Dealer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/services', [App\Http\Controllers\Dealer\ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [App\Http\Controllers\Dealer\ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [App\Http\Controllers\Dealer\ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{id}', [App\Http\Controllers\Dealer\ServiceController::class, 'show'])->name('services.show');
        Route::get('/invoices', [App\Http\Controllers\Dealer\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{id}', [App\Http\Controllers\Dealer\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/shop', [App\Http\Controllers\Dealer\OrderController::class, 'index'])->name('orders.create');
        Route::post('/shop', [App\Http\Controllers\Dealer\OrderController::class, 'store'])->name('orders.store');
        Route::get('/order-history', [App\Http\Controllers\Dealer\OrderController::class, 'history'])->name('orders.history');
        Route::get('/orders/{order}', [App\Http\Controllers\Dealer\OrderController::class, 'show'])->name('orders.show');
        Route::get('/inventory', [App\Http\Controllers\Dealer\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/logs', [App\Http\Controllers\Dealer\InventoryController::class, 'logs'])->name('inventory.logs');
    });

// ─────────────────────────────────────────────────────────────────────────────
// CUSTOMER SUBDOMAIN → customer.thambucomputers.com
// ─────────────────────────────────────────────────────────────────────────────
Route::domain('customer.' . $domain)
    ->name('customer.subdomain.')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('customer.dashboard');
        });

        // Guest routes (login / register)
        Route::middleware('guest:customer')->group(function () {
            Route::get('/login', [App\Http\Controllers\Customer\AuthController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [App\Http\Controllers\Customer\AuthController::class, 'login']);
            Route::get('/register', [App\Http\Controllers\Customer\AuthController::class, 'showRegisterForm'])->name('register');
            Route::post('/register', [App\Http\Controllers\Customer\AuthController::class, 'register']);
        });

        // Authenticated customer routes
        Route::middleware('auth:customer')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
            Route::post('/logout', [App\Http\Controllers\Customer\AuthController::class, 'logout'])->name('logout');
            Route::get('/invoice/{id}/download', [App\Http\Controllers\Customer\DashboardController::class, 'downloadInvoice'])->name('invoice.download');
            Route::get('/service/book', [App\Http\Controllers\Customer\ServiceController::class, 'create'])->name('service.book');
            Route::post('/service/book', [App\Http\Controllers\Customer\ServiceController::class, 'store'])->name('service.store');
            Route::post('/shop/order/{id}', [App\Http\Controllers\Customer\ProductOrderController::class, 'store'])->name('product.order');
            Route::get('/orders', [App\Http\Controllers\Customer\ProductOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{id}/track', [App\Http\Controllers\Customer\ProductOrderController::class, 'track'])->name('orders.track');
            Route::get('/cart', [App\Http\Controllers\Customer\CartController::class, 'index'])->name('cart.index');
            Route::post('/cart/add/{id}', [App\Http\Controllers\Customer\CartController::class, 'add'])->name('cart.add');
            Route::post('/cart/update/{id}', [App\Http\Controllers\Customer\CartController::class, 'update'])->name('cart.update');
            Route::delete('/cart/remove/{id}', [App\Http\Controllers\Customer\CartController::class, 'remove'])->name('cart.remove');
            Route::get('/cart/checkout', [App\Http\Controllers\Customer\CartController::class, 'checkout'])->name('cart.checkout');
            Route::post('/cart/checkout', [App\Http\Controllers\Customer\CartController::class, 'placeOrder'])->name('cart.placeOrder');
            Route::get('/warranty', [App\Http\Controllers\Customer\WarrantyController::class, 'index'])->name('warranty.index');
            Route::get('/warranty/{id}', [App\Http\Controllers\Customer\WarrantyController::class, 'show'])->name('warranty.show');
            Route::post('/warranty/{id}/claim', [App\Http\Controllers\Customer\WarrantyController::class, 'claim'])->name('warranty.claim');
            Route::view('/settings', 'customer.settings.index')->name('settings');
        });
    });
// ─────────────────────────────────────────────────────────────────────────────
// TECHNICIAN SUBDOMAIN → technician.thambucomputers.com
// ─────────────────────────────────────────────────────────────────────────────
Route::domain('technician.' . $domain)
    ->middleware(['auth', 'technician'])
    ->group(function () {
        Route::get('/login', function () {
            return redirect()->route('login.technician');
        });
        Route::get('/dashboard', [App\Http\Controllers\Technician\DashboardController::class, 'index'])
            ->name('technician.subdomain.dashboard');
        Route::post('/services/{id}/status', [App\Http\Controllers\Technician\DashboardController::class, 'updateStatus'])
            ->name('technician.subdomain.services.updateStatus');
        Route::post('/services/{service}/parts', [App\Http\Controllers\Technician\DashboardController::class, 'usePart'])
            ->name('technician.subdomain.services.usePart');
        Route::delete('/services/{service}/parts/{partId}', [App\Http\Controllers\Technician\DashboardController::class, 'removePart'])
            ->name('technician.subdomain.services.removePart');
        Route::post('/services/{id}/visits', [App\Http\Controllers\Technician\DashboardController::class, 'updateVisit'])
            ->name('technician.subdomain.visits.update');
        Route::post('/inspections/{serviceOrderId}/photos', [App\Http\Controllers\Technician\InspectionPhotoController::class, 'store'])
            ->name('technician.subdomain.photos.store');
        Route::delete('/inspections/photos/{id}', [App\Http\Controllers\Technician\InspectionPhotoController::class, 'destroy'])
            ->name('technician.subdomain.photos.destroy');
    });

// ─────────────────────────────────────────────────────────────────────────────
// DELIVERY SUBDOMAIN → delivery.thambucomputers.com
// ─────────────────────────────────────────────────────────────────────────────
Route::domain('delivery.' . $domain)
    ->middleware(['auth', 'delivery_partner'])
    ->group(function () {
        Route::get('/login', function () {
            return redirect()->route('login.delivery');
        });
        Route::get('/dashboard', [App\Http\Controllers\Delivery\DashboardController::class, 'index'])
            ->name('delivery.subdomain.dashboard');
        Route::get('/orders/{id}', [App\Http\Controllers\Delivery\DashboardController::class, 'show'])
            ->name('delivery.subdomain.show');
        Route::post('/orders/{id}/status', [App\Http\Controllers\Delivery\DashboardController::class, 'updateStatus'])
            ->name('delivery.subdomain.status');
        Route::post('/orders/{id}/otp/send', [App\Http\Controllers\Delivery\DashboardController::class, 'sendOtp'])
            ->name('delivery.subdomain.otp.send');
        Route::post('/orders/{id}/otp/verify', [App\Http\Controllers\Delivery\DashboardController::class, 'verifyOtp'])
            ->name('delivery.subdomain.otp.verify');
    });
