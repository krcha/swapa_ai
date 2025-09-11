@extends('layouts.app')

@section('title', 'Buy & Sell Phones - Trusted Device Marketplace')

@section('content')
<!-- Hero Section - Swappa Style -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                {{ __('messages.home.title') }}
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                {{ __('messages.home.subtitle') }}
            </p>
            
            <!-- Device Search -->
            <div class="max-w-3xl mx-auto mb-8">
                <form action="{{ route('listings.index') }}" method="GET" class="relative">
                    <div class="flex bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <input type="text" name="search" placeholder="{{ __('messages.home.search_placeholder') }}" 
                               class="flex-1 px-6 py-4 text-gray-900 text-lg focus:outline-none"
                               value="{{ request('search') }}">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-8 py-4 font-semibold hover:bg-blue-700 transition-colors">
                            {{ __('messages.common.search') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center items-center space-x-8 text-gray-600 mb-12">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Verified Devices</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Safe Payments</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Buyer Protection</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Phone Categories - Swappa Style -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Device</h2>
            <p class="text-gray-600 text-lg">Find the perfect phone or accessory for your needs</p>
        </div>
        
        <!-- Phone Categories -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-12">
            <!-- iPhone -->
            <a href="{{ route('listings.index', ['brand' => 'apple']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">iPhone</h3>
                <p class="text-sm text-gray-500 mt-1">Apple Devices</p>
            </a>

            <!-- Samsung -->
            <a href="{{ route('listings.index', ['brand' => 'samsung']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Samsung</h3>
                <p class="text-sm text-gray-500 mt-1">Galaxy Series</p>
            </a>

            <!-- Google Pixel -->
            <a href="{{ route('listings.index', ['brand' => 'google']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Google Pixel</h3>
                <p class="text-sm text-gray-500 mt-1">Pixel Series</p>
            </a>

            <!-- OnePlus -->
            <a href="{{ route('listings.index', ['brand' => 'oneplus']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">OnePlus</h3>
                <p class="text-sm text-gray-500 mt-1">Flagship Phones</p>
            </a>

            <!-- Xiaomi -->
            <a href="{{ route('listings.index', ['brand' => 'xiaomi']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Xiaomi</h3>
                <p class="text-sm text-gray-500 mt-1">Mi Series</p>
            </a>

            <!-- Other Brands -->
            <a href="{{ route('listings.index') }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Other</h3>
                <p class="text-sm text-gray-500 mt-1">All Brands</p>
            </a>
        </div>

        <!-- Accessories Categories -->
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Accessories</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Chargers -->
            <a href="{{ route('listings.index', ['category' => 'chargers']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">🔌</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Chargers</h3>
                <p class="text-sm text-gray-500 mt-1">Lightning, USB-C, Wireless</p>
            </a>

            <!-- Earphones -->
            <a href="{{ route('listings.index', ['category' => 'earphones']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">🎧</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Earphones</h3>
                <p class="text-sm text-gray-500 mt-1">AirPods, Wired, Bluetooth</p>
            </a>

            <!-- Screen Protectors -->
            <a href="{{ route('listings.index', ['category' => 'screen-protectors']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">🛡️</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Screen Protectors</h3>
                <p class="text-sm text-gray-500 mt-1">Tempered Glass, Film</p>
            </a>

            <!-- Cases -->
            <a href="{{ route('listings.index', ['category' => 'cases']) }}" 
               class="group bg-white rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Cases</h3>
                <p class="text-sm text-gray-500 mt-1">Clear, Leather, Rugged</p>
            </a>
        </div>
    </div>
</section>

<!-- Condition Grading System - Swappa Style -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Device Condition Standards</h2>
            <p class="text-gray-600 text-lg">Every device is carefully inspected and graded for condition</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Mint Condition -->
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">✨</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Mint</h3>
                <p class="text-gray-600 mb-4">Like new condition with no visible wear</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li>• No scratches or dents</li>
                    <li>• Original packaging included</li>
                    <li>• All accessories present</li>
                    <li>• Battery health 95%+</li>
                </ul>
            </div>

            <!-- Good Condition -->
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">👍</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Good</h3>
                <p class="text-gray-600 mb-4">Light wear with minor cosmetic issues</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li>• Minor scratches on body</li>
                    <li>• Screen in excellent condition</li>
                    <li>• All functions working</li>
                    <li>• Battery health 85%+</li>
                </ul>
            </div>

            <!-- Fair Condition -->
            <div class="text-center">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">⚠️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Fair</h3>
                <p class="text-gray-600 mb-4">Visible wear but fully functional</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li>• Noticeable scratches/dents</li>
                    <li>• Screen may have minor marks</li>
                    <li>• All features working</li>
                    <li>• Battery health 70%+</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings - Swappa Style -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Devices</h2>
                <p class="text-gray-600 text-lg">Handpicked phones and accessories from verified sellers</p>
            </div>
            <a href="{{ route('listings.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                View All Devices
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $featuredListings = \App\Models\Listing::where('status', 'active')
                    ->with(['user', 'category', 'brand', 'images'])
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            @endphp

            @foreach($featuredListings as $listing)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Device Image -->
                    <div class="aspect-w-16 aspect-h-12 bg-gray-100 relative">
                        @if($listing->images && count($listing->images) > 0)
                            <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}" 
                                 alt="{{ $listing->title }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                <span class="text-4xl text-gray-400">📱</span>
                            </div>
                        @endif
                        
                        <!-- Condition Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-white text-gray-800 px-2 py-1 rounded text-xs font-medium capitalize shadow-sm">
                                {{ str_replace('_', ' ', $listing->condition) }}
                            </span>
                        </div>
                    </div>

                    <!-- Device Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $listing->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $listing->brand->name }}</p>
                        
                        <!-- Price -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xl font-bold text-gray-900">€{{ number_format($listing->price, 0) }}</span>
                            <span class="text-sm text-gray-500">{{ $listing->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Seller Info -->
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-blue-600 font-medium text-xs">{{ substr($listing->user->first_name, 0, 1) }}</span>
                                </div>
                                <span class="text-gray-600">{{ $listing->user->first_name }}</span>
                            </div>
                            @if($listing->user->is_sms_verified)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Verified</span>
                            @endif
                        </div>

                        <!-- View Button -->
                        <a href="{{ route('listings.show', $listing) }}" 
                           class="block w-full mt-3 bg-blue-600 text-white py-2 px-4 rounded text-center hover:bg-blue-700 transition-colors font-medium">
                            View Device
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Trust & Safety - Swappa Style -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Our Marketplace?</h2>
            <p class="text-gray-600 text-lg">We ensure every transaction is safe and secure</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Device Verification</h3>
                <p class="text-gray-600">Every device is checked for authenticity and condition before listing</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Safe Payments</h3>
                <p class="text-gray-600">Secure payment processing with buyer protection on every transaction</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Buyer Protection</h3>
                <p class="text-gray-600">Full refund guarantee if device doesn't match description</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Buy or Sell?</h2>
        <p class="text-xl text-blue-100 mb-8">Join thousands of satisfied customers in our trusted marketplace</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('listings.index') }}" 
               class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Browse Devices
            </a>
            <a href="{{ route('listings.create') }}" 
               class="bg-blue-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-400 transition-colors">
                Sell Your Device
            </a>
        </div>
    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection