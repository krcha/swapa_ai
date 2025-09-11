@extends('layouts.app')

@section('title', 'Safety Tips - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Safety Tips</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Stay safe while buying and selling on our marketplace. Follow these guidelines to protect yourself and have a positive experience.
            </p>
        </div>

        <!-- Safety Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($tips as $category)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($category['icon'] == 'shield-check')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                @elseif($category['icon'] == 'map-marker')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                @elseif($category['icon'] == 'search')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                @elseif($category['icon'] == 'credit-card')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                @elseif($category['icon'] == 'exclamation-triangle')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                @elseif($category['icon'] == 'check-circle')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @endif
                            </svg>
                        </div>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900">{{ $category['category'] }}</h2>
                </div>
                
                <ul class="space-y-3">
                    @foreach($category['tips'] as $tip)
                    <li class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        </div>
                        <p class="ml-3 text-sm text-gray-600">{{ $tip }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <!-- Emergency Contacts -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-8 mb-12">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-red-900 mb-2">Emergency Contacts</h3>
                    <p class="text-red-700 mb-4">If you feel unsafe or encounter suspicious behavior, contact the authorities immediately:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="font-medium text-red-900">Police Emergency</p>
                            <p class="text-red-700">192</p>
                        </div>
                        <div>
                            <p class="font-medium text-red-900">Police Non-Emergency</p>
                            <p class="text-red-700">+381 11 192</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporting Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Report Suspicious Activity</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">What to report:</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">•</span>
                            Fake or misleading listings
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">•</span>
                            Harassment or inappropriate behavior
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">•</span>
                            Requests for payment before meeting
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">•</span>
                            Pressure to meet in isolated locations
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">•</span>
                            Any behavior that makes you feel unsafe
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">How to report:</h3>
                    <div class="space-y-4">
                        <a href="{{ route('support.report-issue') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Report an Issue
                        </a>
                        <p class="text-sm text-gray-600">
                            Use the "Report" button on any listing or user profile, or contact our support team directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="bg-blue-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Trust Indicators to Look For</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Verified Users</h3>
                    <p class="text-gray-600">Look for users with verified email and phone numbers</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Positive Reviews</h3>
                    <p class="text-gray-600">Check user ratings and reviews from previous transactions</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Clear Photos</h3>
                    <p class="text-gray-600">High-quality photos showing the item from multiple angles</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
