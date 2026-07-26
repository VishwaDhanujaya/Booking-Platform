<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class AuthController extends Controller
{
    public function showLogin()
    {
        $tenant = Tenant::first(['*']);
        return view('auth.login', compact('tenant'));
    }

    public function login(Request $request)
    {
        // Mock authentication - successful login simulation
        session(['user_name' => 'Kavinda Perera', 'user_email' => $request->input('email', 'kavinda@example.com'), 'is_logged_in' => true]);
        return redirect()->route('customer.my-bookings');
    }

    public function showRegister()
    {
        $tenant = Tenant::first(['*']);
        return view('auth.register', compact('tenant'));
    }

    public function register(Request $request)
    {
        // Mock registration simulation
        session(['user_name' => $request->input('name', 'Kavinda Perera'), 'user_email' => $request->input('email', 'kavinda@example.com'), 'is_logged_in' => true]);
        return redirect()->route('customer.my-bookings');
    }

    public function logout()
    {
        session()->forget(['user_name', 'user_email', 'is_logged_in']);
        return redirect()->route('home');
    }
}
