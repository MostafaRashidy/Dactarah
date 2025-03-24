<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();

        // Add more specific logging
        logger('Admin logged in successfully:', [
            'admin' => Auth::guard('admin')->user(),
            'session' => session()->all()
        ]);

        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors([
        'email' => 'These credentials do not match our records.',
    ]);
}

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
