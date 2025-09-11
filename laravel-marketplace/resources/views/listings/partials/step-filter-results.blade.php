<!-- Results Section -->
<div class="text-center mb-8">
    <!-- Brand and Model Display -->
    @if(request('brand') && request('model'))
        @php
            $brandName = ucfirst(request('brand'));
            $modelName = str_replace('-', ' ', request('model'));
            $modelName = ucwords($modelName);
            // Handle special cases like iPhone, iPad, etc.
            $modelName = str_replace('Iphone', 'iPhone', $modelName);
            $modelName = str_replace('Ipad', 'iPad', $modelName);
        @endphp
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-blue-900 mb-1">
                        {{ $brandName }} {{ $modelName }}
                    </h3>
                    <p class="text-sm text-blue-700">
                        @if(request('carrier_status') === 'unlocked')
                            Unlocked Devices
                        @elseif(request('carrier_status') === 'locked' && request('carrier'))
                            {{ strtoupper(request('carrier')) }} Locked Devices
                        @else
                            {{ ucfirst(request('carrier_status')) }} Devices
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @elseif(request('brand'))
        @php
            $brandName = ucfirst(request('brand'));
        @endphp
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-blue-900 mb-1">
                        {{ $brandName }} Devices
                    </h3>
                    <p class="text-sm text-blue-700">
                        @if(request('carrier_status') === 'unlocked')
                            Unlocked Devices
                        @elseif(request('carrier_status') === 'locked' && request('carrier'))
                            {{ strtoupper(request('carrier')) }} Locked Devices
                        @else
                            {{ ucfirst(request('carrier_status')) }} Devices
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    <p class="text-gray-600 mb-8">{{ __('messages.filtering.step4_description') }}</p>
</div>

           @if(isset($listings) && $listings->count() > 0)
               <!-- Filter Options -->
               <div class="bg-gray-50 rounded-lg p-6 mb-8">
                   <form method="GET" action="{{ request()->url() }}">
                       <!-- Hidden fields to preserve current step parameters -->
                       <input type="hidden" name="step" value="{{ request('step') }}">
                       <input type="hidden" name="carrier_status" value="{{ request('carrier_status') }}">
                       <input type="hidden" name="carrier" value="{{ request('carrier') }}">
                       <input type="hidden" name="brand" value="{{ request('brand') }}">
                       <input type="hidden" name="model" value="{{ request('model') }}">
                       
                       <!-- Top Row - Dropdown Filters -->
                       <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                           <!-- Carrier Status Filter -->
                           <div>
                               <label class="block text-sm font-medium text-gray-700 mb-2">Carrier Status</label>
                               <select name="carrier_status" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('carrier_status') ? 'border-green-500 bg-green-50' : '' }}">
                                   <option value="">All Devices</option>
                                   <option value="unlocked" {{ request('carrier_status') == 'unlocked' ? 'selected' : '' }}>Unlocked</option>
                                   <option value="locked" {{ request('carrier_status') == 'locked' ? 'selected' : '' }}>Locked</option>
                               </select>
                           </div>
                           
                           <!-- Color Filter -->
                           <div>
                               <label class="block text-sm font-medium text-gray-700 mb-2">Filter Color</label>
                               <select name="color" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('color') ? 'border-green-500 bg-green-50' : '' }}">
                                   <option value="">All Colors</option>
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
                               <select name="storage" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('storage') ? 'border-green-500 bg-green-50' : '' }}">
                                   <option value="">All Storage</option>
                                   <option value="64GB" {{ request('storage') == '64GB' ? 'selected' : '' }}>64GB</option>
                                   <option value="128GB" {{ request('storage') == '128GB' ? 'selected' : '' }}>128GB</option>
                                   <option value="256GB" {{ request('storage') == '256GB' ? 'selected' : '' }}>256GB</option>
                                   <option value="512GB" {{ request('storage') == '512GB' ? 'selected' : '' }}>512GB</option>
                                   <option value="1TB" {{ request('storage') == '1TB' ? 'selected' : '' }}>1TB</option>
                               </select>
                           </div>
                           
                           <!-- Sort By Filter -->
                           <div>
                               <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                               <select name="sort" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('sort') ? 'border-green-500 bg-green-50' : '' }}">
                                   <option value="">Default</option>
                                   <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                   <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                   <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                                   <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                               </select>
                           </div>
                           
                           <!-- Condition Filter -->
                           <div>
                               <label class="block text-sm font-medium text-gray-700 mb-2">Filter Condition</label>
                               <select name="condition" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('condition') ? 'border-green-500 bg-green-50' : '' }}">
                                   <option value="">All Conditions</option>
                                   <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                                   <option value="excellent" {{ request('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                   <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                   <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                               </select>
                           </div>
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
                   </form>
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
                                <div class="text-lg font-bold text-green-600">${{ number_format($listing->price) }}</div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
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
        // Reset all dropdowns to first option
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
            if (select.value && select.value !== '') {
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

function resetFilters() {
    window.location.href = '{{ request()->url() }}';
}
</script>
