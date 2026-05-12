<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        $remember = $request->has('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $request->session()->regenerate();
            
            // Check if user is banned or inactive
            if ($user->isBanned() || !$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->back()
                    ->withErrors(['email' => 'Your account is not accessible. Please contact support.'])
                    ->withInput($request->except('password'));
            }
            
            // Check if email is verified (skip for admin)
            if (!$user->is_verified && !$user->hasRole(['super-admin', 'admin'])) {
                // Generate OTP if needed
                if (!$user->otp_code || $user->isOtpExpired()) {
                    $otp = $user->generateOtp();
                    
                    try {
                        Mail::to($user->email)->send(new OtpMail($otp, $user->name));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send OTP: ' . $e->getMessage());
                    }
                }
                
                session(['email' => $user->email]);
                
                return redirect()->route('verify_otp')
                    ->with('warning', 'Please verify your email to continue.');
            }
            
            // Redirect based on role
            if ($user->hasRole(['super-admin', 'admin'])) {
                return redirect()->intended(route('admin_dashboard'));
            }
            
            return redirect()->intended(route('home'));
        }
        
        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->except('password'));
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required|accepted'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'is_active' => true,
            'is_verified' => false
        ]);
        
        $user->assignRole('customer');
        
        // Generate and send OTP
        $otp = $user->generateOtp();
        
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        Auth::login($user);
        session(['email' => $user->email]);
        
        return redirect()->route('verify_otp')
            ->with('success', 'Account created! Please verify your email.');
    }
    
    public function showOtpForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::user()->is_verified) {
            return redirect()->route('home');
        }
        
        return view('auth.verify-otp');
    }
    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Session expired. Please login again.']);
        }
        
        if ($user->isOtpExpired()) {
            return redirect()->back()
                ->withErrors(['otp' => 'OTP has expired. Request a new one.']);
        }
        
        if ($user->otp_code !== $request->otp) {
            return redirect()->back()
                ->withErrors(['otp' => 'Invalid OTP code. Try again.']);
        }
        
        // Mark as verified
        $user->is_verified = true;
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();
        
        session()->forget('email');
        
        if ($user->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin_dashboard')
                ->with('success', 'Email verified!');
        }
        
        return redirect()->route('home')
            ->with('success', 'Email verified! Welcome to VenueTickets!');
    }
    
    public function resendOtp()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->is_verified) {
            return redirect()->route('home');
        }
        
        $otp = $user->generateOtp();
        
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        return redirect()->back()
            ->with('success', 'New OTP sent to your email.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Logged out successfully.')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('event')->orderBy('created_at', 'desc')->limit(10)->get();
        $tickets = $user->tickets()->with('event')->where('status', 'active')->get();
        
        return view('auth.profile', compact('user', 'orders', 'tickets'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        
        return redirect()->back()->with('success', 'Profile updated.');
    }
    
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        $user->password = bcrypt($request->password);
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Password changed successfully.');
    }
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        
        $user = User::where('email', $request->email)->first();
        $otp = $user->generateOtp();
        
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        session(['reset_email' => $user->email]);
        
        return redirect()->route('password.reset', ['token' => 'otp'])
            ->with('success', 'OTP sent to your email for password reset.');
    }
    
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'otp' => 'required|string|size:6'
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if ($user->isOtpExpired() || $user->otp_code !== $request->otp) {
            return redirect()->back()
                ->withErrors(['otp' => 'Invalid or expired OTP.']);
        }
        
        $user->password = bcrypt($request->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();
        
        session()->forget('reset_email');
        
        return redirect()->route('login')
            ->with('success', 'Password reset successfully. Please login.');
    }
    
    public function showOrganizerRegistration()
    {
        return view('auth.organizer-register');
    }
    
    public function registerOrganizer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'tax_id' => 'nullable|string|max:50',
            'terms' => 'required|accepted'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'is_active' => true,
            'is_verified' => false
        ]);
        
        $user->organizerDetail()->create([
            'company_name' => $request->company_name,
            'business_phone' => $request->business_phone,
            'website' => $request->website,
            'description' => $request->description,
            'tax_id' => $request->tax_id,
            'status' => 'pending'
        ]);
        
        $user->assignRole('customer');
        $user->assignRole('organizer');
        
        // Generate and send OTP
        $otp = $user->generateOtp();
        
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
        
        Auth::login($user);
        session(['email' => $user->email]);
        
        return redirect()->route('verify_otp')
            ->with('success', 'Account created! Please verify your email to continue.');
    }
}