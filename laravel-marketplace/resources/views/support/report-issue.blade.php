@extends('layouts.app')

@section('title', 'Report an Issue - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Report an Issue</h1>
            <p class="text-xl text-gray-600">
                Help us keep our marketplace safe by reporting suspicious activity or problems
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Report Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Submit a Report</h2>
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('support.report.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">What are you reporting? *</label>
                        <select id="report_type" 
                                name="report_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('report_type') border-red-500 @enderror"
                                required>
                            <option value="">Select report type</option>
                            <option value="fake_listing" {{ old('report_type') == 'fake_listing' ? 'selected' : '' }}>Fake or Misleading Listing</option>
                            <option value="harassment" {{ old('report_type') == 'harassment' ? 'selected' : '' }}>Harassment or Inappropriate Behavior</option>
                            <option value="scam" {{ old('report_type') == 'scam' ? 'selected' : '' }}>Suspected Scam</option>
                            <option value="spam" {{ old('report_type') == 'spam' ? 'selected' : '' }}>Spam or Unwanted Messages</option>
                            <option value="fake_user" {{ old('report_type') == 'fake_user' ? 'selected' : '' }}>Fake User Profile</option>
                            <option value="inappropriate_content" {{ old('report_type') == 'inappropriate_content' ? 'selected' : '' }}>Inappropriate Content</option>
                            <option value="safety_concern" {{ old('report_type') == 'safety_concern' ? 'selected' : '' }}>Safety Concern</option>
                            <option value="other" {{ old('report_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('report_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="listing_id" class="block text-sm font-medium text-gray-700 mb-2">Listing ID (if applicable)</label>
                        <input type="text" 
                               id="listing_id" 
                               name="listing_id" 
                               value="{{ old('listing_id') }}"
                               placeholder="e.g., #12345"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('listing_id') border-red-500 @enderror">
                        @error('listing_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User ID (if applicable)</label>
                        <input type="text" 
                               id="user_id" 
                               name="user_id" 
                               value="{{ old('user_id') }}"
                               placeholder="e.g., @username"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_id') border-red-500 @enderror">
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               placeholder="Brief description of the issue"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                               required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Detailed Description *</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Please provide as much detail as possible about the issue..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="evidence" class="block text-sm font-medium text-gray-700 mb-2">Evidence (optional)</label>
                        <input type="file" 
                               id="evidence" 
                               name="evidence[]" 
                               multiple
                               accept="image/*,.pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('evidence') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Upload screenshots, documents, or other evidence (max 10MB per file)</p>
                        @error('evidence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select id="priority" 
                                name="priority" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror"
                                required>
                            <option value="">Select priority</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - General issue</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium - Important issue</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Urgent issue</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Safety concern</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="confidential" 
                                   name="confidential" 
                                   type="checkbox" 
                                   value="1"
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="confidential" class="font-medium text-gray-700">Keep this report confidential</label>
                            <p class="text-gray-500">We will not share your identity with the reported user</p>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                        Submit Report
                    </button>
                </form>
            </div>

            <!-- Information Panel -->
            <div class="space-y-8">
                <!-- What to Report -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">What to Report</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-gray-900">Fake or Misleading Listings</h3>
                                <p class="text-sm text-gray-600">Items that don't match the description or are fake</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-gray-900">Harassment or Inappropriate Behavior</h3>
                                <p class="text-sm text-gray-600">Abusive, threatening, or inappropriate messages</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-gray-900">Suspected Scams</h3>
                                <p class="text-sm text-gray-600">Requests for payment before meeting or suspicious behavior</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-gray-900">Safety Concerns</h3>
                                <p class="text-sm text-gray-600">Any behavior that makes you feel unsafe</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contacts -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Emergency Contacts</h3>
                            <p class="text-red-700 mb-4">If you feel unsafe or encounter criminal behavior, contact the authorities immediately:</p>
                            <div class="space-y-2">
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

                <!-- What Happens Next -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">What Happens Next?</h3>
                    <div class="space-y-3 text-blue-800">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center text-xs font-semibold">1</div>
                            </div>
                            <p class="ml-3 text-sm">We review your report within 24 hours</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center text-xs font-semibold">2</div>
                            </div>
                            <p class="ml-3 text-sm">We investigate the issue thoroughly</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center text-xs font-semibold">3</div>
                            </div>
                            <p class="ml-3 text-sm">We take appropriate action and update you</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
