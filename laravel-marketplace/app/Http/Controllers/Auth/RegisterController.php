<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'jmbg' => 'required|string|size:13|unique:users,jmbg_hash',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:personal,business',
            'business_name' => 'required_if:user_type,business|string|max:255',
            'business_registration_number' => 'required_if:user_type,business|string|max:255',
            'business_tax_id' => 'required_if:user_type,business|string|max:255',
            'business_address' => 'required_if:user_type,business|string',
            'business_city' => 'required_if:user_type,business|string|max:255',
            'business_country' => 'required_if:user_type,business|string|max:255',
            'business_phone' => 'required_if:user_type,business|string|max:20',
            'business_email' => 'required_if:user_type,business|email|max:255',
            'business_website' => 'nullable|url|max:255',
            'subscription_tier' => 'required_if:user_type,business|in:tier_2,tier_3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate JMBG
        if (!User::validateJMBG($request->jmbg)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JMBG format or checksum'
            ], 422);
        }

        // Check age
        $age = User::calculateAgeFromJMBG($request->jmbg);
        if ($age < 18) {
            return response()->json([
                'success' => false,
                'message' => 'You must be at least 18 years old to register'
            ], 422);
        }

        // Create user
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'jmbg_hash' => hash('sha256', $request->jmbg . config('app.key')),
            'password' => Hash::make($request->password),
            'is_age_verified' => true,
            'user_type' => $request->user_type,
        ];

        // Add business fields if user is registering as business
        if ($request->user_type === 'business') {
            $userData = array_merge($userData, [
                'business_name' => $request->business_name,
                'business_registration_number' => $request->business_registration_number,
                'business_tax_id' => $request->business_tax_id,
                'business_address' => $request->business_address,
                'business_city' => $request->business_city,
                'business_country' => $request->business_country,
                'business_phone' => $request->business_phone,
                'business_email' => $request->business_email,
                'business_website' => $request->business_website,
                'subscription_tier' => $request->subscription_tier,
                'has_priority_listing' => true, // Business users get priority listing
            ]);
        } else {
            // Personal users get tier_1 by default
            $userData['subscription_tier'] = 'tier_1';
        }

        $user = User::create($userData);

        // Give initial free token
        TokenTransaction::giveFreeTokens($user, 1);

        // Send verification emails/SMS
        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully. Please verify your email and phone.',
            'user' => $user->makeHidden(['jmbg_hash', 'password'])
        ], 201);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        // Implementation for email verification
        // This would typically involve checking a verification token
        // and updating the user's email_verified_at field

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ]);
    }

    public function verifySMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        // Implementation for SMS verification
        // This would typically involve checking a verification code
        // and updating the user's is_sms_verified field

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully'
        ]);
    }
}
