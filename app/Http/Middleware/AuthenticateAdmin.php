<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Add this line for debugging
        logger('Admin authenticated:', ['admin' => Auth::guard('admin')->user()]);

        return $next($request);
    }
}
