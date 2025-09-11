<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PhoneMarket') }} - @yield('title', 'Serbia\'s Premier Phone Marketplace')</title>
    <meta name="description" content="Buy and sell used phones in Serbia. Trusted marketplace with verified sellers and secure transactions.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
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
    
    <!-- Additional CSS -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        .hover-lift {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation - Swappa Style -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">📱</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">PhoneMarket</span>
                        </a>
                    </div>

                    <!-- Search Bar - Swappa Style -->
                    <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                        <form action="{{ route('listings.index') }}" method="GET" class="w-full">
                            <div class="relative">
                                <input type="text" name="search" placeholder="{{ __('messages.home.search_placeholder') }}" 
                                       class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ request('search') }}">
                                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">{{ __('messages.nav.home') }}</a>
                    <a href="{{ route('listings.step-filter') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">{{ __('messages.nav.find_phone') }}</a>
                    <a href="{{ route('listings.create') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">{{ __('messages.nav.sell') }}</a>
                    <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">{{ __('messages.nav.pricing') }}</a>
                </div>

                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-lg">{{ \App\Helpers\LanguageHelper::getCurrentLanguage()['flag'] }}</span>
                                <span class="hidden sm:block text-sm font-medium">{{ \App\Helpers\LanguageHelper::getCurrentLanguage()['name'] }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 py-1 hidden group-hover:block z-50">
                                @foreach(\App\Helpers\LanguageHelper::getAvailableLanguages() as $code => $language)
                                    <a href="{{ \App\Helpers\LanguageHelper::getLanguageUrl($code) }}" 
                                       class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === $code ? 'bg-blue-50 text-blue-700' : '' }}">
                                        <span class="text-lg">{{ $language['flag'] }}</span>
                                        <span>{{ $language['name'] }}</span>
                                        @if(app()->getLocale() === $code)
                                            <svg class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- User Menu -->
                        @auth
                            <div class="relative group">
                                <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-medium text-sm">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden lg:block">{{ auth()->user()->first_name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 hidden group-hover:block">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.nav.seller_dashboard') }}</a>
                                    <a href="{{ route('buyer.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.nav.buyer_dashboard') }}</a>
                                    <a href="{{ route('messaging.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('messages.nav.messages') }}
                                        @if(auth()->user()->getUnreadMessageCount() > 0)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ auth()->user()->getUnreadMessageCount() }}
                                            </span>
                                        @endif
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.dashboard.my_listings') }}</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.common.settings') }}</a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.nav.logout') }}</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">{{ __('messages.nav.login') }}</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">{{ __('messages.nav.register') }}</a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="text-gray-700 hover:text-blue-600" onclick="toggleMobileMenu()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-4">
                    <div class="space-y-2">
                        <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Home</a>
                        <a href="{{ route('listings.step-filter') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Find Phone</a>
                        <a href="{{ route('listings.create') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Sell</a>
                        <a href="{{ route('pricing') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Pricing</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">📱</span>
                            </div>
                            <span class="text-xl font-bold">PhoneMarket</span>
                        </div>
                        <p class="text-gray-300 mb-4 max-w-md">Serbia's premier marketplace for used phones. Buy and sell with confidence through our secure platform with verified sellers.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a></li>
                            <li><a href="{{ route('listings.index') }}" class="text-gray-300 hover:text-white transition-colors">Browse Listings</a></li>
                            <li><a href="{{ route('pricing') }}" class="text-gray-300 hover:text-white transition-colors">Pricing</a></li>
                            <li><a href="{{ route('support.how-it-works') }}" class="text-gray-300 hover:text-white transition-colors">How It Works</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Support</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('support.help-center') }}" class="text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="{{ route('support.contact') }}" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="{{ route('support.safety-tips') }}" class="text-gray-300 hover:text-white transition-colors">Safety Tips</a></li>
                            <li><a href="{{ route('support.report-issue') }}" class="text-gray-300 hover:text-white transition-colors">Report Issue</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">&copy; 2024 PhoneMarket. All rights reserved.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="{{ route('legal.terms') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                        <a href="{{ route('legal.privacy') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="{{ route('legal.cookies') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading states
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to main content
            const main = document.querySelector('main');
            if (main) {
                main.classList.add('animate-fade-in');
            }
        });
    </script>
</body>
</html>