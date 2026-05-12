<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title') - Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        .sidebar {
            background: #1a1d23;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: #a0a0a0;
            padding: 10px 20px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="p-3 border-bottom border-secondary">
            <a href="{{ route('admin_dashboard') }}" class="text-white text-decoration-none">
                <h5 class="mb-0"><i class="bi bi-ticket-perforated me-2"></i>VenueTickets</h5>
            </a>
        </div>
        
        <nav class="flex-grow-1 p-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin_dashboard') }}" 
                       class="nav-link {{ request()->routeIs('admin_dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                @can('manage_events')
                <li class="nav-item">
                    <a href="{{ route('admin_events_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_events_*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i> Events
                    </a>
                </li>
                @endcan
                
                @can('manage_venues')
                <li class="nav-item">
                    <a href="{{ route('admin_venues_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_venues_*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Venues
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin_seat_maps_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_seat_maps_*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap"></i> Seat Maps
                    </a>
                </li>
                @endcan
                
                @can('manage_orders')
                <li class="nav-item">
                    <a href="{{ route('admin_orders_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_orders_*') ? 'active' : '' }}">
                        <i class="bi bi-cart"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin_tickets_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_tickets_*') ? 'active' : '' }}">
                        <i class="bi bi-ticket"></i> Tickets
                    </a>
                </li>
                @endcan
                
                @can('manage_users')
                <li class="nav-item">
                    <a href="{{ route('admin_users_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_users_*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin_organizers_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_organizers_*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Organizers
                    </a>
                </li>
                @endcan
                
                @can('manage_promo_codes')
                <li class="nav-item">
                    <a href="{{ route('admin_promo_codes_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_promo_codes_*') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i> Promo Codes
                    </a>
                </li>
                @endcan
                
                @can('view_finance')
                <li class="nav-item">
                    <a href="{{ route('admin_finance_index') }}" 
                       class="nav-link {{ request()->routeIs('admin_finance_*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i> Finance
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin_analytics_index') }}" 
                       class="nav-link {{ request()->routeIs('admin_analytics_*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Analytics
                    </a>
                </li>
                @endcan
                
                @can('manage_system')
                <li class="nav-item">
                    <a href="{{ route('admin_settings_index') }}" 
                       class="nav-link {{ request()->routeIs('admin_settings_*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin_audit_log_list') }}" 
                       class="nav-link {{ request()->routeIs('admin_audit_log_*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Audit Log
                    </a>
                </li>
                @endcan
            </ul>
        </nav>
        
        <div class="p-3 border-top border-secondary">
            <a href="{{ route('home') }}" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> Back to Site
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar px-4 py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">@yield('title')</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-4">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>