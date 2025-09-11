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
                    <a href="{{ route('listings.step-filter', ['step' => 1]) }}" class="flex items-center hover:opacity-80 transition-opacity cursor-pointer">
                        <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $step >= 1 ? 'text-blue-600' : 'text-gray-500' }}">
                            {{ __('messages.filtering.carrier_status') }}
                        </span>
                    </a>
                    <div class="w-8 h-px bg-gray-300"></div>
                    @if($step >= 2 || $carrierStatus)
                        <a href="{{ route('listings.step-filter', ['step' => 2, 'carrier_status' => $carrierStatus]) }}" class="flex items-center hover:opacity-80 transition-opacity cursor-pointer">
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
                        </a>
                    @else
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('messages.filtering.carrier') }}/{{ __('messages.filtering.brand') }}
                            </span>
                        </div>
                    @endif
                    <div class="w-8 h-px bg-gray-300"></div>
                    @if($step >= 3 || ($carrierStatus && $brand))
                        <a href="{{ route('listings.step-filter', ['step' => 3, 'carrier_status' => $carrierStatus, 'carrier' => $carrier, 'brand' => $brand]) }}" class="flex items-center hover:opacity-80 transition-opacity cursor-pointer">
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
                        </a>
                    @else
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('messages.filtering.brand') }}/{{ __('messages.filtering.model') }}
                            </span>
                        </div>
                    @endif
                    @if($carrierStatus === 'locked')
                    <div class="w-8 h-px bg-gray-300"></div>
                    <!-- Step 4: Model (locked) -->
                    @if($step >= 4 || ($carrierStatus && $brand))
                        <a href="{{ route('listings.step-filter', ['step' => 4, 'carrier_status' => $carrierStatus, 'carrier' => $carrier, 'brand' => $brand]) }}" class="flex items-center hover:opacity-80 transition-opacity cursor-pointer">
                            <div class="w-8 h-8 rounded-full {{ $step >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                                4
                            </div>
                            <span class="ml-2 text-sm font-medium {{ $step >= 4 ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ __('messages.filtering.model') }}
                            </span>
                        </a>
                    @else
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">
                                4
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('messages.filtering.model') }}
                            </span>
                        </div>
                    @endif
                    <div class="w-8 h-px bg-gray-300"></div>
                    <!-- Step 5: Results (locked) -->
                    @if($step >= 5 || ($carrierStatus && $brand && $model))
                        <a href="{{ route('listings.step-filter', ['step' => 5, 'carrier_status' => $carrierStatus, 'carrier' => $carrier, 'brand' => $brand, 'model' => $model]) }}" class="flex items-center hover:opacity-80 transition-opacity cursor-pointer">
                            <div class="w-8 h-8 rounded-full {{ $step >= 5 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                                5
                            </div>
                            <span class="ml-2 text-sm font-medium {{ $step >= 5 ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ __('messages.filtering.results') }}
                            </span>
                        </a>
                    @else
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">
                                5
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('messages.filtering.results') }}
                            </span>
                        </div>
                    @endif
                    @else
                    <div class="w-8 h-px bg-gray-300"></div>
                    <!-- Step 4: Results (unlocked) -->
                    @if($step >= 4 || ($carrierStatus && $brand && $model))
                        <a href="{{ route('listings.step-filter', ['step' => 4, 'carrier_status' => $carrierStatus, 'brand' => $brand, 'model' => $model]) }}" class="flex items-center hover:opacity-80 transition-opacity cursor-pointer">
                            <div class="w-8 h-8 rounded-full {{ $step >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                                4
                            </div>
                            <span class="ml-2 text-sm font-medium {{ $step >= 4 ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ __('messages.filtering.results') }}
                            </span>
                        </a>
                    @else
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-medium">
                                4
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('messages.filtering.results') }}
                            </span>
                        </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Step 1: Carrier Status Selection -->
        @if($step == 1)
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
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.filtering.locked') }}</h3>
                    <p class="text-gray-600">{{ __('messages.filtering.locked_description') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 2: Carrier/Brand Selection -->
        @if($step == 2)
            @if($carrierStatus == 'locked')
                <!-- Serbian Carriers -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step2_locked_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step2_locked_description') }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    @foreach($serbianCarriers as $carrier)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6 text-center" 
                             onclick="selectCarrier('{{ $carrier['code'] }}')">
                            <div class="w-12 h-12 mx-auto mb-3">
                                <i class="fas fa-mobile-alt text-2xl text-gray-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $carrier['name'] }}</h3>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Brands for Unlocked -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step2_unlocked_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step2_unlocked_description') }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    @foreach($brands as $brand)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6 text-center" 
                             onclick="selectBrand('{{ $brand['code'] }}')">
                            <div class="w-12 h-12 mx-auto mb-3">
                                <i class="fas fa-mobile-alt text-2xl text-gray-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $brand['name'] }}</h3>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <!-- Step 3: Model/Brand Selection -->
        @if($step == 3)
            @if($carrierStatus == 'locked')
                <!-- Brand Selection for Locked Phones -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.filtering.step2_unlocked_title') }}</h2>
                    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step2_unlocked_description') }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    @foreach($brands as $brand)
                        <div class="bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors cursor-pointer p-6 text-center" 
                             onclick="selectBrand('{{ $brand['code'] }}')">
                            <div class="w-12 h-12 mx-auto mb-3">
                                <i class="fas fa-mobile-alt text-2xl text-gray-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $brand['name'] }}</h3>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Model Selection for Unlocked Phones -->
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
                                    <i class="fas fa-mobile-alt text-3xl text-gray-600"></i>
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

        <!-- Step 4: Model Selection for Locked Phones OR Results for Unlocked -->
        @if($step == 4)
            @if($carrierStatus == 'locked')
                <!-- Model Selection for Locked Phones -->
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
                                    <i class="fas fa-mobile-alt text-3xl text-gray-600"></i>
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
                <!-- Results for Unlocked Phones -->
                @include('listings.partials.step-filter-results')
            @endif
        @endif

        <!-- Step 5: Results for Locked Phones -->
        @if($step == 5)
            @include('listings.partials.step-filter-results')
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
    if (window.location.search.includes('carrier_status=locked')) {
        url.searchParams.set('step', '4');
    } else {
        url.searchParams.set('step', '3');
    }
    window.location.href = url.toString();
}

function selectModel(model) {
    const url = new URL(window.location);
    url.searchParams.set('model', model);
    if (window.location.search.includes('carrier_status=locked')) {
        url.searchParams.set('step', '5');
    } else {
        url.searchParams.set('step', '4');
    }
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = '{{ route('listings.step-filter') }}';
}
</script>
@endsection
