<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'profile', 'updateProfile', 'changePassword']);
        $this->middleware('auth:web')->only(['logout', 'profile', 'updateProfile', 'changePassword']);
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $this->validate($request, [
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
            
            // Check if user is banned
            if ($user->isBanned()) {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Your account has been suspended. Please contact support.'])
                    ->withInput($request->except('password'));
            }
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Your account is not active. Please verify your email.'])
                    ->withInput($request->except('password'));
            }
            
            $request->session()->regenerate();
            
            // Redirect based on role
            if ($user->hasRole(['super-admin', 'admin'])) {
                return redirect()->intended(route('admin_dashboard'));
            } elseif ($user->hasRole('organizer')) {
                return redirect()->intended(route('organizer_dashboard'));
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
        $this->validate($request, [
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
            'is_active' => true
        ]);
        
        // Assign default role
        $user->assignRole('customer');
        
        // Log the user in
        Auth::login($user);
        
        return redirect()->route('home')
            ->with('success', 'Welcome to VenueTickets! Your account has been created successfully.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('event')->orderBy('created_at', 'desc')->limit(10)->get();
        $tickets = $user->tickets()->with('event')->where('status', 'active')->orderBy('created_at', 'desc')->get();
        
        return view('auth.profile', compact('user', 'orders', 'tickets'));
    }
    
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                \Storage::delete('public/' . $user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }
    
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Verify old password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.']);
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
        $this->validate($request, [
            'email' => 'required|email|exists:users,email'
        ]);
        
        // Here you would typically send a password reset email
        // For now, just redirect with a success message
        
        return redirect()->back()
            ->with('success', 'Password reset link has been sent to your email address.');
    }
    
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
    
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Here you would typically validate the token and reset the password
        // For now, just redirect with a success message
        
        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }
    
    public function showOrganizerRegistration()
    {
        return view('auth.organizer-register');
    }
    
    public function registerOrganizer(Request $request)
    {
        $this->validate($request, [
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
            'is_active' => true
        ]);
        
        // Create organizer detail
        $user->organizerDetail()->create([
            'company_name' => $request->company_name,
            'business_phone' => $request->business_phone,
            'website' => $request->website,
            'description' => $request->description,
            'tax_id' => $request->tax_id,
            'status' => 'pending'
        ]);
        
        // Assign roles
        $user->assignRole('customer');
        $user->assignRole('organizer');
        
        Auth::login($user);
        
        return redirect()->route('home')
            ->with('success', 'Your organizer account has been created and is pending approval. We will review your application shortly.');
    }
}