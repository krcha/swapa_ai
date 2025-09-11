@extends('admin.layouts.app')

@section('title', 'Listings Management')

@section('content')
<div class="admin-content">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 mb-8 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <i class="fas fa-list-alt mr-3"></i>Listings Management
                </h1>
                <p class="text-blue-100">Manage all marketplace listings, approve content, and monitor activity</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                <div class="text-blue-100">Total Listings</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</div>
                    <div class="text-sm text-gray-500">Active Listings</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] }}</div>
                    <div class="text-sm text-gray-500">Inactive Listings</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['featured'] }}</div>
                    <div class="text-sm text-gray-500">Featured Listings</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-crown text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['priority'] }}</div>
                    <div class="text-sm text-gray-500">Priority Listings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8" x-data="{ filtersOpen: false }">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-filter mr-2"></i>Advanced Filters
                </h2>
                <button @click="filtersOpen = !filtersOpen" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chevron-down mr-2" :class="{ 'rotate-180': filtersOpen }"></i>
                    <span x-text="filtersOpen ? 'Hide Filters' : 'Show Filters'"></span>
                </button>
            </div>
        </div>

        <div x-show="filtersOpen" x-transition class="p-6 border-t border-gray-200">
            <form method="GET" class="space-y-6">
                <!-- Search and Basic Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-1"></i>Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search listings, users, models..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tags mr-1"></i>Category
                        </label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mobile-alt mr-1"></i>Brand
                        </label>
                        <select name="brand" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star mr-1"></i>Condition
                        </label>
                        <select name="condition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Conditions</option>
                            @foreach($conditions as $condition)
                                <option value="{{ $condition }}" {{ request('condition') == $condition ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $condition)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status and User Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-1"></i>Status
                        </label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="priority" {{ request('status') == 'priority' ? 'selected' : '' }}>Priority</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1"></i>User Type
                        </label>
                        <select name="user_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All User Types</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type }}" {{ request('user_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-shield-alt mr-1"></i>Verification
                        </label>
                        <select name="user_verified" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Users</option>
                            <option value="verified" {{ request('user_verified') == 'verified' ? 'selected' : '' }}>Verified Only</option>
                            <option value="unverified" {{ request('user_verified') == 'unverified' ? 'selected' : '' }}>Unverified Only</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>Location
                        </label>
                        <input type="text" name="location" value="{{ request('location') }}" 
                               placeholder="City or Country"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Price and Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-1"></i>Min Price
                        </label>
                        <input type="number" name="price_min" value="{{ request('price_min') }}" 
                               placeholder="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-1"></i>Max Price
                        </label>
                        <input type="number" name="price_max" value="{{ request('price_max') }}" 
                               placeholder="10000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1"></i>Created After
                        </label>
                        <input type="date" name="created_after" value="{{ request('created_after') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1"></i>Created Before
                        </label>
                        <input type="date" name="created_before" value="{{ request('created_before') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.listings.index') }}" 
                           class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear Filters
                        </a>
                    </div>
                    <div class="text-sm text-gray-500">
                        Showing {{ $listings->count() }} of {{ $listings->total() }} listings
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sorting and Bulk Actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-6">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Sorting -->
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <form method="GET" class="flex items-center space-x-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="brand" value="{{ request('brand') }}">
                        <input type="hidden" name="condition" value="{{ request('condition') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="user_type" value="{{ request('user_type') }}">
                        <input type="hidden" name="user_verified" value="{{ request('user_verified') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                        <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                        <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                        <input type="hidden" name="created_after" value="{{ request('created_after') }}">
                        <input type="hidden" name="created_before" value="{{ request('created_before') }}">
                        
                        <select name="sort_by" onchange="this.form.submit()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                            <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                            <option value="user_name" {{ request('sort_by') == 'user_name' ? 'selected' : '' }}>User Name</option>
                            <option value="category_name" {{ request('sort_by') == 'category_name' ? 'selected' : '' }}>Category</option>
                            <option value="brand_name" {{ request('sort_by') == 'brand_name' ? 'selected' : '' }}>Brand</option>
                        </select>
                        
                        <select name="sort_order" onchange="this.form.submit()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </form>
                </div>

                <!-- Bulk Actions -->
                <div class="flex items-center space-x-4" x-data="{ selectedListings: [] }">
                    <form method="POST" action="{{ route('admin.listings.bulk-action') }}" 
                          @submit="if(selectedListings.length === 0) { alert('Please select listings first'); return false; }">
                        @csrf
                        <input type="hidden" name="listing_ids" :value="selectedListings.join(',')">
                        <div class="flex items-center space-x-2">
                            <select name="action" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Bulk Actions</option>
                                <option value="activate">Activate Selected</option>
                                <option value="deactivate">Deactivate Selected</option>
                                <option value="feature">Feature Selected</option>
                                <option value="unfeature">Unfeature Selected</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-cogs mr-2"></i>Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        @if($listings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       @change="selectedListings = $event.target.checked ? getAllListingIds() : []">
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-image mr-1"></i>Listing
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-user mr-1"></i>Seller
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-tags mr-1"></i>Category & Brand
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-dollar-sign mr-1"></i>Price
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-star mr-1"></i>Condition
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-toggle-on mr-1"></i>Status
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-1"></i>Created
                            </th>
                            <th class="px-4 sm:px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-1"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($listings as $listing)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" value="{{ $listing->id }}" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 listing-checkbox"
                                           x-model="selectedListings">
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            @if($listing->images->count() > 0)
                                                <img class="h-16 w-16 rounded-lg object-cover" 
                                                     src="{{ asset('storage/' . $listing->images->first()->image_path) }}" 
                                                     alt="{{ $listing->title }}">
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs">
                                                {{ $listing->title }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Code: {{ $listing->code }}
                                            </div>
                                            @if($listing->model_name)
                                                <div class="text-xs text-gray-400">
                                                    {{ $listing->model_name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($listing->user->isBusiness() && $listing->user->business_name)
                                            <div class="font-medium">{{ $listing->user->business_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $listing->user->first_name }} {{ $listing->user->last_name }}</div>
                                        @else
                                            <div class="font-medium">{{ $listing->user->first_name }} {{ $listing->user->last_name }}</div>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $listing->user->email }}</div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if($listing->user->is_email_verified)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Email
                                            </span>
                                        @endif
                                        @if($listing->user->is_sms_verified)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-phone mr-1"></i>SMS
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div class="font-medium">{{ $listing->category->name }}</div>
                                        <div class="text-gray-500">{{ $listing->brand->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        ${{ number_format($listing->price, 2) }}
                                    </div>
                                    @if($listing->original_price && $listing->original_price > $listing->price)
                                        <div class="text-xs text-gray-500 line-through">
                                            ${{ number_format($listing->original_price, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($listing->condition === 'new') bg-green-100 text-green-800
                                        @elseif($listing->condition === 'like_new') bg-blue-100 text-blue-800
                                        @elseif($listing->condition === 'good') bg-yellow-100 text-yellow-800
                                        @elseif($listing->condition === 'fair') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $listing->condition)) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @if($listing->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>Inactive
                                            </span>
                                        @endif
                                        
                                        @if($listing->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                        
                                        @if($listing->has_priority_listing)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-crown mr-1"></i>Priority
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $listing->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs">{{ $listing->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.listings.show', $listing) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.listings.toggle-status', $listing) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-{{ $listing->is_active ? 'red' : 'green' }}-600 hover:text-{{ $listing->is_active ? 'red' : 'green' }}-900"
                                                    title="{{ $listing->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $listing->is_active ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.listings.toggle-featured', $listing) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-{{ $listing->is_featured ? 'gray' : 'yellow' }}-600 hover:text-{{ $listing->is_featured ? 'gray' : 'yellow' }}-900"
                                                    title="{{ $listing->is_featured ? 'Unfeature' : 'Feature' }}">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $listings->links('admin.components.pagination') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                    <i class="fas fa-list-alt text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No listings found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your filters or search criteria</p>
                <a href="{{ route('admin.listings.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Clear Filters
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function getAllListingIds() {
    return Array.from(document.querySelectorAll('input[name="listing_ids[]"]:checked')).map(input => input.value);
}
</script>
@endsection