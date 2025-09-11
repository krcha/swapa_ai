@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- User Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-2xl font-medium text-gray-700">
                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-gray-600">{{ $user->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                @if($user->is_banned)
                    <form method="POST" action="{{ route('admin.users.unban', $user) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-unlock mr-2"></i>Unban User
                        </button>
                    </form>
                @else
                    <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->first_name }} {{ $user->last_name }}" 
                            onclick="banUser(this.dataset.userId, this.dataset.userName)" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-ban mr-2"></i>Ban User
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">User Type</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->user_type === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->user_type) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @if($user->is_banned)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-ban mr-1"></i>Banned
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Active
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Joined</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M j, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                    <dd class="mt-1">
                        @if($user->is_email_verified)
                            <span class="text-green-600"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times"></i> No</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone Verified</dt>
                    <dd class="mt-1">
                        @if($user->is_sms_verified)
                            <span class="text-green-600"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times"></i> No</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Listing Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Listing Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Current Listings</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->listings->count() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Listing Limit</dt>
                    <dd class="mt-1">
                        <form method="POST" action="{{ route('admin.users.listing-limit', $user) }}" class="inline-flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="listing_limit" value="{{ $user->listing_limit }}" 
                                   min="0" max="1000" class="w-20 px-2 py-1 border border-gray-300 rounded text-sm">
                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Remaining Listings</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->listing_limit - $user->listings->count() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Priority Listing</dt>
                    <dd class="mt-1">
                        @if($user->has_priority_listing)
                            <span class="text-green-600"><i class="fas fa-star"></i> Yes</span>
                        @else
                            <span class="text-gray-600"><i class="fas fa-star-o"></i> No</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Subscription Management -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Management</h3>
            
            @php
                $activeSubscription = $user->activeSubscription();
            @endphp
            
            <div class="space-y-4">
                <!-- Current Subscription Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Current Subscription</h4>
                    @if($activeSubscription)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Plan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activeSubscription->plan->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $activeSubscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($activeSubscription->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Starts At</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activeSubscription->starts_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ends At</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $activeSubscription->ends_at->format('M j, Y g:i A') }}</dd>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No active subscription</p>
                    @endif
                </div>

                <!-- Subscription Actions -->
                <div class="space-y-3">
                    <!-- Update Subscription Form -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Update Subscription</h4>
                        <form method="POST" action="{{ route('admin.users.subscription.update', $user) }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Plan</label>
                                    <select name="plan_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" {{ $activeSubscription && $activeSubscription->plan_id == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - ${{ $plan->price }}/month
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="active" {{ $activeSubscription && $activeSubscription->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $activeSubscription && $activeSubscription->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="cancelled" {{ $activeSubscription && $activeSubscription->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Starts At</label>
                                    <input type="datetime-local" name="starts_at" 
                                           value="{{ $activeSubscription ? $activeSubscription->starts_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                                           required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ends At</label>
                                    <input type="datetime-local" name="ends_at" 
                                           value="{{ $activeSubscription ? $activeSubscription->ends_at->format('Y-m-d\TH:i') : now()->addMonth()->format('Y-m-d\TH:i') }}"
                                           required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Update Subscription
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex flex-wrap gap-2">
                        @if($activeSubscription && $activeSubscription->status === 'active')
                            <form method="POST" action="{{ route('admin.users.subscription.cancel', $user) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure you want to cancel this subscription?')" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel Subscription
                                </button>
                            </form>
                        @endif

                        <!-- Extend Subscription -->
                        @if($activeSubscription && $activeSubscription->status === 'active')
                            <button onclick="showExtendModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-calendar-plus mr-2"></i>Extend Subscription
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Information (if applicable) -->
    @if($user->user_type === 'business')
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($user->business_name)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->business_name }}</dd>
                    </div>
                @endif
                @if($user->business_registration_number)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->business_registration_number }}</dd>
                    </div>
                @endif
                @if($user->business_address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->business_address }}</dd>
                    </div>
                @endif
                @if($user->business_phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->business_phone }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Business Verified</dt>
                    <dd class="mt-1">
                        @if($user->is_business_verified)
                            <span class="text-green-600"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times"></i> No</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    @endif

    <!-- Ban Information (if banned) -->
    @if($user->is_banned)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-red-900 mb-2">Ban Information</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-red-700">Ban Reason</dt>
                    <dd class="mt-1 text-sm text-red-600">{{ $user->ban_reason }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-red-700">Banned At</dt>
                    <dd class="mt-1 text-sm text-red-600">{{ $user->banned_at ? $user->banned_at->format('M j, Y g:i A') : 'Not specified' }}</dd>
                </div>
            </dl>
        </div>
    @endif

    <!-- Recent Listings -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Listings</h3>
        @if($user->listings->count() > 0)
            <div class="space-y-3">
                @foreach($user->listings as $listing)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $listing->title }}</h4>
                            <p class="text-sm text-gray-500">€{{ number_format($listing->price) }} • {{ ucfirst($listing->condition) }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $listing->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($listing->status) }}
                            </span>
                            <a href="{{ route('listings.show', $listing) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($user->listings->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.users.index', ['search' => $user->email]) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        View all listings
                    </a>
                </div>
            @endif
        @else
            <p class="text-gray-500 text-sm">No listings found.</p>
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
                        Are you sure you want to ban {{ $user->first_name }} {{ $user->last_name }}?
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

<!-- Extend Subscription Modal -->
<div id="extendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Extend Subscription</h3>
            <form method="POST" action="{{ route('admin.users.subscription.extend', $user) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Extension Days</label>
                    <input type="number" name="extension_days" min="1" max="365" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter number of days to extend">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeExtendModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Extend Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function banUser(userId, userName) {
    document.getElementById('banForm').action = `/admin/users/${userId}/ban`;
    document.getElementById('banModal').classList.remove('hidden');
}

function closeBanModal() {
    document.getElementById('banModal').classList.add('hidden');
}

function showExtendModal() {
    document.getElementById('extendModal').classList.remove('hidden');
}

function closeExtendModal() {
    document.getElementById('extendModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const banModal = document.getElementById('banModal');
    const extendModal = document.getElementById('extendModal');
    
    if (event.target === banModal) {
        closeBanModal();
    }
    if (event.target === extendModal) {
        closeExtendModal();
    }
}
</script>
@endsection
