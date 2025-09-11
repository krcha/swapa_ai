@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="mt-2 text-gray-600">Communicate with buyers and sellers about your listings.</p>
                </div>
                @if($unreadCount > 0)
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            {{ $unreadCount }} unread messages
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" action="{{ route('messaging.search') }}" class="max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" 
                           placeholder="Search conversations...">
                </div>
            </form>
        </div>

        <!-- Conversations List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @forelse($conversations as $conversation)
            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 cursor-pointer" 
                 onclick="window.location.href='{{ route('messaging.show', $conversation) }}'">
                <div class="flex items-center space-x-4">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr($conversation->getOtherParticipant(auth()->id())->first_name, 0, 1) }}
                            </span>
                        </div>
                    </div>

                    <!-- Conversation Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $conversation->getOtherParticipant(auth()->id())->first_name }} {{ $conversation->getOtherParticipant(auth()->id())->last_name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">{{ $conversation->listing->title }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($conversation->getUnreadCount(auth()->id()) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $conversation->getUnreadCount(auth()->id()) }}
                                    </span>
                                @endif
                                <p class="text-xs text-gray-500">
                                    {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'No messages' }}
                                </p>
                            </div>
                        </div>
                        
                        @if($conversation->getLastMessage())
                            <p class="text-sm text-gray-500 truncate mt-1">
                                {{ $conversation->getLastMessage()->message }}
                            </p>
                        @endif
                    </div>

                    <!-- Status Indicator -->
                    <div class="flex-shrink-0">
                        @if($conversation->status === 'active')
                            <div class="h-3 w-3 rounded-full bg-green-400"></div>
                        @else
                            <div class="h-3 w-3 rounded-full bg-gray-400"></div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 sm:px-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations</h3>
                <p class="mt-1 text-sm text-gray-500">Start a conversation by messaging a seller about their listing.</p>
                <div class="mt-6">
                    <a href="{{ route('listings.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Browse Listings
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($conversations->hasPages())
        <div class="mt-6">
            {{ $conversations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
