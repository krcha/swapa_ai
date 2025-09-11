@extends('layouts.app')

@section('title', 'Payment - Laravel Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Complete Payment</h1>
            <p class="mt-2 text-gray-600">Secure payment processing for your subscription</p>
        </div>

        <!-- Payment Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Plan Details -->
            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $plan->description }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">{{ $plan->formatted_price }}</div>
                        <div class="text-sm text-gray-500">per month</div>
                    </div>
                </div>
            </div>

            <form id="payment-form" class="p-6">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="gateway" value="stripe">

                <!-- Payment Method Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_gateway" value="stripe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Credit/Debit Card</div>
                                <div class="text-sm text-gray-500">Visa, Mastercard, American Express</div>
                            </div>
                            <div class="ml-auto">
                                <img src="{{ asset('images/stripe-logo.png') }}" alt="Stripe" class="h-6">
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_gateway" value="nlb" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">NLB Bank</div>
                                <div class="text-sm text-gray-500">Serbian bank payment</div>
                            </div>
                            <div class="ml-auto">
                                <img src="{{ asset('images/nlb-logo.png') }}" alt="NLB" class="h-6">
                            </div>
                        </label>

                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_gateway" value="intesa" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Banca Intesa</div>
                                <div class="text-sm text-gray-500">Serbian bank payment</div>
                            </div>
                            <div class="ml-auto">
                                <img src="{{ asset('images/intesa-logo.png') }}" alt="Banca Intesa" class="h-6">
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Stripe Card Element -->
                <div id="stripe-card-element" class="mb-6" style="display: none;">
                    <div class="p-4 border border-gray-300 rounded-lg">
                        <div id="card-element">
                            <!-- Stripe Elements will create form elements here -->
                        </div>
                        <div id="card-errors" class="mt-2 text-sm text-red-600"></div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Plan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plan->name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Billing Cycle</span>
                        <span class="text-sm font-medium text-gray-900">Monthly</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Listing Limit</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plan->listing_limit_description }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-blue-600">{{ $plan->formatted_price }}</span>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-green-800">
                            <strong>Secure Payment:</strong> Your payment information is encrypted and processed securely. We never store your card details.
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-button" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="button-text">Complete Payment</span>
                    <span id="spinner" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>

                <!-- Terms -->
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500">
                        By completing this payment, you agree to our 
                        <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-500">Terms of Service</a> 
                        and 
                        <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stripe Scripts -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const cardElement = document.getElementById('stripe-card-element');
    const paymentGateways = document.querySelectorAll('input[name="payment_gateway"]');

    let stripe;
    let elements;
    let cardElement;

    // Initialize Stripe
    function initializeStripe() {
        stripe = Stripe('{{ config("payment.gateways.stripe.publishable_key") }}');
        elements = stripe.elements();
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
            },
        });
        cardElement.mount('#card-element');

        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    }

    // Show/hide Stripe card element based on payment method
    function togglePaymentMethod() {
        const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
        
        if (selectedGateway === 'stripe') {
            cardElement.style.display = 'block';
            if (!stripe) {
                initializeStripe();
            }
        } else {
            cardElement.style.display = 'none';
        }
    }

    // Add event listeners
    paymentGateways.forEach(gateway => {
        gateway.addEventListener('change', togglePaymentMethod);
    });

    // Initialize on page load
    togglePaymentMethod();

    // Handle form submission
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');

        try {
            if (selectedGateway === 'stripe') {
                // Handle Stripe payment
                const {error, paymentMethod} = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (error) {
                    throw new Error(error.message);
                }

                // Submit to server
                const response = await fetch('{{ route("subscription.subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        plan_id: document.querySelector('input[name="plan_id"]').value,
                        payment_method_id: paymentMethod.id,
                        gateway: 'stripe',
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = '{{ route("dashboard") }}?payment=success';
                } else {
                    throw new Error(result.message);
                }
            } else {
                // Handle Serbian bank payments
                const response = await fetch('{{ route("subscription.subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        plan_id: document.querySelector('input[name="plan_id"]').value,
                        payment_method_id: 'bank_payment',
                        gateway: selectedGateway,
                    }),
                });

                const result = await response.json();

                if (result.success && result.payment.redirect_url) {
                    window.location.href = result.payment.redirect_url;
                } else {
                    throw new Error(result.message);
                }
            }
        } catch (error) {
            alert('Payment failed: ' + error.message);
        } finally {
            // Reset button state
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });
});
</script>
@endsection
