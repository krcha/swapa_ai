<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagingController extends Controller
{
    /**
     * Display conversations list
     */
    public function index()
    {
        $user = Auth::user();
        
        $conversations = $user->conversations()
            ->with(['listing', 'buyer', 'seller', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        $unreadCount = $user->getUnreadMessageCount();

        return view('messaging.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Show conversation messages
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'Unauthorized access to conversation.');
        }

        // Mark messages as read
        $conversation->markAsRead($user->id);

        $conversation->load(['listing', 'buyer', 'seller']);
        
        $messages = $conversation->messages()
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        $otherParticipant = $conversation->getOtherParticipant($user->id);

        return view('messaging.show', compact('conversation', 'messages', 'otherParticipant'));
    }

    /**
     * Start a new conversation
     */
    public function create(Request $request, Listing $listing)
    {
        $user = Auth::user();
        
        // Check if user is not the seller
        if ($listing->user_id === $user->id) {
            return redirect()->back()->with('error', 'You cannot message yourself.');
        }

        // Check if listing is active
        if (!$listing->isActive()) {
            return redirect()->back()->with('error', 'This listing is no longer available.');
        }

        // Check if user is blocked
        if ($listing->user->isBlockedBy($user->id)) {
            return redirect()->back()->with('error', 'You cannot message this seller.');
        }

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'listing_id' => $listing->id,
                'buyer_id' => $user->id,
            ],
            [
                'seller_id' => $listing->user_id,
                'status' => 'active',
            ]
        );

        return redirect()->route('messaging.show', $conversation);
    }

    /**
     * Send a message
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'Unauthorized access to conversation.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Check if user is blocked
        $otherParticipant = $conversation->getOtherParticipant($user->id);
        if ($otherParticipant->isBlockedBy($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot message this user.',
            ], 403);
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'recipient_id' => $otherParticipant->id,
            'message' => $request->message,
        ]);

        // Update conversation last message time
        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Load relationships for response
        $message->load(['sender', 'recipient']);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        
        // Check if user is the recipient
        if ($message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized access to message.');
        }

        $message->markAsRead();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Mark all messages in conversation as read
     */
    public function markConversationAsRead(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'Unauthorized access to conversation.');
        }

        $conversation->markAsRead($user->id);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Close conversation
     */
    public function close(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'Unauthorized access to conversation.');
        }

        $conversation->update(['status' => 'closed']);

        return response()->json([
            'success' => true,
            'message' => 'Conversation closed successfully.',
        ]);
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $count = $user->getUnreadMessageCount();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Search conversations
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('q', '');
        
        $conversations = $user->conversations()
            ->with(['listing', 'buyer', 'seller', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->whereHas('listing', function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orWhereHas('messages', function($q) use ($query) {
                $q->where('message', 'like', "%{$query}%");
            })
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('messaging.index', compact('conversations'));
    }

    /**
     * Get conversation messages (AJAX)
     */
    public function getMessages(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'Unauthorized access to conversation.');
        }

        $messages = $conversation->messages()
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Send message (AJAX)
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403, 'Unauthorized access to conversation.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Check if user is blocked
        $otherParticipant = $conversation->getOtherParticipant($user->id);
        if ($otherParticipant->isBlockedBy($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot message this user.',
            ], 403);
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'recipient_id' => $otherParticipant->id,
            'message' => $request->message,
        ]);

        // Update conversation last message time
        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Load relationships for response
        $message->load(['sender', 'recipient']);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}