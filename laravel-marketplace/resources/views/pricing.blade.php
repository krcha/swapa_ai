@extends('layouts.app')

@section('title', 'Sell Your Device - Pricing Plans')

@section('content')
<!-- Hero Section - Swappa Style -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Sell Your Device
        </h1>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Choose the plan that works best for your selling needs. 
            All plans include device verification and buyer protection.
        </p>
    </div>
</section>

<!-- Pricing Cards - Swappa Style -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                No hidden fees. No surprises. Choose the plan that works best for your selling needs.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($plans as $plan)
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow {{ $plan->slug === 'tier-2' ? 'ring-2 ring-blue-500' : '' }}">
                    @if($plan->slug === 'tier-2')
                        <div class="bg-blue-600 text-white text-center py-2 text-sm font-semibold">
                            Most Popular
                        </div>
                    @endif
                    
                    <div class="p-8">
                        <!-- Plan Header -->
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            <div class="flex items-baseline justify-center">
                                <span class="text-5xl font-bold text-gray-900">€{{ number_format($plan->price, 0) }}</span>
                                @if($plan->price > 0)
                                    <span class="text-gray-500 ml-1">/month</span>
                                @endif
                            </div>
                            @if($plan->trial_days > 0)
                                <p class="text-green-600 font-semibold mt-2">{{ $plan->trial_days }} days free trial</p>
                            @endif
                        </div>

                        <!-- Features -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <span class="text-gray-900 font-medium">
                                        @if($plan->listing_limit == -1)
                                            Unlimited listings per month
                                        @else
                                            {{ $plan->listing_limit }} listings per month
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">{{ $plan->listing_duration_days }} days listing duration</span>
                            </div>

                            @if($plan->features)
                                @foreach($plan->features as $feature)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ route('register') }}" 
                           class="block w-full text-center py-3 px-6 rounded-lg font-semibold transition-colors {{ $plan->slug === 'tier-2' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                            @if($plan->price == 0)
                                Get Started Free
                            @else
                                Start {{ $plan->name }}
                            @endif
                        </a>

                        @if($plan->description)
                            <p class="text-center text-sm text-gray-500 mt-4">{{ $plan->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Market Value Section - Swappa Style -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Device Market Values</h2>
            <p class="text-gray-600 text-lg">Get instant estimates for your devices</p>
        </div>

        <!-- Popular Devices -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- iPhone 14 Pro -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">iPhone 14 Pro</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mint:</span>
                            <span class="font-semibold">€850-950</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Good:</span>
                            <span class="font-semibold">€750-850</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fair:</span>
                            <span class="font-semibold">€650-750</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Samsung Galaxy S23 -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Galaxy S23</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mint:</span>
                            <span class="font-semibold">€600-700</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Good:</span>
                            <span class="font-semibold">€500-600</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fair:</span>
                            <span class="font-semibold">€400-500</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google Pixel 7 -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Pixel 7</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mint:</span>
                            <span class="font-semibold">€400-500</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Good:</span>
                            <span class="font-semibold">€350-400</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fair:</span>
                            <span class="font-semibold">€300-350</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AirPods Pro -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🎧</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">AirPods Pro</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mint:</span>
                            <span class="font-semibold">€180-220</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Good:</span>
                            <span class="font-semibold">€150-180</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fair:</span>
                            <span class="font-semibold">€120-150</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Value Calculator -->
        <div class="bg-blue-50 rounded-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Get Instant Value Estimate</h3>
            <p class="text-gray-600 mb-6">Enter your device details to get an instant market value estimate</p>
            <div class="max-w-md mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Select Brand</option>
                        <option>Apple</option>
                        <option>Samsung</option>
                        <option>Google</option>
                        <option>OnePlus</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Select Model</option>
                        <option>iPhone 14 Pro</option>
                        <option>Galaxy S23</option>
                        <option>Pixel 7</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Select Condition</option>
                        <option>Mint</option>
                        <option>Good</option>
                        <option>Fair</option>
                    </select>
                </div>
                <button class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Get Estimate
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Trust & Safety - Swappa Style -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Sell With Us?</h2>
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
                <p class="text-gray-600">Secure payment processing with seller protection on every transaction</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Fast Listing</h3>
                <p class="text-gray-600">List your device in minutes with our streamlined process</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-600 text-lg">Everything you need to know about selling on our platform</p>
        </div>

        <div class="space-y-8">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">How do I determine my device's condition?</h3>
                <p class="text-gray-700">We provide detailed condition guidelines. Mint means like new with no visible wear, Good means light wear with minor cosmetic issues, and Fair means visible wear but fully functional.</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">What verification is required?</h3>
                <p class="text-gray-700">We verify device authenticity through serial numbers, IMEI checks, and condition assessment. Sellers must provide clear photos from multiple angles.</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">How are payments processed?</h3>
                <p class="text-gray-700">We use secure payment processing with buyer and seller protection. Payments are held in escrow until the transaction is completed successfully.</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Can I change my plan anytime?</h3>
                <p class="text-gray-700">Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate any billing differences.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Selling?</h2>
        <p class="text-xl text-blue-100 mb-8">Join thousands of successful sellers on our trusted marketplace</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Get Started Free
            </a>
            <a href="{{ route('listings.create') }}" 
               class="bg-blue-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-400 transition-colors">
                List Your Device
            </a>
        </div>
    </div>
</section>
@endsection