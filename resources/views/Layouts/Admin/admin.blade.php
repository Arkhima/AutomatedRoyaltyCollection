<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | ARC Admin</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Admin Styles -->
    @stack('styles')
    
    <style>
        /* ===== DESIGN TOKENS - EXACT MATCH FROM REFERENCE ===== */
        :root {
            /* Colors */
            --primary-dark: #1c2541;
            --primary-medium: #3a506b;
            --primary-teal: #5bc0be;
            --accent-gold: #f2c94c;
            --bg-light: #f8f9fa;
            --bg-white: #ffffff;
            --text-dark: #333333;
            --text-navy: #1c2541;
            --danger: #dc3545;
            --success: #28a745;
            --warning: #ffc107;
            --info: #17a2b8;
            
            /* Gradients */
            --primary-gradient: linear-gradient(135deg, #1c2541 0%, #3a506b 50%, #5bc0be 100%);
            
            /* Spacing */
            --space-1: 0.25rem;
            --space-2: 0.5rem;
            --space-3: 0.75rem;
            --space-4: 1rem;
            --space-5: 1.5rem;
            --space-6: 2rem;
            --space-8: 3rem;
            
            /* Shadows */
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
            
            /* Border radii */
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 12px;
            
            /* Layout */
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
        }

        /* ===== BASE STYLES ===== */
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Playfair Display', serif;
            color: var(--text-navy);
        }

        /* ===== LAYOUT ===== */
        .admin-wrapper {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
            transition: grid-template-columns 0.3s ease;
        }

        .admin-wrapper.collapsed {
            grid-template-columns: var(--sidebar-collapsed) 1fr;
        }

        /* ===== SIDEBAR - EXACT MATCH FROM REFERENCE ===== */
        .sidebar {
            background: var(--primary-gradient);
            color: white;
            padding: var(--space-6) var(--space-4);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            z-index: 10;
            height: 100vh;
            position: sticky;
            top: 0;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: rgba(255,255,255,0.1);
        }

        .admin-wrapper.collapsed .sidebar {
            width: var(--sidebar-collapsed);
            padding: var(--space-6) var(--space-2);
        }

        .sidebar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: var(--space-6);
            display: flex;
            align-items: center;
            gap: var(--space-3);
            white-space: nowrap;
            color: white;
        }

        .sidebar-brand i {
            color: var(--accent-gold);
            font-size: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        .admin-wrapper.collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin-bottom: var(--space-3);
        }

        .sidebar-nav a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--space-4);
            padding: var(--space-3) var(--space-4);
            border-radius: var(--radius-md);
            white-space: nowrap;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 600;
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--accent-gold);
        }

        .sidebar-nav i {
            min-width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }

        .admin-wrapper.collapsed .sidebar-nav span {
            display: none;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: var(--space-4);
            font-size: 0.875rem;
            text-align: center;
        }

        /* ===== HEADER ===== */
        .admin-header {
            background: var(--bg-white);
            padding: var(--space-4) var(--space-6);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: var(--space-4);
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--text-navy);
            cursor: pointer;
            transition: all 0.3s ease;
            padding: var(--space-2);
            line-height: 1;
        }

        .toggle-btn:hover {
            color: var(--accent-gold);
            transform: rotate(90deg);
        }

        .header-title h1 {
            font-size: 1.5rem;
            margin: 0;
            line-height: 1.2;
        }

        .header-title p {
            margin: 0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: var(--space-4);
        }

        /* Search */
        .search-wrapper {
            position: relative;
            display: none;
        }

        @media (min-width: 768px) {
            .search-wrapper {
                display: block;
            }
        }

        .search-wrapper i {
            position: absolute;
            left: var(--space-3);
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 0.9rem;
        }

        .search-input {
            padding: var(--space-2) var(--space-2) var(--space-2) 2.2rem;
            border: 1px solid #e9ecef;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            width: 220px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px rgba(242, 201, 76, 0.1);
            width: 260px;
        }

        /* Notifications */
        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-navy);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            color: var(--accent-gold);
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.1rem 0.4rem;
            border-radius: 999px;
            min-width: 18px;
            text-align: center;
        }

        /* User menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            cursor: pointer;
            padding: var(--space-2);
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: rgba(0,0,0,0.02);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-gold);
            transition: all 0.3s ease;
        }

        .user-menu:hover .user-avatar {
            transform: scale(1.05);
            box-shadow: 0 0 0 3px rgba(242, 201, 76, 0.3);
        }

        .user-info {
            display: none;
        }

        @media (min-width: 640px) {
            .user-info {
                display: block;
            }
        }

        .user-name {
            font-weight: 600;
            color: var(--text-navy);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.7rem;
            background: rgba(242, 201, 76, 0.15);
            color: #b48a00;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            display: inline-block;
        }

        /* Logout button */
        .logout-btn {
            background: none;
            border: none;
            color: var(--text-navy);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: var(--space-2);
        }

        .logout-btn:hover {
            color: var(--accent-gold);
            transform: scale(1.1);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            padding: var(--space-6);
            background: var(--bg-light);
            min-height: calc(100vh - 140px);
        }

        /* ===== FOOTER ===== */
        .admin-footer {
            background: var(--bg-white);
            padding: var(--space-4) var(--space-6);
            text-align: center;
            color: var(--text-navy);
            font-size: 0.875rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }

        .admin-footer a {
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 500;
        }

        /* ===== CARD COMPONENT ===== */
        .admin-card {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            padding: var(--space-5);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .admin-card.accent::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-gold);
            transition: width 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .admin-card:hover.accent::before {
            width: 8px;
        }

        /* ===== TABLE COMPONENT ===== */
        .table-container {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .admin-table th {
            text-align: left;
            padding: var(--space-4) var(--space-5);
            background: #f8fafc;
            color: var(--text-navy);
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
        }

        .admin-table td {
            padding: var(--space-4) var(--space-5);
            border-bottom: 1px solid #e9ecef;
        }

        .admin-table tbody tr:hover {
            background: #f8fafc;
        }

        /* ===== BADGES ===== */
        .admin-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .admin-badge.active, .admin-badge.admin {
            background: #d4edda;
            color: #155724;
        }

        .admin-badge.pending, .admin-badge.user {
            background: #fff3cd;
            color: #856404;
        }

        .admin-badge.inactive, .admin-badge.artist {
            background: #e2e3e5;
            color: #383d41;
        }

        .admin-badge.super-admin {
            background: #cce5ff;
            color: #004085;
        }

        /* ===== BUTTONS ===== */
        .admin-btn {
            padding: var(--space-2) var(--space-4);
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            text-decoration: none;
        }

        .admin-btn-primary {
            background: var(--accent-gold);
            color: var(--text-navy);
        }

        .admin-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: #e5b93b;
        }

        .admin-btn-secondary {
            background: var(--primary-dark);
            color: white;
        }

        .admin-btn-secondary:hover {
            background: var(--primary-medium);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .admin-btn-danger {
            background: var(--danger);
            color: white;
        }

        .admin-btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .admin-btn-sm {
            padding: var(--space-1) var(--space-3);
            font-size: 0.85rem;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: var(--space-2);
            margin-top: var(--space-6);
        }

        .pagination-item {
            padding: var(--space-2) var(--space-4);
            border: 1px solid #dee2e6;
            border-radius: var(--radius-sm);
            color: var(--text-navy);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-item:hover:not(.disabled):not(.active) {
            background: var(--accent-gold);
            border-color: var(--accent-gold);
            color: var(--text-navy);
        }

        .pagination-item.active {
            background: var(--primary-dark);
            color: white;
            border-color: var(--primary-dark);
        }

        .pagination-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 767px) {
            .admin-wrapper {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 85%;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            
            .admin-wrapper.sidebar-open .sidebar {
                left: 0;
            }
            
            .header-title h1 {
                font-size: 1.2rem;
            }
            
            .main-content {
                padding: var(--space-4);
            }
        }

        @media (max-width: 480px) {
            .user-info {
                display: none;
            }
            
            .header-right {
                gap: var(--space-2);
            }
        }

        /* ===== LOADER ===== */
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(28, 37, 65, 0.98);
            transition: opacity 0.5s ease;
            pointer-events: none;
        }

        #page-loader.fade-out {
            opacity: 0;
        }

        .loader-spinner {
            width: 4rem;
            height: 4rem;
            border: 6px solid var(--accent-gold);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: relative;
        }

        .loader-spinner::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1rem;
            height: 1rem;
            background: black;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            font-style: italic;
            font-weight: 600;
            color: var(--accent-gold);
            font-family: 'Playfair Display', serif;
            margin-top: var(--space-2);
        }

        .marquee-content {
            display: inline-block;
            padding-left: 100%;
            animation: creative-marquee 4s linear infinite;
        }

        @keyframes creative-marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
    </style>
</head>
<body>

    <!-- Page Loader -->
    <div id="page-loader">
        <div class="loader-spinner"></div>
        <p class="mt-4 text-yellow-400 font-semibold tracking-wide text-lg select-none brand-font">
            Loading Admin Panel...
        </p>
        <div class="marquee-container text-sm sm:text-base">
            <div class="marquee-content">
                🎵 Managing your music ecosystem 🎶
            </div>
        </div>
    </div>

    <div class="admin-wrapper" id="adminWrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div>
                <div class="sidebar-brand">
                    <i class="fas fa-shield-alt"></i>
                    <span>ARC Admin</span>
                </div>

                <nav class="sidebar-nav">
                    <ul>
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                               class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.users.index') }}"
                               class="{{ request()->routeIs('Admin.Users*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.artists.index') }}"
                               class="{{ request()->routeIs('Admin.Artists*') ? 'active' : '' }}">
                                <i class="fas fa-microphone-alt"></i>
                                <span>Artists</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.tracks.index') }}"
                               class="{{ request()->routeIs('Admin.Tracks*') ? 'active' : '' }}">
                                <i class="fas fa-music"></i>
                                <span>Tracks</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.payments.index') }}"
                                class="{{ request()->routeIs('Admin.Payments*') ? 'active' : '' }}">
                                    <i class="fas fa-wallet"></i>
                                <span>Payments</span>
                            </a>
                        </li>

                        @can('view reports')
                        <li>
                            <a href="{{ route('admin.reports.index') }}"
                               class="{{ request()->routeIs('Admin.Reports*') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        @endcan

                        @can('manage settings')
                        <li>
                            <a href="{{ route('admin.settings.index') }}"
                               class="{{ request()->routeIs('Admin.Settings*') ? 'active' : '' }}">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </nav>
            </div>

            <div class="sidebar-footer">
                <i class="fas fa-music floating"></i>
                <div class="mt-2">© {{ date('Y') }} ARC</div>
                <small class="opacity-75">v2.0.0</small>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col">
            <!-- HEADER -->
            <header class="admin-header">
                <div class="header-left">
                    <button onclick="toggleSidebar()" class="toggle-btn" aria-label="Toggle sidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="header-title">
                        <h1>@yield('title', 'Dashboard')</h1>
                        <p>@yield('breadcrumb', 'Admin / Dashboard')</p>
                    </div>
                </div>

                <div class="header-right">
                    <!-- Search -->
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search..." class="search-input" id="globalSearch">
                    </div>

                    <!-- Notifications -->
                    <button class="notification-btn" onclick="showNotifications()">
                        <i class="far fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>

                    <!-- User Menu -->
                    <div class="user-menu" onclick="toggleUserMenu()">
                        <img 
                            src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Admin').'&background=1c2541&color=fff' }}" 
                            alt="{{ Auth::user()->name ?? 'Admin' }}"
                            class="user-avatar"
                        >
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <span class="user-role">
                                {{ Auth::user()->is_super_admin ? 'Super Admin' : (Auth::user()->is_admin ? 'Admin' : 'Moderator') }}
                            </span>
                        </div>
                    </div>

                    <!-- Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <!-- Logout Button -->
                    <button onclick="confirmLogout(event)" class="logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="main-content">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="admin-footer">
                <div class="container">
                    <p>© {{ date('Y') }} <a href="/">ARC</a> - Automated Royalty Collection. All rights reserved. 
                        <span class="hidden sm:inline">|</span> 
                        <br class="sm:hidden"> 
                        Empowering creators since 2025
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- User Menu Dropdown (hidden by default) -->
    <div id="userDropdown" class="hidden absolute right-6 top-16 bg-white rounded-lg shadow-lg py-2 min-w-[200px] z-50">
        <a href="{{ route('Artists.Profile.show') }}" class="block px-4 py-2 hover:bg-gray-100 transition">
            <i class="fas fa-user mr-2"></i> Profile
        </a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 transition">
            <i class="fas fa-cog mr-2"></i> Settings
        </a>
        <hr class="my-2">
        <button onclick="confirmLogout(event)" class="w-full text-left px-4 py-2 hover:bg-gray-100 transition text-red-600">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const wrapper = document.getElementById('adminWrapper');
            const isMobile = window.innerWidth <= 767;
            
            if (isMobile) {
                wrapper.classList.toggle('sidebar-open');
            } else {
                wrapper.classList.toggle('collapsed');
            }
        }

        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('adminWrapper');
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.toggle-btn');
            
            if (window.innerWidth <= 767 && 
                wrapper.classList.contains('sidebar-open') && 
                !sidebar.contains(event.target) && 
                !toggleBtn.contains(event.target)) {
                wrapper.classList.remove('sidebar-open');
            }
        });

        // Logout confirmation
        function confirmLogout(event) {
            event.preventDefault();
            if (confirm('Ready to take a break?\nYou can always come back to manage your music ecosystem.')) {
                document.getElementById('logout-form').submit();
            }
        }

        // Toggle user menu dropdown
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const userMenu = document.querySelector('.user-menu');
            
            if (!dropdown.classList.contains('hidden') && 
                !userMenu.contains(event.target) && 
                !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Show notifications (placeholder)
        function showNotifications() {
            alert('Notifications feature coming soon!');
        }

        // Page loader
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            setTimeout(function() {
                loader.classList.add('fade-out');
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 1500);
        });

      // Global search functionality
document.getElementById('globalSearch')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query.length >= 2) {  // Only search if at least 2 characters
            window.location.href = "{{ route('admin.search.index') }}?q=" + encodeURIComponent(query);
        } else {
            alert('Please enter at least 2 characters to search');
        }
    }
});

        // Add floating animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.admin-card');
            cards.forEach((card, index) => {
                if (index % 2 === 0) {
                    card.classList.add('floating');
                    card.style.animationDelay = `${index * 0.2}s`;
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>