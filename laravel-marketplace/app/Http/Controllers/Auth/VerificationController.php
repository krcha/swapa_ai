<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class VerificationController extends Controller
{
    /**
     * Send email verification
     */
    public function sendEmailVerification(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->is_email_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is already verified'
                ], 400);
            }

            // Generate verification code
            $verificationCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store in cache for 10 minutes
            Cache::put("email_verification_{$user->id}", $verificationCode, 600);

            // Send email (in real implementation, use Mail facade)
            // Mail::to($user->email)->send(new EmailVerificationMail($verificationCode));
            
            Log::info('Email verification sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email',
                'data' => [
                    'verification_code' => $verificationCode // Remove in production
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Email verification send failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email'
            ], 500);
        }
    }

    /**
     * Verify email with code
     */
    public function verifyEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|size:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $storedCode = Cache::get("email_verification_{$user->id}");

            if (!$storedCode || $storedCode !== $request->code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code'
                ], 400);
            }

            // Verify email
            $user->update([
                'is_email_verified' => true,
                'email_verified_at' => now()
            ]);

            // Clear verification code
            Cache::forget("email_verification_{$user->id}");

            Log::info('Email verified', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully',
                'data' => [
                    'user' => $user->makeHidden(['jmbg_hash', 'password']),
                    'verification_status' => [
                        'email' => true,
                        'sms' => $user->is_sms_verified,
                        'age' => $user->is_age_verified,
                        'overall' => $user->isFullyVerified()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Email verification failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email verification failed'
            ], 500);
        }
    }

    /**
     * Send SMS verification
     */
    public function sendSMSVerification(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->is_sms_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone is already verified'
                ], 400);
            }

            // Generate verification code
            $verificationCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store in cache for 10 minutes
            Cache::put("sms_verification_{$user->id}", $verificationCode, 600);

            // Send SMS (in real implementation, use Twilio or Serbian SMS provider)
            // $this->sendSMS($user->phone, "Your verification code is: {$verificationCode}");
            
            Log::info('SMS verification sent', [
                'user_id' => $user->id,
                'phone' => $user->phone
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your phone',
                'data' => [
                    'verification_code' => $verificationCode // Remove in production
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('SMS verification send failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification SMS'
            ], 500);
        }
    }

    /**
     * Verify SMS with code
     */
    public function verifySMS(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|size:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $storedCode = Cache::get("sms_verification_{$user->id}");

            if (!$storedCode || $storedCode !== $request->code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code'
                ], 400);
            }

            // Verify SMS
            $user->update([
                'is_sms_verified' => true
            ]);

            // Clear verification code
            Cache::forget("sms_verification_{$user->id}");

            // Check if user is now fully verified
            if ($user->isFullyVerified()) {
                $user->update(['is_verified' => true]);
            }

            Log::info('SMS verified', [
                'user_id' => $user->id,
                'phone' => $user->phone
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Phone verified successfully',
                'data' => [
                    'user' => $user->makeHidden(['jmbg_hash', 'password']),
                    'verification_status' => [
                        'email' => $user->is_email_verified,
                        'sms' => true,
                        'age' => $user->is_age_verified,
                        'overall' => $user->isFullyVerified()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('SMS verification failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'SMS verification failed'
            ], 500);
        }
    }

    /**
     * Resend verification code
     */
    public function resendVerification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:email,sms'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $type = $request->type;

            // Check rate limiting (max 3 attempts per hour)
            $key = "verification_resend_{$type}_{$user->id}";
            $attempts = Cache::get($key, 0);
            
            if ($attempts >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many resend attempts. Please try again later.'
                ], 429);
            }

            // Increment attempts
            Cache::put($key, $attempts + 1, 3600);

            if ($type === 'email') {
                return $this->sendEmailVerification($request);
            } else {
                return $this->sendSMSVerification($request);
            }

        } catch (\Exception $e) {
            Log::error('Resend verification failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null,
                'type' => $request->type ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend verification'
            ], 500);
        }
    }
}
