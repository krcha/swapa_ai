<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:20|unique:users',
                'jmbg' => ['required', 'string', 'size:13', 'unique:users,jmbg_hash', new \App\Http\Requests\ValidJMBG()],
                'password' => 'required|string|min:8|confirmed',
                'terms_accepted' => 'required|accepted',
            ], [
                'jmbg.unique' => 'This JMBG is already registered.',
                'terms_accepted.accepted' => 'You must accept the terms and conditions.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate JMBG format and checksum
            if (!User::validateJMBG($request->jmbg)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JMBG format or checksum'
                ], 422);
            }

            // Check age requirement
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

            // Log registration
            Log::info('User registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone
            ]);

            // Send verification emails/SMS
            event(new Registered($user));

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully. Please verify your email and phone.',
                'data' => [
                    'user' => $user->makeHidden(['jmbg_hash', 'password']),
                    'verification_required' => [
                        'email' => true,
                        'sms' => true
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['password', 'jmbg'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            // Log successful login
            Log::info('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user->makeHidden(['jmbg_hash', 'password']),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Login failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            Log::info('User logged out', [
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);

        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Logout failed'
            ], 500);
        }
    }

    /**
     * Get current user profile
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->makeHidden(['jmbg_hash', 'password']),
                    'token_balance' => $user->token_balance,
                    'verification_status' => [
                        'email' => $user->is_email_verified,
                        'sms' => $user->is_sms_verified,
                        'age' => $user->is_age_verified,
                        'overall' => $user->isFullyVerified()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Profile fetch failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile'
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:20|unique:users,phone,' . $user->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update($request->only(['first_name', 'last_name', 'phone']));

            Log::info('Profile updated', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($request->only(['first_name', 'last_name', 'phone']))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user->makeHidden(['jmbg_hash', 'password'])
            ]);

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Profile update failed'
            ], 500);
        }
    }
}
