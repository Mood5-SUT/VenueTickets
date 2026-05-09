<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Allowed routes for unverified users
            $allowedRoutes = [
                'verify_otp',
                'verify_otp_submit', 
                'resend_otp',
                'logout',
                'login',
                'login_submit',
                'register',
                'register_submit',
                'password.request',
                'password.email',
                'password.reset',
                'password.update'
            ];
            
            // Check if user is banned
            if ($user->isBanned()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account is not active. Please contact support.']);
            }
            
            // Check if email is verified (skip for super-admin and admin)
            if (!$user->is_verified && !$user->hasRole(['super-admin', 'admin'])) {
                // Only allow specific routes for unverified users
                if (!in_array($request->route()->getName(), $allowedRoutes)) {
                    return redirect()->route('verify_otp')
                        ->with('warning', 'Please verify your email address before continuing.');
                }
            }
        }
        
        return $next($request);
    }
}