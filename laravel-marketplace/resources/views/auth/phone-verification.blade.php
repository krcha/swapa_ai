@extends('layouts.app')

@section('title', 'Phone Verification - Laravel Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Verify Your Phone</h1>
            <p class="mt-2 text-gray-600">We'll send you a verification code to confirm your phone number</p>
        </div>

        <!-- Phone Verification Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form id="phone-verification-form" class="p-6">
                @csrf
                
                <!-- Phone Number Input -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">+381</span>
                        </div>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="block w-full pl-16 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="123456789"
                            value="{{ old('phone', auth()->user()->phone ?? '') }}"
                            required
                        >
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Enter your Serbian phone number (e.g., +381123456789 or 0123456789)
                    </p>
                    <div id="phone-error" class="mt-1 text-sm text-red-600 hidden"></div>
                </div>

                <!-- SMS Provider Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">SMS Provider</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="provider" value="twilio" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Twilio (International)</div>
                                <div class="text-sm text-gray-500">Reliable global SMS service</div>
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="provider" value="vip" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">VIP Mobile</div>
                                <div class="text-sm text-gray-500">Serbian mobile operator</div>
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="provider" value="telenor" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Telenor</div>
                                <div class="text-sm text-gray-500">Serbian mobile operator</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="send-code-btn" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="button-text">Send Verification Code</span>
                    <span id="spinner" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>

                <!-- Security Notice -->
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-green-800">
                            <strong>Secure:</strong> Your phone number is encrypted and only used for verification. We never share your data.
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Code Verification Form (Hidden Initially) -->
        <div id="code-verification-form" class="bg-white shadow-lg rounded-lg overflow-hidden mt-6 hidden">
            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Enter Verification Code</h2>
                    <p class="mt-2 text-gray-600">We sent a 6-digit code to <span id="verification-phone"></span></p>
                </div>

                <form id="verify-code-form">
                    @csrf
                    <input type="hidden" id="verification-phone-input" name="phone">
                    
                    <!-- Code Input -->
                    <div class="mb-6">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Verification Code
                        </label>
                        <input 
                            type="text" 
                            id="code" 
                            name="code" 
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-2xl font-mono tracking-widest" 
                            placeholder="123456"
                            maxlength="6"
                            required
                        >
                        <div id="code-error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>

                    <!-- Timer -->
                    <div class="mb-6 text-center">
                        <div id="timer" class="text-sm text-gray-500">
                            Code expires in <span id="countdown">5:00</span>
                        </div>
                    </div>

                    <!-- Verify Button -->
                    <button type="submit" id="verify-code-btn" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="verify-button-text">Verify Code</span>
                        <span id="verify-spinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Verifying...
                        </span>
                    </button>

                    <!-- Resend Code -->
                    <div class="mt-4 text-center">
                        <button type="button" id="resend-code-btn" class="text-blue-600 hover:text-blue-500 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Resend Code
                        </button>
                        <div id="resend-timer" class="text-xs text-gray-500 mt-1 hidden">
                            Resend available in <span id="resend-countdown">60</span> seconds
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Message (Hidden Initially) -->
        <div id="success-message" class="bg-green-50 border border-green-200 rounded-lg p-6 mt-6 hidden">
            <div class="flex items-center">
                <svg class="h-8 w-8 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Phone Verified Successfully!</h3>
                    <p class="text-sm text-green-600 mt-1">Your phone number has been verified and your account is now fully activated.</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Continue to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneForm = document.getElementById('phone-verification-form');
    const codeForm = document.getElementById('code-verification-form');
    const successMessage = document.getElementById('success-message');
    const sendCodeBtn = document.getElementById('send-code-btn');
    const verifyCodeBtn = document.getElementById('verify-code-btn');
    const resendCodeBtn = document.getElementById('resend-code-btn');
    
    let countdownInterval;
    let resendCountdownInterval;
    let expiresAt;

    // Phone form submission
    phoneForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const phone = document.getElementById('phone').value;
        const provider = document.querySelector('input[name="provider"]:checked').value;
        
        // Validate phone number
        if (!validatePhoneNumber(phone)) {
            showError('phone-error', 'Please enter a valid Serbian phone number');
            return;
        }

        // Show loading state
        setLoading(sendCodeBtn, true);

        try {
            const response = await fetch('{{ route("phone.verification.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    phone: phone,
                    provider: provider,
                }),
            });

            const result = await response.json();

            if (result.success) {
                // Show code verification form
                document.getElementById('verification-phone').textContent = phone;
                document.getElementById('verification-phone-input').value = phone;
                phoneForm.classList.add('hidden');
                codeForm.classList.remove('hidden');
                
                // Start countdown
                expiresAt = new Date(result.expires_at);
                startCountdown();
                
                // Start resend countdown
                startResendCountdown();
            } else {
                showError('phone-error', result.message);
            }
        } catch (error) {
            showError('phone-error', 'Failed to send verification code. Please try again.');
        } finally {
            setLoading(sendCodeBtn, false);
        }
    });

    // Code verification form submission
    codeForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const code = document.getElementById('code').value;
        const phone = document.getElementById('verification-phone-input').value;
        
        if (code.length !== 6) {
            showError('code-error', 'Please enter a 6-digit verification code');
            return;
        }

        // Show loading state
        setLoading(verifyCodeBtn, true);

        try {
            const response = await fetch('{{ route("phone.verification.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    code: code,
                    phone: phone,
                }),
            });

            const result = await response.json();

            if (result.success) {
                // Show success message
                codeForm.classList.add('hidden');
                successMessage.classList.remove('hidden');
                
                // Clear intervals
                if (countdownInterval) clearInterval(countdownInterval);
                if (resendCountdownInterval) clearInterval(resendCountdownInterval);
            } else {
                showError('code-error', result.message);
            }
        } catch (error) {
            showError('code-error', 'Failed to verify code. Please try again.');
        } finally {
            setLoading(verifyCodeBtn, false);
        }
    });

    // Resend code
    resendCodeBtn.addEventListener('click', async function() {
        const phone = document.getElementById('verification-phone-input').value;
        const provider = document.querySelector('input[name="provider"]:checked').value;
        
        setLoading(resendCodeBtn, true);

        try {
            const response = await fetch('{{ route("phone.verification.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    phone: phone,
                    provider: provider,
                }),
            });

            const result = await response.json();

            if (result.success) {
                // Restart countdowns
                expiresAt = new Date(result.expires_at);
                startCountdown();
                startResendCountdown();
                
                // Clear code input
                document.getElementById('code').value = '';
            } else {
                showError('code-error', result.message);
            }
        } catch (error) {
            showError('code-error', 'Failed to resend code. Please try again.');
        } finally {
            setLoading(resendCodeBtn, false);
        }
    });

    // Helper functions
    function validatePhoneNumber(phone) {
        const pattern = /^(\+381|381|0)[0-9]{8,9}$/;
        return pattern.test(phone.replace(/\s/g, ''));
    }

    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    function setLoading(button, loading) {
        const buttonText = button.querySelector('#button-text, #verify-button-text');
        const spinner = button.querySelector('#spinner, #verify-spinner');
        
        if (loading) {
            button.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
        } else {
            button.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    }

    function startCountdown() {
        if (countdownInterval) clearInterval(countdownInterval);
        
        countdownInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = expiresAt.getTime() - now;
            
            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById('countdown').textContent = 'Expired';
                verifyCodeBtn.disabled = true;
            } else {
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById('countdown').textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            }
        }, 1000);
    }

    function startResendCountdown() {
        if (resendCountdownInterval) clearInterval(resendCountdownInterval);
        
        resendCodeBtn.disabled = true;
        document.getElementById('resend-timer').classList.remove('hidden');
        
        let timeLeft = 60;
        resendCountdownInterval = setInterval(function() {
            timeLeft--;
            document.getElementById('resend-countdown').textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(resendCountdownInterval);
                resendCodeBtn.disabled = false;
                document.getElementById('resend-timer').classList.add('hidden');
            }
        }, 1000);
    }
});
</script>
@endsection
