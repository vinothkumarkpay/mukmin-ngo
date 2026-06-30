<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('welfare.admin.login')->with('error', 'Please login to access the Admin Panel.');
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welfare.admin.login')->with('error', 'Your account has been deactivated.');
        }

        if (! $user->role_id) {
            return redirect()->route('welfare.admin.login')->with('error', 'Your account has no assigned role. Contact a super admin.');
        }

        $user->loadMissing('role.permissions');

        return $next($request);
    }
}
