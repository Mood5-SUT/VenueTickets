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
        }
        
        return $next($request);
    }
}