<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-item {
            transition: all 0.2s ease;
        }
        
        .nav-item:hover {
            transform: translateX(4px);
        }
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-card.purple {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
        }
        
        .stat-card.orange {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
        }
        
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="h-full bg-gray-50" x-data="{ 
    sidebarOpen: false, 
    darkMode: false,
    notifications: [],
    loading: false
}" :class="{ 'dark': darkMode }">
    <div class="min-h-screen flex">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             @click="sidebarOpen = false">
        </div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-72 gradient-sidebar transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 sidebar-transition"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-crown text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Admin Panel</h1>
                        <p class="text-white/70 text-xs">Phone Marketplace</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-4 flex-1 overflow-y-auto custom-scrollbar">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-lg {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-white/70 group-hover:text-white' }}"></i>
                        <span>Dashboard</span>
                        @if(request()->routeIs('admin.dashboard'))
                            <div class="ml-auto w-2 h-2 bg-white/60 rounded-full"></div>
                        @endif
                    </a>
                    
                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'active' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-users mr-3 text-lg {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}"></i>
                        <span>User Management</span>
                        @if(request()->routeIs('admin.users.*'))
                            <div class="ml-auto w-2 h-2 bg-white/60 rounded-full"></div>
                        @endif
                    </a>
                    
                    <!-- Listings Management -->
                    <a href="{{ route('admin.listings.index') }}" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.listings.*') ? 'active' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-list-alt mr-3 text-lg {{ request()->routeIs('admin.listings.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}"></i>
                        <span>Listings</span>
                        @if(request()->routeIs('admin.listings.*'))
                            <div class="ml-auto w-2 h-2 bg-white/60 rounded-full"></div>
                        @endif
                    </a>
                    
                    <!-- Phone Models -->
                    <a href="{{ route('admin.approved-models.index') }}" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.approved-models.*') ? 'active' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-mobile-alt mr-3 text-lg {{ request()->routeIs('admin.approved-models.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}"></i>
                        <span>Phone Models</span>
                        @if(request()->routeIs('admin.approved-models.*'))
                            <div class="ml-auto w-2 h-2 bg-white/60 rounded-full"></div>
                        @endif
                    </a>
                    
                    <!-- Analytics -->
                    <a href="{{ route('admin.statistics') }}" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.statistics') ? 'active' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-chart-pie mr-3 text-lg {{ request()->routeIs('admin.statistics') ? 'text-white' : 'text-white/70 group-hover:text-white' }}"></i>
                        <span>Analytics</span>
                        @if(request()->routeIs('admin.statistics'))
                            <div class="ml-auto w-2 h-2 bg-white/60 rounded-full"></div>
                        @endif
                    </a>
                    
                    <!-- Reports -->
                    <a href="#" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-file-alt mr-3 text-lg text-white/70 group-hover:text-white"></i>
                        <span>Reports</span>
                    </a>
                    
                    <!-- Settings -->
                    <a href="#" 
                       class="nav-item group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
                        <i class="fas fa-cog mr-3 text-lg text-white/70 group-hover:text-white"></i>
                        <span>Settings</span>
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="mt-8 px-4">
                    <div class="glass-effect rounded-xl p-4">
                        <h3 class="text-sm font-medium text-white mb-3">Quick Stats</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-white/70 text-xs">Total Users</span>
                                <span class="text-white font-semibold text-sm">{{ \App\Models\User::count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/70 text-xs">Active Listings</span>
                                <span class="text-white font-semibold text-sm">{{ \App\Models\Listing::where('status', 'active')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/70 text-xs">Business Users</span>
                                <span class="text-white font-semibold text-sm">{{ \App\Models\User::where('user_type', 'business')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/70 text-xs">Banned Users</span>
                                <span class="text-red-300 font-semibold text-sm">{{ \App\Models\User::where('is_banned', true)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-0 main-content">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 shadow-sm sticky top-0 z-30">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        
                        <!-- Page title and breadcrumbs -->
                        <div class="flex items-center flex-1">
                            <h2 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                            @yield('breadcrumbs')
                        </div>
                        
                        <!-- Right side -->
                        <div class="flex items-center space-x-4">
                            <!-- Search -->
                            <div class="hidden md:block relative" x-data="{ open: false }">
                                <input type="text" 
                                       placeholder="Search..." 
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       @focus="open = true"
                                       @blur="setTimeout(() => open = false, 200)">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                            
                            <!-- Dark mode toggle -->
                            <button @click="darkMode = !darkMode" 
                                    class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-moon" x-show="!darkMode"></i>
                                <i class="fas fa-sun" x-show="darkMode"></i>
                            </button>
                            
                            <!-- Notifications -->
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button @click.prevent="open = !open" class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors relative">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full notification-badge"></span>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50"
                                     @click.stop>
                                    <div class="p-4 border-b border-gray-200">
                                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="p-4">
                                        <div class="text-center py-8">
                                            <i class="fas fa-bell-slash text-gray-300 text-3xl mb-2"></i>
                                            <p class="text-sm text-gray-500">No new notifications</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- User menu -->
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button @click.prevent="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                        <p class="text-xs text-gray-500">Administrator</p>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs" :class="{ 'rotate-180': open }"></i>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50"
                                     @click.stop>
                                    <div class="py-1">
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            <i class="fas fa-user mr-3"></i>
                                            Profile
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            <i class="fas fa-cog mr-3"></i>
                                            Settings
                                        </a>
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-sign-out-alt mr-3"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 bg-gray-50">
                <div class="fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 flex items-center space-x-4 shadow-2xl">
            <div class="loading-spinner rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 font-medium">Processing...</span>
        </div>
    </div>

    <script>
        // Global admin functions
        function showLoading() {
            Alpine.store('loading', true);
        }
        
        function hideLoading() {
            Alpine.store('loading', false);
        }
        
        function showNotification(message, type = 'info') {
            // Notification system implementation
            console.log(`${type}: ${message}`);
        }
    </script>
</body>
</html>