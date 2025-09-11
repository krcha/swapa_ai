@extends('layouts.app')

@section('title', $listing->title . ' - Verified Device')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('listings.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Devices</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('listings.index', ['brand' => $listing->brand->name]) }}" 
                       class="ml-1 text-blue-600 hover:text-blue-800 md:ml-2 bg-blue-50 px-2 py-1 rounded-md transition-colors">
                        {{ $listing->brand->name }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ \Illuminate\Support\Str::limit($listing->title, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Device Images - Swappa Style -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-8">
                @if($listing->images && count($listing->images) > 0)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}" 
                             alt="{{ $listing->title }}" 
                             class="w-full h-96 object-cover">
                        
                        <!-- Image Navigation -->
                        <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-2 transition-all">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-2 transition-all">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            1 / {{ count($listing->images) }}
                        </div>
                    </div>
                @else
                    <div class="h-96 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <span class="text-8xl text-gray-400 mb-4 block">📱</span>
                            <p class="text-gray-500 text-lg">No images available</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Device Description -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Device Description</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                </div>
            </div>

            <!-- Condition Report - Swappa Style -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Condition Report</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Overall Condition -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Overall Condition</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $listing->condition) }}</span>
                            <div class="ml-4">
                                @if($listing->condition === 'mint')
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 text-lg">✨</span>
                                    </div>
                                @elseif($listing->condition === 'good')
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 text-lg">👍</span>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <span class="text-yellow-600 text-lg">⚠️</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Device Specifications -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Specifications</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Brand:</span>
                                <span class="font-medium">{{ $listing->brand->name }}</span>
                            </div>
                            @if($listing->storage)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Storage:</span>
                                <span class="font-medium">{{ $listing->storage }}</span>
                            </div>
                            @endif
                            @if($listing->color)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Color:</span>
                                <span class="font-medium">{{ $listing->color }}</span>
                            </div>
                            @endif
                            @if($listing->battery_health)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Battery Health:</span>
                                <span class="font-medium">{{ $listing->battery_health }}%</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Condition Details -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-3">Condition Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600 mb-1">Screen</div>
                            <div class="font-semibold text-gray-900">
                                @if($listing->condition === 'mint') Perfect
                                @elseif($listing->condition === 'good') Excellent
                                @else Good
                                @endif
                            </div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600 mb-1">Body</div>
                            <div class="font-semibold text-gray-900">
                                @if($listing->condition === 'mint') No Wear
                                @elseif($listing->condition === 'good') Minor Wear
                                @else Visible Wear
                                @endif
                            </div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600 mb-1">Functionality</div>
                            <div class="font-semibold text-gray-900">Fully Working</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Devices -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Devices</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $similarListings = \App\Models\Listing::where('brand_id', $listing->brand_id)
                            ->where('id', '!=', $listing->id)
                            ->where('status', 'active')
                            ->limit(4)
                            ->get();
                    @endphp
                    
                    @forelse($similarListings as $similar)
                        <div class="flex bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <span class="text-2xl">📱</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $similar->title }}</h3>
                                <p class="text-blue-600 font-bold">€{{ number_format($similar->price, 0) }}</p>
                                <p class="text-sm text-gray-600 truncate">{{ \Illuminate\Support\Str::limit($similar->description, 60) }}</p>
                                <a href="{{ route('listings.show', $similar) }}" 
                                   class="inline-block mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View Device →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8">
                            <p class="text-gray-600">No similar devices found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Price and Actions - Swappa Style -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6 sticky top-24">
                <div class="text-center mb-6">
                    <div class="text-4xl font-bold text-gray-900 mb-2">€{{ number_format($listing->price, 0) }}</div>
                    <div class="text-sm text-gray-500">Free shipping included</div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3" x-data="contactSeller()">
                    @if($listing->user->phone)
                        <div>
                            <button @click="showPhone = !showPhone; trackContactClick()" 
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span x-text="showPhone ? 'Hide Phone' : 'Contact Seller'"></span>
                            </button>
                            
                            <!-- Phone Number Display -->
                            <div x-show="showPhone" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="mt-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="text-center">
                                    <p class="text-sm text-green-700 mb-2">Seller's Phone Number:</p>
                                    <p class="text-2xl font-bold text-green-900 mb-3">{{ $listing->user->phone }}</p>
                                    <div class="flex space-x-2 justify-center">
                                        <a href="tel:{{ $listing->user->phone }}" 
                                           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            Call Now
                                        </a>
                                        <button @click="navigator.clipboard.writeText('{{ $listing->user->phone }}'); alert('Phone number copied to clipboard!')" 
                                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($listing->user->email)
                        <a href="mailto:{{ $listing->user->email }}" 
                           class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Send Message
                        </a>
                    @endif

                    <button class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Save to Favorites
                    </button>

                    <button class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Share Device
                    </button>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">Buyer Protection</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Device authenticity verified</li>
                        <li>• 30-day return guarantee</li>
                        <li>• Secure payment processing</li>
                        <li>• Free shipping included</li>
                    </ul>
                </div>
            </div>

            <!-- Seller Information - Swappa Style -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Seller Information</h3>
                
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-lg">{{ substr($listing->user->first_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $listing->user->first_name }} {{ $listing->user->last_name }}</h4>
                        <div class="flex items-center space-x-2">
                            @if($listing->user->is_sms_verified)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Verified</span>
                            @endif
                            <span class="text-sm text-gray-500">Member since {{ $listing->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email Verified:</span>
                        <span class="font-medium {{ $listing->user->is_email_verified ? 'text-green-600' : 'text-red-600' }}">
                            {{ $listing->user->is_email_verified ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">SMS Verified:</span>
                        <span class="font-medium {{ $listing->user->is_sms_verified ? 'text-green-600' : 'text-red-600' }}">
                            {{ $listing->user->is_sms_verified ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function contactSeller() {
    return {
        showPhone: false,
        trackContactClick() {
            // Track the click
            fetch('{{ route('contact.click.track', $listing) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(error => {
                console.log('Click tracking failed:', error);
            });
        }
    }
}
</script>
@endsection