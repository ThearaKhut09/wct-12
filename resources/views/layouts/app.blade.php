<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-smooth Online - Premium Shopping Experience')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
      <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- JavaScript Functions -->
    <script>        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            const currentTheme = html.getAttribute('data-theme');
            
            if (currentTheme === 'dark') {
                html.removeAttribute('data-theme');
                if (themeIcon) themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                if (themeIcon) themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }
        }        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#3b82f6'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                max-width: 300px;
                word-wrap: break-word;
                font-family: inherit;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Update authentication UI
        function updateAuthUI() {
            const token = localStorage.getItem('auth_token');
            const userData = localStorage.getItem('user_data');
            const authSection = document.getElementById('authSection');
            
            if (token && userData) {
                const user = JSON.parse(userData);
                authSection.innerHTML = `
                    <div class="user-menu" style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: var(--text-primary);">Welcome, ${user.name}</span>
                        <a href="{{ route('profile') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Profile</a>
                        <button onclick="logout()" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Logout</button>
                    </div>
                `;
            } else {
                authSection.innerHTML = `
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                `;
            }
        }

        // Logout function
        function logout() {
            const token = localStorage.getItem('auth_token');
            
            if (token) {
                // Call logout API
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    // Clear local storage regardless of API response
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user_data');
                    updateAuthUI();
                    showNotification('Logged out successfully!', 'success');
                }).catch(() => {
                    // Clear local storage even if API fails
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user_data');
                    updateAuthUI();
                    showNotification('Logged out!', 'info');
                });
            }
        }        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('themeIcon');
            
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                if (themeIcon) themeIcon.className = 'fas fa-sun';
            } else {
                document.documentElement.removeAttribute('data-theme');
                if (themeIcon) themeIcon.className = 'fas fa-moon';
            }
            
            // Initialize authentication UI
            updateAuthUI();
        });

        // Add notification animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        [data-theme="dark"] {
            --primary-color: #3b82f6;
            --secondary-color: #94a3b8;
            --dark-color: #0f172a;
            --light-color: #1e293b;
            --border-color: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Header Styles */
        .header {
            background: white;
            box-shadow: var(--box-shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        [data-theme="dark"] .header {
            background: var(--dark-color);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary-color);
            background-color: rgba(37, 99, 235, 0.1);
        }        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .cart-icon {
            position: relative;
            color: var(--text-primary);
            font-size: 1.25rem;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: var(--transition);
        }

        .cart-icon:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .cart-count {
            position: absolute;
            top: -0.25rem;
            right: -0.25rem;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Footer */
        .footer {
            background: var(--dark-color);
            color: var(--light-color);
            padding: 3rem 2rem 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-section ul li a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            border-top: 1px solid var(--border-color);
            padding-top: 2rem;
            text-align: center;
            color: var(--secondary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                gap: 1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: var(--box-shadow);
                padding: 1rem;
                flex-direction: column;
            }

            [data-theme="dark"] .nav-links {
                background: var(--dark-color);
            }

            .nav-links.active {
                display: flex;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <a href="{{ route('home') }}" class="logo">E-smooth Online</a>
            
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
              <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">Products</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                <li><a href="{{ route('wishlist') }}" class="{{ request()->routeIs('wishlist') ? 'active' : '' }}">
                    <i class="fas fa-heart"></i> Wishlist
                </a></li>            </ul>            <div class="nav-actions">
                <button class="theme-toggle" id="themeToggleBtn" onclick="toggleTheme()" title="Toggle Dark Mode">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                <a href="{{ route('cart') }}" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <div id="authSection">
                    <a href="{{ route('login') }}" class="btn btn-primary" id="loginBtn">Login</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>E-smooth Online</h3>
                <p>Your premier destination for high-quality products at unbeatable prices. Shop with confidence and style.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('products') }}">Products</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Categories</h3>
                <ul>
                    <li><a href="#">Electronics</a></li>
                    <li><a href="#">Mobile Phones</a></li>
                    <li><a href="#">Clothing</a></li>
                    <li><a href="#">Shoes</a></li>
                    <li><a href="#">Accessories</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-envelope"></i> info@esmooth.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Commerce St, NY 10001</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 E-smooth Online. All rights reserved. | Designed with ❤️ for amazing shopping experience</p>
        </div>
    </footer>    <!-- JavaScript -->
    <script>
        console.log('Script is loading...');
        
        // Test function to verify JavaScript is working
        function testClick() {
            console.log('TEST Button clicked! JavaScript is working.');
            alert('JavaScript is working!');
        }
        
        console.log('testClick function defined:', typeof testClick);
        
        // Make functions globally accessible
        window.testClick = testClick;

        // Theme Toggle
        function toggleTheme() {
            console.log('toggleTheme() function called');
            
            try {
                const body = document.documentElement;
                const themeIcon = document.getElementById('themeIcon');
                const currentTheme = body.getAttribute('data-theme');
                
                console.log('Current theme before toggle:', currentTheme);
                console.log('Theme icon element:', themeIcon);
                
                if (currentTheme === 'dark') {
                    body.removeAttribute('data-theme');
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-moon';
                        console.log('Switched to light mode, icon changed to moon');
                    }
                    localStorage.setItem('theme', 'light');
                    console.log('Theme stored as light in localStorage');
                } else {
                    body.setAttribute('data-theme', 'dark');
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-sun';
                        console.log('Switched to dark mode, icon changed to sun');
                    }
                    localStorage.setItem('theme', 'dark');
                    console.log('Theme stored as dark in localStorage');
                }
                
                // Verify the change                console.log('New theme after toggle:', document.documentElement.getAttribute('data-theme') || 'light');
                
            } catch (error) {
                console.error('Error in toggleTheme():', error);
            }
        }
        
        // Make toggleTheme globally accessible
        window.toggleTheme = toggleTheme;// Load saved theme
        function loadTheme() {
            console.log('loadTheme() function called');
            
            try {
                const savedTheme = localStorage.getItem('theme');
                const themeIcon = document.getElementById('themeIcon');
                
                console.log('Saved theme from localStorage:', savedTheme);
                console.log('Theme icon element found:', !!themeIcon);
                
                if (savedTheme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-sun';
                        console.log('Applied dark theme, icon set to sun');
                    }
                } else {
                    document.documentElement.removeAttribute('data-theme');
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-moon';
                        console.log('Applied light theme, icon set to moon');
                    }
                }
                
                console.log('Theme loaded successfully. Current theme:', document.documentElement.getAttribute('data-theme') || 'light');
                
            } catch (error) {
                console.error('Error in loadTheme():', error);
            }
        }

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Cart Management
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Listen for cart updates from other tabs/windows
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                cart = JSON.parse(e.newValue) || [];
                updateCartCount();
                
                // If we're on the cart page, refresh the display
                if (window.location.pathname === '/cart' && typeof renderCart === 'function') {
                    renderCart();
                }
            }
        });

        function updateCartCount() {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = totalItems;
                
                // Update cart badge visibility
                if (totalItems > 0) {
                    cartCount.style.display = 'flex';
                } else {
                    cartCount.style.display = 'none';
                }
            }
        }

        function addToCart(productId, name, price, image) {
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: productId,
                    name: name,
                    price: price,
                    image: image,
                    quantity: 1
                });
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            
            // Show notification
            showNotification('Product added to cart!', 'success');
        }

        // Authentication Management        function updateAuthUI() {
            const authSection = document.getElementById('authSection');
            if (!authSection) return;
            
            const userData = localStorage.getItem('user_data');
            const token = localStorage.getItem('auth_token');
            
            if (userData && token) {
                try {
                    const user = JSON.parse(userData);
                    authSection.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span style="color: var(--text-primary);">Welcome, ${user.name.split(' ')[0]}!</span>
                            <a href="{{ route('profile') }}" class="btn btn-outline" title="My Profile">
                                <i class="fas fa-user"></i>
                            </a>
                            <button class="btn btn-secondary" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    `;
                } catch (e) {
                    console.error('Error parsing user data:', e);
                    authSection.innerHTML = `
                        <a href="{{ route('login') }}" class="btn btn-primary" id="loginBtn">Login</a>
                    `;
                }
            } else {
                authSection.innerHTML = `
                    <a href="{{ route('login') }}" class="btn btn-primary" id="loginBtn">Login</a>
                `;
            }
        }        async function logout() {
            try {
                const token = localStorage.getItem('auth_token');
                if (token) {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });
                }
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_data');
                updateAuthUI();
                showNotification('Logged out successfully!', 'success');
                
                // Redirect to home if on admin page
                if (window.location.pathname.includes('/admin')) {
                    window.location.href = '/';
                }
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success-color)' : type === 'error' ? 'var(--danger-color)' : type === 'warning' ? 'var(--warning-color)' : 'var(--primary-color)'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 0.5rem;
                box-shadow: var(--box-shadow);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                max-width: 300px;
                word-wrap: break-word;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Add slide-in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            updateCartCount();
            updateAuthUI();
            
            // Also check authentication status on page load
            checkAuthenticationStatus();
        });

        // Check authentication status
        async function checkAuthenticationStatus() {
            const token = localStorage.getItem('auth_token');
            const userData = localStorage.getItem('user_data');
            
            if (token && userData) {
                try {
                    // Verify token is still valid
                    const response = await fetch('/api/profile', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        // Token is invalid, clear localStorage
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user_data');
                        updateAuthUI();
                    }
                } catch (error) {
                    console.error('Auth check error:', error);
                    // On network error, keep the stored auth data
                }
            }        }

        // Add click event listener as backup
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up theme functionality...');
            
            // Load theme first
            loadTheme();
            
            // Add event listener to theme toggle button
            const themeToggle = document.getElementById('themeToggleBtn');
            if (themeToggle) {
                console.log('Theme toggle button found, adding event listener');
                themeToggle.addEventListener('click', function() {
                    console.log('Theme toggle clicked via event listener');
                    toggleTheme();
                });
            } else {
                console.log('Theme toggle button NOT found');
            }
              // Test CSS variables
            const computedStyle = getComputedStyle(document.documentElement);
            console.log('CSS Variables test:');
            console.log('--primary-color:', computedStyle.getPropertyValue('--primary-color'));
            console.log('--text-primary:', computedStyle.getPropertyValue('--text-primary'));
            console.log('--light-color:', computedStyle.getPropertyValue('--light-color'));
              console.log('Theme initialization complete.');
            
            // Test function availability
            console.log('Functions available:');
            console.log('testClick:', typeof window.testClick);
            console.log('toggleTheme:', typeof window.toggleTheme);
        });</script>
    
    <!-- External JS files temporarily commented out for debugging -->
    <!-- <script src="{{ asset('js/api-client.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script> -->

    @stack('scripts')
</body>
</html>
