@extends('layouts.app')

@section('title', 'How It Works - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Learn how to buy and sell phones and accessories safely on our marketplace
            </p>
        </div>

        <!-- Steps -->
        <div class="space-y-16">
            @foreach($steps as $step)
            <div class="relative">
                <!-- Step Number -->
                <div class="absolute left-0 top-0 flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full text-lg font-semibold z-10">
                    {{ $step['step'] }}
                </div>
                
                <!-- Step Content -->
                <div class="ml-16 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-6">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($step['icon'] == 'user-plus')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    @elseif($step['icon'] == 'search')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    @elseif($step['icon'] == 'message-circle')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    @elseif($step['icon'] == 'shield-check')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    @elseif($step['icon'] == 'handshake')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    @endif
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold text-gray-900 mb-3">{{ $step['title'] }}</h2>
                            <p class="text-lg text-gray-600 mb-6">{{ $step['description'] }}</p>
                            
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">What you'll do:</h3>
                                <ul class="space-y-2">
                                    @foreach($step['details'] as $detail)
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                                        </div>
                                        <p class="ml-3 text-gray-700">{{ $detail }}</p>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Connector Line -->
                @if(!$loop->last)
                <div class="absolute left-6 top-12 w-0.5 h-16 bg-gray-300"></div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Call to Action -->
        <div class="mt-16 bg-blue-600 rounded-lg p-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
            <p class="text-xl text-blue-100 mb-8">Join thousands of users buying and selling phones safely</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('listings.create') }}" 
                       class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        Start Selling
                    </a>
                    <a href="{{ route('listings.index') }}" 
                       class="inline-flex items-center px-8 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Browse Listings
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-8 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>

        <!-- Additional Resources -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Safety First</h3>
                <p class="text-gray-600 mb-4">Learn our safety guidelines to protect yourself and others</p>
                <a href="{{ route('support.safety-tips') }}" 
                   class="text-blue-600 hover:text-blue-700 font-medium">
                    View Safety Tips →
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Need Help?</h3>
                <p class="text-gray-600 mb-4">Browse our help center for answers to common questions</p>
                <a href="{{ route('support.help-center') }}" 
                   class="text-blue-600 hover:text-blue-700 font-medium">
                    Help Center →
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact Us</h3>
                <p class="text-gray-600 mb-4">Get in touch with our support team for assistance</p>
                <a href="{{ route('support.contact') }}" 
                   class="text-blue-600 hover:text-blue-700 font-medium">
                    Contact Support →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
