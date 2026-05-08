<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

// Public Routes (No Authentication Required)
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login_submit');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register_submit');

// Password Reset Routes
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Organizer Registration
Route::get('organizer/register', [AuthController::class, 'showOrganizerRegistration'])->name('organizer.register');
Route::post('organizer/register', [AuthController::class, 'registerOrganizer'])->name('organizer.register_submit');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile Routes
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('profile', [AuthController::class, 'updateProfile'])->name('profile_update');
    Route::get('change-password', [AuthController::class, 'showChangePasswordForm'])->name('password_change');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('password_change_submit');
    
    // Admin Routes here...
});