<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\BlockedUser;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SafetyController extends Controller
{
    /**
     * Display safety guidelines
     */
    public function guidelines()
    {
        return view('safety.guidelines');
    }

    /**
     * Display report form
     */
    public function reportForm(Request $request)
    {
        $type = $request->get('type', 'listing');
        $listingId = $request->get('listing_id');
        $userId = $request->get('user_id');

        $listing = null;
        $user = null;

        if ($type === 'listing' && $listingId) {
            $listing = Listing::findOrFail($listingId);
        } elseif ($type === 'user' && $userId) {
            $user = User::findOrFail($userId);
        }

        return view('safety.report-form', compact('type', 'listing', 'user'));
    }

    /**
     * Submit report
     */
    public function submitReport(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'type' => 'required|in:listing,user,message',
            'listing_id' => 'nullable|exists:listings,id',
            'user_id' => 'nullable|exists:users,id',
            'reason' => 'required|in:spam,inappropriate,fraud,fake,harassment,copyright,other',
            'description' => 'required|string|max:1000',
        ]);

        // Check if user is trying to report themselves
        if ($request->user_id && $request->user_id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot report yourself.',
            ], 400);
        }

        // Check if user is trying to report their own listing
        if ($request->listing_id) {
            $listing = Listing::findOrFail($request->listing_id);
            if ($listing->user_id == $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot report your own listing.',
                ], 400);
            }
        }

        // Create report
        $report = Report::create([
            'reporter_id' => $user->id,
            'listing_id' => $request->listing_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        // Send notification to admin (in real implementation)
        // $this->notifyAdmin($report);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. We will review it shortly.',
        ]);
    }

    /**
     * Block user
     */
    public function blockUser(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Check if user is trying to block themselves
        if ($user->id == $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot block yourself.',
            ], 400);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Check if already blocked
        if ($currentUser->hasBlocked($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User is already blocked.',
            ], 400);
        }

        // Block user
        $currentUser->blockUser($user->id, $request->reason);

        return response()->json([
            'success' => true,
            'message' => 'User blocked successfully.',
        ]);
    }

    /**
     * Unblock user
     */
    public function unblockUser(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if user is blocked
        if (!$currentUser->hasBlocked($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'User is not blocked.',
            ], 400);
        }

        // Unblock user
        $currentUser->unblockUser($user->id);

        return response()->json([
            'success' => true,
            'message' => 'User unblocked successfully.',
        ]);
    }

    /**
     * Get blocked users list
     */
    public function blockedUsers()
    {
        $user = Auth::user();
        
        $blockedUsers = $user->blockedUsers()
            ->with('blocked')
            ->latest()
            ->paginate(20);

        return view('safety.blocked-users', compact('blockedUsers'));
    }

    /**
     * Display dispute resolution form
     */
    public function disputeForm(Request $request)
    {
        $conversationId = $request->get('conversation_id');
        $listingId = $request->get('listing_id');

        $conversation = null;
        $listing = null;

        if ($conversationId) {
            $conversation = \App\Models\Conversation::findOrFail($conversationId);
        }

        if ($listingId) {
            $listing = Listing::findOrFail($listingId);
        }

        return view('safety.dispute-form', compact('conversation', 'listing'));
    }

    /**
     * Submit dispute
     */
    public function submitDispute(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'conversation_id' => 'nullable|exists:conversations,id',
            'listing_id' => 'nullable|exists:listings,id',
            'dispute_type' => 'required|in:payment,item_condition,communication,other',
            'description' => 'required|string|max:1000',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Create dispute report
        $report = Report::create([
            'reporter_id' => $user->id,
            'listing_id' => $request->listing_id,
            'type' => 'dispute',
            'reason' => $request->dispute_type,
            'description' => $request->description,
        ]);

        // Handle evidence uploads (in real implementation)
        // $this->handleEvidenceUpload($report, $request->evidence);

        return response()->json([
            'success' => true,
            'message' => 'Dispute submitted successfully. We will review it within 24 hours.',
        ]);
    }

    /**
     * Get user's reports
     */
    public function myReports()
    {
        $user = Auth::user();
        
        $reports = $user->reports()
            ->with(['listing', 'reportedUser', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('safety.my-reports', compact('reports'));
    }

    /**
     * Get safety tips
     */
    public function safetyTips()
    {
        $tips = [
            [
                'title' => 'Meet in Public Places',
                'description' => 'Always meet in well-lit, public places for transactions. Avoid meeting at private residences.',
                'icon' => 'location',
            ],
            [
                'title' => 'Verify Item Condition',
                'description' => 'Inspect the item thoroughly before completing the transaction. Test all functions.',
                'icon' => 'search',
            ],
            [
                'title' => 'Use Secure Payment',
                'description' => 'Use secure payment methods and avoid wire transfers or gift cards.',
                'icon' => 'credit-card',
            ],
            [
                'title' => 'Trust Your Instincts',
                'description' => 'If something feels wrong, trust your instincts and walk away from the deal.',
                'icon' => 'heart',
            ],
            [
                'title' => 'Keep Records',
                'description' => 'Keep records of all communications and transactions for your protection.',
                'icon' => 'document',
            ],
            [
                'title' => 'Report Suspicious Activity',
                'description' => 'Report any suspicious activity or users immediately to our safety team.',
                'icon' => 'flag',
            ],
        ];

        return view('safety.tips', compact('tips'));
    }

    /**
     * Get safety statistics
     */
    public function safetyStats()
    {
        $user = Auth::user();
        
        $stats = [
            'reports_submitted' => $user->reports()->count(),
            'users_blocked' => $user->blockedUsers()->count(),
            'disputes_resolved' => $user->reports()->where('status', 'resolved')->count(),
            'safety_score' => $this->calculateSafetyScore($user),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Calculate user safety score
     */
    private function calculateSafetyScore($user)
    {
        $score = 100;
        
        // Deduct points for reports against user
        $reportsAgainst = $user->reportedAgainst()->count();
        $score -= min($reportsAgainst * 10, 50);
        
        // Deduct points for blocked users
        $blockedBy = $user->blockedByUsers()->count();
        $score -= min($blockedBy * 5, 30);
        
        // Add points for verification
        if ($user->email_verified_at) $score += 10;
        if ($user->phone_verified_at) $score += 10;
        
        return max($score, 0);
    }

    /**
     * Notify admin about new report
     */
    private function notifyAdmin($report)
    {
        // In real implementation, this would send email to admin
        // Mail::to('admin@marketplace.com')->send(new NewReportNotification($report));
    }

    /**
     * Handle evidence upload
     */
    private function handleEvidenceUpload($report, $evidence)
    {
        // In real implementation, this would handle file uploads
        // and store them securely
    }
}