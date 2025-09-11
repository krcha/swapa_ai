@extends('layouts.app')

@section('title', 'Sign Up - PhoneMarket')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center">
                    <span class="text-white font-bold text-2xl">📱</span>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Create your account</h2>
            <p class="mt-2 text-gray-600">Join thousands of phone sellers on PhoneMarket</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Account Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Account Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <input type="radio" id="personal" name="user_type" value="personal" 
                                   class="sr-only peer" {{ old('user_type', 'personal') == 'personal' ? 'checked' : '' }}>
                            <label for="personal" class="flex flex-col items-center justify-center w-full p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-50">
                                <div class="text-2xl mb-2">👤</div>
                                <div class="text-sm font-medium">Personal</div>
                                <div class="text-xs text-center">For individual sellers</div>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" id="business" name="user_type" value="business" 
                                   class="sr-only peer" {{ old('user_type') == 'business' ? 'checked' : '' }}>
                            <label for="business" class="flex flex-col items-center justify-center w-full p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-50">
                                <div class="text-2xl mb-2">🏢</div>
                                <div class="text-sm font-medium">Business</div>
                                <div class="text-xs text-center">For companies & stores</div>
                            </label>
                        </div>
                    </div>
                    @if($errors && $errors->has('user_type'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('user_type') }}</p>
                    @endif
                </div>

                <!-- Business Fields (Hidden by default) -->
                <div id="business-fields" class="space-y-4" style="display: none;">
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
                        
                        <!-- Business Name -->
                        <div class="mb-4">
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input id="business_name" name="business_name" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_name')) border-red-500 @endif"
                                   placeholder="Your business name"
                                   value="{{ old('business_name') }}">
                            @if($errors && $errors->has('business_name'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_name') }}</p>
                            @endif
                        </div>

                        <!-- Business Registration Number -->
                        <div class="mb-4">
                            <label for="business_registration_number" class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                            <input id="business_registration_number" name="business_registration_number" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_registration_number')) border-red-500 @endif"
                                   placeholder="Business registration number"
                                   value="{{ old('business_registration_number') }}">
                            @if($errors && $errors->has('business_registration_number'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_registration_number') }}</p>
                            @endif
                        </div>

                        <!-- Business Tax ID -->
                        <div class="mb-4">
                            <label for="business_tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
                            <input id="business_tax_id" name="business_tax_id" type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_tax_id')) border-red-500 @endif"
                                   placeholder="Business tax identification number"
                                   value="{{ old('business_tax_id') }}">
                            @if($errors && $errors->has('business_tax_id'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_tax_id') }}</p>
                            @endif
                        </div>

                        <!-- Business Address -->
                        <div class="mb-4">
                            <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                            <textarea id="business_address" name="business_address" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_address')) border-red-500 @endif"
                                      placeholder="Full business address">{{ old('business_address') }}</textarea>
                            @if($errors && $errors->has('business_address'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_address') }}</p>
                            @endif
                        </div>

                        <!-- Business City and Country -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="business_city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input id="business_city" name="business_city" type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_city')) border-red-500 @endif"
                                       placeholder="City"
                                       value="{{ old('business_city') }}">
                                @if($errors && $errors->has('business_city'))
                                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_city') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="business_country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input id="business_country" name="business_country" type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_country')) border-red-500 @endif"
                                       placeholder="Country"
                                       value="{{ old('business_country') }}">
                                @if($errors && $errors->has('business_country'))
                                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_country') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Business Phone and Email -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">Business Phone</label>
                                <input id="business_phone" name="business_phone" type="tel" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_phone')) border-red-500 @endif"
                                       placeholder="Business phone number"
                                       value="{{ old('business_phone') }}">
                                @if($errors && $errors->has('business_phone'))
                                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_phone') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="business_email" class="block text-sm font-medium text-gray-700 mb-2">Business Email</label>
                                <input id="business_email" name="business_email" type="email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_email')) border-red-500 @endif"
                                       placeholder="Business email address"
                                       value="{{ old('business_email') }}">
                                @if($errors && $errors->has('business_email'))
                                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_email') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Business Website -->
                        <div class="mb-4">
                            <label for="business_website" class="block text-sm font-medium text-gray-700 mb-2">Website (Optional)</label>
                            <input id="business_website" name="business_website" type="url" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('business_website')) border-red-500 @endif"
                                   placeholder="https://your-website.com"
                                   value="{{ old('business_website') }}">
                            @if($errors && $errors->has('business_website'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('business_website') }}</p>
                            @endif
                        </div>

                        <!-- Subscription Tier Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Choose Your Plan</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative">
                                    <input type="radio" id="tier_2" name="subscription_tier" value="tier_2" 
                                           class="sr-only peer" {{ old('subscription_tier') == 'tier_2' ? 'checked' : '' }}>
                                    <label for="tier_2" class="flex flex-col items-center justify-center w-full p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-50">
                                        <div class="text-lg font-bold mb-1">Tier 2</div>
                                        <div class="text-sm">Standard Business</div>
                                        <div class="text-xs text-center">Priority listings</div>
                                    </label>
                                </div>
                                <div class="relative">
                                    <input type="radio" id="tier_3" name="subscription_tier" value="tier_3" 
                                           class="sr-only peer" {{ old('subscription_tier') == 'tier_3' ? 'checked' : '' }}>
                                    <label for="tier_3" class="flex flex-col items-center justify-center w-full p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-50">
                                        <div class="text-lg font-bold mb-1">Tier 3</div>
                                        <div class="text-sm">Premium Business</div>
                                        <div class="text-xs text-center">Maximum visibility</div>
                                    </label>
                                </div>
                            </div>
                            @if($errors && $errors->has('subscription_tier'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('subscription_tier') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input id="first_name" name="first_name" type="text" autocomplete="given-name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('first_name')) border-red-500 @endif"
                               placeholder="First name"
                               value="{{ old('first_name') }}">
                        @if($errors && $errors->has('first_name'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('first_name') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input id="last_name" name="last_name" type="text" autocomplete="family-name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('last_name')) border-red-500 @endif"
                               placeholder="Last name"
                               value="{{ old('last_name') }}">
                        @if($errors && $errors->has('last_name'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('last_name') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('email')) border-red-500 @endif"
                           placeholder="Enter your email"
                           value="{{ old('email') }}">
                    @if($errors && $errors->has('email'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('phone')) border-red-500 @endif"
                           placeholder="+381601234567"
                           value="{{ old('phone') }}">
                    <p class="mt-1 text-xs text-gray-500">We'll send you a verification code</p>
                    @if($errors && $errors->has('phone'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('phone') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('password')) border-red-500 @endif"
                               placeholder="Create a strong password">
                        <button type="button" onclick="togglePassword()" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="text-xs text-gray-500">Password strength:</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="password-strength" class="bg-gray-400 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                    @if($errors && $errors->has('password'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @if($errors && $errors->has('password_confirmation')) border-red-500 @endif"
                           placeholder="Confirm your password">
                    @if($errors && $errors->has('password_confirmation'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <!-- Terms & Privacy -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-700">
                            I agree to the 
                            <a href="#" class="text-primary-600 hover:text-primary-500">Terms of Service</a> 
                            and 
                            <a href="#" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-primary-600 text-white py-3 px-4 rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-medium transition-colors">
                    Create account
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>
            </div>

            <!-- Social Login -->
            <div class="mt-6 grid grid-cols-2 gap-3">
                <button class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="ml-2">Google</span>
                </button>
                <button class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="ml-2">Facebook</span>
                </button>
            </div>

            <!-- Sign In Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                        Sign in
                    </a>
                </p>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="text-center">
            <div class="flex justify-center items-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    Secure
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Verified
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Trusted
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('password-strength');
    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    
    strengthBar.style.width = strength + '%';
    
    if (strength < 50) {
        strengthBar.className = 'bg-red-400 h-2 rounded-full transition-all duration-300';
    } else if (strength < 75) {
        strengthBar.className = 'bg-yellow-400 h-2 rounded-full transition-all duration-300';
    } else {
        strengthBar.className = 'bg-green-400 h-2 rounded-full transition-all duration-300';
    }
});

// Show/hide business fields based on account type
document.addEventListener('DOMContentLoaded', function() {
    const personalRadio = document.getElementById('personal');
    const businessRadio = document.getElementById('business');
    const businessFields = document.getElementById('business-fields');
    
    function toggleBusinessFields() {
        if (businessRadio.checked) {
            businessFields.style.display = 'block';
        } else {
            businessFields.style.display = 'none';
        }
    }
    
    personalRadio.addEventListener('change', toggleBusinessFields);
    businessRadio.addEventListener('change', toggleBusinessFields);
    
    // Initialize on page load
    toggleBusinessFields();
});
</script>
@endsection