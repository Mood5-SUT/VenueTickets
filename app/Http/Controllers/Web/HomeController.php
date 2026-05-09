<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is logged in but not verified
        if ($user && !$user->is_verified && !$user->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('verify_otp')
                ->with('warning', 'Please verify your email address first.');
        }
        
        return view('welcome');
    }
}