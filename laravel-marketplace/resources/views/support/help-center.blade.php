@extends('layouts.app')

@section('title', 'Help Center - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Help Center</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Find answers to common questions and get help with using our marketplace
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-12">
            <div class="relative">
                <input type="text" 
                       placeholder="Search for help..." 
                       class="w-full px-4 py-3 pl-12 pr-4 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="space-y-12">
            @foreach($faqs as $category)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $category['category'] }}</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($category['questions'] as $faq)
                    <div class="px-6 py-4">
                        <button class="w-full text-left flex justify-between items-center focus:outline-none group" 
                                onclick="toggleFAQ(this)">
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-blue-600">
                                {{ $faq['question'] }}
                            </h3>
                            <svg class="h-5 w-5 text-gray-400 transform transition-transform duration-200" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mt-3 text-gray-600 hidden">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Contact Support -->
        <div class="mt-16 bg-blue-50 rounded-lg p-8 text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Still need help?</h2>
            <p class="text-gray-600 mb-6">Can't find what you're looking for? Our support team is here to help.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('support.contact') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Contact Support
                </a>
                <a href="{{ route('support.safety-tips') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Safety Tips
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('svg');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection
