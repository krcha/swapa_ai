@extends('layouts.app')

@section('title', 'Create New Listing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                            <i class="fas fa-home mr-1"></i>Home
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <span class="text-sm font-medium text-gray-900">Create Listing</span>
                    </li>
                </ol>
            </nav>
            
            <!-- Page Title -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Sell Your Device</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Join thousands of successful sellers on Serbia's most trusted marketplace</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">1</div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Device Type</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Details</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Photos</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold">4</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Review</span>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form Container -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">What are you selling?</h2>
                        <p class="text-blue-100 mt-1">Select the type of device you want to list</p>
                    </div>

                    <!-- Form Content -->
                    <div class="p-6">
                        <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" 
                              x-data="listingForm()" @submit.prevent="submitForm">
                            @csrf

                            <!-- Step 1: Device Type Selection -->
                            <div x-show="currentStep === 1" x-transition class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <!-- Phone Option -->
                                    <div @click="selectDeviceType('phone')" 
                                         :class="selectedDeviceType === 'phone' ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                         class="relative cursor-pointer rounded-xl border-2 border-gray-200 p-6 transition-all duration-200">
                                        <div class="text-center">
                                            <div class="mx-auto w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                                <i class="fas fa-mobile-alt text-blue-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone</h3>
                                            <p class="text-sm text-gray-600">Smartphones and mobile devices</p>
                                        </div>
                                        <div x-show="selectedDeviceType === 'phone'" class="absolute top-2 right-2">
                                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Charger Option -->
                                    <div @click="selectDeviceType('charger')" 
                                         :class="selectedDeviceType === 'charger' ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                         class="relative cursor-pointer rounded-xl border-2 border-gray-200 p-6 transition-all duration-200">
                                        <div class="text-center">
                                            <div class="mx-auto w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                                                <i class="fas fa-bolt text-green-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Charger</h3>
                                            <p class="text-sm text-gray-600">Charging cables and adapters</p>
                                        </div>
                                        <div x-show="selectedDeviceType === 'charger'" class="absolute top-2 right-2">
                                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Case Option -->
                                    <div @click="selectDeviceType('case')" 
                                         :class="selectedDeviceType === 'case' ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                         class="relative cursor-pointer rounded-xl border-2 border-gray-200 p-6 transition-all duration-200">
                                        <div class="text-center">
                                            <div class="mx-auto w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                                                <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Case</h3>
                                            <p class="text-sm text-gray-600">Phone cases and covers</p>
                                        </div>
                                        <div x-show="selectedDeviceType === 'case'" class="absolute top-2 right-2">
                                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Headphones Option -->
                                    <div @click="selectDeviceType('headphones')" 
                                         :class="selectedDeviceType === 'headphones' ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                         class="relative cursor-pointer rounded-xl border-2 border-gray-200 p-6 transition-all duration-200">
                                        <div class="text-center">
                                            <div class="mx-auto w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                                                <i class="fas fa-headphones text-orange-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Headphones</h3>
                                            <p class="text-sm text-gray-600">Earphones and headphones</p>
                                        </div>
                                        <div x-show="selectedDeviceType === 'headphones'" class="absolute top-2 right-2">
                                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Screen Protector Option -->
                                    <div @click="selectDeviceType('screen_protector')" 
                                         :class="selectedDeviceType === 'screen_protector' ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                         class="relative cursor-pointer rounded-xl border-2 border-gray-200 p-6 transition-all duration-200">
                                        <div class="text-center">
                                            <div class="mx-auto w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                                                <i class="fas fa-mobile-alt text-indigo-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Screen Protector</h3>
                                            <p class="text-sm text-gray-600">Screen protectors and films</p>
                                        </div>
                                        <div x-show="selectedDeviceType === 'screen_protector'" class="absolute top-2 right-2">
                                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Brand Selection (for phones) -->
                            <div x-show="currentStep === 2 && selectedDeviceType === 'phone'" x-transition class="space-y-6">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Choose Your Brand</h3>
                                    <p class="text-gray-600">Select the brand of your phone</p>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                    <template x-for="brand in phoneBrands" :key="brand.code">
                                        <div @click="selectBrand(brand.code)" 
                                             :class="selectedBrand === brand.code ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                             class="cursor-pointer rounded-lg border-2 border-gray-200 p-4 text-center transition-all duration-200">
                                            <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i :class="brand.icon" class="text-2xl text-gray-600"></i>
                                            </div>
                                            <h4 class="font-medium text-gray-900" x-text="brand.name"></h4>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Step 3: Model Selection (for phones) -->
                            <div x-show="currentStep === 3 && selectedDeviceType === 'phone'" x-transition class="space-y-6">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Choose Your Model</h3>
                                    <p class="text-gray-600">Select your specific phone model</p>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="model in getModelsForBrand()" :key="model.code">
                                        <div @click="selectModel(model)" 
                                             :class="selectedModel && selectedModel.code === model.code ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-50'"
                                             class="cursor-pointer rounded-lg border-2 border-gray-200 p-4 transition-all duration-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-medium text-gray-900" x-text="model.name"></h4>
                                                    <p class="text-sm text-gray-600" x-text="model.description"></p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm font-medium text-blue-600" x-text="model.count + ' available'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Step 4: Listing Details -->
                            <div x-show="currentStep === 4" x-transition class="space-y-6">
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Listing Details</h3>
                                    <p class="text-gray-600">Provide information about your device</p>
                                </div>

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                    <input type="text" name="title" x-model="formData.title" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                           placeholder="e.g., iPhone 14 Pro Max 256GB">
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                    <textarea name="description" x-model="formData.description" required rows="4"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                              placeholder="Describe your device's condition, any issues, and why someone should buy it..."></textarea>
                                </div>

                                <!-- Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (€) *</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">€</span>
                                        </div>
                                        <input type="number" name="price" x-model="formData.price" required min="0" step="0.01"
                                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                               placeholder="0.00">
                                    </div>
                                </div>

                                <!-- Condition -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition *</label>
                                    <select name="condition" x-model="formData.condition" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                        <option value="">Select condition</option>
                                        <option value="new">New - Never used</option>
                                        <option value="like_new">Like New - Barely used</option>
                                        <option value="good">Good - Minor wear</option>
                                        <option value="fair">Fair - Visible wear</option>
                                    </select>
                                </div>

                                <!-- Phone-specific fields -->
                                <template x-if="selectedDeviceType === 'phone'">
                                    <div class="space-y-4">
                                        <!-- Battery Health -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Battery Health (%) *</label>
                                            <input type="number" name="battery_percentage" x-model="formData.battery_percentage" required min="0" max="100"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                                   placeholder="e.g., 85">
                                        </div>

                                        <!-- Carrier -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                                            <select name="carrier" x-model="formData.carrier"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                                <option value="">Select Carrier (Optional)</option>
                                                <option value="telenor">Telenor</option>
                                                <option value="vip">VIP</option>
                                                <option value="mts">MTS</option>
                                                <option value="yettel">Yettel</option>
                                                <option value="unlocked">Unlocked</option>
                                            </select>
                                        </div>

                                        <!-- Screen Condition -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Screen Condition *</label>
                                            <select name="screen_condition" x-model="formData.screen_condition" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                                <option value="">Select Screen Condition</option>
                                                <option value="perfect">Perfect - No scratches or cracks</option>
                                                <option value="good">Good - Minor scratches, not visible when screen is on</option>
                                                <option value="fair">Fair - Visible scratches or minor cracks</option>
                                                <option value="poor">Poor - Major scratches or cracks affecting use</option>
                                            </select>
                                        </div>

                                        <!-- Body Condition -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Body Condition *</label>
                                            <select name="body_condition" x-model="formData.body_condition" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                                <option value="">Select Body Condition</option>
                                                <option value="perfect">Perfect - No wear or damage</option>
                                                <option value="good">Good - Minor wear, barely noticeable</option>
                                                <option value="fair">Fair - Visible wear but functional</option>
                                                <option value="poor">Poor - Significant wear or damage</option>
                                            </select>
                                        </div>

                                        <!-- Functionality -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Functionality *</label>
                                            <select name="functionality" x-model="formData.functionality" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                                <option value="">Select Functionality</option>
                                                <option value="fully_working">Fully Working - All features work perfectly</option>
                                                <option value="mostly_working">Mostly Working - Minor issues, all core features work</option>
                                                <option value="partially_working">Partially Working - Some features don't work</option>
                                                <option value="needs_repair">Needs Repair - Major functionality issues</option>
                                            </select>
                                        </div>
                                    </div>
                                </template>

                                <!-- Images -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Photos *</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                        <input type="file" name="images[]" multiple accept="image/*" required
                                               class="hidden" id="image-upload" @change="handleImageUpload">
                                        <label for="image-upload" class="cursor-pointer">
                                            <i class="fas fa-camera text-4xl text-gray-400 mb-4"></i>
                                            <p class="text-lg font-medium text-gray-900 mb-2">Upload Photos</p>
                                            <p class="text-sm text-gray-600">Click to select images or drag and drop</p>
                                            <p class="text-xs text-gray-500 mt-2">Minimum 3 photos, maximum 5 photos</p>
                                        </label>
                                    </div>
                                    
                                    <!-- Image Preview -->
                                    <div x-show="imagePreviews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
                                        <template x-for="(preview, index) in imagePreviews" :key="index">
                                            <div class="relative">
                                                <img :src="preview" :alt="'Preview ' + (index + 1)" 
                                                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                                <button type="button" @click="removeImage(index)"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" name="category_id" x-model="formData.category_id">
                            <input type="hidden" name="brand_id" x-model="formData.brand_id">
                            <input type="hidden" name="model_name" x-model="formData.model_name">
                            <input type="hidden" name="model_code" x-model="formData.model_code">

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between pt-6 border-t border-gray-200">
                                <button type="button" @click="previousStep()" 
                                        x-show="currentStep > 1"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>Previous
                                </button>
                                
                                <div class="flex-1"></div>
                                
                                <button type="button" @click="nextStep()" 
                                        x-show="currentStep < 4"
                                        :disabled="!canProceed()"
                                        :class="canProceed() ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                        class="px-6 py-3 rounded-lg transition-colors">
                                    Next<i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                
                                <button type="submit" 
                                        x-show="currentStep === 4"
                                        :disabled="!canProceed()"
                                        :class="canProceed() ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                        class="px-8 py-3 rounded-lg transition-colors">
                                    <i class="fas fa-check mr-2"></i>Create Listing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Tips Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Pro Tips
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-camera text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Great Photos</h4>
                                <p class="text-sm text-gray-600">Take clear, well-lit photos from multiple angles. Show any scratches or wear.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-edit text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Honest Description</h4>
                                <p class="text-sm text-gray-600">Be honest about the condition and include all relevant details.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Competitive Pricing</h4>
                                <p class="text-sm text-gray-600">Research similar listings to set a fair, competitive price.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Marketplace Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-blue-100">Avg. listing time</span>
                            <span class="font-semibold">3 days</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Success rate</span>
                            <span class="font-semibold">94%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Required photos</span>
                            <span class="font-semibold">3-5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function listingForm() {
    return {
        currentStep: 1,
        selectedDeviceType: '',
        selectedBrand: '',
        selectedModel: null,
        imagePreviews: [],
        formData: {
            title: '',
            description: '',
            price: '',
            condition: '',
            battery_percentage: '',
            carrier: '',
            screen_condition: '',
            body_condition: '',
            functionality: '',
            category_id: '1',
            brand_id: '',
            model_name: '',
            model_code: ''
        },
        
        phoneBrands: [
            { code: 'apple', name: 'Apple iPhone', icon: 'fab fa-apple' },
            { code: 'samsung', name: 'Samsung Galaxy', icon: 'fab fa-android' },
            { code: 'google', name: 'Google Pixel', icon: 'fab fa-google' },
            { code: 'xiaomi', name: 'Xiaomi', icon: 'fas fa-mobile-alt' },
            { code: 'oneplus', name: 'OnePlus', icon: 'fas fa-mobile-alt' },
            { code: 'huawei', name: 'Huawei', icon: 'fas fa-mobile-alt' }
        ],
        
        approvedModels: {!! json_encode($approvedModels) !!},
        
        init() {
            console.log('Approved models data:', this.approvedModels);
        },
        
        selectDeviceType(type) {
            this.selectedDeviceType = type;
            this.formData.category_id = type === 'phone' ? '1' : '2';
            this.nextStep();
        },
        
        selectBrand(brandCode) {
            this.selectedBrand = brandCode;
            console.log('Selected brand:', brandCode);
            console.log('Available models:', this.getModelsForBrand());
            this.nextStep();
        },
        
        selectModel(model) {
            this.selectedModel = model;
            this.formData.model_name = model.name;
            this.formData.model_code = model.code;
            
            // Debug logging
            console.log('Selected model:', model);
            console.log('Form data after model selection:', this.formData);
            
            this.nextStep();
        },
        
        getModelsForBrand() {
            if (!this.selectedBrand || !this.approvedModels) {
                console.log('No selected brand or approved models');
                return [];
            }
            
            // Map brand codes to brand names (exact match from database)
            const brandNameMapping = {
                'apple': 'Apple iPhone',
                'samsung': 'Samsung Galaxy (S/Note)',
                'google': 'Google Pixel',
                'xiaomi': 'Xiaomi',
                'oneplus': 'OPPO (Find series / flagship)', // Using OPPO as OnePlus alternative
                'huawei': 'Huawei'
            };
            
            const brandName = brandNameMapping[this.selectedBrand];
            console.log('Looking for brand:', brandName);
            console.log('Available brands:', Object.keys(this.approvedModels));
            
            if (!brandName || !this.approvedModels[brandName]) {
                console.log('Brand not found in approved models, trying fallback');
                // Try to find any brand that contains the selected brand name
                const availableBrands = Object.keys(this.approvedModels);
                const fallbackBrand = availableBrands.find(brand => 
                    brand.toLowerCase().includes(this.selectedBrand.toLowerCase()) ||
                    this.selectedBrand.toLowerCase().includes(brand.toLowerCase())
                );
                
                if (fallbackBrand) {
                    console.log('Using fallback brand:', fallbackBrand);
                    return this.approvedModels[fallbackBrand].map(model => ({
                        code: model.model_code,
                        name: model.model_name,
                        description: model.model_name,
                        count: 0
                    }));
                }
                
                return [];
            }
            
            console.log('Found models for brand:', this.approvedModels[brandName]);
            
            // Transform the data to include count and format for display
            return this.approvedModels[brandName].map(model => ({
                code: model.model_code,
                name: model.model_name,
                description: model.model_name,
                count: 0 // We'll add count later if needed
            }));
        },
        
        nextStep() {
            if (this.canProceed()) {
                this.currentStep++;
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        canProceed() {
            switch(this.currentStep) {
                case 1:
                    return this.selectedDeviceType !== '';
                case 2:
                    return this.selectedBrand !== '';
                case 3:
                    return this.selectedModel !== null;
                case 4:
                    return this.formData.title && this.formData.description && 
                           this.formData.price && this.formData.condition &&
                           (this.selectedDeviceType !== 'phone' || 
                            (this.formData.battery_percentage && this.formData.screen_condition && 
                             this.formData.body_condition && this.formData.functionality));
                default:
                    return false;
            }
        },
        
        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            this.imagePreviews = files.map(file => URL.createObjectURL(file));
        },
        
        removeImage(index) {
            this.imagePreviews.splice(index, 1);
        },
        
        submitForm() {
            if (this.canProceed()) {
                // Add brand and model information
                if (this.selectedDeviceType === 'phone') {
                    const brandMapping = {
                        'apple': 1,
                        'samsung': 2,
                        'google': 3,
                        'xiaomi': 4,
                        'oneplus': 5,
                        'huawei': 6
                    };
                    this.formData.brand_id = brandMapping[this.selectedBrand] || 1;
                } else {
                    this.formData.brand_id = 1; // Default brand for accessories
                }
                
                // Debug logging
                console.log('Submitting form with data:');
                console.log('Device Type:', this.selectedDeviceType);
                console.log('Selected Brand:', this.selectedBrand);
                console.log('Selected Model:', this.selectedModel);
                console.log('Form Data:', this.formData);
                
                document.querySelector('form').submit();
            }
        }
    }
}
</script>
@endsection