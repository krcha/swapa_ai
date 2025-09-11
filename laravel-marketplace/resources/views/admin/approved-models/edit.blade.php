@extends('admin.layouts.app')

@section('title', 'Edit Phone Model')
@section('page-title', 'Edit Phone Model')

@section('breadcrumbs')
<nav class="hidden sm:flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        <li>
            <div class="flex items-center">
                <i class="fas fa-home text-gray-400"></i>
                <span class="ml-2 text-sm font-medium text-gray-500">Admin</span>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('admin.approved-models.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Approved Models</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-900">Edit Model</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-8 admin-content">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-3 text-blue-600"></i>
                    Edit Phone Model
                </h1>
                <p class="text-gray-600 mt-1">Update the approved phone model information</p>
            </div>
            <a href="{{ route('admin.approved-models.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Models
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.approved-models.update', $approvedPhoneModel) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Brand Name -->
                <div class="md:col-span-2">
                    <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>Brand Name *
                    </label>
                    <input type="text" 
                           id="brand_name" 
                           name="brand_name" 
                           value="{{ old('brand_name', $approvedPhoneModel->brand_name) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                           placeholder="e.g., iPhone, Samsung Galaxy, Huawei">
                    <p class="text-sm text-gray-500 mt-1">Enter the brand name for this phone model</p>
                </div>

                <!-- Model Name -->
                <div>
                    <label for="model_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-mobile-alt mr-1"></i>Model Name *
                    </label>
                    <input type="text" 
                           id="model_name" 
                           name="model_name" 
                           value="{{ old('model_name', $approvedPhoneModel->model_name) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                           placeholder="e.g., iPhone 15 Pro, Galaxy S24">
                    <p class="text-sm text-gray-500 mt-1">Enter the specific model name</p>
                </div>

                <!-- Model Code -->
                <div>
                    <label for="model_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-code mr-1"></i>Model Code *
                    </label>
                    <input type="text" 
                           id="model_code" 
                           name="model_code" 
                           value="{{ old('model_code', $approvedPhoneModel->model_code) }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                           placeholder="e.g., iphone-15-pro, galaxy-s24">
                    <p class="text-sm text-gray-500 mt-1">URL-friendly code (lowercase, hyphens)</p>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-1"></i>Sort Order
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', $approvedPhoneModel->sort_order) }}" 
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                    <p class="text-sm text-gray-500 mt-1">Lower numbers appear first (0 = highest priority)</p>
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $approvedPhoneModel->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </label>
                    <p class="text-sm text-gray-500 ml-2">Only active models can be selected when creating listings</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.approved-models.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Model
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate model code from model name
    const modelNameInput = document.getElementById('model_name');
    const modelCodeInput = document.getElementById('model_code');
    
    modelNameInput.addEventListener('input', function() {
        if (!modelCodeInput.value || modelCodeInput.value === '') {
            const code = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .trim();
            modelCodeInput.value = code;
        }
    });
});
</script>
@endsection
