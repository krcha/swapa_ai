@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

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
                <span class="text-sm font-medium text-gray-900">Users</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-8 admin-content" x-data="userManagement()">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-users mr-3 text-blue-600"></i>
                    User Management
                </h1>
                <p class="text-gray-600 mt-1">Manage users, permissions, and account settings</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="exportUsers" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
                <button @click="showBulkActions = !showBulkActions" 
                        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                    <i class="fas fa-tasks mr-2"></i>
                    Bulk Actions
                </button>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div x-show="showFilters" x-transition class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Advanced Filters</h3>
            <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Search
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email, phone..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- User Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-1"></i>User Type
                    </label>
                    <select name="user_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="personal" {{ request('user_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="business" {{ request('user_type') == 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                </div>

                <!-- Subscription Tier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-crown mr-1"></i>Subscription Tier
                    </label>
                    <select name="subscription_tier" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Tiers</option>
                        <option value="tier1" {{ request('subscription_tier') == 'tier1' ? 'selected' : '' }}>Tier 1 (Free)</option>
                        <option value="tier2" {{ request('subscription_tier') == 'tier2' ? 'selected' : '' }}>Tier 2 (Basic)</option>
                        <option value="tier3" {{ request('subscription_tier') == 'tier3' ? 'selected' : '' }}>Tier 3 (Premium)</option>
                    </select>
                </div>

                <!-- Listing Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-list mr-1"></i>Has Listings
                    </label>
                    <select name="has_listings" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        <option value="yes" {{ request('has_listings') == 'yes' ? 'selected' : '' }}>Has Listings</option>
                        <option value="no" {{ request('has_listings') == 'no' ? 'selected' : '' }}>No Listings</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-check mr-1"></i>Status
                    </label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Verification Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-shield-alt mr-1"></i>Verification
                    </label>
                    <select name="verification" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Verification</option>
                        <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>Joined After
                    </label>
                    <input type="date" name="joined_after" value="{{ request('joined_after') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>Location
                    </label>
                    <input type="text" name="location" value="{{ request('location') }}" 
                           placeholder="City, Country..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" @click="clearFilters" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Clear All
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Panel -->
    <div x-show="showBulkActions" x-transition class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-yellow-800">
                    <span x-text="selectedUsers.length"></span> users selected
                </span>
                <div class="flex space-x-2">
                    <button @click="bulkAction('ban')" 
                            class="px-3 py-1 bg-red-100 text-red-800 rounded-lg text-sm hover:bg-red-200 transition-colors">
                        Ban Selected
                    </button>
                    <button @click="bulkAction('unban')" 
                            class="px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm hover:bg-green-200 transition-colors">
                        Unban Selected
                    </button>
                    <button @click="bulkAction('delete')" 
                            class="px-3 py-1 bg-red-100 text-red-800 rounded-lg text-sm hover:bg-red-200 transition-colors">
                        Delete Selected
                    </button>
                </div>
            </div>
            <button @click="showBulkActions = false" class="text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-blue-600"></i>
                        Users
                    </h3>
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full shadow-sm">
                        {{ $users->total() }} total
                    </span>
                    @if(request()->hasAny(['search', 'user_type', 'status', 'has_listings', 'subscription_tier', 'verification', 'joined_after', 'location']))
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full shadow-sm">
                            Filtered
                        </span>
                    @endif
                </div>
                
                <!-- Table Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Filter Toggle -->
                    <button @click="showFilters = !showFilters" 
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-filter"></i>
                    </button>
                    
                    <!-- Refresh -->
                    <button @click="refreshData" 
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left">
                            <input type="checkbox" 
                                   name="select_all"
                                   x-model="selectAll"
                                   @change="toggleAllUsers"
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors duration-200">
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-400"></i>
                                <span>User</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-tag text-gray-400"></i>
                                <span>Type & Status</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-crown text-gray-400"></i>
                                <span>Subscription</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-list text-gray-400"></i>
                                <span>Listings</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <span>Joined</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-cog text-gray-400"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition-all duration-200 group" 
                            x-data="{ showActions: false }"
                            @mouseenter="showActions = true"
                            @mouseleave="showActions = false">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       name="user_ids[]"
                                       value="{{ $user->id }}"
                                       x-model="selectedUsers"
                                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                                            <span class="text-lg font-semibold text-white">{{ substr($user->first_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div class="text-xs text-gray-400">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="space-y-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->user_type === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="fas {{ $user->user_type === 'business' ? 'fa-building' : 'fa-user' }} mr-1"></i>
                                        {{ ucfirst($user->user_type) }}
                                    </span>
                                    @if($user->is_banned)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i>
                                            Banned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Active
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @php
                                        $activeSubscription = $user->subscriptions->where('status', 'active')->first();
                                    @endphp
                                    @if($activeSubscription && $activeSubscription->plan)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $activeSubscription->plan->name ?? 'Tier 2' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Free Tier
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $user->listings->count() }}</span> listings
                                    @if($user->listings->count() > 0)
                                        <div class="text-xs text-gray-500">
                                            {{ $user->listings->where('status', 'active')->count() }} active
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->is_banned)
                                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 transition-colors">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 sm:px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No users found</h3>
                                    <p class="text-gray-500 max-w-sm">Try adjusting your search or filter criteria to find users.</p>
                                    <button @click="showFilters = true" 
                                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-filter mr-2"></i>
                                        Adjust Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200">
            {{ $users->appends(request()->query())->links('admin.components.pagination') }}
        </div>
    </div>
</div>

<script>
function userManagement() {
    return {
        showFilters: false,
        showBulkActions: false,
        selectedUsers: [],
        selectAll: false,
        loading: false,
        
        init() {
            this.$watch('selectAll', value => {
                if (value) {
                    this.selectedUsers = this.getAllUserIds();
                } else {
                    this.selectedUsers = [];
                }
            });
        },
        
        getAllUserIds() {
            return Array.from(document.querySelectorAll('input[name="user_ids[]"]')).map(input => input.value);
        },
        
        toggleAllUsers() {
            this.selectAll = !this.selectAll;
        },
        
        clearFilters() {
            window.location.href = '{{ route("admin.users.index") }}';
        },
        
        refreshData() {
            this.loading = true;
            window.location.reload();
        },
        
        exportUsers() {
            this.loading = true;
            // Implement export functionality
            setTimeout(() => {
                this.loading = false;
                alert('Export functionality will be implemented');
            }, 1000);
        },
        
        bulkAction(action) {
            if (this.selectedUsers.length === 0) {
                alert('Please select users first');
                return;
            }
            
            if (confirm(`Are you sure you want to ${action} ${this.selectedUsers.length} users?`)) {
                this.loading = true;
                // Implement bulk actions
                setTimeout(() => {
                    this.loading = false;
                    alert(`Bulk ${action} functionality will be implemented`);
                }, 1000);
            }
        }
    }
}
</script>
@endsection