<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $conversations = Conversation::with(['listing', 'buyer', 'seller', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->forUser($user->id)
        ->orderBy('last_message_at', 'desc')
        ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }

    public function show($id)
    {
        $conversation = Conversation::with(['listing', 'buyer', 'seller', 'messages.sender'])
            ->forUser(Auth::id())
            ->findOrFail($id);

        // Mark messages as read
        $conversation->markAsRead(Auth::id());

        return response()->json([
            'success' => true,
            'data' => $conversation
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'message' => 'required|string|max:1000'
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        $buyer = Auth::user();
        $seller = $listing->user;

        // Check if user is verified
        if (!$buyer->isFullyVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be verified to start a conversation'
            ], 403);
        }

        // Check if listing is active
        if (!$listing->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This listing is no longer available'
            ], 403);
        }

        // Check if conversation already exists
        $conversation = Conversation::where('listing_id', $listing->id)
            ->where('buyer_id', $buyer->id)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'listing_id' => $listing->id,
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'last_message_at' => now()
            ]);
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $buyer->id,
            'recipient_id' => $seller->id,
            'message' => $request->message
        ]);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        // Send notification to seller
        // This would typically send an email/SMS notification

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $conversation->load(['listing', 'buyer', 'seller', 'messages'])
        ], 201);
    }

    public function sendMessage(Request $request, $id)
    {
        $conversation = Conversation::forUser(Auth::id())->findOrFail($id);

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $sender = Auth::user();
        $recipient = $conversation->getOtherParticipant($sender->id);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => $request->message
        ]);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        // Send notification to recipient
        // This would typically send an email/SMS notification

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load('sender')
        ], 201);
    }

    public function close($id)
    {
        $conversation = Conversation::forUser(Auth::id())->findOrFail($id);

        $conversation->update(['status' => 'closed']);

        return response()->json([
            'success' => true,
            'message' => 'Conversation closed successfully'
        ]);
    }
}
