@extends('admin.layouts.app')

@section('title', 'Listing Details')

@section('content')
<div class="admin-content">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 mb-8 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <i class="fas fa-eye mr-3"></i>Listing Details
                </h1>
                <p class="text-blue-100">View and manage listing information</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.listings.index') }}" 
                   class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Listings
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Listing Images -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-images mr-2"></i>Listing Images
                </h2>
                @if($listing->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($listing->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $listing->title }}" 
                                     class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <button class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-white text-2xl">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-image text-4xl mb-4"></i>
                        <p>No images available</p>
                    </div>
                @endif
            </div>

            <!-- Listing Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2"></i>Listing Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-gray-900">{{ $listing->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                        <p class="text-gray-900 font-mono">{{ $listing->code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <p class="text-gray-900">{{ $listing->category->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <p class="text-gray-900">{{ $listing->brand->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <p class="text-gray-900">{{ $listing->model_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($listing->condition === 'new') bg-green-100 text-green-800
                            @elseif($listing->condition === 'like_new') bg-blue-100 text-blue-800
                            @elseif($listing->condition === 'good') bg-yellow-100 text-yellow-800
                            @elseif($listing->condition === 'fair') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $listing->condition)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <p class="text-gray-900 text-lg font-semibold">${{ number_format($listing->price, 2) }}</p>
                        @if($listing->original_price && $listing->original_price > $listing->price)
                            <p class="text-sm text-gray-500 line-through">${{ number_format($listing->original_price, 2) }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Battery Health</label>
                        <p class="text-gray-900">{{ $listing->battery_health ?? 'N/A' }}%</p>
                    </div>
                </div>
                
                @if($listing->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $listing->description }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Technical Specifications -->
            @if($listing->category->name === 'phones' && $listing->model_name)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-mobile-alt mr-2"></i>Technical Specifications
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Storage</label>
                            <p class="text-gray-900">{{ $listing->storage ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <p class="text-gray-900">{{ $listing->color ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carrier Status</label>
                            <p class="text-gray-900">{{ ucfirst($listing->carrier_status ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Battery Health</label>
                            <p class="text-gray-900">{{ $listing->battery_health ?? 'N/A' }}%</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Seller Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user mr-2"></i>Seller Information
                </h2>
                <div class="space-y-4">
                    <div>
                        @if($listing->user->isBusiness() && $listing->user->business_name)
                            <div class="font-medium text-gray-900">{{ $listing->user->business_name }}</div>
                            <div class="text-sm text-gray-500">{{ $listing->user->first_name }} {{ $listing->user->last_name }}</div>
                        @else
                            <div class="font-medium text-gray-900">{{ $listing->user->first_name }} {{ $listing->user->last_name }}</div>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $listing->user->email }}</p>
                    </div>
                    
                    @if($listing->user->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-gray-900">{{ $listing->user->phone }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $listing->user->isBusiness() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($listing->user->user_type) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if($listing->user->is_email_verified)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Email Verified
                            </span>
                        @endif
                        @if($listing->user->is_sms_verified)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-phone mr-1"></i>SMS Verified
                            </span>
                        @endif
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.users.show', $listing->user) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user mr-2"></i>View User Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Listing Status -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-toggle-on mr-2"></i>Listing Status
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $listing->is_active ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Featured</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $listing->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $listing->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Priority</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $listing->has_priority_listing ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $listing->has_priority_listing ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cogs mr-2"></i>Quick Actions
                </h2>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.listings.toggle-status', $listing) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 {{ $listing->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors">
                            <i class="fas fa-{{ $listing->is_active ? 'times' : 'check' }} mr-2"></i>
                            {{ $listing->is_active ? 'Deactivate' : 'Activate' }} Listing
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.listings.toggle-featured', $listing) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 {{ $listing->is_featured ? 'bg-gray-600 hover:bg-gray-700' : 'bg-yellow-600 hover:bg-yellow-700' }} text-white rounded-lg transition-colors">
                            <i class="fas fa-star mr-2"></i>
                            {{ $listing->is_featured ? 'Unfeature' : 'Feature' }} Listing
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}" 
                          class="w-full" 
                          onsubmit="return confirm('Are you sure you want to delete this listing?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete Listing
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2"></i>Timestamps
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-sm text-gray-900">{{ $listing->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $listing->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
