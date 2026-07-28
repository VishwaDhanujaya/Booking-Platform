<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentSiteController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TenantRegistrationController;

/*
|--------------------------------------------------------------------------
| 1. SLT DIGITAL SERVICES - PARENT MARKETING SITE ROUTES (Apex Domain)
|--------------------------------------------------------------------------
*/
Route::group([], function () {
    Route::get('/', [ParentSiteController::class, 'index'])->name('home');
    Route::get('/parent', [ParentSiteController::class, 'index'])->name('parent.home');
    Route::get('/features', [ParentSiteController::class, 'features'])->name('parent.features');
    Route::get('/where-to-use', [ParentSiteController::class, 'whereToUse'])->name('parent.where-to-use');
    Route::get('/customers', [ParentSiteController::class, 'customers'])->name('parent.customers');
    Route::get('/platform-pricing', [ParentSiteController::class, 'pricing'])->name('parent.pricing');
    Route::get('/contact', [ParentSiteController::class, 'contact'])->name('parent.contact');
    Route::get('/demo', [ParentSiteController::class, 'contact'])->name('parent.demo');
    Route::post('/contact', [ParentSiteController::class, 'storeContact'])->name('parent.contact.submit');
});

/*
|--------------------------------------------------------------------------
| SLTDS PLATFORM ADMIN ROUTES (Super-Admin Staff Only)
|--------------------------------------------------------------------------
*/
Route::get('/platform-admin/login', [AuthController::class, 'showPlatformLogin'])->name('superadmin.login');

Route::middleware(['auth', \App\Http\Middleware\EnsureSuperAdmin::class])->prefix('platform-admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/tenants', [\App\Http\Controllers\SuperAdminController::class, 'tenants'])->name('superadmin.tenants');
    Route::get('/tenants/create', [\App\Http\Controllers\SuperAdminController::class, 'createTenant'])->name('superadmin.tenants.create');
    Route::post('/tenants', [\App\Http\Controllers\SuperAdminController::class, 'storeTenant'])->name('superadmin.tenants.store');
    Route::get('/tenants/{id}/edit', [\App\Http\Controllers\SuperAdminController::class, 'editTenant'])->name('superadmin.tenants.edit');
    Route::post('/tenants/{id}/update', [\App\Http\Controllers\SuperAdminController::class, 'updateTenant'])->name('superadmin.tenants.update');
    Route::post('/tenants/{id}/toggle-status', [\App\Http\Controllers\SuperAdminController::class, 'toggleStatus'])->name('superadmin.tenants.toggle-status');
    Route::post('/tenants/{id}/toggle-public', [\App\Http\Controllers\SuperAdminController::class, 'togglePublic'])->name('superadmin.tenants.toggle-public');
    Route::post('/tenants/{id}/impersonate', [\App\Http\Controllers\SuperAdminController::class, 'impersonate'])->name('superadmin.tenants.impersonate');
});

Route::middleware('auth')->match(['get', 'post'], '/platform-admin/stop-impersonating', [\App\Http\Controllers\SuperAdminController::class, 'stopImpersonating'])->name('superadmin.stop-impersonating');

/*
|--------------------------------------------------------------------------
| 2. TENANT SCOPED PUBLIC & BOOKING ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/book', [PublicBookingController::class, 'index'])->name('booking.index');
Route::get('/pricing', [PublicBookingController::class, 'pricing'])->name('pricing');
Route::get('/booking/checkout', [PublicBookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/checkout', [PublicBookingController::class, 'process'])->name('booking.process');
Route::get('/booking/confirmation/{reference}', [PublicBookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/invoice/{reference}', [InvoiceController::class, 'show'])->name('booking.invoice');

/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
    Route::get('/register-business', [TenantRegistrationController::class, 'showRegisterForm'])->name('tenant.register');
    Route::post('/register-business', [TenantRegistrationController::class, 'register'])->name('tenant.register.perform');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showLogin'])->name('password.reset');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 4. CUSTOMER PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/my-account', [CustomerDashboardController::class, 'index'])->name('customer.my-bookings');
    Route::post('/my-account/cancel/{id}', [CustomerDashboardController::class, 'cancelBooking'])->name('customer.booking.cancel');
    Route::post('/my-account/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/booking/waitlist', [PublicBookingController::class, 'joinWaitlist'])->name('booking.waitlist');
});

/*
|--------------------------------------------------------------------------
| 5. TENANT ADMIN & STAFF MANAGEMENT PORTAL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner,manager,trainer_staff,front_desk'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminDashboardController::class, 'bookings'])->name('admin.bookings');
    Route::get('/admin/bookings/{id}/invoice', [InvoiceController::class, 'showAdmin'])->name('admin.bookings.invoice');
    Route::get('/admin/courts', [AdminDashboardController::class, 'courts'])->name('admin.courts');
    Route::get('/admin/customers', [AdminDashboardController::class, 'customers'])->name('admin.customers');
    Route::post('/admin/bookings/{id}/no-show', [AdminDashboardController::class, 'markNoShow'])->name('admin.bookings.noshow');
    Route::post('/admin/bookings/{id}/mark-paid', [AdminDashboardController::class, 'markPaid'])->name('admin.bookings.mark-paid');
    Route::post('/admin/bookings/{id}/cancel', [AdminDashboardController::class, 'cancelBooking'])->name('admin.bookings.cancel');
    
    Route::middleware('role:owner,manager')->group(function () {
        Route::post('/admin/customers/{id}/issue-credits', [AdminDashboardController::class, 'issueCredits'])->name('admin.customers.issue-credits');
        Route::post('/admin/customers/{id}/issue-pass', [AdminDashboardController::class, 'issuePass'])->name('admin.customers.issue-pass');

        Route::post('/admin/courts', [AdminDashboardController::class, 'storeCourt'])->name('admin.courts.store');
        Route::post('/admin/courts/{id}', [AdminDashboardController::class, 'updateCourt'])->name('admin.courts.update');
        Route::delete('/admin/courts/{id}', [AdminDashboardController::class, 'deleteCourt'])->name('admin.courts.delete');
        
        Route::get('/admin/pricing', [AdminDashboardController::class, 'pricing'])->name('admin.pricing');
        Route::post('/admin/pricing', [AdminDashboardController::class, 'storePricingRule'])->name('admin.pricing.store');
        Route::post('/admin/pricing/{id}/toggle', [AdminDashboardController::class, 'togglePricingRule'])->name('admin.pricing.toggle');
        Route::delete('/admin/pricing/{id}', [AdminDashboardController::class, 'deletePricingRule'])->name('admin.pricing.delete');

        Route::get('/admin/staff', [AdminDashboardController::class, 'staff'])->name('admin.staff');
        Route::post('/admin/staff', [AdminDashboardController::class, 'storeStaff'])->name('admin.staff.store');
        Route::delete('/admin/staff/{id}', [AdminDashboardController::class, 'deleteStaff'])->name('admin.staff.delete');

        Route::get('/admin/settings', [\App\Http\Controllers\TenantSettingsController::class, 'edit'])->name('admin.settings');
        Route::post('/admin/settings', [\App\Http\Controllers\TenantSettingsController::class, 'update'])->name('admin.settings.update');
    });
});

/*
|--------------------------------------------------------------------------
| 6. PATH-BASED TENANT DIRECT URL ROUTES (e.g. /zenith-yoga/book, /zenith-yoga/admin)
|--------------------------------------------------------------------------
*/
Route::prefix('{tenant_slug}')->where(['tenant_slug' => '[a-zA-Z0-9\-]+'])->group(function () {
    Route::get('/', function ($tenant_slug) {
        return redirect('/' . $tenant_slug . '/book');
    });
    Route::get('/book', [PublicBookingController::class, 'index']);
    Route::get('/pricing', [PublicBookingController::class, 'pricing']);
    Route::get('/booking/checkout', [PublicBookingController::class, 'checkout']);
    Route::post('/booking/checkout', [PublicBookingController::class, 'process']);
    Route::get('/booking/confirmation/{reference}', [PublicBookingController::class, 'confirmation']);
    Route::get('/booking/invoice/{reference}', [InvoiceController::class, 'show']);

    Route::middleware('auth')->group(function () {
        Route::get('/my-account', [CustomerDashboardController::class, 'index']);
        Route::post('/my-account/cancel/{id}', [CustomerDashboardController::class, 'cancelBooking']);
        Route::post('/my-account/profile', [CustomerDashboardController::class, 'updateProfile']);
        Route::post('/booking/waitlist', [PublicBookingController::class, 'joinWaitlist']);
    });

    Route::middleware(['auth', 'role:owner,manager,trainer_staff,front_desk'])->prefix('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index']);
        Route::get('/bookings', [AdminDashboardController::class, 'bookings']);
        Route::get('/bookings/{id}/invoice', [InvoiceController::class, 'showAdmin']);
        Route::get('/courts', [AdminDashboardController::class, 'courts']);
        Route::get('/customers', [AdminDashboardController::class, 'customers']);
        Route::post('/bookings/{id}/no-show', [AdminDashboardController::class, 'markNoShow']);
        Route::post('/bookings/{id}/mark-paid', [AdminDashboardController::class, 'markPaid']);
        Route::post('/bookings/{id}/cancel', [AdminDashboardController::class, 'cancelBooking']);

        Route::middleware('role:owner,manager')->group(function () {
            Route::post('/customers/{id}/issue-credits', [AdminDashboardController::class, 'issueCredits']);
            Route::post('/customers/{id}/issue-pass', [AdminDashboardController::class, 'issuePass']);
            Route::post('/courts', [AdminDashboardController::class, 'storeCourt']);
            Route::post('/courts/{id}', [AdminDashboardController::class, 'updateCourt']);
            Route::delete('/courts/{id}', [AdminDashboardController::class, 'deleteCourt']);
            Route::get('/pricing', [AdminDashboardController::class, 'pricing']);
            Route::post('/pricing', [AdminDashboardController::class, 'storePricingRule']);
            Route::post('/pricing/{id}/toggle', [AdminDashboardController::class, 'togglePricingRule']);
            Route::delete('/pricing/{id}', [AdminDashboardController::class, 'deletePricingRule']);
            Route::get('/staff', [AdminDashboardController::class, 'staff']);
            Route::post('/staff', [AdminDashboardController::class, 'storeStaff']);
            Route::delete('/staff/{id}', [AdminDashboardController::class, 'deleteStaff']);
            Route::get('/settings', [\App\Http\Controllers\TenantSettingsController::class, 'edit']);
            Route::post('/settings', [\App\Http\Controllers\TenantSettingsController::class, 'update']);
        });
    });
});
