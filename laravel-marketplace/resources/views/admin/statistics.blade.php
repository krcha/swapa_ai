@extends('admin.layouts.app')

@section('title', 'Statistics')
@section('page-title', 'Statistics')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <!-- Individual Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Individual Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['individual_users']) }}</p>
                </div>
            </div>
        </div>

        <!-- Business Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Business Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['business_users']) }}</p>
                </div>
            </div>
        </div>

        <!-- Banned Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ban text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Banned Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['banned_users']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Listings Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Total Listings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_listings']) }}</p>
                </div>
            </div>
        </div>

        <!-- Active Listings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_listings']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Type Distribution -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">User Type Distribution</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Individual Users</span>
                    <span class="text-sm text-gray-500">{{ $stats['individual_users'] }} ({{ $stats['total_users'] > 0 ? round(($stats['individual_users'] / $stats['total_users']) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_users'] > 0 ? ($stats['individual_users'] / $stats['total_users']) * 100 : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Business Users</span>
                    <span class="text-sm text-gray-500">{{ $stats['business_users'] }} ({{ $stats['total_users'] > 0 ? round(($stats['business_users'] / $stats['total_users']) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $stats['total_users'] > 0 ? ($stats['business_users'] / $stats['total_users']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Manage Users</p>
                    <p class="text-sm text-gray-500">View, edit, ban, or delete users</p>
                </div>
            </a>

            <a href="{{ route('admin.users.index', ['status' => 'banned']) }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex-shrink-0">
                    <i class="fas fa-ban text-red-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Banned Users</p>
                    <p class="text-sm text-gray-500">View and manage banned users</p>
                </div>
            </a>

            <a href="{{ route('admin.users.index', ['user_type' => 'business']) }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex-shrink-0">
                    <i class="fas fa-building text-purple-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Business Users</p>
                    <p class="text-sm text-gray-500">Manage business accounts</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Contact Click Analytics -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Contact Seller Button Analytics</h3>
            <p class="text-sm text-gray-500">Track how many times users click the "Contact Seller" button</p>
        </div>
        
        <div class="p-6">
            <!-- Click Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-mouse-pointer text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Total Clicks</p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($contactClickStats['total_clicks']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-day text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-700">Today's Clicks</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($contactClickStats['today_clicks']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-week text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-700">This Week</p>
                            <p class="text-2xl font-bold text-purple-900">{{ number_format($contactClickStats['this_week_clicks']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-700">This Month</p>
                            <p class="text-2xl font-bold text-orange-900">{{ number_format($contactClickStats['this_month_clicks']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Clicked Listings -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Most Clicked Listings</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($topClickedListings->count() > 0)
                        <div class="space-y-3">
                            @foreach($topClickedListings as $item)
                                <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-900">{{ $item->listing->title ?? 'Unknown Listing' }}</h5>
                                        <p class="text-sm text-gray-500">€{{ number_format($item->listing->price ?? 0) }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-green-100 text-green-800 text-sm font-medium px-2 py-1 rounded-full">
                                            {{ $item->click_count }} clicks
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No contact clicks recorded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Click Trends Chart -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Click Trends (Last 7 Days)</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <canvas id="clickTrendsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Click Trends Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('clickTrendsChart').getContext('2d');
    const trendsData = @json($clickTrends);
    
    const labels = trendsData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });
    
    const clicks = trendsData.map(item => item.clicks);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Contact Clicks',
                data: clicks,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection
