@extends('layouts.app')

@section('title', 'Conversation')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('messaging.index') }}" 
                       class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr($otherParticipant->first_name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">
                                {{ $otherParticipant->first_name }} {{ $otherParticipant->last_name }}
                            </h1>
                            <p class="text-sm text-gray-500">{{ $conversation->listing->title }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($conversation->status === 'active') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($conversation->status) }}
                    </span>
                    @if($conversation->status === 'active')
                        <button onclick="closeConversation({{ $conversation->id }})" 
                                class="text-gray-400 hover:text-gray-600 text-sm font-medium">
                            Close
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Listing Info -->
        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <div class="flex items-center space-x-4">
                @if($conversation->listing->images->count() > 0)
                    <img class="h-16 w-16 rounded-lg object-cover" 
                         src="{{ $conversation->listing->images->first()->url }}" 
                         alt="{{ $conversation->listing->title }}">
                @else
                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ $conversation->listing->title }}</h3>
                    <p class="text-sm text-gray-500">by {{ $conversation->listing->user->first_name }} {{ $conversation->listing->user->last_name }}</p>
                    <p class="text-lg font-semibold text-gray-900">${{ number_format($conversation->listing->price, 2) }}</p>
                </div>
                <div>
                    <a href="{{ route('listings.show', $conversation->listing) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View Listing
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="bg-white rounded-lg shadow">
            <!-- Messages List -->
            <div id="messages-container" class="h-96 overflow-y-auto p-4 space-y-4">
                @foreach($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                        <p class="text-sm">{{ $message->message }}</p>
                        <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-indigo-200' : 'text-gray-500' }}">
                            {{ $message->created_at->format('M j, Y g:i A') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Message Input -->
            @if($conversation->status === 'active')
            <div class="border-t border-gray-200 p-4">
                <form id="message-form" class="flex space-x-4">
                    @csrf
                    <div class="flex-1">
                        <textarea id="message-input" 
                                  name="message" 
                                  rows="2" 
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                  placeholder="Type your message..." 
                                  required></textarea>
                    </div>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-500">This conversation has been closed.</p>
            </div>
            @endif
        </div>

        <!-- Safety Guidelines -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Safety Guidelines</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Meet in public places for transactions</li>
                            <li>Never send money before seeing the item</li>
                            <li>Trust your instincts - if something feels wrong, walk away</li>
                            <li>Report any suspicious behavior immediately</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-scroll to bottom of messages
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

// Scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

// Message form submission
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Send message via AJAX
    fetch(`/messaging/{{ $conversation->id }}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add message to UI
            addMessageToUI(data.message);
            messageInput.value = '';
            scrollToBottom();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the message.');
    });
});

// Add message to UI
function addMessageToUI(message) {
    const container = document.getElementById('messages-container');
    const isOwnMessage = message.sender_id === {{ auth()->id() }};
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;
    
    const messageTime = new Date(message.created_at).toLocaleString();
    
    messageDiv.innerHTML = `
        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isOwnMessage ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900'}">
            <p class="text-sm">${message.message}</p>
            <p class="text-xs mt-1 ${isOwnMessage ? 'text-indigo-200' : 'text-gray-500'}">
                ${messageTime}
            </p>
        </div>
    `;
    
    container.appendChild(messageDiv);
}

// Close conversation
function closeConversation(conversationId) {
    if (confirm('Are you sure you want to close this conversation?')) {
        fetch(`/messaging/${conversationId}/close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while closing the conversation.');
        });
    }
}

// Auto-refresh messages every 30 seconds
setInterval(function() {
    fetch(`/messaging/{{ $conversation->id }}/messages`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update messages if needed
                // This is a simple implementation - in production you'd want to be more sophisticated
            }
        })
        .catch(error => {
            console.error('Error refreshing messages:', error);
        });
}, 30000);
</script>
@endpush
@endsection
