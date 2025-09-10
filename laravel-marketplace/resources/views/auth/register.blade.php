@extends('layouts.app')

@section('title', 'Register - PhoneMarket')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary-100">
                <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Join the Serbian phone marketplace
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}" x-data="registrationForm()">
            @csrf
            
            <div class="space-y-4">
                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input id="first_name" name="first_name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm @error('first_name') border-red-500 @enderror"
                               value="{{ old('first_name') }}" placeholder="Enter your first name">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm @error('last_name') border-red-500 @enderror"
                               value="{{ old('last_name') }}" placeholder="Enter your last name">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}" placeholder="Enter your email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">+381</span>
                        </div>
                        <input id="phone" name="phone" type="tel" required 
                               class="appearance-none relative block w-full pl-12 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm @error('phone') border-red-500 @enderror"
                               value="{{ old('phone') }}" placeholder="601234567" pattern="[0-9]{8,9}">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format: +381XXXXXXXX</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- JMBG -->
                <div>
                    <label for="jmbg" class="block text-sm font-medium text-gray-700">JMBG (Serbian ID)</label>
                    <input id="jmbg" name="jmbg" type="text" required maxlength="13" 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm @error('jmbg') border-red-500 @enderror"
                           value="{{ old('jmbg') }}" placeholder="1234567890123" pattern="[0-9]{13}"
                           x-model="jmbg" @input="validateJMBG()">
                    <div x-show="jmbgError" class="mt-1 text-sm text-red-600" x-text="jmbgError"></div>
                    <div x-show="jmbgValid" class="mt-1 text-sm text-green-600">✓ Valid JMBG format</div>
                    <p class="mt-1 text-xs text-gray-500">13-digit Serbian national ID number</p>
                    @error('jmbg')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="Enter your password" x-model="password" @input="validatePassword()">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showPassword = !showPassword" class="text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div x-show="passwordError" class="mt-1 text-sm text-red-600" x-text="passwordError"></div>
                    <div x-show="passwordValid" class="mt-1 text-sm text-green-600">✓ Strong password</div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                           placeholder="Confirm your password" x-model="passwordConfirmation" @input="validatePasswordMatch()">
                    <div x-show="passwordMatchError" class="mt-1 text-sm text-red-600" x-text="passwordMatchError"></div>
                    <div x-show="passwordMatchValid" class="mt-1 text-sm text-green-600">✓ Passwords match</div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input id="terms_accepted" name="terms_accepted" type="checkbox" required 
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded @error('terms_accepted') border-red-500 @enderror">
                    <label for="terms_accepted" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-primary-600 hover:text-primary-500">Terms of Service</a> and <a href="#" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
                    </label>
                </div>
                @error('terms_accepted')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!isFormValid()">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-primary-500 group-hover:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Create Account
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Sign in here</a>
                </p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function registrationForm() {
    return {
        jmbg: '',
        password: '',
        passwordConfirmation: '',
        showPassword: false,
        jmbgError: '',
        jmbgValid: false,
        passwordError: '',
        passwordValid: false,
        passwordMatchError: '',
        passwordMatchValid: false,

        validateJMBG() {
            if (this.jmbg.length === 0) {
                this.jmbgError = '';
                this.jmbgValid = false;
                return;
            }

            if (this.jmbg.length !== 13 || !/^\d{13}$/.test(this.jmbg)) {
                this.jmbgError = 'JMBG must be exactly 13 digits';
                this.jmbgValid = false;
                return;
            }

            // Basic JMBG validation
            const weights = [7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
            let sum = 0;
            
            for (let i = 0; i < 12; i++) {
                sum += parseInt(this.jmbg[i]) * weights[i];
            }
            
            const remainder = sum % 11;
            const checkDigit = remainder < 2 ? remainder : 11 - remainder;
            
            if (checkDigit !== parseInt(this.jmbg[12])) {
                this.jmbgError = 'Invalid JMBG checksum';
                this.jmbgValid = false;
                return;
            }

            this.jmbgError = '';
            this.jmbgValid = true;
        },

        validatePassword() {
            if (this.password.length === 0) {
                this.passwordError = '';
                this.passwordValid = false;
                return;
            }

            const hasUpperCase = /[A-Z]/.test(this.password);
            const hasLowerCase = /[a-z]/.test(this.password);
            const hasNumbers = /\d/.test(this.password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(this.password);
            const isLongEnough = this.password.length >= 8;

            if (!isLongEnough) {
                this.passwordError = 'Password must be at least 8 characters long';
                this.passwordValid = false;
                return;
            }

            if (!hasUpperCase || !hasLowerCase || !hasNumbers || !hasSpecialChar) {
                this.passwordError = 'Password must contain uppercase, lowercase, number, and special character';
                this.passwordValid = false;
                return;
            }

            this.passwordError = '';
            this.passwordValid = true;
        },

        validatePasswordMatch() {
            if (this.passwordConfirmation.length === 0) {
                this.passwordMatchError = '';
                this.passwordMatchValid = false;
                return;
            }

            if (this.password !== this.passwordConfirmation) {
                this.passwordMatchError = 'Passwords do not match';
                this.passwordMatchValid = false;
                return;
            }

            this.passwordMatchError = '';
            this.passwordMatchValid = true;
        },

        isFormValid() {
            return this.jmbgValid && this.passwordValid && this.passwordMatchValid;
        }
    }
}
</script>
@endpush
@endsection
