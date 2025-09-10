@extends('layouts.app')

@section('title', 'Create Listing - PhoneMarket')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Create New Listing</h1>
            <p class="mt-1 text-sm text-gray-600">List your phone for sale (1 token required)</p>
        </div>

        <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" 
              class="p-6 space-y-6" x-data="listingForm()">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('title') border-red-500 @enderror"
                               value="{{ old('title') }}" placeholder="e.g., iPhone 13 Pro Max 256GB Space Gray">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                        <select name="category_id" id="category_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand *</label>
                        <select name="brand_id" id="brand_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('brand_id') border-red-500 @enderror">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (€) *</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">€</span>
                            </div>
                            <input type="number" name="price" id="price" step="0.01" min="0" required
                                   class="pl-8 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('price') border-red-500 @enderror"
                                   value="{{ old('price') }}" placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700">Condition *</label>
                        <select name="condition" id="condition" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('condition') border-red-500 @enderror">
                            <option value="">Select Condition</option>
                            <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                            <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                        @error('condition')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('description') border-red-500 @enderror"
                              placeholder="Describe your phone's condition, any issues, accessories included...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Phone Details -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900">Phone Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Storage -->
                    <div>
                        <label for="storage" class="block text-sm font-medium text-gray-700">Storage</label>
                        <select name="storage" id="storage"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Storage</option>
                            <option value="32GB" {{ old('storage') == '32GB' ? 'selected' : '' }}>32GB</option>
                            <option value="64GB" {{ old('storage') == '64GB' ? 'selected' : '' }}>64GB</option>
                            <option value="128GB" {{ old('storage') == '128GB' ? 'selected' : '' }}>128GB</option>
                            <option value="256GB" {{ old('storage') == '256GB' ? 'selected' : '' }}>256GB</option>
                            <option value="512GB" {{ old('storage') == '512GB' ? 'selected' : '' }}>512GB</option>
                            <option value="1TB" {{ old('storage') == '1TB' ? 'selected' : '' }}>1TB</option>
                        </select>
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                        <input type="text" name="color" id="color"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               value="{{ old('color') }}" placeholder="e.g., Space Gray, Silver, Gold">
                    </div>

                    <!-- Battery Health -->
                    <div>
                        <label for="battery_health" class="block text-sm font-medium text-gray-700">Battery Health (%)</label>
                        <input type="number" name="battery_health" id="battery_health" min="0" max="100"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               value="{{ old('battery_health') }}" placeholder="85">
                    </div>

                    <!-- Carrier -->
                    <div>
                        <label for="carrier" class="block text-sm font-medium text-gray-700">Carrier</label>
                        <select name="carrier" id="carrier"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Select Carrier</option>
                            <option value="Unlocked" {{ old('carrier') == 'Unlocked' ? 'selected' : '' }}>Unlocked</option>
                            <option value="Telenor" {{ old('carrier') == 'Telenor' ? 'selected' : '' }}>Telenor</option>
                            <option value="MTS" {{ old('carrier') == 'MTS' ? 'selected' : '' }}>MTS</option>
                            <option value="Vip" {{ old('carrier') == 'Vip' ? 'selected' : '' }}>Vip</option>
                            <option value="A1" {{ old('carrier') == 'A1' ? 'selected' : '' }}>A1</option>
                        </select>
                    </div>
                </div>

                <!-- Contact Preference -->
                <div>
                    <label for="contact_preference" class="block text-sm font-medium text-gray-700">Contact Preference *</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="contact_phone" name="contact_preference" type="radio" value="phone" 
                                   class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300" 
                                   {{ old('contact_preference') == 'phone' ? 'checked' : '' }}>
                            <label for="contact_phone" class="ml-2 block text-sm text-gray-900">Phone only</label>
                        </div>
                        <div class="flex items-center">
                            <input id="contact_email" name="contact_preference" type="radio" value="email" 
                                   class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300"
                                   {{ old('contact_preference') == 'email' ? 'checked' : '' }}>
                            <label for="contact_email" class="ml-2 block text-sm text-gray-900">Email only</label>
                        </div>
                        <div class="flex items-center">
                            <input id="contact_both" name="contact_preference" type="radio" value="both" 
                                   class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300"
                                   {{ old('contact_preference') == 'both' ? 'checked' : '' }}>
                            <label for="contact_both" class="ml-2 block text-sm text-gray-900">Both phone and email</label>
                        </div>
                    </div>
                    @error('contact_preference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Images -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900">Photos (3-10 images required)</h3>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <label for="images" class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                    Upload photos
                                </span>
                                <span class="mt-1 block text-sm text-gray-500">
                                    PNG, JPG up to 2MB each
                                </span>
                            </label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" 
                                   class="sr-only" @change="handleImageUpload($event)">
                        </div>
                    </div>
                </div>

                <!-- Image Preview -->
                <div x-show="images.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="(image, index) in images" :key="index">
                        <div class="relative">
                            <img :src="image.preview" :alt="`Preview ${index + 1}`" 
                                 class="w-full h-32 object-cover rounded-lg">
                            <button type="button" @click="removeImage(index)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">
                                ×
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('listings.index') }}"
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-primary-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
                        :disabled="images.length < 3">
                    Create Listing (1 Token)
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function listingForm() {
    return {
        images: [],

        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.images.push({
                        file: file,
                        preview: e.target.result
                    });
                };
                reader.readAsDataURL(file);
            });
        },

        removeImage(index) {
            this.images.splice(index, 1);
        }
    }
}
</script>
@endpush
@endsection
