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
<div class="space-y-8" x-data="{ 
    showFilters: false, 
    loading: false,
    selectedUsers: [],
    selectAll: false,
    sortBy: 'created_at',
    sortDirection: 'desc',
    searchTerm: '{{ request('search') }}',
    userTypeFilter: '{{ request('user_type') }}',
    statusFilter: '{{ request('status') }}',
    
    init() {
        this.$watch('selectAll', value => {
            this.selectedUsers = value ? this.getAllUserIds() : [];
        });
    },
    
    getAllUserIds() {
        return Array.from(document.querySelectorAll('input[name=\"user_ids[]\"]')).map(input => input.value);
    },
    
    toggleUserSelection(userId) {
        if (this.selectedUsers.includes(userId)) {
            this.selectedUsers = this.selectedUsers.filter(id => id !== userId);
        } else {
            this.selectedUsers.push(userId);
        }
        this.selectAll = this.selectedUsers.length === this.getAllUserIds().length;
    },
    
    async bulkAction(action) {
        if (this.selectedUsers.length === 0) return;
        
        this.loading = true;
        // Implement bulk actions here
        setTimeout(() => {
            this.loading = false;
            this.selectedUsers = [];
            this.selectAll = false;
        }, 2000);
    }
}">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                        <p class="mt-1 text-gray-600">Manage users, permissions, and account settings</p>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-600">Total Users</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $users->total() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-check text-green-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-600">Active</p>
                                <p class="text-2xl font-bold text-green-900">{{ $users->where('is_banned', false)->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-times text-red-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-600">Banned</p>
                                <p class="text-2xl font-bold text-red-900">{{ $users->where('is_banned', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-building text-purple-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-600">Business</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $users->where('user_type', 'business')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button @click="showFilters = !showFilters" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>
                
                <div class="relative" x-show="selectedUsers.length > 0" x-transition>
                    <button @click="$refs.bulkMenu.toggle()" 
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-all duration-200">
                        <i class="fas fa-tasks mr-2"></i>
                        Bulk Actions ({{ selectedUsers.length }})
                    </button>
                    
                    <div x-ref="bulkMenu" x-show="false" @click.away="$refs.bulkMenu.hide()" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <button @click="bulkAction('ban')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-ban mr-3"></i>Ban Selected
                            </button>
                            <button @click="bulkAction('unban')" class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                <i class="fas fa-check mr-3"></i>Unban Selected
                            </button>
                            <button @click="bulkAction('delete')" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-trash mr-3"></i>Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
                
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Add User
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
         x-show="showFilters" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">
        <div class="p-6">
            <form method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-1"></i>Search Users
                        </label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by name, email, or phone..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- User Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tag mr-1"></i>User Type
                        </label>
                        <select name="user_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="">All Types</option>
                            <option value="personal" {{ request('user_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="business" {{ request('user_type') == 'business' ? 'selected' : '' }}>Business</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-check mr-1"></i>Status
                        </label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showFilters = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Advanced Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                        {{ $users->total() }} total
                    </span>
                </div>
                
                <!-- Table Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               x-model="searchTerm"
                               placeholder="Search users..."
                               class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <select x-model="sortBy" 
                                class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at">Joined Date</option>
                            <option value="first_name">Name</option>
                            <option value="email">Email</option>
                            <option value="user_type">Type</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Sort Direction -->
                    <button @click="sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'"
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-sort" :class="{ 'fa-sort-up': sortDirection === 'asc', 'fa-sort-down': sortDirection === 'desc' }"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   x-model="selectAll"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type & Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Verification
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Listings
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Joined
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150" 
                            x-data="{ showActions: false }"
                            @mouseenter="showActions = true"
                            @mouseleave="showActions = false">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       value="{{ $user->id }}"
                                       x-model="selectedUsers"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->user_type === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="fas {{ $user->user_type === 'business' ? 'fa-building' : 'fa-user' }} mr-1"></i>
                                        {{ ucfirst($user->user_type) }}
                                    </span>
                                    <div>
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
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope {{ $user->is_email_verified ? 'text-green-500' : 'text-gray-300' }} text-sm"></i>
                                        <span class="ml-1 text-xs text-gray-500">Email</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-sms {{ $user->is_sms_verified ? 'text-green-500' : 'text-gray-300' }} text-sm"></i>
                                        <span class="ml-1 text-xs text-gray-500">SMS</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-id-card {{ $user->is_age_verified ? 'text-green-500' : 'text-gray-300' }} text-sm"></i>
                                        <span class="ml-1 text-xs text-gray-500">Age</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->listings_count ?? 0 }} / {{ $user->listing_limit ?? 0 }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $user->listing_limit > 0 ? (($user->listings_count ?? 0) / $user->listing_limit) * 100 : 0 }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2" 
                                     x-show="showActions" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100">
                                    
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($user->is_banned)
                                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Unban User">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Ban User">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Always visible action button for mobile -->
                                <div class="lg:hidden">
                                    <button @click="$refs.mobileMenu.toggle()" 
                                            class="p-2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-ref="mobileMenu" x-show="false" @click.away="$refs.mobileMenu.hide()"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        <div class="py-1">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-eye mr-3"></i>View Details
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-edit mr-3"></i>Edit User
                                            </a>
                                            @if($user->is_banned)
                                                <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                                        <i class="fas fa-check mr-3"></i>Unban User
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        <i class="fas fa-ban mr-3"></i>Ban User
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-trash mr-3"></i>Delete User
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                                    <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-700">
                    <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results</span>
                </div>
                <div class="flex items-center space-x-2">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
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
    <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Processing...</span>
    </div>
</div>
@endsection
