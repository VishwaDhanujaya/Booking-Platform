<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParentSiteController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\SuperAdminController;

/*
|--------------------------------------------------------------------------
| 1. PARENT MARKETING SITE ROUTES (Apex Domain & Tenant Root)
|--------------------------------------------------------------------------
*/
Route::get('/', [ParentSiteController::class, 'index'])->name('home');
Route::get('/parent', [ParentSiteController::class, 'index'])->name('parent.home');
Route::get('/features', [ParentSiteController::class, 'features'])->name('parent.features');
Route::get('/solutions', [ParentSiteController::class, 'solutions'])->name('parent.solutions');
Route::get('/where-to-use', [ParentSiteController::class, 'solutions'])->name('parent.where-to-use');
Route::get('/live-venues', [ParentSiteController::class, 'liveVenues'])->name('parent.live-venues');
Route::get('/customers', [ParentSiteController::class, 'liveVenues'])->name('parent.customers');
Route::get('/platform-pricing', [ParentSiteController::class, 'pricing'])->name('parent.pricing');
Route::get('/contact', [ParentSiteController::class, 'contact'])->name('parent.contact');
Route::get('/demo', [ParentSiteController::class, 'contact'])->name('parent.demo');
Route::post('/contact', [ParentSiteController::class, 'storeContact'])->name('parent.contact.submit');

/*
|--------------------------------------------------------------------------
| 2. PLATFORM ADMIN ROUTES (Super-Admin Staff Only)
|--------------------------------------------------------------------------
*/
Route::get('/platform-admin/login', [AuthController::class, 'showPlatformLogin'])->name('superadmin.login');

Route::middleware(['auth', \App\Http\Middleware\EnsureSuperAdmin::class])->prefix('platform-admin')->group(function () {
    Route::get('/', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('superadmin.tenants');
    Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])->name('superadmin.tenants.create');
    Route::post('/tenants', [SuperAdminController::class, 'storeTenant'])->name('superadmin.tenants.store');
    Route::get('/tenants/{id}/edit', [SuperAdminController::class, 'editTenant'])->name('superadmin.tenants.edit');
    Route::post('/tenants/{id}/update', [SuperAdminController::class, 'updateTenant'])->name('superadmin.tenants.update');
    Route::post('/tenants/{id}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('superadmin.tenants.toggle-status');
    Route::post('/tenants/{id}/toggle-public', [SuperAdminController::class, 'togglePublic'])->name('superadmin.tenants.toggle-public');
    Route::get('/tenants/{id}/users', [SuperAdminController::class, 'tenantUsers'])->name('superadmin.tenants.users');
    Route::post('/tenants/{id}/users', [SuperAdminController::class, 'storeTenantUser'])->name('superadmin.tenants.users.store');
    Route::delete('/tenants/{tenantId}/users/{userId}', [SuperAdminController::class, 'deleteTenantUser'])->name('superadmin.tenants.users.delete');
});

/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATION & REGISTRATION ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showLogin'])->name('password.reset');
});

// Business Venue Registration (Accessible to anyone, logged-in or guest)
Route::get('/register-business', [TenantRegistrationController::class, 'showRegisterForm'])->name('tenant.register');
Route::post('/register-business', [TenantRegistrationController::class, 'register'])->name('tenant.register.perform');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 4. TENANT FACILITY ROUTES (Single, Non-Duplicated Registration)
|--------------------------------------------------------------------------
| Tenant identity is resolved from Host header (subdomain) by IdentifyTenant
| middleware prior to routing. Routes themselves are clean paths.
*/

// Public Tenant Routes
Route::get('/book', [PublicBookingController::class, 'index'])->name('booking.index');
Route::get('/pricing', [PublicBookingController::class, 'pricing'])->name('pricing');
Route::get('/booking/checkout', [PublicBookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/checkout', [PublicBookingController::class, 'process'])->name('booking.process');
Route::get('/booking/confirmation/{reference}', [PublicBookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/invoice/{reference}', [InvoiceController::class, 'show'])->name('booking.invoice');

// Customer Account
Route::middleware('auth')->group(function () {
    Route::get('/my-account', [CustomerDashboardController::class, 'index'])->name('customer.my-bookings');
    Route::post('/my-account/cancel/{id}', [CustomerDashboardController::class, 'cancelBooking'])->name('customer.booking.cancel');
    Route::post('/my-account/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/booking/waitlist', [PublicBookingController::class, 'joinWaitlist'])->name('booking.waitlist');
});

// Staff & Owner Management Portal
Route::middleware(['auth', 'role:owner,manager,trainer_staff,front_desk'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('admin.bookings');
    Route::get('/bookings/{id}/invoice', [InvoiceController::class, 'showAdmin'])->name('admin.bookings.invoice');
    Route::get('/courts', [AdminDashboardController::class, 'courts'])->name('admin.courts');
    Route::get('/customers', [AdminDashboardController::class, 'customers'])->name('admin.customers');
    Route::post('/bookings/{id}/no-show', [AdminDashboardController::class, 'markNoShow'])->name('admin.bookings.noshow');
    Route::post('/bookings/{id}/mark-paid', [AdminDashboardController::class, 'markPaid'])->name('admin.bookings.mark-paid');
    Route::post('/bookings/{id}/cancel', [AdminDashboardController::class, 'cancelBooking'])->name('admin.bookings.cancel');

    Route::middleware('role:owner,manager')->group(function () {
        Route::post('/customers/{id}/issue-credits', [AdminDashboardController::class, 'issueCredits'])->name('admin.customers.issue-credits');
        Route::post('/customers/{id}/issue-pass', [AdminDashboardController::class, 'issuePass'])->name('admin.customers.issue-pass');
        Route::post('/courts', [AdminDashboardController::class, 'storeCourt'])->name('admin.courts.store');
        Route::post('/courts/{id}', [AdminDashboardController::class, 'updateCourt'])->name('admin.courts.update');
        Route::delete('/courts/{id}', [AdminDashboardController::class, 'deleteCourt'])->name('admin.courts.delete');
        Route::get('/pricing', [AdminDashboardController::class, 'pricing'])->name('admin.pricing');
        Route::post('/pricing', [AdminDashboardController::class, 'storePricingRule'])->name('admin.pricing.store');
        Route::post('/pricing/{id}/toggle', [AdminDashboardController::class, 'togglePricingRule'])->name('admin.pricing.toggle');
        Route::delete('/pricing/{id}', [AdminDashboardController::class, 'deletePricingRule'])->name('admin.pricing.delete');
        Route::get('/staff', [AdminDashboardController::class, 'staff'])->name('admin.staff');
        Route::post('/staff', [AdminDashboardController::class, 'storeStaff'])->name('admin.staff.store');
        Route::delete('/staff/{id}', [AdminDashboardController::class, 'deleteStaff'])->name('admin.staff.delete');
        Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('admin.settings');
        Route::post('/settings', [TenantSettingsController::class, 'update'])->name('admin.settings.update');
    });
});
