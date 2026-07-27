<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InvoiceController;

// Public Guest Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/book', [PublicBookingController::class, 'index'])->name('booking.index');
Route::get('/booking/checkout', [PublicBookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/checkout', [PublicBookingController::class, 'process'])->name('booking.process');
Route::get('/booking/confirmation/{reference}', [PublicBookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/invoice/{reference}', [InvoiceController::class, 'show'])->name('booking.invoice');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showLogin'])->name('password.reset');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/my-account', [CustomerDashboardController::class, 'index'])->name('customer.my-bookings');
    Route::post('/my-account/cancel/{id}', [CustomerDashboardController::class, 'cancelBooking'])->name('customer.booking.cancel');
    Route::post('/my-account/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/booking/waitlist', [PublicBookingController::class, 'joinWaitlist'])->name('booking.waitlist');
});

// Admin / Staff Management Portal Routes (Role Enforced)
Route::middleware(['auth', 'role:owner,manager,trainer_staff,front_desk'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminDashboardController::class, 'bookings'])->name('admin.bookings');
    Route::get('/admin/bookings/{id}/invoice', [InvoiceController::class, 'showAdmin'])->name('admin.bookings.invoice');
    Route::get('/admin/courts', [AdminDashboardController::class, 'courts'])->name('admin.courts');
    Route::get('/admin/customers', [AdminDashboardController::class, 'customers'])->name('admin.customers');
    Route::post('/admin/bookings/{id}/no-show', [AdminDashboardController::class, 'markNoShow'])->name('admin.bookings.noshow');
    Route::post('/admin/bookings/{id}/mark-paid', [AdminDashboardController::class, 'markPaid'])->name('admin.bookings.mark-paid');
    Route::post('/admin/customers/{id}/issue-credits', [AdminDashboardController::class, 'issueCredits'])->name('admin.customers.issue-credits');
    Route::post('/admin/customers/{id}/issue-pass', [AdminDashboardController::class, 'issuePass'])->name('admin.customers.issue-pass');
    
    // Manage Courts, Pricing Rules, & Staff Members restricted to Owner & Manager
    Route::middleware('role:owner,manager')->group(function () {
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
    });
});
