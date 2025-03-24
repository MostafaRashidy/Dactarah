<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('doctor')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            return redirect()->route('doctors.create');
        }

        return $next($request);
    }
}
