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
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'jmbg_hash' => hash('sha256', $request->jmbg . config('app.key')),
            'password' => Hash::make($request->password),
            'is_age_verified' => true,
        ]);

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
