@extends('layouts.app')

@section('title', 'Invoice - Laravel Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Invoice Header -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                        <p class="text-sm text-gray-500">Invoice #{{ $invoice->invoice_number ?? 'INV-000001' }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Status</div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ ucfirst($invoice->status ?? 'Paid') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Invoice Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Invoice Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Bill To</h3>
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                                    <div>{{ auth()->user()->email }}</div>
                                    <div>{{ auth()->user()->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Invoice Information</h3>
                                <div class="text-sm text-gray-900">
                                    <div><strong>Invoice Number:</strong> {{ $invoice->invoice_number ?? 'INV-000001' }}</div>
                                    <div><strong>Issue Date:</strong> {{ $invoice->created_at ? $invoice->created_at->format('M d, Y') : 'N/A' }}</div>
                                    <div><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</div>
                                    <div><strong>Paid Date:</strong> {{ $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Details -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Subscription Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Plan Information</h3>
                                <div class="text-sm text-gray-900">
                                    <div><strong>Plan:</strong> {{ $subscription->plan->name ?? 'Pro Plan' }}</div>
                                    <div><strong>Billing Cycle:</strong> Monthly</div>
                                    <div><strong>Subscription ID:</strong> {{ $subscription->id ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Features</h3>
                                <div class="text-sm text-gray-900">
                                    <div>• {{ $subscription->plan->listing_limit_description ?? 'Unlimited listings' }}</div>
                                    <div>• Priority support</div>
                                    <div>• Advanced analytics</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Subtotal</span>
                                <span class="text-sm font-medium text-gray-900">{{ $invoice->formatted_amount ?? 'RSD 2,990.00' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tax (VAT 20%)</span>
                                <span class="text-sm font-medium text-gray-900">RSD 0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Discount</span>
                                <span class="text-sm font-medium text-gray-900">-RSD 0.00</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-bold text-blue-600">{{ $invoice->formatted_amount ?? 'RSD 2,990.00' }}</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Payment Method</h3>
                            <div class="text-sm text-gray-900">
                                <div>{{ $payment->gateway ?? 'Stripe' }}</div>
                                <div class="text-gray-500">{{ $payment->gateway_payment_id ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 space-y-3">
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download PDF
                            </button>
                            <button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                Share Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Invoice Items</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $subscription->plan->name ?? 'Pro Plan' }}</div>
                                <div class="text-sm text-gray-500">{{ $subscription->plan->description ?? 'Monthly subscription' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : 'N/A' }} - 
                                {{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->formatted_amount ?? 'RSD 2,990.00' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $invoice->formatted_amount ?? 'RSD 2,990.00' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Thank you for your business! If you have any questions about this invoice, please contact our support team.</p>
            <p class="mt-2">
                <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-500">Contact Support</a> | 
                <a href="{{ route('payment.history') }}" class="text-blue-600 hover:text-blue-500">Payment History</a>
            </p>
        </div>
    </div>
</div>
@endsection
