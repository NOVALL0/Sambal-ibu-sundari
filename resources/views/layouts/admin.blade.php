{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Sambal admin</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: #e5e5e5;
            overflow: hidden;
        }

        /* Admin Layout */
        .admin-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #111111 0%, #0a0a0a 100%);
            border-right: 1px solid #2a2a2a;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 4px;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #2a2a2a;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            font-size: 32px;
            animation: flicker 3s infinite;
        }

        .sidebar-logo-text {
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, #dc2626, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-logo-badge {
            font-size: 10px;
            color: #9ca3af;
            margin-left: 4px;
        }

        /* Admin Profile */
        .admin-profile {
            padding: 20px 24px;
            border-bottom: 1px solid #2a2a2a;
        }

        .profile-card {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #2a2a2a;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            background: #dc2626;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: white;
        }

        .profile-details {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            color: white;
            font-weight: 600;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-role {
            color: #9ca3af;
            font-size: 12px;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 24px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: #1f1f1f;
            color: white;
        }

        .nav-link.active {
            background: #dc2626;
            color: white;
        }

        .nav-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .nav-text {
            font-size: 14px;
            font-weight: 500;
        }

        .nav-divider {
            height: 1px;
            background: #2a2a2a;
            margin: 20px 0;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            overflow-y: auto;
            background: #0a0a0a;
            padding: 24px 32px;
        }

        .admin-main::-webkit-scrollbar {
            width: 6px;
        }

        .admin-main::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .admin-main::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 6px;
        }

        /* Mobile Sidebar */
        .mobile-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 40;
            display: none;
        }

        .mobile-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: #111111;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #111111;
            border-bottom: 1px solid #2a2a2a;
            z-index: 30;
            padding: 12px 16px;
        }

        /* Animations */
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; transform: scale(1.05); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                display: none;
            }

            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .admin-main {
                padding: 80px 16px 16px;
            }

            .mobile-sidebar-overlay.active {
                display: block;
            }
        }

        /* Cards */
        .admin-card {
            background: #111111;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 24px;
            transition: border-color 0.2s ease;
        }

        .admin-card:hover {
            border-color: #dc2626;
        }

        /* Stats Card */
        .stats-card {
            background: #111111;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 20px;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        /* Tables */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            text-align: left;
            padding: 16px 12px;
            color: #9ca3af;
            font-weight: 500;
            font-size: 14px;
            border-bottom: 1px solid #2a2a2a;
        }

        .admin-table td {
            padding: 16px 12px;
            color: #e5e5e5;
            border-bottom: 1px solid #2a2a2a;
        }

        .admin-table tr:hover td {
            background: #1a1a1a;
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .badge-warning {
            background: rgba(234, 179, 8, 0.1);
            color: #eab308;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        /* Buttons */
        .btn-primary {
            background: #dc2626;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #1f1f1f;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            border: 1px solid #2a2a2a;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: #2a2a2a;
        }

        /* Forms */
        .admin-input {
            background: #1f1f1f;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: 10px 16px;
            color: white;
            width: 100%;
            transition: all 0.2s ease;
        }

        .admin-input:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.2);
        }

        /* Utilities */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>
    <div class="admin-container">
        {{-- DESKTOP SIDEBAR --}}
        <aside class="admin-sidebar">
            {{-- Header --}}
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo group flex items-center gap-3">
                    {{-- Logo Container dengan efek hover --}}
                    <div class="relative flex-shrink-0">
                        <img src="{{ asset('image/logo.png') }}"
                             alt="Logo"
                             class="w-70 h-70 object-contain transition-all duration-300 group-hover:scale-110">




                </a>
            </div>




{{-- Navigation di Sidebar --}}
<nav class="sidebar-nav">
    <ul class="nav-menu">
        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a href="{{ url('/admin/dashboard') }}"
               class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        {{-- PRODUCTS --}}
        <li class="nav-item">
            <a href="{{ url('/admin/products') }}"
               class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                <span class="nav-icon">🌶️</span>
                <span class="nav-text">Products</span>
            </a>
        </li>

        {{-- ORDERS --}}
        <li class="nav-item">
            <a href="{{ url('/admin/orders') }}"
               class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span class="nav-text">Orders</span>
            </a>
        </li>

        {{-- USERS --}}
        <li class="nav-item">
            <a href="{{ url('/admin/users') }}"
               class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Users</span>
            </a>
        </li>

        <li class="nav-divider"></li>

        {{-- BACK TO SITE --}}
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link">
                <span class="nav-icon">🏠</span>
                <span class="nav-text">Back to Site</span>
            </a>
        </li>

        {{-- LOGOUT --}}
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-full">
                    <span class="nav-icon">🚪</span>
                    <span class="nav-text">Logout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>
        </aside>

        {{-- MOBILE HEADER --}}
        <div class="mobile-header" x-data="{ mobileOpen: false }">
            <div class="flex items-center gap-3">
                <button @click="mobileOpen = !mobileOpen" class="text-2xl text-gray-400">
                    <span x-show="!mobileOpen">☰</span>
                    <span x-show="mobileOpen">✕</span>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-2xl">🔥</span>
                    <span class="font-bold text-white">Sambal ibu Sundari</span>
                </a>
            </div>
        </div>

        {{-- MOBILE SIDEBAR OVERLAY --}}
        <div x-data="{ mobileOpen: false }"
     x-show="mobileOpen"
     x-cloak
     class="fixed inset-0 z-50 md:hidden">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/80" @click="mobileOpen = false"></div>

    {{-- Sidebar Content --}}
    <div class="absolute left-0 top-0 bottom-0 w-64 bg-[#111111] overflow-y-auto">
        <div class="p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2">
                    <span class="text-3xl">🔥</span>
                    <span class="font-bold text-xl text-white">SAMBAL</span>
                </a>
                <button @click="mobileOpen = false" class="text-2xl text-gray-400">✕</button>
            </div>

            {{-- Profile --}}
            <div class="mb-6 p-4 bg-[#1a1a1a] rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="space-y-1">
                {{-- DASHBOARD --}}
                <a href="{{ url('/admin/dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                          {{ request()->is('admin/dashboard') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-[#1f1f1f] hover:text-white' }}"
                   @click="mobileOpen = false">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>

                {{-- PRODUCTS --}}
                <a href="{{ url('/admin/products') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                          {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-[#1f1f1f] hover:text-white' }}"
                   @click="mobileOpen = false">
                    <span>🌶️</span>
                    <span>Products</span>
                </a>

                {{-- ORDERS --}}
                <a href="{{ url('/admin/orders') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                          {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-[#1f1f1f] hover:text-white' }}"
                   @click="mobileOpen = false">
                    <span>📋</span>
                    <span>Orders</span>
                </a>

                {{-- USERS --}}
                <a href="{{ url('/admin/users') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                          {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-[#1f1f1f] hover:text-white' }}"
                   @click="mobileOpen = false">
                    <span>👥</span>
                    <span>Users</span>
                </a>

                <div class="border-t border-gray-700 my-4"></div>

                {{-- BACK TO SITE --}}
                <a href="{{ url('/') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-[#1f1f1f] hover:text-white"
                   @click="mobileOpen = false">
                    <span>🏠</span>
                    <span>Back to Site</span>
                </a>

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-[#1f1f1f] hover:text-white">
                        <span>🚪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>
    </div>
</div>

        {{-- MAIN CONTENT --}}
        <main class="admin-main">
            @yield('content')
        </main>
    </div>

    <script>
        // Axios setup
        axios.defaults.baseURL = '{{ url('/api') }}';
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.withCredentials = true;
    </script>

    @stack('scripts')
</body>
</html>