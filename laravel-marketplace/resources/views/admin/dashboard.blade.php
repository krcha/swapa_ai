@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumbs')
<nav class="hidden sm:flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        <li>
            <div class="flex items-center">
                <i class="fas fa-home text-gray-400"></i>
                <span class="ml-2 text-sm font-medium text-gray-500">Admin</span>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-900">Dashboard</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-8 admin-content" x-data="dashboardData()">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->first_name }}!</h1>
                <p class="text-blue-100 text-lg">Here's what's happening with your marketplace today.</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                    <p class="text-sm text-green-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Listings -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Listings</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Listing::where('status', 'active')->count() }}</p>
                    <p class="text-sm text-green-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +8% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-list text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Business Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Business Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('user_type', 'business')->count() }}</p>
                    <p class="text-sm text-blue-600 flex items-center mt-1">
                        <i class="fas fa-building mr-1"></i>
                        {{ round((\App\Models\User::where('user_type', 'business')->count() / \App\Models\User::count()) * 100, 1) }}% of total
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Revenue (Mock) -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">€12,450</p>
                    <p class="text-sm text-green-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +15% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-euro-sign text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- User Growth Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">7D</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">30D</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">90D</button>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Listing Categories -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Listings by Category</h3>
                <i class="fas fa-chart-pie text-gray-400"></i>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">{{ substr($user->first_name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->user_type === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($user->user_type) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Listings -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Listings</h3>
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @foreach(\App\Models\Listing::with('user')->latest()->take(5)->get() as $listing)
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-gray-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $listing->title }}</p>
                        <p class="text-sm text-gray-500">by {{ $listing->user->first_name }} • {{ $listing->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-sm font-medium text-green-600">€{{ number_format($listing->price) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">System Status</h3>
                <i class="fas fa-server text-gray-400"></i>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Database</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        Online
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Storage</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        2.1GB / 10GB
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Memory Usage</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        78%
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Backup</span>
                    <span class="text-sm text-gray-500">2 hours ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Manage Users</p>
                    <p class="text-sm text-gray-600">View and manage all users</p>
                </div>
            </a>
            <a href="{{ route('admin.approved-models.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-mobile-alt text-green-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Phone Models</p>
                    <p class="text-sm text-gray-600">Manage approved models</p>
                </div>
            </a>
            <a href="{{ route('admin.statistics') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-chart-bar text-purple-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Analytics</p>
                    <p class="text-sm text-gray-600">View detailed reports</p>
                </div>
            </a>
            <a href="#" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-cog text-yellow-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Settings</p>
                    <p class="text-sm text-gray-600">Configure system</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboardData() {
    return {
        init() {
            this.initCharts();
        },
        
        initCharts() {
            // User Growth Chart
            const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'New Users',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Phones', 'Accessories', 'Chargers', 'Cases'],
                    datasets: [{
                        data: [65, 20, 10, 5],
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}
</script>
@endsection