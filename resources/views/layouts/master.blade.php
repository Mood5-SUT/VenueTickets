<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VenueTickets') - VenueTickets</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Theme Script (Put in head to prevent FOUC) -->
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <style>
        :root {
            /* Light Mode Tokens (Default) */
            --bg-color: #f8f9fa;
            --surface-color: rgba(255, 255, 255, 0.85);
            --navbar-bg: rgba(255, 255, 255, 0.85);
            --dropdown-bg: rgba(255, 255, 255, 0.95);
            --text-primary: #1f2128;
            --text-secondary: #6c757d;
            --glass-border: rgba(0, 0, 0, 0.08);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            
            /* Theme Colors (Base) */
            --theme-primary: #4361ee;
            --theme-secondary: #f72585;
            --theme-gradient: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
            
            /* Event Specific Themes */
            --theme-concert-start: #7209b7;
            --theme-concert-end: #f72585;
            
            --theme-football-start: #00b4d8;
            --theme-football-end: #03045e;
            
            --theme-theater-start: #e0aa3e;
            --theme-theater-end: #855309;
            
            --theme-conference-start: #11998e;
            --theme-conference-end: #38ef7d;
        }

        [data-theme="dark"] {
            /* Dark Mode Tokens */
            --bg-color: #0b0c10;
            --surface-color: rgba(31, 33, 40, 0.65);
            --navbar-bg: rgba(11, 12, 16, 0.8);
            --dropdown-bg: rgba(31, 33, 40, 0.95);
            --text-primary: #ffffff;
            --text-secondary: #a0a5b5;
            --glass-border: rgba(255, 255, 255, 0.1);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Abstract Background Elements */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 50% 50%, rgba(67, 97, 238, 0.12), transparent 60%),
                        radial-gradient(circle at 80% 20%, rgba(247, 37, 133, 0.08), transparent 40%);
            z-index: -1;
            animation: pulseBg 15s ease-in-out infinite alternate;
        }

        @keyframes pulseBg {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        /* Glassmorphism Navbar */
        .navbar {
            background: var(--navbar-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--theme-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-primary) !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--theme-primary) !important;
        }

        /* Glassmorphism Cards */
        .card.glass-card {
            background: var(--surface-color);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            color: var(--text-primary);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
        }

        .card.glass-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--card-shadow);
            border-color: var(--theme-primary);
        }

        /* Thematic Gradients for Cards */
        .theme-concert::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, var(--theme-concert-start), var(--theme-concert-end));
            z-index: 10;
        }
        .theme-football::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, var(--theme-football-start), var(--theme-football-end));
            z-index: 10;
        }
        .theme-theater::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, var(--theme-theater-start), var(--theme-theater-end));
            z-index: 10;
        }
        .theme-conference::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, var(--theme-conference-start), var(--theme-conference-end));
            z-index: 10;
        }
        .theme-default::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px;
            background: var(--theme-gradient);
            z-index: 10;
        }

        /* Physical Ticket Card Design */
        .ticket-card {
            background: var(--surface-color);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            color: var(--text-primary);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            display: flex;
            flex-direction: column;
            /* Using mask to create smooth cutouts */
            -webkit-mask-image: radial-gradient(circle at -5px 65%, transparent 15px, black 16px), radial-gradient(circle at calc(100% + 5px) 65%, transparent 15px, black 16px);
            -webkit-mask-composite: source-in, source-in;
            -webkit-mask-position: top left, top left;
            mask-image: radial-gradient(circle at -5px 65%, transparent 15px, black 16px), radial-gradient(circle at calc(100% + 5px) 65%, transparent 15px, black 16px);
            mask-composite: intersect;
        }

        .ticket-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--card-shadow);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* The dashed perforated line */
        .ticket-card .perforated-line {
            position: absolute;
            top: 65%;
            left: 15px;
            right: 15px;
            border-top: 2px dashed rgba(160, 165, 181, 0.4);
            z-index: 1;
        }

        /* Buttons */
        .btn-modern {
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: none;
        }
        
        .btn-gradient {
            background: var(--theme-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(247, 37, 133, 0.3);
        }
        .btn-gradient:hover {
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.5);
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-outline-glass {
            background: transparent;
            border: 2px solid var(--glass-border);
            color: var(--text-primary);
            backdrop-filter: blur(4px);
        }
        .btn-outline-glass:hover {
            background: var(--glass-border);
            border-color: var(--theme-primary);
            color: var(--theme-primary);
        }

        /* Badges */
        .badge-glass {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(4px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            font-weight: 600;
            padding: 0.5em 1em;
            border-radius: 30px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        [data-theme="light"] .badge-glass {
            background: rgba(0,0,0,0.5);
            color: white;
        }

        main {
            flex: 1;
        }

        footer {
            background: var(--navbar-bg) !important;
            border-top: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
        }

        /* Dropdown overrides */
        .dropdown-menu.glass-card {
            background: var(--dropdown-bg) !important;
        }
        .dropdown-item.text-white {
            color: var(--text-primary) !important;
        }
        .dropdown-item:hover {
            background: var(--glass-border);
        }
        
        /* Theme Toggle Button */
        #theme-toggle {
            background: transparent;
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        #theme-toggle:hover {
            background: var(--glass-border);
            transform: rotate(15deg);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-ticket-detailed me-2"></i>VenueTickets
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="color: var(--text-primary);">
                <i class="bi bi-list fs-2"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Explore</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <button id="theme-toggle" aria-label="Toggle Theme">
                            <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
                        </button>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-modern btn-gradient" href="{{ route('register') }}">Sign Up</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                                <div style="width:35px; height:35px; border-radius:50%; background:var(--theme-gradient); display:flex; align-items:center; justify-content:center; font-weight:bold; color:white;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end glass-card p-2">
                                <li><a class="dropdown-item text-white" href="{{ route('my_schedule') }}"><i class="bi bi-calendar-event me-2"></i>My Schedule</a></li>
                                <li><a class="dropdown-item text-white" href="{{ route('my_tickets') }}"><i class="bi bi-ticket-perforated me-2"></i>My Tickets</a></li>
                                <li><a class="dropdown-item text-white" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                                @if(auth()->user()->hasRole(['super-admin', 'admin']))
                                    <li><a class="dropdown-item text-white" href="{{ route('admin_dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                                @endif
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Added padding top to account for fixed navbar -->
    <main style="padding-top: 80px;">
        @yield('content')
    </main>
    
    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 text-secondary">&copy; {{ date('Y') }} VenueTickets. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Theme Toggle Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            
            // Set initial icon based on data-theme attribute (which was set in the <head>)
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if(currentTheme === 'light') {
                themeIcon.classList.remove('bi-moon-stars-fill');
                themeIcon.classList.add('bi-sun-fill', 'text-warning');
            }

            themeToggle.addEventListener('click', () => {
                let theme = document.documentElement.getAttribute('data-theme');
                if (theme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.remove('bi-moon-stars-fill');
                    themeIcon.classList.add('bi-sun-fill', 'text-warning');
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.remove('bi-sun-fill', 'text-warning');
                    themeIcon.classList.add('bi-moon-stars-fill');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>