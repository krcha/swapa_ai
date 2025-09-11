<?php

namespace App\Services;

use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class SMSService
{
    protected $provider;
    protected $config;

    public function __construct($provider = 'twilio')
    {
        $this->provider = $provider;
        $this->config = config("sms.providers.{$provider}");
    }

    /**
     * Send verification code to phone number
     */
    public function sendVerificationCode($phoneNumber, $userId = null)
    {
        try {
            // Validate Serbian phone number format
            if (!$this->isValidSerbianPhone($phoneNumber)) {
                throw new Exception('Invalid Serbian phone number format');
            }

            // Check rate limiting
            if (!$this->checkRateLimit($phoneNumber)) {
                throw new Exception('Rate limit exceeded. Please try again later.');
            }

            // Generate verification code
            $code = $this->generateVerificationCode();
            $expiresAt = now()->addMinutes(5);

            // Store verification code
            $verificationCode = VerificationCode::create([
                'phone_number' => $phoneNumber,
                'code' => $code,
                'user_id' => $userId,
                'expires_at' => $expiresAt,
                'attempts' => 0,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Send SMS based on provider
            $result = $this->sendSMS($phoneNumber, $code);

            if ($result['success']) {
                // Update rate limiting
                $this->updateRateLimit($phoneNumber);
                
                Log::info('SMS verification code sent', [
                    'phone' => $phoneNumber,
                    'provider' => $this->provider,
                    'code_id' => $verificationCode->id,
                ]);

                return [
                    'success' => true,
                    'message' => 'Verification code sent successfully',
                    'code_id' => $verificationCode->id,
                    'expires_at' => $expiresAt,
                ];
            } else {
                throw new Exception($result['error']);
            }

        } catch (Exception $e) {
            Log::error('SMS verification failed: ' . $e->getMessage(), [
                'phone' => $phoneNumber,
                'provider' => $this->provider,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify the provided code
     */
    public function verifyCode($phoneNumber, $code, $userId = null)
    {
        try {
            // Find the verification code
            $verificationCode = VerificationCode::where('phone_number', $phoneNumber)
                ->where('code', $code)
                ->where('expires_at', '>', now())
                ->where('verified', false)
                ->latest()
                ->first();

            if (!$verificationCode) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired verification code',
                ];
            }

            // Check attempt limit
            if ($verificationCode->attempts >= 5) {
                $verificationCode->update(['verified' => false]);
                return [
                    'success' => false,
                    'message' => 'Too many attempts. Please request a new code.',
                ];
            }

            // Increment attempts
            $verificationCode->increment('attempts');

            // Verify the code
            if ($verificationCode->code === $code) {
                $verificationCode->update([
                    'verified' => true,
                    'verified_at' => now(),
                ]);

                // Update user phone verification if user ID provided
                if ($userId) {
                    User::where('id', $userId)->update([
                        'phone_verified_at' => now(),
                    ]);
                }

                Log::info('SMS verification successful', [
                    'phone' => $phoneNumber,
                    'code_id' => $verificationCode->id,
                ]);

                return [
                    'success' => true,
                    'message' => 'Phone number verified successfully',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid verification code',
                ];
            }

        } catch (Exception $e) {
            Log::error('SMS verification failed: ' . $e->getMessage(), [
                'phone' => $phoneNumber,
                'code' => $code,
            ]);

            return [
                'success' => false,
                'message' => 'Verification failed. Please try again.',
            ];
        }
    }

    /**
     * Send SMS using Twilio
     */
    protected function sendTwilioSMS($phoneNumber, $code)
    {
        try {
            $twilio = new \Twilio\Rest\Client(
                $this->config['account_sid'],
                $this->config['auth_token']
            );

            $message = $twilio->messages->create(
                $phoneNumber,
                [
                    'from' => $this->config['from_number'],
                    'body' => "Your Laravel Marketplace verification code is: {$code}. Valid for 5 minutes.",
                ]
            );

            return [
                'success' => true,
                'message_id' => $message->sid,
            ];

        } catch (\Twilio\Exceptions\TwilioException $e) {
            return [
                'success' => false,
                'error' => 'Twilio error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS using VIP (Serbian provider)
     */
    protected function sendVipSMS($phoneNumber, $code)
    {
        try {
            // VIP SMS API integration
            $url = $this->config['api_url'];
            $data = [
                'username' => $this->config['username'],
                'password' => $this->config['password'],
                'to' => $phoneNumber,
                'message' => "Your Laravel Marketplace verification code is: {$code}. Valid for 5 minutes.",
                'from' => $this->config['from_name'],
            ];

            $response = $this->makeHttpRequest($url, $data);

            if ($response['success']) {
                return [
                    'success' => true,
                    'message_id' => $response['data']['message_id'] ?? 'vip_' . uniqid(),
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'VIP SMS error: ' . $response['error'],
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'VIP SMS error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS using Telenor (Serbian provider)
     */
    protected function sendTelenorSMS($phoneNumber, $code)
    {
        try {
            // Telenor SMS API integration
            $url = $this->config['api_url'];
            $data = [
                'api_key' => $this->config['api_key'],
                'to' => $phoneNumber,
                'message' => "Your Laravel Marketplace verification code is: {$code}. Valid for 5 minutes.",
                'sender' => $this->config['sender_name'],
            ];

            $response = $this->makeHttpRequest($url, $data);

            if ($response['success']) {
                return [
                    'success' => true,
                    'message_id' => $response['data']['message_id'] ?? 'telenor_' . uniqid(),
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Telenor SMS error: ' . $response['error'],
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Telenor SMS error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS based on provider
     */
    protected function sendSMS($phoneNumber, $code)
    {
        switch ($this->provider) {
            case 'twilio':
                return $this->sendTwilioSMS($phoneNumber, $code);
            case 'vip':
                return $this->sendVipSMS($phoneNumber, $code);
            case 'telenor':
                return $this->sendTelenorSMS($phoneNumber, $code);
            default:
                throw new Exception("Unsupported SMS provider: {$this->provider}");
        }
    }

    /**
     * Validate Serbian phone number format
     */
    protected function isValidSerbianPhone($phoneNumber)
    {
        // Remove all non-digit characters
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Serbian phone number patterns
        $patterns = [
            '/^\+381[0-9]{8,9}$/',  // +381XXXXXXXXX
            '/^381[0-9]{8,9}$/',    // 381XXXXXXXXX
            '/^0[0-9]{8,9}$/',      // 0XXXXXXXXX
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleaned)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate 6-digit verification code
     */
    protected function generateVerificationCode()
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check rate limiting for phone number
     */
    protected function checkRateLimit($phoneNumber)
    {
        $key = "sms_rate_limit:{$phoneNumber}";
        $attempts = Cache::get($key, 0);
        
        return $attempts < 3; // Max 3 codes per hour
    }

    /**
     * Update rate limiting for phone number
     */
    protected function updateRateLimit($phoneNumber)
    {
        $key = "sms_rate_limit:{$phoneNumber}";
        $attempts = Cache::get($key, 0);
        
        Cache::put($key, $attempts + 1, now()->addHour());
    }

    /**
     * Check IP-based fraud protection
     */
    protected function checkFraudProtection($ipAddress)
    {
        $key = "sms_fraud_protection:{$ipAddress}";
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= 10) { // Max 10 SMS per IP per hour
            return false;
        }

        Cache::put($key, $attempts + 1, now()->addHour());
        return true;
    }

    /**
     * Make HTTP request to SMS provider
     */
    protected function makeHttpRequest($url, $data)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                return [
                    'success' => true,
                    'data' => $data,
                ];
            } else {
                return [
                    'success' => false,
                    'error' => "HTTP error: {$httpCode}",
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get verification code status
     */
    public function getVerificationStatus($phoneNumber)
    {
        $verificationCode = VerificationCode::where('phone_number', $phoneNumber)
            ->where('verified', true)
            ->latest()
            ->first();

        return $verificationCode ? [
            'verified' => true,
            'verified_at' => $verificationCode->verified_at,
        ] : [
            'verified' => false,
        ];
    }

    /**
     * Clean up expired verification codes
     */
    public function cleanupExpiredCodes()
    {
        $deleted = VerificationCode::where('expires_at', '<', now())
            ->orWhere('created_at', '<', now()->subDay())
            ->delete();

        Log::info("Cleaned up {$deleted} expired verification codes");
        return $deleted;
    }
}
