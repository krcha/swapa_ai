@extends('layouts.app')

@section('title', 'Browse Listings - PhoneMarket')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Browse Phone Listings</h1>
        <p class="mt-2 text-gray-600">Find your next phone from verified sellers</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('listings.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                           placeholder="Search phones...">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                    <select name="brand_id" id="brand_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Condition -->
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700">Condition</label>
                    <select name="condition" id="condition"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <option value="">All Conditions</option>
                        <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                        <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                    </select>
                </div>
            </div>

            <!-- Price Range -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price (€)</label>
                    <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                           placeholder="0">
                </div>
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price (€)</label>
                    <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                           placeholder="2000">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($listings as $listing)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Image -->
                <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                    @if($listing->primary_image)
                        <img src="{{ $listing->primary_image->image_url }}" alt="{{ $listing->title }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $listing->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $listing->brand->name }} • {{ $listing->condition }}</p>
                    
                    <!-- Price -->
                    <div class="mt-2">
                        <span class="text-2xl font-bold text-primary-600">€{{ number_format($listing->price, 2) }}</span>
                    </div>

                    <!-- Details -->
                    <div class="mt-3 space-y-1 text-sm text-gray-600">
                        @if($listing->storage)
                            <p><span class="font-medium">Storage:</span> {{ $listing->storage }}</p>
                        @endif
                        @if($listing->color)
                            <p><span class="font-medium">Color:</span> {{ $listing->color }}</p>
                        @endif
                        @if($listing->battery_health)
                            <p><span class="font-medium">Battery:</span> {{ $listing->battery_health }}%</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('listings.show', $listing) }}"
                           class="flex-1 bg-primary-600 text-white text-center px-4 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            View Details
                        </a>
                        @auth
                            <button onclick="startConversation({{ $listing->id }})"
                                    class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Contact
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No listings found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($listings->hasPages())
        <div class="mt-8">
            {{ $listings->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function startConversation(listingId) {
    // Implementation for starting conversation
    console.log('Starting conversation for listing:', listingId);
}
</script>
@endpush
@endsection
