{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - VenueTickets Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin_dashboard') }}" class="sidebar-brand">
                    <i class="bi bi-ticket-perforated"></i>
                    <span class="brand-text">VenueTickets</span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            
            <!-- Global Search -->
            <div class="sidebar-search p-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           class="form-control" 
                           id="globalSearch" 
                           placeholder="Search... (Ctrl+K)"
                           autocomplete="off">
                </div>
                <div class="search-results" id="searchResults" style="display: none;"></div>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('admin_dashboard') }}" 
                           class="nav-link {{ request()->routeIs('admin_dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Events Section -->
                    @can('manage_events')
                    <li class="nav-item">
                        <a href="#eventsSubmenu" 
                           class="nav-link collapsed" 
                           data-bs-toggle="collapse" 
                           role="button">
                            <i class="bi bi-calendar-event"></i>
                            <span>Events</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul class="nav flex-column collapse {{ request()->is('admin/events*') ? 'show' : '' }}" 
                            id="eventsSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('admin_events_list') }}" 
                                   class="nav-link {{ request()->routeIs('admin_events_list') ? 'active' : '' }}">
                                    All Events
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_events_create') }}" 
                                   class="nav-link">
                                    Create Event
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    
                    <!-- Venues Section -->
                    @can('manage_venues')
                    <li class="nav-item">
                        <a href="#venuesSubmenu" 
                           class="nav-link collapsed" 
                           data-bs-toggle="collapse">
                            <i class="bi bi-building"></i>
                            <span>Venues</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul class="nav flex-column collapse {{ request()->is('admin/venues*') ? 'show' : '' }}" 
                            id="venuesSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('admin_venues_list') }}" 
                                   class="nav-link">
                                    All Venues
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_seat_maps_list') }}" 
                                   class="nav-link">
                                    Seat Maps
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    
                    <!-- Orders & Tickets -->
                    @can('manage_orders')
                    <li class="nav-item">
                        <a href="#ordersSubmenu" 
                           class="nav-link collapsed" 
                           data-bs-toggle="collapse">
                            <i class="bi bi-cart"></i>
                            <span>Orders & Tickets</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul class="nav flex-column collapse {{ request()->is('admin/orders*') ? 'show' : '' }}" 
                            id="ordersSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('admin_orders_list') }}" 
                                   class="nav-link">
                                    All Orders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_resale_list') }}" 
                                   class="nav-link">
                                    Resale Moderation
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    
                    <!-- Users & Organizers -->
                    @can('manage_users')
                    <li class="nav-item">
                        <a href="#usersSubmenu" 
                           class="nav-link collapsed" 
                           data-bs-toggle="collapse">
                            <i class="bi bi-people"></i>
                            <span>Users</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul class="nav flex-column collapse {{ request()->is('admin/users*') || request()->is('admin/organizers*') ? 'show' : '' }}" 
                            id="usersSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('admin_users_list') }}" 
                                   class="nav-link">
                                    All Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_organizers_list') }}" 
                                   class="nav-link">
                                    Organizers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_roles_list') }}" 
                                   class="nav-link">
                                    Roles & Permissions
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    
                    <!-- Finance -->
                    @can('view_finance')
                    <li class="nav-item">
                        <a href="#financeSubmenu" 
                           class="nav-link collapsed" 
                           data-bs-toggle="collapse">
                            <i class="bi bi-cash-stack"></i>
                            <span>Finance</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul class="nav flex-column collapse" id="financeSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('admin_finance') }}" 
                                   class="nav-link">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_analytics') }}" 
                                   class="nav-link">
                                    Analytics
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    
                    <!-- Promo Codes -->
                    @can('manage_promo_codes')
                    <li class="nav-item">
                        <a href="{{ route('admin_promo_codes_list') }}" 
                           class="nav-link {{ request()->routeIs('admin_promo_codes*') ? 'active' : '' }}">
                            <i class="bi bi-tag"></i>
                            <span>Promo Codes</span>
                        </a>
                    </li>
                    @endcan
                    
                    <!-- System -->
                    @can('manage_system')
                    <li class="nav-item">
                        <a href="#systemSubmenu" 
                           class="nav-link collapsed" 
                           data-bs-toggle="collapse">
                            <i class="bi bi-gear"></i>
                            <span>System</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul class="nav flex-column collapse" id="systemSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('admin_settings') }}" 
                                   class="nav-link">
                                    Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_audit_log') }}" 
                                   class="nav-link">
                                    Audit Log
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                </ul>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="theme-toggle">
                        <button class="btn btn-sm btn-outline-secondary" id="themeToggle">
                            <i class="bi bi-sun-fill light-icon"></i>
                            <i class="bi bi-moon-fill dark-icon"></i>
                        </button>
                    </div>
                    <div class="user-info">
                        <small class="text-muted">{{ auth()->user()->name }}</small>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-topbar">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <button class="btn btn-link sidebar-toggle-mobile" id="mobileSidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <div class="d-flex align-items-center gap-3">
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Notifications</h6>
                                <a class="dropdown-item" href="#">New organizer application</a>
                                <a class="dropdown-item" href="#">Refund request pending</a>
                                <a class="dropdown-item" href="#">Failed payment alert</a>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Page Content -->
            <div class="admin-content">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>