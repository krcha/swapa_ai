@extends('admin.layouts.app')

@section('title', 'Approved Phone Models')
@section('page-title', 'Approved Phone Models')

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
                <span class="text-sm font-medium text-gray-900">Approved Models</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-8 admin-content" x-data="approvedModels()">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-mobile-alt mr-3 text-blue-600"></i>
                    Approved Phone Models
                </h1>
                <p class="text-gray-600 mt-1">Manage approved phone models for listings</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.approved-models.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add Model
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div x-show="showFilters" x-transition class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.approved-models.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Search
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search models, brands, codes..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Brand Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>Brand
                    </label>
                    <select name="brand" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Model Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-mobile-alt mr-1"></i>Model
                    </label>
                    <select name="model" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Models</option>
                        @foreach($models->pluck('model_name')->unique()->sort() as $model)
                        <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>{{ $model }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-circle mr-1"></i>Status
                    </label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-1"></i>Sort By
                    </label>
                    <select name="sort_by" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="brand_name" {{ request('sort_by') == 'brand_name' ? 'selected' : '' }}>Brand Name</option>
                        <option value="model_name" {{ request('sort_by') == 'model_name' ? 'selected' : '' }}>Model Name</option>
                        <option value="model_code" {{ request('sort_by') == 'model_code' ? 'selected' : '' }}>Model Code</option>
                        <option value="sort_order" {{ request('sort_by') == 'sort_order' ? 'selected' : '' }}>Sort Order</option>
                        <option value="is_active" {{ request('sort_by') == 'is_active' ? 'selected' : '' }}>Status</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                    </select>
                </div>
            </div>

            <!-- Sort Order -->
            <div class="mt-4">
                <div class="flex items-center space-x-4">
                    <label class="block text-sm font-medium text-gray-700">Sort Order:</label>
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center">
                            <input type="radio" name="sort_order" value="asc" {{ request('sort_order') == 'asc' || !request('sort_order') ? 'checked' : '' }} 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Ascending</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="sort_order" value="desc" {{ request('sort_order') == 'desc' ? 'checked' : '' }} 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Descending</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center mt-6">
                <div class="text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'brand', 'model', 'status', 'sort_by', 'sort_order']))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-filter mr-1"></i>Filters Applied
                        </span>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.approved-models.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear All
                    </a>
                    <button type="button" @click="showFilters = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-blue-600"></i>
                        Models
                    </h3>
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full shadow-sm">
                        {{ $models->total() }} total
                    </span>
                </div>
                
                <!-- Table Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Filter Toggle -->
                    <button @click="showFilters = !showFilters" 
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left">
                            <input type="checkbox" 
                                   name="select_all"
                                   x-model="selectAll"
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors duration-200">
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'model_name', 'sort_order' => request('sort_by') == 'model_name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center space-x-2 hover:text-gray-900 transition-colors">
                                <i class="fas fa-mobile-alt text-gray-400"></i>
                                <span>Model</span>
                                @if(request('sort_by') == 'model_name')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'brand_name', 'sort_order' => request('sort_by') == 'brand_name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center space-x-2 hover:text-gray-900 transition-colors">
                                <i class="fas fa-tag text-gray-400"></i>
                                <span>Brand</span>
                                @if(request('sort_by') == 'brand_name')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'model_code', 'sort_order' => request('sort_by') == 'model_code' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center space-x-2 hover:text-gray-900 transition-colors">
                                <i class="fas fa-code text-gray-400"></i>
                                <span>Code</span>
                                @if(request('sort_by') == 'model_code')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'sort_order', 'sort_order' => request('sort_by') == 'sort_order' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center space-x-2 hover:text-gray-900 transition-colors">
                                <i class="fas fa-sort text-gray-400"></i>
                                <span>Order</span>
                                @if(request('sort_by') == 'sort_order')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'is_active', 'sort_order' => request('sort_by') == 'is_active' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="flex items-center space-x-2 hover:text-gray-900 transition-colors">
                                <i class="fas fa-circle text-gray-400"></i>
                                <span>Status</span>
                                @if(request('sort_by') == 'is_active')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-cog text-gray-400"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($models as $model)
                        <tr class="hover:bg-blue-50 transition-all duration-200 group">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       name="model_ids[]"
                                       value="{{ $model->id }}"
                                       x-model="selectedModels"
                                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $model->model_name }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $model->brand_name }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <code class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $model->model_code }}</code>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $model->sort_order }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.approved-models.toggle-status', $model) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors
                                                   {{ $model->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        <i class="fas {{ $model->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $model->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.approved-models.edit', $model) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.approved-models.destroy', $model) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this model?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 sm:px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-mobile-alt text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No models found</h3>
                                    <p class="text-gray-500 max-w-sm">Try adjusting your search or filter criteria to find models.</p>
                                    <a href="{{ route('admin.approved-models.create') }}" 
                                       class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>
                                        Add First Model
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200">
            {{ $models->appends(request()->query())->links('admin.components.pagination') }}
        </div>
    </div>
</div>

<script>
function approvedModels() {
    return {
        showFilters: false,
        selectedModels: [],
        selectAll: false,
        
        init() {
            this.$watch('selectAll', value => {
                this.selectedModels = value ? this.getAllModelIds() : [];
            });
        },
        
        getAllModelIds() {
            return Array.from(document.querySelectorAll('input[name="model_ids[]"]')).map(input => input.value);
        },
        
        toggleModelSelection(modelId) {
            if (this.selectedModels.includes(modelId)) {
                this.selectedModels = this.selectedModels.filter(id => id !== modelId);
            } else {
                this.selectedModels.push(modelId);
            }
        }
    }
}
</script>
@endsection
