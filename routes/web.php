<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/book', [PublicBookingController::class, 'index'])->name('booking.index');
Route::get('/booking/checkout', [PublicBookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/checkout', [PublicBookingController::class, 'process'])->name('booking.process');
Route::get('/booking/confirmation/{reference}', [PublicBookingController::class, 'confirmation'])->name('booking.confirmation');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Dashboard Routes
Route::get('/my-account', [CustomerDashboardController::class, 'index'])->name('customer.my-bookings');
Route::post('/my-account/cancel/{id}', [CustomerDashboardController::class, 'cancelBooking'])->name('customer.booking.cancel');
Route::post('/my-account/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');

// Admin / Staff Management Portal Routes
Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/bookings', [AdminDashboardController::class, 'bookings'])->name('admin.bookings');
Route::get('/admin/courts', [AdminDashboardController::class, 'courts'])->name('admin.courts');
Route::post('/admin/courts', [AdminDashboardController::class, 'storeCourt'])->name('admin.courts.store');
