// resources/js/admin.js
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }
    
    if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-show');
        });
    }
    
    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }
    
    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Save to session via AJAX
            fetch('{{ route("admin_theme_toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ theme: newTheme })
            });
        });
    }
    
    // Global Search
    const globalSearch = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;
    
    if (globalSearch) {
        globalSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route("admin_search") }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        
                        if (data.events && data.events.length > 0) {
                            html += '<div class="search-result-category">Events</div>';
                            data.events.forEach(event => {
                                html += `<div class="search-result-item" data-url="/admin/events/${event.id}/edit">
                                    <i class="bi bi-calendar-event"></i> ${event.name}
                                </div>`;
                            });
                        }
                        
                        if (data.users && data.users.length > 0) {
                            html += '<div class="search-result-category">Users</div>';
                            data.users.forEach(user => {
                                html += `<div class="search-result-item" data-url="/admin/users/${user.id}">
                                    <i class="bi bi-person"></i> ${user.name} (${user.email})
                                </div>`;
                            });
                        }
                        
                        if (data.orders && data.orders.length > 0) {
                            html += '<div class="search-result-category">Orders</div>';
                            data.orders.forEach(order => {
                                html += `<div class="search-result-item" data-url="/admin/orders/${order.id}">
                                    <i class="bi bi-cart"></i> #${order.order_number}
                                </div>`;
                            });
                        }
                        
                        if (html === '') {
                            html = '<div class="p-3 text-muted">No results found</div>';
                        }
                        
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                        
                        // Add click handlers
                        searchResults.querySelectorAll('.search-result-item').forEach(item => {
                            item.addEventListener('click', function() {
                                window.location.href = this.dataset.url;
                            });
                        });
                    });
            }, 300);
        });
        
        // Keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                globalSearch.focus();
            }
        });
        
        // Close search on click outside
        document.addEventListener('click', function(e) {
            if (!globalSearch.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
    
    // Close mobile sidebar on click outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991 && 
            !sidebar.contains(e.target) && 
            !mobileSidebarToggle.contains(e.target) &&
            sidebar.classList.contains('mobile-show')) {
            sidebar.classList.remove('mobile-show');
        }
    });
});