<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PhoneVerificationController extends Controller
{
    /**
     * Send phone verification code
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^(\+381|381|0)[0-9]{8,9}$/',
            'provider' => 'nullable|in:twilio,vip,telenor',
        ]);

        $user = Auth::user();
        
        // Check if phone is already verified
        if ($user->hasPhoneVerification()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number is already verified.'
            ], 400);
        }

        // Rate limiting check
        $rateLimitKey = 'phone_verification:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'success' => false,
                'message' => "Too many attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        // Normalize phone number
        $phoneNumber = $this->normalizePhoneNumber($request->phone);
        
        // Update user phone number
        $user->update(['phone' => $phoneNumber]);

        // Send SMS using SMS service
        $smsService = new SMSService($request->provider ?? 'twilio');
        $result = $smsService->sendVerificationCode($phoneNumber, $user->id);

        if ($result['success']) {
            // Record rate limiting
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to ' . $phoneNumber . '. Code expires in 5 minutes.',
                'expires_at' => $result['expires_at'],
                'code_id' => $result['code_id'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }
    }

    /**
     * Verify phone number with code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'phone' => 'required|string',
        ]);

        $user = Auth::user();
        $phoneNumber = $this->normalizePhoneNumber($request->phone);

        // Rate limiting for verification attempts
        $rateLimitKey = 'phone_verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'success' => false,
                'message' => "Too many verification attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        // Verify code using SMS service
        $smsService = new SMSService();
        $result = $smsService->verifyCode($phoneNumber, $request->code, $user->id);

        if ($result['success']) {
            // Record rate limiting
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes

            return response()->json([
                'success' => true,
                'message' => 'Phone number verified successfully!',
                'user' => [
                    'id' => $user->id,
                    'phone' => $user->phone,
                    'phone_verified_at' => $user->phone_verified_at,
                ]
            ]);
        } else {
            // Record failed attempt
            RateLimiter::hit($rateLimitKey, 300);

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'provider' => 'nullable|in:twilio,vip,telenor',
        ]);

        $user = Auth::user();
        $phoneNumber = $this->normalizePhoneNumber($request->phone);

        if (!$user->phone) {
            return response()->json([
                'success' => false,
                'message' => 'No phone number found. Please add a phone number first.'
            ], 400);
        }

        if ($user->hasPhoneVerification()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number is already verified.'
            ], 400);
        }

        // Rate limiting for resend
        $rateLimitKey = 'phone_resend:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'success' => false,
                'message' => "Please wait {$seconds} seconds before requesting a new code.",
            ], 429);
        }

        // Send SMS using SMS service
        $smsService = new SMSService($request->provider ?? 'twilio');
        $result = $smsService->sendVerificationCode($phoneNumber, $user->id);

        if ($result['success']) {
            // Record rate limiting
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes

            return response()->json([
                'success' => true,
                'message' => 'New verification code sent to ' . $phoneNumber . '. Code expires in 5 minutes.',
                'expires_at' => $result['expires_at'],
                'code_id' => $result['code_id'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }
    }

    /**
     * Check phone verification status
     */
    public function status()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'phone_verified' => $user->hasPhoneVerification(),
            'phone' => $user->phone,
            'phone_verified_at' => $user->phone_verified_at,
        ]);
    }

    /**
     * Normalize Serbian phone number to international format
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-digit characters except +
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);
        
        // Convert to international format
        if (str_starts_with($cleaned, '+381')) {
            return $cleaned;
        } elseif (str_starts_with($cleaned, '381')) {
            return '+' . $cleaned;
        } elseif (str_starts_with($cleaned, '0')) {
            return '+381' . substr($cleaned, 1);
        }
        
        return $cleaned;
    }

    /**
     * Get verification code statistics
     */
    public function statistics(Request $request)
    {
        $user = Auth::user();
        $phoneNumber = $this->normalizePhoneNumber($request->phone ?? $user->phone);

        $stats = VerificationCode::getStatistics($phoneNumber);

        return response()->json([
            'success' => true,
            'statistics' => $stats,
        ]);
    }

    /**
     * Clean up expired verification codes (admin only)
     */
    public function cleanup()
    {
        $smsService = new SMSService();
        $deleted = $smsService->cleanupExpiredCodes();

        return response()->json([
            'success' => true,
            'message' => "Cleaned up {$deleted} expired verification codes.",
        ]);
    }
}
