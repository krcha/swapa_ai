@extends('layouts.modern')

@section('title', 'Buy & Sell Phones - Trusted Device Marketplace')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 animate-fade-in">
                {{ __('messages.home.title') }}
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto animate-slide-up">
                {{ __('messages.home.subtitle') }}
            </p>
            
            <!-- Enhanced Search Bar -->
            <div class="max-w-4xl mx-auto mb-12 animate-slide-up">
                <form action="{{ route('listings.index') }}" method="GET" class="relative">
                    <div class="flex bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search" 
                                   placeholder="{{ __('messages.home.search_placeholder') }}" 
                                   class="w-full px-6 py-4 text-gray-900 text-lg focus:outline-none"
                                   value="{{ request('search') }}">
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        <button type="submit" 
                                class="bg-primary-600 text-white px-8 py-4 font-semibold hover:bg-primary-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-search"></i>
                            <span>{{ __('messages.common.search') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center items-center gap-8 text-white/90 animate-slide-up">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-2xl"></i>
                    <span class="font-medium">Verified Sellers</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-lock text-2xl"></i>
                    <span class="font-medium">Secure Payments</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-star text-2xl"></i>
                    <span class="font-medium">4.9/5 Rating</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-users text-2xl"></i>
                    <span class="font-medium">10,000+ Users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-bounce-gentle"></div>
    <div class="absolute top-40 right-20 w-16 h-16 bg-white/10 rounded-full animate-bounce-gentle" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-20 left-20 w-12 h-12 bg-white/10 rounded-full animate-bounce-gentle" style="animation-delay: 2s;"></div>
</section>

<!-- Featured Categories -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Popular Categories</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Find the perfect device from our most popular categories</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <!-- iPhone Category -->
            <a href="{{ route('listings.index', ['brand' => 'Apple']) }}" class="group">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <div class="w-16 h-16 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition-colors">
                        <i class="fab fa-apple text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">iPhone</h3>
                    <p class="text-sm text-gray-600">Latest models</p>
                </div>
            </a>

            <!-- Samsung Category -->
            <a href="{{ route('listings.index', ['brand' => 'Samsung']) }}" class="group">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition-colors">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Samsung</h3>
                    <p class="text-sm text-gray-600">Galaxy series</p>
                </div>
            </a>

            <!-- Xiaomi Category -->
            <a href="{{ route('listings.index', ['brand' => 'Xiaomi']) }}" class="group">
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <div class="w-16 h-16 bg-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition-colors">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Xiaomi</h3>
                    <p class="text-sm text-gray-600">Redmi & Mi</p>
                </div>
            </a>

            <!-- Google Category -->
            <a href="{{ route('listings.index', ['brand' => 'Google']) }}" class="group">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition-colors">
                        <i class="fab fa-google text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Google</h3>
                    <p class="text-sm text-gray-600">Pixel series</p>
                </div>
            </a>

            <!-- OnePlus Category -->
            <a href="{{ route('listings.index', ['brand' => 'OnePlus']) }}" class="group">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition-colors">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">OnePlus</h3>
                    <p class="text-sm text-gray-600">Flagship phones</p>
                </div>
            </a>

            <!-- Accessories Category -->
            <a href="{{ route('listings.index', ['category' => 'Accessories']) }}" class="group">
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-600 transition-colors">
                        <i class="fas fa-headphones text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Accessories</h3>
                    <p class="text-sm text-gray-600">Cases & more</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Listings -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Listings</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked devices from verified sellers</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Featured Listing 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden floating-card">
                <div class="relative">
                    <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-gray-100 to-gray-200">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-6xl text-gray-400"></i>
                        </div>
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="trust-badge">Featured</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <button class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <i class="fas fa-heart text-gray-600"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">iPhone 14 Pro</h3>
                    <p class="text-gray-600 mb-4">128GB • Space Black • Excellent Condition</p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl font-bold text-primary-600">$899</span>
                        <div class="flex items-center space-x-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-600">(24)</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Belgrade</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-clock"></i>
                            <span>2 days ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Listing 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden floating-card">
                <div class="relative">
                    <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-blue-100 to-blue-200">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-6xl text-blue-400"></i>
                        </div>
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="trust-badge">Staff Pick</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <button class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <i class="fas fa-heart text-gray-600"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Samsung Galaxy S23 Ultra</h3>
                    <p class="text-gray-600 mb-4">256GB • Phantom Black • Like New</p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl font-bold text-primary-600">$1,199</span>
                        <div class="flex items-center space-x-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-600">(18)</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Novi Sad</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-clock"></i>
                            <span>1 day ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Listing 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden floating-card">
                <div class="relative">
                    <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-green-100 to-green-200">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-6xl text-green-400"></i>
                        </div>
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="trust-badge">Best Value</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <button class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <i class="fas fa-heart text-gray-600"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Google Pixel 7</h3>
                    <p class="text-gray-600 mb-4">128GB • Obsidian • Good Condition</p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl font-bold text-primary-600">$599</span>
                        <div class="flex items-center space-x-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-600">(31)</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Niš</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-clock"></i>
                            <span>3 days ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('listings.index') }}" class="inline-flex items-center space-x-2 bg-primary-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary-700 transition-colors">
                <span>View All Listings</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Simple steps to buy or sell your phone</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">1. Find Your Phone</h3>
                <p class="text-gray-600">Browse our extensive collection of verified phones or use our step-by-step finder to discover the perfect device.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-handshake text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">2. Connect with Seller</h3>
                <p class="text-gray-600">Message the seller directly, ask questions, and arrange a safe meeting or shipping method.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-credit-card text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">3. Secure Payment</h3>
                <p class="text-gray-600">Complete your purchase with our secure payment system and enjoy your new phone with confidence.</p>
            </div>
        </div>
    </div>
</section>

<!-- Trust & Security -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Trust & Security</h2>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">Your safety and satisfaction are our top priorities</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Verified Sellers</h3>
                <p class="text-gray-300 text-sm">All sellers are verified with ID and contact information</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Secure Payments</h3>
                <p class="text-gray-300 text-sm">Encrypted payment processing with buyer protection</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Money Back</h3>
                <p class="text-gray-300 text-sm">30-day money-back guarantee on all purchases</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">24/7 Support</h3>
                <p class="text-gray-300 text-sm">Round-the-clock customer support for all users</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Join thousands of satisfied customers buying and selling phones safely</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('listings.step-filter') }}" class="bg-white text-primary-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                Find Your Phone
            </a>
            <a href="{{ route('listings.create') }}" class="bg-primary-700 text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary-800 transition-colors border border-primary-500">
                Sell Your Phone
            </a>
        </div>
    </div>
</section>
@endsection
