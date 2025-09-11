@extends('layouts.app')

@section('title', 'Buy Phones & Accessories - Verified Device Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('messages.listings.title') }}</h1>
        <p class="text-gray-600">{{ __('messages.listings.subtitle') }}</p>
    </div>

    <!-- Swappa-Style Filter Interface -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
        <form id="filter-form" method="GET" action="{{ route('listings.index') }}">
            <!-- Top Row - Dropdown Filters -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                @if(!request('category') || request('category') === 'smartphones' || request('category') === 'tablets')
                    <!-- Phone-specific filters -->
                    <!-- Carrier Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Carrier Status</label>
                        <select name="carrier_status" id="carrier-status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Devices</option>
                            <option value="unlocked" {{ request('carrier_status') == 'unlocked' ? 'selected' : '' }}>Unlocked</option>
                            <option value="locked" {{ request('carrier_status') == 'locked' ? 'selected' : '' }}>Locked</option>
                        </select>
                    </div>

                    <!-- Color Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Color</label>
                        <select name="color" id="color-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Colors</option>
                            <option value="black" {{ request('color') == 'black' ? 'selected' : '' }}>Black</option>
                            <option value="white" {{ request('color') == 'white' ? 'selected' : '' }}>White</option>
                            <option value="silver" {{ request('color') == 'silver' ? 'selected' : '' }}>Silver</option>
                            <option value="gold" {{ request('color') == 'gold' ? 'selected' : '' }}>Gold</option>
                            <option value="blue" {{ request('color') == 'blue' ? 'selected' : '' }}>Blue</option>
                            <option value="purple" {{ request('color') == 'purple' ? 'selected' : '' }}>Purple</option>
                            <option value="pink" {{ request('color') == 'pink' ? 'selected' : '' }}>Pink</option>
                            <option value="green" {{ request('color') == 'green' ? 'selected' : '' }}>Green</option>
                        </select>
                    </div>

                    <!-- Storage Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Storage</label>
                        <select name="storage" id="storage-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Storage</option>
                            <option value="64GB" {{ request('storage') == '64GB' ? 'selected' : '' }}>64GB</option>
                            <option value="128GB" {{ request('storage') == '128GB' ? 'selected' : '' }}>128GB</option>
                            <option value="256GB" {{ request('storage') == '256GB' ? 'selected' : '' }}>256GB</option>
                            <option value="512GB" {{ request('storage') == '512GB' ? 'selected' : '' }}>512GB</option>
                            <option value="1TB" {{ request('storage') == '1TB' ? 'selected' : '' }}>1TB</option>
                        </select>
                    </div>

                    <!-- Condition Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Condition</label>
                        <select name="condition" id="condition-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Conditions</option>
                            <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                            <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" id="sort-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="condition" {{ request('sort') == 'condition' ? 'selected' : '' }}>Best Condition</option>
                        </select>
                    </div>
                @else
                    <!-- Accessory-specific filters -->
                    <!-- Accessory Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accessory Type</label>
                        <select name="accessory_type" id="accessory-type-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Types</option>
                            <option value="chargers" {{ request('accessory_type') == 'chargers' ? 'selected' : '' }}>Chargers</option>
                            <option value="earphones" {{ request('accessory_type') == 'earphones' ? 'selected' : '' }}>Earphones</option>
                            <option value="screen-protectors" {{ request('accessory_type') == 'screen-protectors' ? 'selected' : '' }}>Screen Protectors</option>
                            <option value="cases" {{ request('accessory_type') == 'cases' ? 'selected' : '' }}>Cases</option>
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select name="brand" id="brand-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Brands</option>
                            <option value="Apple" {{ request('brand') == 'Apple' ? 'selected' : '' }}>Apple</option>
                            <option value="Samsung" {{ request('brand') == 'Samsung' ? 'selected' : '' }}>Samsung</option>
                            <option value="Sony" {{ request('brand') == 'Sony' ? 'selected' : '' }}>Sony</option>
                            <option value="Anker" {{ request('brand') == 'Anker' ? 'selected' : '' }}>Anker</option>
                            <option value="Spigen" {{ request('brand') == 'Spigen' ? 'selected' : '' }}>Spigen</option>
                        </select>
                    </div>

                    <!-- Condition Filter (hidden for screen protectors) -->
                    @if(request('category') !== 'screen-protectors')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Condition</label>
                        <select name="condition" id="condition-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Conditions</option>
                            <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                            <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                    </div>
                    @endif

                    <!-- Price Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <select name="price_range" id="price-range-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">All Prices</option>
                            <option value="0-50" {{ request('price_range') == '0-50' ? 'selected' : '' }}>€0 - €50</option>
                            <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>€50 - €100</option>
                            <option value="100-200" {{ request('price_range') == '100-200' ? 'selected' : '' }}>€100 - €200</option>
                            <option value="200+" {{ request('price_range') == '200+' ? 'selected' : '' }}>€200+</option>
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" id="sort-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="condition" {{ request('sort') == 'condition' ? 'selected' : '' }}>Best Condition</option>
                        </select>
                    </div>
                @endif
            </div>

            <!-- Bottom Row - Toggle Filters -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- One Year Warranty Toggle -->
                <div class="flex items-center">
                    <input type="checkbox" name="warranty" id="warranty-toggle" value="1" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                           {{ request('warranty') ? 'checked' : '' }}>
                    <label for="warranty-toggle" class="ml-2 text-sm text-gray-700">One Year Warranty</label>
                </div>

                <!-- Accepts Credit Cards Toggle -->
                <div class="flex items-center">
                    <input type="checkbox" name="credit_cards" id="credit-cards-toggle" value="1" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                           {{ request('credit_cards') ? 'checked' : '' }}>
                    <label for="credit-cards-toggle" class="ml-2 text-sm text-gray-700">Accepts Credit Cards</label>
                </div>

                <!-- Exclude Businesses Toggle -->
                <div class="flex items-center">
                    <input type="checkbox" name="exclude_businesses" id="exclude-businesses-toggle" value="1" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                           {{ request('exclude_businesses') ? 'checked' : '' }}>
                    <label for="exclude-businesses-toggle" class="ml-2 text-sm text-gray-700">Exclude Businesses</label>
                </div>

                <!-- Clear Filters Button -->
                <div class="flex items-center">
                    <button type="button" id="clearFilters" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                        Clear All Filters
                    </button>
                </div>
            </div>

            <!-- Hidden fields for existing filters -->
            <input type="hidden" name="search" value="{{ request('search', '') }}">
            <input type="hidden" name="brand" value="{{ request('brand', 'all') }}">
            <input type="hidden" name="category" value="{{ request('category', 'all') }}">
            <input type="hidden" name="min_price" value="{{ request('min_price', '') }}">
            <input type="hidden" name="max_price" value="{{ request('max_price', '') }}">
        </form>
    </div>

    <!-- Main Content -->
    <div class="w-full">
            <!-- Results Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div class="mb-4 sm:mb-0">
                    <p class="text-gray-600">
                        {{ __('messages.listings.showing_devices', ['count' => $listings->count()]) }}
                    </p>
                </div>
            </div>

            <!-- Listings Table - Swappa Style -->
            @if($listings->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('sort') == 'price' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                           class="group inline-flex items-center hover:text-gray-700">
                                            Price
                                            @if(request('sort') == 'price')
                                                @if(request('direction') == 'asc')
                                                    <i class="fas fa-sort-up ml-1"></i>
                                                @else
                                                    <i class="fas fa-sort-down ml-1"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                            @endif
                                        </a>
                                    </th>
                                    @if(!request('category') || request('category') === 'smartphones' || request('category') === 'tablets')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'carrier', 'direction' => request('sort') == 'carrier' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="group inline-flex items-center hover:text-gray-700">
                                                Carrier
                                                @if(request('sort') == 'carrier')
                                                    @if(request('direction') == 'asc')
                                                        <i class="fas fa-sort-up ml-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ml-1"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'color', 'direction' => request('sort') == 'color' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="group inline-flex items-center hover:text-gray-700">
                                                Color
                                                @if(request('sort') == 'color')
                                                    @if(request('direction') == 'asc')
                                                        <i class="fas fa-sort-up ml-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ml-1"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'storage', 'direction' => request('sort') == 'storage' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="group inline-flex items-center hover:text-gray-700">
                                                Storage
                                                @if(request('sort') == 'storage')
                                                    @if(request('direction') == 'asc')
                                                        <i class="fas fa-sort-up ml-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ml-1"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('sort') == 'title' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="group inline-flex items-center hover:text-gray-700">
                                                Model
                                                @if(request('sort') == 'title')
                                                    @if(request('direction') == 'asc')
                                                        <i class="fas fa-sort-up ml-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ml-1"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'battery_health', 'direction' => request('sort') == 'battery_health' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="group inline-flex items-center hover:text-gray-700">
                                                Battery
                                                @if(request('sort') == 'battery_health')
                                                    @if(request('direction') == 'asc')
                                                        <i class="fas fa-sort-up ml-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ml-1"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                                @endif
                                            </a>
                                        </th>
                                    @else
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    @endif
                                    @if(request('category') !== 'screen-protectors')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'condition', 'direction' => request('sort') == 'condition' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="group inline-flex items-center hover:text-gray-700">
                                                Condition
                                                @if(request('sort') == 'condition')
                                                    @if(request('direction') == 'asc')
                                                        <i class="fas fa-sort-up ml-1"></i>
                                                    @else
                                                        <i class="fas fa-sort-down ml-1"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-sort ml-1 opacity-50 group-hover:opacity-100"></i>
                                                @endif
                                            </a>
                                        </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seller</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($listings as $index => $listing)
                                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('listings.show', $listing) }}'">
                                        <!-- Row Number -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $listings->firstItem() + $index }}
                                        </td>

                                        <!-- Price -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-lg font-bold text-green-600">€{{ number_format($listing->price) }}</div>
                                        </td>

                                        @if(!request('category') || request('category') === 'smartphones' || request('category') === 'tablets')
                                            <!-- Carrier -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listing->carrier ? ucfirst($listing->carrier) : 'Unlocked' }}
                                            </td>

                                            <!-- Color -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listing->color ?? 'N/A' }}
                                            </td>

                                            <!-- Storage -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listing->storage ?? 'N/A' }}
                                            </td>

                                            <!-- Model -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    // Extract model from title (e.g., "iPhone 15 Pro Max" from "iPhone 15 Pro Max 256GB - Natural Titanium")
                                                    $title = $listing->title;
                                                    $model = $title;
                                                    
                                                    // Remove storage info (e.g., "256GB", "128GB", etc.)
                                                    $model = preg_replace('/\s+\d+GB\s*/', ' ', $model);
                                                    
                                                    // Remove color info (e.g., "- Natural Titanium", "- Blue", etc.)
                                                    $model = preg_replace('/\s*-\s*[^-]+$/', '', $model);
                                                    
                                                    // Clean up extra spaces
                                                    $model = trim($model);
                                                @endphp
                                                {{ $model ?: 'N/A' }}
                                            </td>

                                            <!-- Brand -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listing->brand->name ?? 'N/A' }}
                                            </td>

                                            <!-- Battery Health -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listing->battery_health ?? 'N/A' }}%
                                            </td>
                                        @else
                                            <!-- Title (for accessories) -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="max-w-xs truncate" title="{{ $listing->title }}">
                                                    {{ $listing->title }}
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Condition (hidden for screen protectors) -->
                                        @if(request('category') !== 'screen-protectors')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($listing->condition === 'like_new') bg-green-100 text-green-800
                                                @elseif($listing->condition === 'excellent') bg-blue-100 text-blue-800
                                                @elseif($listing->condition === 'good') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $listing->condition)) }}
                                            </span>
                                        </td>
                                        @endif

                                        <!-- Seller -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-medium text-gray-600">
                                                            {{ substr($listing->user->first_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $listing->user->first_name }} {{ $listing->user->last_name }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <div class="flex text-yellow-400">
                                                            @for($i = 0; $i < 5; $i++)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-xs text-gray-600 ml-1">({{ rand(10, 100) }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Location -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Belgrade, RS
                                        </td>

                                        <!-- Payment -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-credit-card text-gray-400"></i>
                                                <i class="fab fa-paypal text-blue-600"></i>
                                            </div>
                                        </td>

                                        <!-- Code -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-mono text-green-600">
                                                {{ strtoupper(substr(md5($listing->id), 0, 8)) }}
                                            </span>
                                        </td>

                                        <!-- Favorite -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button class="text-yellow-400 hover:text-yellow-500 transition-colors">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No devices found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all devices</p>
                    <a href="{{ route('listings.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Browse All Devices
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Auto-filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const filterInputs = filterForm.querySelectorAll('select, input');
    
    // Handle price sorting logic
    function handlePriceSorting(selectElement) {
        const currentSort = new URLSearchParams(window.location.search).get('sort');
        const currentOrder = new URLSearchParams(window.location.search).get('order');
        const orderInput = filterForm.querySelector('input[name="order"]');
        
        if (selectElement.value === 'price') {
            if (currentSort === 'price' && currentOrder === 'asc') {
                orderInput.value = 'desc';
            } else {
                orderInput.value = 'asc';
            }
        } else {
            orderInput.value = 'desc';
        }
    }
    
    // Auto-submit form when any filter changes
    function autoSubmit() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const newUrl = window.location.pathname + '?' + params.toString();
        
        // Update URL and reload with filters
        window.location.href = newUrl;
    }
    
    filterInputs.forEach(input => {
        // Skip hidden inputs and clear button
        if (input.type === 'hidden' || input.id === 'clearFilters') {
            return;
        }
        
        input.addEventListener('change', function() {
            // Handle special price sorting logic
            if (input.name === 'sort' && input.value === 'price') {
                handlePriceSorting(input);
            }
            
            // Auto-submit form when any filter changes
            autoSubmit();
        });
        
        // For text inputs, add debounced search
        if (input.type === 'text' || input.type === 'search' || input.type === 'number') {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    autoSubmit();
                }, 500); // 500ms delay for typing
            });
        }
    });
    
    // Clear filters button
    const clearFilters = document.getElementById('clearFilters');
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            // Clear all form inputs
            filterForm.reset();
            
            // Clear URL parameters and reload
            window.location.href = window.location.pathname;
        });
    }
});
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const selects = form.querySelectorAll('select');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    const clearButton = document.getElementById('clearFilters');
    
    // Auto-submit on dropdown change
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Auto-submit on checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Clear filters functionality
    clearButton.addEventListener('click', function() {
        // Reset all dropdowns to 'all' or first option
        selects.forEach(select => {
            select.selectedIndex = 0;
        });
        
        // Uncheck all checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Submit form
        form.submit();
    });
    
    // Highlight active filters
    function highlightActiveFilters() {
        selects.forEach(select => {
            if (select.value && select.value !== 'all') {
                select.classList.add('border-green-500', 'bg-green-50');
            } else {
                select.classList.remove('border-green-500', 'bg-green-50');
            }
        });
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.closest('div').classList.add('bg-green-50', 'rounded-lg', 'p-2');
            } else {
                checkbox.closest('div').classList.remove('bg-green-50', 'rounded-lg', 'p-2');
            }
        });
    }
    
    // Initialize highlighting
    highlightActiveFilters();
});
</script>
@endpush