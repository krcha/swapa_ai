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

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Users ({{ $users->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->user_type === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->user_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->listings_count }} / {{ $user->listing_limit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_banned)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>Banned
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($user->is_banned)
                                    <form method="POST" action="{{ route('admin.users.unban', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-unlock"></i> Unban
                                        </button>
                                    </form>
                                @else
                                    <button onclick="banUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-ban"></i> Ban
                                    </button>
                                @endif
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Ban User Modal -->
<div id="banModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="banForm" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ban User</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to ban <span id="banUserName"></span>?
                    </p>
                    <div>
                        <label for="ban_reason" class="block text-sm font-medium text-gray-700 mb-2">Ban Reason</label>
                        <textarea id="ban_reason" name="ban_reason" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter reason for banning this user..." required></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeBanModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Ban User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Delete User</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to delete <span id="deleteUserName"></span>? This action cannot be undone and will also delete all their listings.
                    </p>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Delete User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function banUser(userId, userName) {
    document.getElementById('banUserName').textContent = userName;
    document.getElementById('banForm').action = `/admin/users/${userId}/ban`;
    document.getElementById('banModal').classList.remove('hidden');
}

function closeBanModal() {
    document.getElementById('banModal').classList.add('hidden');
}

function deleteUser(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const banModal = document.getElementById('banModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === banModal) {
        closeBanModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>
@endsection