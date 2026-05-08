# VenueTickets - Event Ticketing & Seat Reservation Platform

A comprehensive event ticketing and seat reservation platform built with Laravel 12. VenueTickets provides a complete solution for event organizers to manage events, venues, seat maps, pricing, and ticket sales, while offering customers an easy way to discover and book event tickets.

---

## Project Information

| | |
|---|---|
| **Project Name** | VenueTickets |
| **Laravel Version** | 12.58.0 |
| **PHP Version** | 8.2.12 |
| **Database** | MySQL |
| **Frontend** | Bootstrap 5 (CDN) |
| **Authentication** | Custom Auth with Spatie Permissions |
| **Icons** | Bootstrap Icons |

---

## Features

### For Customers
- Browse and search events
- Interactive seat selection
- Multiple pricing tiers (Early Bird, VIP, Regular)
- Secure ticket purchasing
- Digital tickets with QR codes
- Ticket transfer to other users
- Promo code support
- Order history and ticket management

### For Organizers
- Event creation and management
- Venue and seat map builder
- Dynamic pricing tiers
- Sales analytics and reports
- Attendee check-in via QR scanning
- Payout management
- Promotional code creation

### For Administrators
- Complete admin dashboard with KPI widgets
- Global spotlight search
- User and organizer management
- Role-based access control (Spatie)
- Order and refund management
- Resale marketplace moderation
- Financial reporting and analytics
- Audit logging system
- System settings configuration
- Maintenance mode toggle

---

## System Requirements

- PHP >= 8.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer

---

##Database

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web-project
DB_USERNAME=root
DB_PASSWORD=your_password

## Installation

### File Structure

venue-tickets/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Web/
│   │   │       ├── AdminController.php
│   │   │       ├── AnalyticsController.php
│   │   │       ├── AuditController.php
│   │   │       ├── AuthController.php
│   │   │       ├── EventsController.php
│   │   │       ├── FinanceController.php
│   │   │       ├── OrdersController.php
│   │   │       ├── OrganizersController.php
│   │   │       ├── PricingTiersController.php
│   │   │       ├── PromoCodesController.php
│   │   │       ├── ResaleController.php
│   │   │       ├── RolesController.php
│   │   │       ├── ScanController.php
│   │   │       ├── SettingsController.php
│   │   │       ├── TicketsController.php
│   │   │       ├── UsersController.php
│   │   │       └── VenuesController.php
│   │   └── Middleware/
│   │       ├── CheckUserStatus.php
│   │       └── NoCache.php
│   └── Models/
│       ├── AuditLog.php
│       ├── Event.php
│       ├── FailedPayment.php
│       ├── Order.php
│       ├── OrganizerDetail.php
│       ├── Payout.php
│       ├── PlatformSetting.php
│       ├── PricingTier.php
│       ├── PromoCode.php
│       ├── PromoCodeUsage.php
│       ├── ResaleListing.php
│       ├── ScanLog.php
│       ├── Seat.php
│       ├── SeatMap.php
│       ├── Ticket.php
│       ├── User.php
│       ├── UserBan.php
│       ├── Venue.php
│       └── Zone.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_05_07_193800_create_venues_table.php
│   │   ├── 2026_05_07_193810_create_seat_maps_table.php
│   │   ├── 2026_05_07_193815_create_zones_table.php
│   │   ├── 2026_05_07_193820_create_events_table.php
│   │   ├── 2026_05_07_193830_create_seats_table.php
│   │   ├── 2026_05_07_193840_create_organizer_details_table.php
│   │   ├── 2026_05_07_194000_create_pricing_tiers_table.php
│   │   ├── 2026_05_07_194040_create_resale_listings_table.php
│   │   ├── 2026_05_07_194050_create_orders_table.php
│   │   ├── 2026_05_07_194060_create_tickets_table.php
│   │   ├── 2026_05_07_194100_create_promo_codes_table.php
│   │   ├── 2026_05_07_194110_create_promo_code_usage_table.php
│   │   ├── 2026_05_07_194120_create_payouts_table.php
│   │   ├── 2026_05_07_194130_create_user_bans_table.php
│   │   ├── 2026_05_07_194140_create_audit_logs_table.php
│   │   ├── 2026_05_07_194150_create_failed_payments_table.php
│   │   ├── 2026_05_07_194200_create_scan_logs_table.php
│   │   └── 2026_05_07_194210_create_platform_settings_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── RolePermissionSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── events/
│       │   │   ├── list.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── sales.blade.php
│       │   ├── venues/
│       │   │   ├── list.blade.php
│       │   │   └── edit.blade.php
│       │   ├── seat-maps/
│       │   │   ├── list.blade.php
│       │   │   └── edit.blade.php
│       │   ├── orders/
│       │   │   ├── list.blade.php
│       │   │   └── view.blade.php
│       │   ├── tickets/
│       │   │   └── list.blade.php
│       │   ├── users/
│       │   │   ├── list.blade.php
│       │   │   └── view.blade.php
│       │   ├── organizers/
│       │   │   ├── list.blade.php
│       │   │   ├── events.blade.php
│       │   │   └── revenue.blade.php
│       │   ├── promo-codes/
│       │   │   ├── list.blade.php
│       │   │   └── edit.blade.php
│       │   ├── finance/
│       │   │   └── overview.blade.php
│       │   ├── analytics/
│       │   │   └── index.blade.php
│       │   ├── settings/
│       │   │   └── edit.blade.php
│       │   └── audit/
│       │       └── list.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── organizer-register.blade.php
│       │   ├── change-password.blade.php
│       │   └── profile.blade.php
│       ├── layouts/
│       │   ├── master.blade.php
│       │   └── admin.blade.php
│       └── welcome.blade.php
└── routes/
    └── web.php



Database Tables
Table	Description
users	User accounts with Spatie roles
venues	Venue information
seat_maps	Seat map layouts
zones	Seating zones within maps
seats	Individual seat records
events	Event details
pricing_tiers	Pricing configurations per event
orders	Customer orders
tickets	Individual ticket records
resale_listings	Ticket resale marketplace
promo_codes	Promotional discount codes
promo_code_usage	Promo code usage tracking
organizer_details	Organizer profiles
payouts	Organizer payment records
user_bans	User ban/suspension records
audit_logs	System activity logs
failed_payments	Failed payment attempts
scan_logs	Ticket scan/check-in records
platform_settings	System configuration
Roles & Permissions
Role	Permissions
super-admin	All permissions
admin	Manage events, venues, users, orders, promo codes, view finance, audit log
organizer	Create/edit events, scan tickets
staff	Scan tickets
customer	Browse events, purchase tickets
Admin Routes
Route	Name	Description
/admin/dashboard	admin_dashboard	Admin dashboard with KPIs
/admin/search	admin_search	Global search
/admin/events	admin_events_list	List all events
/admin/events/create	admin_events_create	Create new event
/admin/events/{id}/edit	admin_events_edit	Edit event
/admin/events/{id}/sales	admin_events_sales	Event sales summary
/admin/events/{id}/pricing	admin_events_pricing_list	Pricing tiers
/admin/events/{id}/scan	admin_events_scan	QR scan dashboard
/admin/venues	admin_venues_list	List venues
/admin/venues/create	admin_venues_create	Create venue
/admin/seat-maps	admin_seat_maps_list	List seat maps
/admin/seat-maps/create	admin_seat_maps_create	Create seat map
/admin/orders	admin_orders_list	List orders
/admin/orders/{id}	admin_orders_view	View order details
/admin/tickets	admin_tickets_list	List tickets
/admin/users	admin_users_list	List users
/admin/users/{id}	admin_users_view	View user details
/admin/organizers	admin_organizers_list	List organizers
/admin/organizers/{id}/events	admin_organizers_events	Organizer events
/admin/organizers/{id}/revenue	admin_organizers_revenue	Organizer revenue
/admin/promo-codes	admin_promo_codes_list	List promo codes
/admin/roles	admin_roles_list	Manage roles
/admin/finance	admin_finance_index	Finance overview
/admin/analytics	admin_analytics_index	Analytics dashboard
/admin/settings	admin_settings_index	Platform settings
/admin/audit-log	admin_audit_log_list	Audit log
Public Routes
Route	Name	Description
/	home	Home page
/login	login	Login form
/register	register	Registration form
/organizer/register	organizer.register	Organizer registration
/profile	profile	User profile
/change-password	password_change	Change password
/forgot-password	password.request	Forgot password
Coding Standards
Controllers
Namespace: App\Http\Controllers\Web

Extend: App\Http\Controllers\Controller

Validation: $request->validate([...])

Permission check: if(!auth()->user()->hasPermissionTo('permission')) { abort(403); }

Models
Namespace: App\Models

User model uses Spatie\Permission\Traits\HasRoles

All models use $fillable for mass assignment

Routes
Format: Route::get('resource/{param?}', [Controller::class, 'method'])->name('route_name')

Protected routes use: Route::middleware('auth')->group(function () {...})

Views
Extend: layouts.master or layouts.admin

Forms include: {{ csrf_field() }}

Auth checks: @auth / @guest

Role checks: @role('role_name')

Permission checks: @can('permission_name')

Authentication
Password hashing: bcrypt($request->password)

Login: Auth::attempt(['email' => $email, 'password' => $password])

Password verify: Hash::check($request->current_password, $user->password)

Logout: Auth::logout() with session invalidation

Middleware
CheckUserStatus - Verifies user is not banned/inactive on every request

NoCache - Prevents browser caching of authenticated pages
