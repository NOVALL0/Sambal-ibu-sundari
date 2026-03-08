{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sambal Ibu Sundari - Pedasnya Mantap! Resep turun-temurun dengan cinta di dapur kami.">
    <meta name="keywords" content="sambal, sambal tuna, sambal cumi, sambal bawang, makanan pedas">
    <meta name="author" content="Sambal Ibu Sundari">

    <title>@yield('title', 'Sambal Ibu Sundari') - Pedasnya Mantap!</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom Styles --}}
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #030303;
            color: #f3f4f6;
            overflow-x: hidden;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main Content Wrapper */
        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }

        main {
            flex: 1 0 auto;
            width: 100%;
            position: relative;
        }


        .kitchen-bg {
            background: radial-gradient(circle at 50% 50%, #1a1a1a 0%, #0a0a0a 100%);
            position: relative;
        }

        .kitchen-bg::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(220, 38, 38, 0.03) 0%, transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(249, 115, 22, 0.03) 0%, transparent 30%),
                repeating-linear-gradient(45deg, rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px, transparent 1px, transparent 10px);
            pointer-events: none;
            z-index: 0;
        }

        /* Enhanced Cards */
        .sambal-card {
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 51, 51, 0.5);
            border-radius: 1.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .sambal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .sambal-card:hover::before {
            left: 100%;
        }

        .sambal-card:hover {
            border-color: #dc2626;
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -10px rgba(220, 38, 38, 0.3);
        }

        /* Premium Buttons */
        .sambal-btn-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.3);
        }

        .sambal-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .sambal-btn-primary:hover::before {
            left: 100%;
        }

        .sambal-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 30px -5px rgba(220, 38, 38, 0.5);
        }

        .sambal-btn-primary:active {
            transform: translateY(0);
        }

        .sambal-btn-secondary {
            background: transparent;
            color: #dc2626;
            padding: 0.875rem 2rem;
            border-radius: 1rem;
            font-weight: 600;
            border: 2px solid #dc2626;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .sambal-btn-secondary:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 20px 30px -5px rgba(220, 38, 38, 0.3);
        }

        /* Logo */
        .logo {
            width: 100px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        /* Spice Tags */
        .spice-tag {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 9999px;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            color: #dc2626;
            transition: all 0.3s ease;
            cursor: default;
            backdrop-filter: blur(4px);
        }

        .spice-tag:hover {
            background: rgba(220, 38, 38, 0.2);
            border-color: #dc2626;
            transform: scale(1.05);
        }

        /* Navbar */
        .navbar-link {
            position: relative;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
        }

        .navbar-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #dc2626, #f97316);
            transition: width 0.3s ease;
        }

        .navbar-link:hover::after {
            width: 100%;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: rgba(31, 31, 31, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 51, 51, 0.5);
            border-radius: 1rem;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transform-origin: top right;
            animation: dropdownFade 0.2s ease;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #d1d5db;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .dropdown-item:hover {
            background: #dc2626;
            color: white;
            padding-left: 2rem;
        }

        /* Footer */
        .footer-link {
            position: relative;
            display: inline-block;
            padding: 0.25rem 0;
            transition: color 0.3s ease;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: #dc2626;
            transition: width 0.3s ease;
        }

        .footer-link:hover::after {
            width: 100%;
        }

        /* Flame Animation */
        .flame-icon {
            color: #dc2626;
            animation: flicker 3s infinite;
            filter: drop-shadow(0 0 8px rgba(220, 38, 38, 0.5));
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.05); filter: drop-shadow(0 0 12px rgba(220, 38, 38, 0.8)); }
        }

        /* Kitchen Divider */
        .kitchen-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #dc2626, #f97316, #dc2626, transparent);
            margin: 3rem 0;
            border: none;
        }

        /* Loading Animation */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(220, 38, 38, 0.1);
            border-top-color: #dc2626;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Hide elements initially */
        [x-cloak] { display: none !important; }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sambal-card {
                border-radius: 1.25rem;
            }

            .sambal-btn-primary, .sambal-btn-secondary {
                padding: 0.75rem 1.5rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b91c1c;
        }
    </style>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="kitchen-bg">
    <div id="app">
        {{-- Navbar --}}
        <nav class="fixed w-full z-50 top-0 transition-all duration-500"
             x-data="{ scrolled: false, mobileOpen: false, cartCount: 0 }"
             @scroll.window="scrolled = window.scrollY > 50"
             x-init="
                 fetch('/api/cart/count')
                     .then(res => res.json())
                     .then(data => cartCount = data.count)
                     .catch(() => cartCount = 0);
                 window.addEventListener('cart-updated', () => {
                     fetch('/api/cart/count')
                         .then(res => res.json())
                         .then(data => cartCount = data.count);
                 });
             ">
            <div class="container mx-auto px-6 py-4 transition-all duration-500"
                 :class="{
                     'bg-[#0a0a0a]/95 backdrop-blur-xl shadow-2xl': scrolled,
                     'bg-transparent': !scrolled
                 }">
                <div class="flex justify-between items-center">
                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="relative">
                            <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute -inset-2 bg-red-600/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-red-500 via-orange-500 to-red-500 bg-clip-text text-transparent animate-gradient">
                            Sambal Ibu Sundari
                        </span>
                    </a>

                    {{-- Desktop Menu --}}
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="navbar-link text-gray-300 hover:text-red-500">Beranda</a>
                        <a href="{{ route('products') }}" class="navbar-link text-gray-300 hover:text-red-500">Produk</a>
                        <a href="{{ route('orders.index') }}" class="navbar-link text-gray-300 hover:text-red-500">Pesananku</a>


                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ url('/admin/dashboard') }}" class="navbar-link text-gray-300 hover:text-red-500 font-semibold">Admin</a>
                            @else
                                <a href="{{ route('cart.index') }}" class="navbar-link text-gray-300 hover:text-red-500 relative">
                                    Keranjang
                                    <span x-show="cartCount > 0"
                                          x-cloak
                                          class="absolute -top-2 -right-4 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                        <span x-text="cartCount"></span>
                                    </span>
                                </a>

                                <div class="relative" x-data="{ open: false }">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left  text-white hover:text-red-500">🚪 Logout</button>
                                    </form>

                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="navbar-link text-gray-300 hover:text-red-500">Masuk</a>
                            <a href="{{ route('register') }}" class="sambal-btn-primary px-6 py-2.5 text-sm">Daftar</a>
                        @endauth
                    </div>

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden text-2xl text-gray-300 hover:text-red-500 transition-colors focus:outline-none">
                        <span x-show="!mobileOpen">☰</span>
                        <span x-show="mobileOpen">✕</span>
                    </button>
                </div>

                {{-- Mobile Menu (SATU MENU SAJA) --}}
                <div x-show="mobileOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                     x-cloak
                     class="md:hidden mt-6 p-6 bg-[#1a1a1a]/95 backdrop-blur-xl rounded-2xl border border-gray-800/50">

                    <div class="space-y-4">
                        <a href="{{ route('home') }}" class="block py-3 text-gray-300 hover:text-red-500 transition-colors border-b border-gray-800">Home</a>
                        <a href="{{ route('products') }}" class="block py-3 text-gray-300 hover:text-red-500 transition-colors border-b border-gray-800">Products</a>
                        <a href="{{ route('about') }}" class="block py-3 text-gray-300 hover:text-red-500 transition-colors border-b border-gray-800">About</a>

                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ url('/admin/dashboard') }}" class="block py-3 text-gray-300 hover:text-red-500 transition-colors border-b border-gray-800">👑 Admin Panel</a>
                            @else
                                <a href="{{ route('cart.index') }}" class="block py-3 text-gray-300 hover:text-red-500 transition-colors border-b border-gray-800 flex justify-between">
                                    <span>🛒 Cart</span>
                                    <span x-show="cartCount > 0"
                                          class="bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                        <span x-text="cartCount"></span>
                                    </span>
                                </a>
                                <a href="{{ route('orders.index') }}" class="block py-3 text-gray-300 hover:text-red-500 transition-colors border-b border-gray-800">📦 My Orders</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                                @csrf
                                <button type="submit" class="w-full text-left py-3 text-red-500 hover:text-red-400 transition-colors">
                                    🚪 Logout ({{ Auth::user()->name }})
                                </button>
                            </form>
                        @else
                            <div class="space-y-3 pt-2">
                                <a href="{{ route('login') }}" class="block w-full text-center py-3 text-gray-300 hover:text-red-500 transition-colors border border-gray-700 rounded-xl">Login</a>
                                <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-gradient-to-b from-[#0a0a0a] to-[#000000] border-t border-gray-800/50 mt-20 relative overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-0 left-0 w-64 h-64 bg-red-600 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-600 rounded-full blur-3xl"></div>
            </div>

            <div class="container mx-auto px-6 py-16 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    {{-- Brand Column --}}
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="relative group">
                                <img src="{{ asset('image/logo.png') }}" alt="Sambal Ibu Sundari" class="h-12 w-auto group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute -inset-2 bg-red-600/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <span class="text-xl font-bold text-white">Sambal Ibu Sundari</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Pedasnya bikin nagih, kaya bumbu dapur turun-temurun. Dibuat dengan cinta di dapur kami.
                        </p>
                        <div class="flex space-x-4 text-2xl pt-2">
                            <a href="#" class="text-gray-500 hover:text-red-500 transition-all hover:scale-110">📷</a>
                            <a href="#" class="text-gray-500 hover:text-red-500 transition-all hover:scale-110">📘</a>
                            <a href="#" class="text-gray-500 hover:text-red-500 transition-all hover:scale-110">🐦</a>
                            <a href="#" class="text-gray-500 hover:text-red-500 transition-all hover:scale-110">📱</a>
                        </div>
                    </div>

                    {{-- Products Column --}}
                    <div>
                        <h4 class="text-white font-bold text-lg mb-6 relative inline-block">
                            Products
                            <span class="absolute -bottom-2 left-0 w-12 h-0.5 bg-red-600 rounded-full"></span>
                        </h4>
                        <ul class="space-y-3 text-gray-400">
                            <li><a href="{{ route('products') }}?variant=Tuna" class="footer-link text-sm hover:text-red-500 transition-colors">🌶️ Sambal Tuna</a></li>
                            <li><a href="{{ route('products') }}?variant=Cumi" class="footer-link text-sm hover:text-red-500 transition-colors">🦑 Sambal Cumi</a></li>
                            <li><a href="{{ route('products') }}?variant=Bawang" class="footer-link text-sm hover:text-red-500 transition-colors">🧄 Sambal Bawang</a></li>
                        </ul>
                    </div>

                    {{-- Newsletter Column --}}
                    <div>
                        <h4 class="text-white font-bold text-lg mb-6 relative inline-block">
                            Newsletter
                            <span class="absolute -bottom-2 left-0 w-12 h-0.5 bg-red-600 rounded-full"></span>
                        </h4>
                        <p class="text-gray-400 text-sm mb-4">Rasakan setiap pedasnya sambal kami</p>
                        <form class="flex flex-col space-y-3" @submit.prevent="subscribeNewsletter">
                            <input type="email"
                                   placeholder="Email Anda"
                                   class="w-full px-4 py-3 bg-[#1a1a1a] border border-gray-800 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/20 transition-all">
                            <button type="submit"
                                    class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-red-600/30">
                                Subscribe
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-3">Dapatkan promo dan update terbaru</p>
                    </div>
                </div>

                {{-- Copyright --}}
                <div class="mt-16 pt-8 border-t border-gray-800/50 text-center">
                    <p class="text-gray-500 text-sm">
                        © {{ date('Y') }} Sambal Ibu Sundari. All rights reserved.
                        <span class="block md:inline mt-2 md:mt-0 md:ml-2 text-red-500/80">
                            Dibakar dengan ❤️ di Purbalingga.
                        </span>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    {{-- Global Scripts --}}
    <script>
        // Axios Configuration
        axios.defaults.baseURL = '{{ url('/') }}';
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.withCredentials = true;
        axios.defaults.withXSRFToken = true;

        // Global Error Handler
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response?.status === 419) {
                    console.warn('CSRF token expired. Refreshing page...');
                    window.location.reload();
                } else if (error.response?.status === 401) {
                    console.log('Session expired. Redirecting to login...');
                    window.location.href = '{{ route("login") }}';
                }
                return Promise.reject(error);
            }
        );

        // Alpine.js Data
        document.addEventListener('alpine:init', () => {
            Alpine.data('navbar', () => ({
                scrolled: false,
                init() {
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 50;
                    });
                }
            }));
        });

        // Newsletter Subscription
        function subscribeNewsletter(event) {
            const email = event.target.querySelector('input[type="email"]').value;
            if (email) {
                alert('Thank you for subscribing!');
                event.target.reset();
            }
        }
    </script>

    @stack('scripts')
</body>
</html>