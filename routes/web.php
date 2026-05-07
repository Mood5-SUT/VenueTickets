<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\EventsController;
use App\Http\Controllers\Web\VenuesController;
use App\Http\Controllers\Web\PricingTiersController;
use App\Http\Controllers\Web\PromoCodesController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\OrganizersController;
use App\Http\Controllers\Web\RolesController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\Web\TicketsController;
use App\Http\Controllers\Web\ScanController;
use App\Http\Controllers\Web\ResaleController;
use App\Http\Controllers\Web\FinanceController;
use App\Http\Controllers\Web\AnalyticsController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\AuditController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login_submit');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register_submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Organizer Registration
Route::get('organizer/register', [AuthController::class, 'showOrganizerRegistration'])->name('organizer.register');
Route::post('organizer/register', [AuthController::class, 'registerOrganizer'])->name('organizer.register_submit');

// Protected Routes
Route::middleware('auth:web')->group(function () {
    
    // Profile Routes
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('profile', [AuthController::class, 'updateProfile'])->name('profile_update');
    Route::get('change-password', [AuthController::class, 'showChangePasswordForm'])->name('password_change');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('password_change_submit');
    
    // Admin Routes
    Route::prefix('admin')->name('admin_')->group(function () {
        
        // Dashboard
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('search', [AdminController::class, 'search'])->name('search');
        Route::post('theme-toggle', [AdminController::class, 'themeToggle'])->name('theme_toggle');
        
        // Events Management
        Route::prefix('events')->name('events_')->group(function () {
            Route::get('/', [EventsController::class, 'list'])->name('list');
            Route::get('create', [EventsController::class, 'edit'])->name('create');
            Route::post('save/{id?}', [EventsController::class, 'save'])->name('save');
            Route::get('{id}/edit', [EventsController::class, 'edit'])->name('edit');
            Route::delete('{id}', [EventsController::class, 'delete'])->name('delete');
            Route::post('{id}/status', [EventsController::class, 'toggleStatus'])->name('toggle_status');
            Route::get('{id}/sales', [EventsController::class, 'salesSummary'])->name('sales');
            
            // Pricing Tiers
            Route::prefix('{id}/pricing')->name('pricing_')->group(function () {
                Route::get('/', [PricingTiersController::class, 'list'])->name('list');
                Route::post('save', [PricingTiersController::class, 'save'])->name('save');
                Route::post('{tierId}/edit', [PricingTiersController::class, 'edit'])->name('edit');
                Route::post('{tierId}/toggle', [PricingTiersController::class, 'toggle'])->name('toggle');
                Route::delete('{tierId}', [PricingTiersController::class, 'delete'])->name('delete');
            });
            
            // QR Scan
            Route::get('{id}/scan', [ScanController::class, 'scanDashboard'])->name('scan');
            Route::post('{id}/scan/verify', [ScanController::class, 'verify'])->name('scan_verify');
            Route::post('{id}/scan/manual', [ScanController::class, 'manualCheckin'])->name('scan_manual');
            Route::get('{id}/scan/log', [ScanController::class, 'checkinLog'])->name('scan_log');
            Route::post('{id}/scan/staff', [ScanController::class, 'assignStaff'])->name('scan_staff');
        });
        
        // Venues Management
        Route::prefix('venues')->name('venues_')->group(function () {
            Route::get('/', [VenuesController::class, 'list'])->name('list');
            Route::get('create', [VenuesController::class, 'edit'])->name('create');
            Route::post('save/{id?}', [VenuesController::class, 'save'])->name('save');
            Route::get('{id}/edit', [VenuesController::class, 'edit'])->name('edit');
            Route::delete('{id}', [VenuesController::class, 'delete'])->name('delete');
        });
        
        // Seat Maps
        Route::prefix('seat-maps')->name('seat_maps_')->group(function () {
            Route::get('/', [VenuesController::class, 'seatMapsList'])->name('list');
            Route::get('create', [VenuesController::class, 'seatMapEdit'])->name('create');
            Route::post('save/{id?}', [VenuesController::class, 'seatMapSave'])->name('save');
            Route::get('{id}/edit', [VenuesController::class, 'seatMapEdit'])->name('edit');
            Route::delete('{id}', [VenuesController::class, 'seatMapDelete'])->name('delete');
            Route::post('{id}/seats/toggle', [VenuesController::class, 'seatToggle'])->name('seat_toggle');
        });
        
        // Promo Codes
        Route::prefix('promo-codes')->name('promo_codes_')->group(function () {
            Route::get('/', [PromoCodesController::class, 'list'])->name('list');
            Route::get('create', [PromoCodesController::class, 'edit'])->name('create');
            Route::post('save/{id?}', [PromoCodesController::class, 'save'])->name('save');
            Route::get('{id}/edit', [PromoCodesController::class, 'edit'])->name('edit');
            Route::delete('{id}', [PromoCodesController::class, 'delete'])->name('delete');
            Route::post('{id}/deactivate', [PromoCodesController::class, 'deactivate'])->name('deactivate');
            Route::post('{id}/activate', [PromoCodesController::class, 'activate'])->name('activate');
            Route::get('{id}/usage', [PromoCodesController::class, 'usageLog'])->name('usage');
        });
        
        // User Management
        Route::prefix('users')->name('users_')->group(function () {
            Route::get('/', [UsersController::class, 'list'])->name('list');
            Route::get('{id}', [UsersController::class, 'view'])->name('view');
            Route::post('{id}/role', [UsersController::class, 'updateRole'])->name('update_role');
            Route::post('{id}/ban', [UsersController::class, 'ban'])->name('ban');
            Route::post('{id}/unban', [UsersController::class, 'unban'])->name('unban');
            Route::get('export', [UsersController::class, 'export'])->name('export');
        });
        
        // Organizers
        Route::prefix('organizers')->name('organizers_')->group(function () {
            Route::get('/', [OrganizersController::class, 'list'])->name('list');
            Route::get('{id}', [OrganizersController::class, 'view'])->name('view');
            Route::post('{id}/approve', [OrganizersController::class, 'approve'])->name('approve');
            Route::post('{id}/reject', [OrganizersController::class, 'reject'])->name('reject');
            Route::post('{id}/suspend', [OrganizersController::class, 'suspend'])->name('suspend');
            Route::get('{id}/events', [OrganizersController::class, 'events'])->name('events');
            Route::get('{id}/revenue', [OrganizersController::class, 'revenue'])->name('revenue');
        });
        
        // Roles & Permissions
        Route::prefix('roles')->name('roles_')->group(function () {
            Route::get('/', [RolesController::class, 'list'])->name('list');
            Route::post('{id}/permissions', [RolesController::class, 'updatePermissions'])->name('update_permissions');
            Route::post('staff', [RolesController::class, 'createStaff'])->name('create_staff');
            Route::get('activity-log', [RolesController::class, 'activityLog'])->name('activity_log');
        });
        
        // Orders & Tickets
        Route::prefix('orders')->name('orders_')->group(function () {
            Route::get('/', [OrdersController::class, 'list'])->name('list');
            Route::get('{id}', [OrdersController::class, 'view'])->name('view');
            Route::post('{id}/refund', [OrdersController::class, 'refund'])->name('refund');
        });
        
        // Tickets
        Route::prefix('tickets')->name('tickets_')->group(function () {
            Route::get('/', [TicketsController::class, 'list'])->name('list');
            Route::get('{id}', [TicketsController::class, 'view'])->name('view');
            Route::post('{id}/void', [TicketsController::class, 'void'])->name('void');
            Route::post('{id}/resend', [TicketsController::class, 'resend'])->name('resend');
            Route::post('bulk-action', [TicketsController::class, 'bulkAction'])->name('bulk_action');
        });
        
        // Resale
        Route::prefix('resale')->name('resale_')->group(function () {
            Route::get('/', [ResaleController::class, 'list'])->name('list');
            Route::get('{id}', [ResaleController::class, 'view'])->name('view');
            Route::post('{id}/remove', [ResaleController::class, 'remove'])->name('remove');
            Route::post('price-cap', [ResaleController::class, 'setPriceCap'])->name('price_cap');
            Route::get('transactions', [ResaleController::class, 'transactions'])->name('transactions');
        });
        
        // Finance
        Route::prefix('finance')->name('finance_')->group(function () {
            Route::get('/', [FinanceController::class, 'overview'])->name('index');
            Route::get('payouts', [FinanceController::class, 'payouts'])->name('payouts');
            Route::post('payouts/{id}/process', [FinanceController::class, 'processPayout'])->name('process_payout');
            Route::get('refunds', [FinanceController::class, 'refunds'])->name('refunds');
            Route::get('failed-payments', [FinanceController::class, 'failedPayments'])->name('failed_payments');
            Route::get('stripe-balance', [FinanceController::class, 'stripeBalance'])->name('stripe_balance');
        });
        
        // Analytics
        Route::prefix('analytics')->name('analytics_')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('export', [AnalyticsController::class, 'export'])->name('export');
        });
        
        // System Settings
        Route::prefix('settings')->name('settings_')->group(function () {
            Route::get('/', [SettingsController::class, 'edit'])->name('index');
            Route::post('save', [SettingsController::class, 'save'])->name('save');
            Route::post('maintenance', [SettingsController::class, 'toggleMaintenance'])->name('maintenance');
            Route::post('initialize', [SettingsController::class, 'initializeDefaultSettings'])->name('initialize');
        });
        
        // Audit Log
        Route::prefix('audit-log')->name('audit_log_')->group(function () {
            Route::get('/', [AuditController::class, 'list'])->name('list');
            Route::get('{id}', [AuditController::class, 'view'])->name('view');
            Route::get('user/{userId}', [AuditController::class, 'userActivity'])->name('user_activity');
            Route::get('export', [AuditController::class, 'export'])->name('export');
        });
    });
});