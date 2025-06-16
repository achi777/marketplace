<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Marketplace'))</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f8fafc;
                padding-top: 76px;
            }

            /* Top Navigation Bar */
            .top-navbar {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1030;
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
                color: white !important;
            }

            .navbar-nav .nav-link {
                color: rgba(255, 255, 255, 0.9) !important;
                font-weight: 500;
                transition: all 0.3s ease;
                position: relative;
                margin: 0 0.5rem;
            }

            .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
                color: white !important;
                transform: translateY(-2px);
            }

            .navbar-nav .nav-link::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                width: 0;
                height: 2px;
                background: white;
                transition: all 0.3s ease;
                transform: translateX(-50%);
            }

            .navbar-nav .nav-link:hover::after,
            .navbar-nav .nav-link.active::after {
                width: 100%;
            }

            /* Dropdown Menus */
            .dropdown-menu {
                border: none;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                border-radius: 12px;
                padding: 1rem;
                min-width: 280px;
                z-index: 1040;
            }

            .dropdown-item {
                padding: 0.75rem 1rem;
                border-radius: 8px;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .dropdown-item:hover {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                transform: translateX(5px);
            }

            /* Multilevel dropdown */
            .dropdown-submenu {
                position: relative;
            }

            .dropdown-submenu > .dropdown-menu {
                top: 0;
                left: 100%;
                margin-top: -1px;
                margin-left: -1px;
                border-radius: 8px;
                padding: 0.5rem;
                min-width: 200px;
            }

            .dropdown-submenu:hover > .dropdown-menu {
                display: block;
            }

            .dropdown-submenu .dropdown-item {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            @media (max-width: 768px) {
                .dropdown-submenu > .dropdown-menu {
                    position: static !important;
                    float: none;
                    width: auto;
                    margin-top: 0;
                    background-color: rgba(0,0,0,.125);
                    border: 0;
                    box-shadow: none;
                    border-radius: 0;
                    margin-left: 1rem;
                }
            }

            /* Search Bar */
            .search-container {
                position: relative;
            }

            .search-input {
                background: rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: white;
                border-radius: 25px;
                padding: 0.5rem 1rem 0.5rem 2.5rem;
                transition: all 0.3s ease;
                width: 300px;
            }

            .search-input::placeholder {
                color: rgba(255, 255, 255, 0.7);
            }

            .search-input:focus {
                background: white;
                color: #333;
                box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
                border-color: white;
            }

            .search-icon {
                position: absolute;
                left: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                color: rgba(255, 255, 255, 0.7);
            }

            /* Cart Badge */
            .cart-icon {
                position: relative;
            }

            .cart-badge {
                position: absolute;
                top: -8px;
                right: -8px;
                background: #ef4444;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                font-size: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }

            /* Footer */
            .footer {
                background: #1f2937;
                color: white;
                padding: 3rem 0 1rem;
                margin-top: 3rem;
            }

            .footer h6 {
                color: #f3f4f6;
                font-weight: 600;
                margin-bottom: 1rem;
            }

            .footer a {
                color: #9ca3af;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .footer a:hover {
                color: white;
            }

            .footer-social a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                margin-right: 0.5rem;
                transition: all 0.3s ease;
            }

            .footer-social a:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-2px);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .search-input {
                    width: 200px;
                }
                
                body {
                    padding-top: 56px;
                }
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="d-flex flex-column min-vh-100">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <i class="bi bi-shop me-2"></i>Marketplace
                </a>
                
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                                <i class="bi bi-house me-1"></i>Home
                            </a>
                        </li>
                        
                        <!-- Categories Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('categories*') || request()->is('category*') ? 'active' : '' }}" 
                               href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-grid me-1"></i>Categories
                            </a>
                            <ul class="dropdown-menu">
                                @php
                                    try {
                                        $navCategories = DB::table('categories')
                                            ->select(['id', 'name', 'slug', 'parent_id'])
                                            ->where('is_active', true)
                                            ->orderBy('sort_order')
                                            ->orderBy('name')
                                            ->get();
                                        
                                        $navCategoryTree = [];
                                        foreach ($navCategories as $category) {
                                            if ($category->parent_id === null) {
                                                $navCategoryTree[$category->id] = [
                                                    'id' => $category->id,
                                                    'name' => $category->name,
                                                    'slug' => $category->slug,
                                                    'children' => []
                                                ];
                                            }
                                        }
                                        
                                        foreach ($navCategories as $category) {
                                            if ($category->parent_id !== null && isset($navCategoryTree[$category->parent_id])) {
                                                $navCategoryTree[$category->parent_id]['children'][] = [
                                                    'id' => $category->id,
                                                    'name' => $category->name,
                                                    'slug' => $category->slug
                                                ];
                                            }
                                        }
                                    } catch (Exception $e) {
                                        $navCategoryTree = [];
                                    }
                                @endphp
                                
                                @foreach($navCategoryTree as $parentCategory)
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item d-flex justify-content-between align-items-center" href="/category/{{ $parentCategory['slug'] }}">
                                            <span><i class="bi bi-folder me-2"></i>{{ $parentCategory['name'] }}</span>
                                            @if(count($parentCategory['children']) > 0)
                                                <i class="bi bi-chevron-right"></i>
                                            @endif
                                        </a>
                                        @if(count($parentCategory['children']) > 0)
                                            <ul class="dropdown-menu">
                                                @foreach($parentCategory['children'] as $child)
                                                    <li>
                                                        <a class="dropdown-item" href="/category/{{ $child['slug'] }}">
                                                            <i class="bi bi-tag me-2"></i>{{ $child['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/categories">
                                        <i class="bi bi-list me-2"></i>View All Categories
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="bi bi-box me-1"></i>Products
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('sellers*') ? 'active' : '' }}" href="{{ route('sellers.index') }}">
                                <i class="bi bi-people me-1"></i>Sellers
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Search Bar -->
                    <div class="search-container me-3 d-none d-lg-block">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control search-input" placeholder="Search products..." id="searchInput">
                    </div>
                    
                    <!-- Right Side Menu -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link position-relative cart-icon {{ request()->is('cart*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3"></i>
                                <span class="cart-badge" id="cart-count">0</span>
                            </a>
                        </li>
                        
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" onclick="return false;">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu">
                                    <li><a class="dropdown-item" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                    @if(Auth::user()->role === 'seller')
                                        <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}"><i class="bi bi-shop me-2"></i>Seller Panel</a></li>
                                    @elseif(Auth::user()->role === 'admin')
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear me-2"></i>Admin Panel</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-2"></i>My Orders</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="guestDropdown" role="button" onclick="return false;">
                                    <i class="bi bi-person-circle me-1"></i>Account
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" id="guestDropdownMenu">
                                    <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                                    <li><a class="dropdown-item" href="{{ route('register') }}"><i class="bi bi-person-plus me-2"></i>Register</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/seller/register"><i class="bi bi-shop me-2"></i>Become a Seller</a></li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="min-vh-100">
            @isset($header)
                <div class="bg-light py-3">
                    <div class="container">
                        {{ $header }}
                    </div>
                </div>
            @endisset
            
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer mt-5 py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <h6>Get to Know Us</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-light text-decoration-none">About Us</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Careers</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Press Releases</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6>Make Money with Us</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('register') }}" class="text-light text-decoration-none">Sell on Marketplace</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Become an Affiliate</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Advertise Your Products</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6>Payment Products</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-light text-decoration-none">Business Card</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Shop with Points</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Reload Your Balance</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h6>Let Us Help You</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-light text-decoration-none">Your Account</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Your Orders</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Returns & Replacements</a></li>
                            <li><a href="#" class="text-light text-decoration-none">Help</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="border-light">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} Marketplace. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            // Load cart count on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateCartCount();
                
                // Simple dropdown functionality
                const userDropdown = document.getElementById('userDropdown');
                const userDropdownMenu = document.getElementById('userDropdownMenu');
                const guestDropdown = document.getElementById('guestDropdown');
                const guestDropdownMenu = document.getElementById('guestDropdownMenu');
                
                // User dropdown click handler
                if (userDropdown && userDropdownMenu) {
                    userDropdown.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('User dropdown clicked');
                        
                        // Close guest dropdown if open
                        if (guestDropdownMenu) {
                            guestDropdownMenu.classList.remove('show');
                        }
                        
                        // Toggle user dropdown
                        userDropdownMenu.classList.toggle('show');
                        console.log('User dropdown is now:', userDropdownMenu.classList.contains('show') ? 'open' : 'closed');
                    });
                }
                
                // Guest dropdown click handler
                if (guestDropdown && guestDropdownMenu) {
                    guestDropdown.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Guest dropdown clicked');
                        
                        // Close user dropdown if open
                        if (userDropdownMenu) {
                            userDropdownMenu.classList.remove('show');
                        }
                        
                        // Toggle guest dropdown
                        guestDropdownMenu.classList.toggle('show');
                        console.log('Guest dropdown is now:', guestDropdownMenu.classList.contains('show') ? 'open' : 'closed');
                    });
                }
                
                // Close dropdowns when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown')) {
                        if (userDropdownMenu) userDropdownMenu.classList.remove('show');
                        if (guestDropdownMenu) guestDropdownMenu.classList.remove('show');
                    }
                });
            });

            function updateCartCount() {
                fetch('{{ route('cart.count') }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('cart-count').textContent = data.count;
                    })
                    .catch(error => console.log('Error loading cart count:', error));
            }

            // Global function to add item to cart
            function addToCart(variationId, quantity = 1) {
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_variation_id: variationId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount();
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error adding item to cart', 'error');
                });
            }

            function showNotification(message, type) {
                // Simple notification system
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 5000);
            }
        </script>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Dropdown functionality -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle dropdown submenu functionality
                const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
                
                dropdownSubmenus.forEach(function(submenu) {
                    let timeoutId;
                    
                    submenu.addEventListener('mouseenter', function() {
                        clearTimeout(timeoutId);
                        const submenuDropdown = this.querySelector('.dropdown-menu');
                        if (submenuDropdown) {
                            submenuDropdown.classList.add('show');
                        }
                    });
                    
                    submenu.addEventListener('mouseleave', function() {
                        const submenuDropdown = this.querySelector('.dropdown-menu');
                        if (submenuDropdown) {
                            timeoutId = setTimeout(() => {
                                submenuDropdown.classList.remove('show');
                            }, 100);
                        }
                    });
                    
                    // Handle clicks on submenu items for mobile
                    const submenuToggle = submenu.querySelector('a.dropdown-item');
                    if (submenuToggle) {
                        submenuToggle.addEventListener('click', function(e) {
                            if (window.innerWidth <= 768) {
                                const submenuDropdown = submenu.querySelector('.dropdown-menu');
                                if (submenuDropdown && submenu.querySelector('.dropdown-menu .dropdown-item')) {
                                    e.preventDefault();
                                    submenuDropdown.classList.toggle('show');
                                }
                            }
                        });
                    }
                });
                
                // Initialize Bootstrap dropdowns
                console.log('Initializing dropdowns...');
                
                // Test if Bootstrap is loaded
                if (typeof bootstrap !== 'undefined') {
                    console.log('Bootstrap JS is loaded');
                    console.log('Bootstrap version:', bootstrap);
                } else {
                    console.error('Bootstrap JS is not loaded');
                    console.log('Available window properties:', Object.keys(window));
                }
                
                // Initialize both guest and user dropdowns
                const initializeDropdown = (dropdownId) => {
                    const dropdown = document.getElementById(dropdownId);
                    if (dropdown) {
                        console.log(dropdownId + ' dropdown found');
                        
                        // Check if Bootstrap dropdown is initialized
                        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                            console.log('Bootstrap Dropdown class available for ' + dropdownId);
                            
                            // Try to initialize Bootstrap dropdown manually
                            try {
                                const dropdownToggle = new bootstrap.Dropdown(dropdown);
                                console.log('Bootstrap dropdown initialized successfully for ' + dropdownId);
                            } catch (error) {
                                console.error('Error initializing Bootstrap dropdown for ' + dropdownId + ':', error);
                            }
                        } else {
                            console.log('Bootstrap Dropdown class not available - using manual toggle for ' + dropdownId);
                            dropdown.addEventListener('click', function(e) {
                                console.log(dropdownId + ' dropdown clicked - manual toggle');
                                e.preventDefault();
                                
                                // Toggle dropdown manually
                                const dropdownMenu = this.nextElementSibling;
                                if (dropdownMenu) {
                                    dropdownMenu.classList.toggle('show');
                                }
                            });
                        }
                    }
                };
                
                // Initialize both dropdowns
                initializeDropdown('guestDropdown');
                initializeDropdown('userDropdown');
                
                // Close dropdowns when clicking outside (but don't interfere with Bootstrap)
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown')) {
                        // Only close custom dropdowns, not Bootstrap dropdowns
                        document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(dropdown) {
                            dropdown.classList.remove('show');
                        });
                    }
                });
                
                // Search functionality
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            const query = this.value.trim();
                            if (query) {
                                window.location.href = `/search?q=${encodeURIComponent(query)}`;
                            }
                        }
                    });
                }
                
                // Categories dropdown special behavior
                const categoriesDropdown = document.getElementById('categoriesDropdown');
                if (categoriesDropdown) {
                    categoriesDropdown.addEventListener('click', function(e) {
                        // On mobile or when dropdown is already open, prevent navigation and toggle dropdown
                        if (window.innerWidth <= 768 || this.getAttribute('aria-expanded') === 'true') {
                            e.preventDefault();
                            return;
                        }
                        // On desktop with dropdown closed, allow navigation to categories page
                        window.location.href = '/categories';
                    });
                }
            });
        </script>
        
        @stack('scripts')
    </body>
</html>
