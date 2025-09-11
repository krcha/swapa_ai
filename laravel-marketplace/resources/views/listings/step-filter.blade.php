@extends('layouts.app')

@section('title', 'Find Your Perfect Phone - Step by Step')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('messages.filtering.find_perfect_phone') }}</h1>
                <p class="text-gray-600">{{ __('messages.filtering.step_by_step_guide') }}</p>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 1 ? 'text-blue-600' : 'text-gray-500' }}">
                            {{ __('messages.filtering.carrier_status') }}
                        </span>
                    </div>
                    <div class="w-8 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 2 ? 'text-blue-600' : 'text-gray-500' }}">
                            @if($carrierStatus === 'locked')
                                {{ __('messages.filtering.carrier') }}
                            @else
                                {{ __('messages.filtering.brand') }}
                            @endif
                        </span>
                    </div>
                    <div class="w-8 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 3 ? 'text-blue-600' : 'text-gray-500' }}">
                            @if($carrierStatus === 'locked')
                                {{ __('messages.filtering.brand') }}
                            @else
                                {{ __('messages.filtering.model') }}
                            @endif
                        </span>
                    </div>
                    <div class="w-8 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $step >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            4
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 4 ? 'text-blue-600' : 'text-gray-500' }}">
                            @if($carrierStatus === 'locked')
                                {{ __('messages.filtering.model') }}
                            @else
                                {{ __('messages.filtering.results') }}
                            @endif
                        </span>
                    </div>
                    @if($carrierStatus === 'locked')
                    <div class="w-8 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $step >= 5 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            5
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 5 ? 'text-blue-600' : 'text-gray-500' }}">
                            {{ __('messages.filtering.results') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($step == 1)
            <!-- Step 1: Carrier Status Selection -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step1_title') }}</h2>
                <p class="text-gray-600 mb-8">{{ __('messages.filtering.step1_description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <!-- Unlocked Option -->
                <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer" 
                     onclick="selectCarrierStatus('unlocked')">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.filtering.unlocked') }}</h3>
                        <p class="text-gray-600">{{ __('messages.filtering.unlocked_description') }}</p>
                    </div>
                </div>

                <!-- Locked Option -->
                <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer" 
                     onclick="selectCarrierStatus('locked')">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.filtering.locked') }}</h3>
                        <p class="text-gray-600">{{ __('messages.filtering.locked_description') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($step == 2)
            <!-- Step 2: Carrier or Brand Selection -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    @if($carrierStatus == 'locked')
                        {{ __('messages.filtering.step2_locked_title') }}
                    @else
                        {{ __('messages.filtering.step2_unlocked_title') }}
                    @endif
                </h2>
                <p class="text-gray-600 mb-8">
                    @if($carrierStatus == 'locked')
                        {{ __('messages.filtering.step2_locked_description') }}
                    @else
                        {{ __('messages.filtering.step2_unlocked_description') }}
                    @endif
                </p>
            </div>

            @if($carrierStatus == 'locked')
                <!-- Serbian Carriers -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    @foreach($serbianCarriers as $carrier)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6 text-center" 
                             onclick="selectCarrier('{{ $carrier['code'] }}')">
                            <div class="w-12 h-12 mx-auto mb-3">
                                <img src="{{ $carrier['logo'] }}" alt="{{ $carrier['name'] }}" class="w-full h-full object-contain">
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $carrier['name'] }}</h3>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Brands for Unlocked -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    @foreach($brands as $brand)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6 text-center" 
                             onclick="selectBrand('{{ $brand['code'] }}')">
                            <div class="w-12 h-12 mx-auto mb-3">
                                <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" class="w-full h-full object-contain">
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $brand['name'] }}</h3>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        @if($step == 3)
            @if($carrierStatus == 'locked')
                <!-- Step 3: Brand Selection for Locked Phones -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step2_unlocked_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step2_unlocked_description') }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    @foreach($brands as $brandOption)
                        <form method="GET" class="block">
                            <input type="hidden" name="step" value="4">
                            <input type="hidden" name="carrier_status" value="{{ $carrierStatus }}">
                            <input type="hidden" name="carrier" value="{{ $carrier }}">
                            <input type="hidden" name="brand" value="{{ $brandOption['code'] }}">
                            <button type="submit" class="w-full p-6 bg-white rounded-xl border-2 border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all duration-200 text-center group">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-blue-50">
                                    <i class="fas fa-mobile-alt text-2xl text-gray-600 group-hover:text-blue-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $brandOption['name'] }}</h3>
                                <p class="text-sm text-gray-600">Phone brand</p>
                            </button>
                        </form>
                    @endforeach
                </div>
            @else
                <!-- Step 3: Model Selection for Unlocked Phones -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step3_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step3_description') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-6xl mx-auto">
                    @foreach($topModels as $model)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6" 
                             onclick="selectModel('{{ $model['code'] }}')">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16">
                                    <img src="{{ $model['image'] }}" alt="{{ $model['name'] }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $model['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $model['description'] }}</p>
                                    <p class="text-sm text-blue-600 font-medium">{{ $model['price_range'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        @if($step == 4)
            @if($carrierStatus == 'locked')
                <!-- Step 4: Model Selection for Locked Phones -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step3_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step3_description') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-6xl mx-auto">
                    @foreach($topModels as $model)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6" 
                             onclick="selectModel('{{ $model['code'] }}')">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16">
                                    <img src="{{ $model['image'] }}" alt="{{ $model['name'] }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $model['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $model['description'] }}</p>
                                    <p class="text-sm text-blue-600 font-medium">{{ $model['price_range'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Step 4: Results for Unlocked Phones - Show Phone Listings -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step4_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step4_description') }}</p>
                </div>

                @if(isset($listings) && $listings->count() > 0)
                    <!-- Results Summary -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('messages.filtering.results_summary') }}</h3>
                                <p class="text-gray-600">
                                    {{ $listings->count() }} {{ __('messages.filtering.listings_found') }} • 
                                    {{ __('messages.filtering.price_range') }}: ${{ $minPrice }}-${{ $maxPrice }}
                                </p>
                            </div>
                            <button onclick="resetFilters()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                {{ __('messages.filtering.reset_filters') }}
                            </button>
                        </div>
                    </div>

                    <!-- Swappa-Style Listings Table -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Available Listings</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600">Showing {{ $listings->firstItem() }}-{{ $listings->lastItem() }} of {{ $listings->total() }}</span>
                                    <span class="text-sm text-gray-600">•</span>
                                    <span class="text-sm text-gray-600">${{ $minPrice }}-${{ $maxPrice }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pics</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrier</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Storage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Battery</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seller</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($listings as $index => $listing)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <!-- Row Number -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listings->firstItem() + $index }}
                                            </td>
                                            
                                            <!-- Price -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-lg font-bold text-green-600">${{ number_format($listing->price) }}</div>
                                            </td>
                                            
                                            <!-- Device Image -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                    @if($listing->images && count($listing->images) > 0)
                                                        <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}" 
                                                             alt="{{ $listing->title }}" 
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            
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
                                                {{ $listing->brand->name ?? 'N/A' }}
                                            </td>
                                            
                                            <!-- Condition -->
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
                                            
                                            <!-- Battery Health -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $listing->battery_health ?? 'N/A' }}%
                                            </td>
                                            
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
                                            
                                            <!-- Shipping -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Free
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

                        <!-- Pagination -->
                        @if($listings->hasPages())
                            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                                {{ $listings->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-search text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.filtering.no_results') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('messages.filtering.no_results_description') }}</p>
                        <button onclick="resetFilters()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            {{ __('messages.filtering.try_again') }}
                        </button>
                    </div>
                @endif
            @endif
        @endif

        @if($step == 5)
            <!-- Step 5: Results for Locked Phones - Show Phone Listings -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step4_title') }}</h2>
                <p class="text-gray-600 mb-8">{{ __('messages.filtering.step4_description') }}</p>
            </div>

            @if(isset($listings) && $listings->count() > 0)
                <!-- Results Summary -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('messages.filtering.results_summary') }}</h3>
                            <p class="text-gray-600">
                                {{ $listings->count() }} {{ __('messages.filtering.listings_found') }} • 
                                {{ __('messages.filtering.price_range') }}: ${{ $minPrice }}-${{ $maxPrice }}
                            </p>
                        </div>
                        <button onclick="resetFilters()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            {{ __('messages.filtering.reset_filters') }}
                        </button>
                    </div>
                </div>

                <!-- Swappa-Style Listings Table -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Available Listings</h3>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">Showing {{ $listings->firstItem() }}-{{ $listings->lastItem() }} of {{ $listings->total() }}</span>
                                <span class="text-sm text-gray-600">•</span>
                                <span class="text-sm text-gray-600">${{ $minPrice }}-${{ $maxPrice }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pics</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Storage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Battery</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seller</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($listings as $index => $listing)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <!-- Row Number -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $listings->firstItem() + $index }}
                                        </td>
                                        
                                        <!-- Price -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-lg font-bold text-green-600">${{ number_format($listing->price) }}</div>
                                        </td>
                                        
                                        <!-- Device Image -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                @if($listing->images && count($listing->images) > 0)
                                                    <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}" 
                                                         alt="{{ $listing->title }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                        <i class="fas fa-mobile-alt text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        
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
                                            {{ $listing->brand->name ?? 'N/A' }}
                                        </td>
                                        
                                        <!-- Condition -->
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
                                        
                                        <!-- Battery Health -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $listing->battery_health ?? 'N/A' }}%
                                        </td>
                                        
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
                                        
                                        <!-- Shipping -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Free
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

                    <!-- Pagination -->
                    @if($listings->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $listings->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.filtering.no_results') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('messages.filtering.no_results_description') }}</p>
                    <button onclick="resetFilters()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        {{ __('messages.filtering.try_again') }}
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function selectCarrierStatus(status) {
    window.location.href = `{{ route('listings.step-filter') }}?step=2&carrier_status=${status}`;
}

function selectCarrier(carrier) {
    const url = new URL(window.location);
    url.searchParams.set('carrier', carrier);
    url.searchParams.set('step', '3');
    window.location.href = url.toString();
}

function selectBrand(brand) {
    const url = new URL(window.location);
    url.searchParams.set('brand', brand);
    url.searchParams.set('step', '3');
    window.location.href = url.toString();
}

function selectModel(model) {
    const url = new URL(window.location);
    url.searchParams.set('model', model);
    url.searchParams.set('step', '4');
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = '{{ route('listings.step-filter') }}';
}
</script>
@endsection
