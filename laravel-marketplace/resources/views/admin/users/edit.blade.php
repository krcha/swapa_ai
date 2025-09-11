@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror" required>
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror" required>
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- User Type and Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">User Type and Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
                    <select id="user_type" name="user_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_type') border-red-500 @enderror" required>
                        <option value="personal" {{ old('user_type', $user->user_type) === 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="business" {{ old('user_type', $user->user_type) === 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                    @error('user_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="listing_limit" class="block text-sm font-medium text-gray-700 mb-2">Listing Limit</label>
                    <input type="number" id="listing_limit" name="listing_limit" value="{{ old('listing_limit', $user->listing_limit) }}" 
                           min="0" max="1000" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('listing_limit') border-red-500 @enderror" required>
                    @error('listing_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ban Status</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_banned" value="1" {{ old('is_banned', $user->is_banned) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Banned</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label for="ban_reason" class="block text-sm font-medium text-gray-700 mb-2">Ban Reason</label>
                <textarea id="ban_reason" name="ban_reason" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ban_reason') border-red-500 @enderror"
                          placeholder="Enter reason for banning this user...">{{ old('ban_reason', $user->ban_reason) }}</textarea>
                @error('ban_reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Business Information (shown when user type is business) -->
        <div id="businessInfo" class="bg-white rounded-lg shadow p-6" style="{{ old('user_type', $user->user_type) === 'business' ? '' : 'display: none;' }}">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                    <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $user->business_name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('business_name') border-red-500 @enderror">
                    @error('business_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="business_registration" class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                    <input type="text" id="business_registration" name="business_registration" value="{{ old('business_registration', $user->business_registration_number) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('business_registration') border-red-500 @enderror">
                    @error('business_registration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                    <textarea id="business_address" name="business_address" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('business_address') border-red-500 @enderror">{{ old('business_address', $user->business_address) }}</textarea>
                    @error('business_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">Business Phone</label>
                    <input type="text" id="business_phone" name="business_phone" value="{{ old('business_phone', $user->business_phone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('business_phone') border-red-500 @enderror">
                    @error('business_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Update User
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('user_type').addEventListener('change', function() {
    const businessInfo = document.getElementById('businessInfo');
    if (this.value === 'business') {
        businessInfo.style.display = 'block';
    } else {
        businessInfo.style.display = 'none';
    }
});
</script>
@endsection
