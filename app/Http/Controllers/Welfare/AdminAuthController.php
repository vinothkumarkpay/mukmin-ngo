<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in') === true) {
            return redirect()->route('welfare.admin.dashboard');
        }
        return view('welfare.admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Static credentials for ease of demo & admin management
        if ($credentials['email'] === 'admin@mukmin.com' && $credentials['password'] === 'admin123') {
            session(['admin_logged_in' => true]);
            return redirect()->route('welfare.admin.dashboard')->with('success', 'Welcome back, Administrator!');
        }

        return redirect()->back()->withInput($request->only('email'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('welfare.admin.login')->with('success', 'You have been logged out successfully.');
    }
}
