<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PhoneMarket') }} - @yield('title', 'Serbia\'s Premier Phone Marketplace')</title>
    <meta name="description" content="Buy and sell used phones in Serbia. Trusted marketplace with verified sellers and secure transactions.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Additional Styles -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .floating-card {
            transition: all 0.3s ease;
        }
        
        .floating-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        
        .trust-badge {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .search-glow:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.2s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #2563eb;
            transition: width 0.2s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        .breadcrumb {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .breadcrumb-item {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .breadcrumb-item:hover {
            color: #2563eb;
        }
        
        .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 500;
        }
        
        .skeleton {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .spinner {
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded-lg z-50">
        Skip to main content
    </a>

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">PhoneMarket</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link text-gray-700 hover:text-primary-600 font-medium transition-colors {{ request()->routeIs('home') ? 'active' : '' }}">
                        {{ __('messages.nav.home') }}
                    </a>
                    <a href="{{ route('listings.index') }}" class="nav-link text-gray-700 hover:text-primary-600 font-medium transition-colors {{ request()->routeIs('listings.index') ? 'active' : '' }}">
                        {{ __('messages.nav.devices') }}
                    </a>
                    <a href="{{ route('listings.step-filter') }}" class="nav-link text-gray-700 hover:text-primary-600 font-medium transition-colors {{ request()->routeIs('listings.step-filter') ? 'active' : '' }}">
                        {{ __('messages.nav.find_phone') }}
                    </a>
                    <a href="{{ route('listings.create') }}" class="nav-link text-gray-700 hover:text-primary-600 font-medium transition-colors {{ request()->routeIs('listings.create') ? 'active' : '' }}">
                        {{ __('messages.nav.sell') }}
                    </a>
                    <a href="{{ route('pricing') }}" class="nav-link text-gray-700 hover:text-primary-600 font-medium transition-colors {{ request()->routeIs('pricing') ? 'active' : '' }}">
                        {{ __('messages.nav.pricing') }}
                    </a>
                </nav>

                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:flex items-center flex-1 max-w-md mx-8">
                    <form action="{{ route('listings.index') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   placeholder="{{ __('messages.home.search_placeholder') }}" 
                                   class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent search-glow transition-all duration-200"
                                   value="{{ request('search') }}">
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-gray-400 hover:text-primary-600 transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <span class="text-lg">{{ \App\Helpers\LanguageHelper::getCurrentLanguage()['flag'] }}</span>
                            <span class="hidden sm:block text-sm font-medium">{{ \App\Helpers\LanguageHelper::getCurrentLanguage()['name'] }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 py-1 hidden group-hover:block z-50">
                            @foreach(\App\Helpers\LanguageHelper::getAvailableLanguages() as $code => $language)
                                <a href="{{ \App\Helpers\LanguageHelper::getLanguageUrl($code) }}" 
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === $code ? 'bg-primary-50 text-primary-700' : '' }}">
                                    <span class="text-lg">{{ $language['flag'] }}</span>
                                    <span>{{ $language['name'] }}</span>
                                    @if(app()->getLocale() === $code)
                                        <i class="fas fa-check text-primary-600 ml-auto"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- User Menu -->
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-700">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                                </div>
                                <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->first_name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 hidden group-hover:block z-50">
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt w-4"></i>
                                    <span>{{ __('messages.nav.dashboard') }}</span>
                                </a>
                                <a href="{{ route('listings.create') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-plus w-4"></i>
                                    <span>{{ __('messages.nav.sell') }}</span>
                                </a>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-envelope w-4"></i>
                                    <span>{{ __('messages.nav.messages') }}</span>
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        <span>{{ __('messages.nav.logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                            {{ __('messages.nav.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 transition-colors font-medium">
                            {{ __('messages.nav.register') }}
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="md:hidden p-2 text-gray-700 hover:text-primary-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu fixed inset-y-0 right-0 w-80 bg-white shadow-xl z-50 md:hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <span class="text-lg font-semibold text-gray-900">Menu</span>
                <button id="mobile-menu-close" class="p-2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-4">
                <!-- Mobile Search -->
                <form action="{{ route('listings.index') }}" method="GET" class="mb-6">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="{{ __('messages.home.search_placeholder') }}" 
                               class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               value="{{ request('search') }}">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-gray-400 hover:text-primary-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Mobile Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>{{ __('messages.nav.home') }}</span>
                    </a>
                    <a href="{{ route('listings.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('listings.index') ? 'bg-primary-50 text-primary-700' : '' }}">
                        <i class="fas fa-mobile-alt w-5"></i>
                        <span>{{ __('messages.nav.devices') }}</span>
                    </a>
                    <a href="{{ route('listings.step-filter') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('listings.step-filter') ? 'bg-primary-50 text-primary-700' : '' }}">
                        <i class="fas fa-search w-5"></i>
                        <span>{{ __('messages.nav.find_phone') }}</span>
                    </a>
                    <a href="{{ route('listings.create') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('listings.create') ? 'bg-primary-50 text-primary-700' : '' }}">
                        <i class="fas fa-plus w-5"></i>
                        <span>{{ __('messages.nav.sell') }}</span>
                    </a>
                    <a href="{{ route('pricing') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('pricing') ? 'bg-primary-50 text-primary-700' : '' }}">
                        <i class="fas fa-tag w-5"></i>
                        <span>{{ __('messages.nav.pricing') }}</span>
                    </a>
                </nav>

                <!-- Mobile User Actions -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    @auth
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-tachometer-alt w-5"></i>
                                <span>{{ __('messages.nav.dashboard') }}</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-envelope w-5"></i>
                                <span>{{ __('messages.nav.messages') }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors w-full text-left">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    <span>{{ __('messages.nav.logout') }}</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                {{ __('messages.nav.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                {{ __('messages.nav.register') }}
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    @hasSection('breadcrumb')
        <div class="breadcrumb">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                @yield('breadcrumb')
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold">PhoneMarket</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        Serbia's premier marketplace for buying and selling phones. Trusted by thousands of users with verified sellers and secure transactions.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.nav.home') }}</a></li>
                        <li><a href="{{ route('listings.index') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.nav.devices') }}</a></li>
                        <li><a href="{{ route('listings.step-filter') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.nav.find_phone') }}</a></li>
                        <li><a href="{{ route('listings.create') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.nav.sell') }}</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.nav.pricing') }}</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('help-center') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.footer.help_center') }}</a></li>
                        <li><a href="{{ route('contact-us') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.footer.contact_us') }}</a></li>
                        <li><a href="{{ route('safety-tips') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.footer.safety_tips') }}</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="text-gray-400 hover:text-white transition-colors">{{ __('messages.footer.how_it_works') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © 2024 PhoneMarket. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="{{ route('terms-of-service') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                        <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="{{ route('cookie-policy') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('open');
        });

        document.getElementById('mobile-menu-close').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.remove('open');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.remove('open');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
