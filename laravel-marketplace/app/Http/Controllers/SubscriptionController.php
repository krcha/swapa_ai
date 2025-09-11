<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display available subscription plans
     */
    public function index()
    {
        $plans = Plan::active()->orderBy('price')->get();
        
        return response()->json([
            'success' => true,
            'plans' => $plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'formatted_price' => $plan->formatted_price,
                    'listing_limit' => $plan->listing_limit,
                    'listing_limit_description' => $plan->listing_limit_description,
                    'features' => $plan->features,
                    'trial_days' => $plan->trial_days,
                    'description' => $plan->description,
                ];
            })
        ]);
    }

    /**
     * Subscribe user to a plan
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method_id' => 'required|string',
            'gateway' => 'required|in:stripe,nlb,intesa',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Check if user already has an active subscription
        $activeSubscription = $user->activeSubscription();
        if ($activeSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription. Please cancel it first before subscribing to a new plan.'
            ], 400);
        }

        // Check if user has phone verification (required for all plans)
        if (!$user->hasPhoneVerification()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone verification is required before subscribing to any plan.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
                'status' => $plan->trial_days > 0 ? Subscription::STATUS_TRIALING : Subscription::STATUS_ACTIVE,
                'payment_method' => $request->payment_method_id,
                'billing_cycle' => 'monthly',
                'auto_renew' => true,
            ]);

            // Process payment if plan has a price
            if ($plan->price > 0) {
                $paymentService = new PaymentService($request->gateway);
                $paymentResult = $paymentService->processPayment($subscription, [
                    'payment_method_id' => $request->payment_method_id,
                    'gateway' => $request->gateway,
                ]);

                if (!$paymentResult['success']) {
                    throw new \Exception('Payment processing failed');
                }

                // Update subscription status based on payment
                if ($paymentResult['status'] === 'succeeded' || $paymentResult['status'] === 'completed') {
                    $subscription->update(['status' => Subscription::STATUS_ACTIVE]);
                } else {
                    $subscription->update(['status' => Subscription::STATUS_PAST_DUE]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to ' . $plan->name . ' plan.',
                'subscription' => [
                    'id' => $subscription->id,
                    'plan_name' => $plan->name,
                    'status' => $subscription->status,
                    'starts_at' => $subscription->starts_at,
                    'ends_at' => $subscription->ends_at,
                    'trial_ends_at' => $subscription->trial_ends_at,
                    'days_remaining' => $subscription->days_remaining,
                ],
                'payment' => $plan->price > 0 ? [
                    'payment_id' => $paymentResult['payment_id'] ?? null,
                    'status' => $paymentResult['status'] ?? 'pending',
                    'client_secret' => $paymentResult['client_secret'] ?? null,
                ] : null,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscription. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user's current subscription
     */
    public function current()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();
        $currentPlan = $user->currentPlan();

        if (!$subscription && !$currentPlan) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'plan_name' => $subscription->plan->name,
                'status' => $subscription->status,
                'starts_at' => $subscription->starts_at,
                'ends_at' => $subscription->ends_at,
                'trial_ends_at' => $subscription->trial_ends_at,
                'days_remaining' => $subscription->days_remaining,
                'trial_days_remaining' => $subscription->trial_days_remaining,
                'auto_renew' => $subscription->auto_renew,
            ] : null,
            'current_plan' => $currentPlan ? [
                'id' => $currentPlan->id,
                'name' => $currentPlan->name,
                'price' => $currentPlan->price,
                'formatted_price' => $currentPlan->formatted_price,
                'listing_limit' => $currentPlan->listing_limit,
                'listing_limit_description' => $currentPlan->listing_limit_description,
                'features' => $currentPlan->features,
            ] : null,
            'remaining_quota' => $user->getRemainingListingQuota(),
        ]);
    }

    /**
     * Cancel user's subscription
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found to cancel.'
            ], 404);
        }

        $subscription->cancel();

        return response()->json([
            'success' => true,
            'message' => 'Subscription canceled successfully. You can continue using your current plan until ' . $subscription->ends_at->format('M d, Y') . '.'
        ]);
    }

    /**
     * Renew user's subscription
     */
    public function renew(Request $request)
    {
        $user = Auth::user();
        $subscription = $user->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->where('ends_at', '>', now())
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found to renew.'
            ], 404);
        }

        if ($subscription->renew()) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription renewed successfully.',
                'subscription' => [
                    'id' => $subscription->id,
                    'plan_name' => $subscription->plan->name,
                    'ends_at' => $subscription->ends_at,
                    'days_remaining' => $subscription->days_remaining,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to renew subscription.'
        ], 500);
    }

    /**
     * Get subscription history
     */
    public function history()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'plan_name' => $subscription->plan->name,
                    'status' => $subscription->status,
                    'starts_at' => $subscription->starts_at,
                    'ends_at' => $subscription->ends_at,
                    'trial_ends_at' => $subscription->trial_ends_at,
                    'created_at' => $subscription->created_at,
                ];
            })
        ]);
    }

    /**
     * Get payment history
     */
    public function payments()
    {
        $user = Auth::user();
        $payments = $user->payments()
            ->with('subscription.plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'payments' => $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'formatted_amount' => $payment->formatted_amount,
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'gateway' => $payment->gateway,
                    'gateway_payment_id' => $payment->gateway_payment_id,
                    'payment_date' => $payment->payment_date,
                    'plan_name' => $payment->subscription->plan->name,
                    'created_at' => $payment->created_at,
                ];
            })
        ]);
    }

    /**
     * Process payment for subscription upgrade
     */
    public function upgrade(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method_id' => 'required|string',
            'gateway' => 'required|in:stripe,nlb,intesa',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);
        $currentSubscription = $user->activeSubscription();

        if (!$currentSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found to upgrade.'
            ], 404);
        }

        if ($currentSubscription->plan_id === $plan->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to this plan.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate prorated amount
            $daysRemaining = $currentSubscription->ends_at->diffInDays(now());
            $totalDays = $currentSubscription->starts_at->diffInDays($currentSubscription->ends_at);
            $proratedAmount = ($plan->price * $daysRemaining) / $totalDays;

            // Process payment
            $paymentService = new PaymentService($request->gateway);
            $paymentResult = $paymentService->processPayment($currentSubscription, [
                'payment_method_id' => $request->payment_method_id,
                'gateway' => $request->gateway,
                'amount' => $proratedAmount,
            ]);

            if (!$paymentResult['success']) {
                throw new \Exception('Payment processing failed');
            }

            // Update subscription
            $currentSubscription->update([
                'plan_id' => $plan->id,
                'status' => $paymentResult['status'] === 'succeeded' ? Subscription::STATUS_ACTIVE : Subscription::STATUS_PAST_DUE,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully upgraded to ' . $plan->name . ' plan.',
                'subscription' => [
                    'id' => $currentSubscription->id,
                    'plan_name' => $plan->name,
                    'status' => $currentSubscription->status,
                    'ends_at' => $currentSubscription->ends_at,
                    'days_remaining' => $currentSubscription->days_remaining,
                ],
                'payment' => [
                    'payment_id' => $paymentResult['payment_id'],
                    'amount' => $proratedAmount,
                    'status' => $paymentResult['status'],
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription upgrade failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upgrade subscription. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user's invoices
     */
    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->invoices()
            ->with('subscription.plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'invoices' => $invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'amount' => $invoice->amount,
                    'formatted_amount' => $invoice->formatted_amount,
                    'currency' => $invoice->currency,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'paid_at' => $invoice->paid_at,
                    'pdf_path' => $invoice->pdf_path,
                    'plan_name' => $invoice->subscription->plan->name,
                    'created_at' => $invoice->created_at,
                ];
            })
        ]);
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice($invoiceId)
    {
        $user = Auth::user();
        $invoice = $user->invoices()->findOrFail($invoiceId);

        if (!$invoice->pdf_path || !file_exists(storage_path('app/' . $invoice->pdf_path))) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice PDF not found.'
            ], 404);
        }

        return response()->download(storage_path('app/' . $invoice->pdf_path));
    }

    /**
     * Get user's payment methods
     */
    public function paymentMethods()
    {
        $user = Auth::user();
        $paymentMethods = $user->paymentMethods()
            ->active()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'payment_methods' => $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'type' => $method->type,
                    'gateway' => $method->gateway,
                    'display_name' => $method->display_name,
                    'masked_number' => $method->masked_number,
                    'formatted_expiry' => $method->formatted_expiry,
                    'is_default' => $method->is_default,
                    'is_active' => $method->is_active,
                    'is_expired' => $method->isExpired(),
                    'created_at' => $method->created_at,
                ];
            })
        ]);
    }

    /**
     * Add payment method
     */
    public function addPaymentMethod(Request $request)
    {
        $request->validate([
            'type' => 'required|in:card,bank_account,digital_wallet',
            'gateway' => 'required|in:stripe,nlb,intesa',
            'payment_method_id' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        $user = Auth::user();

        try {
            $paymentService = new PaymentService($request->gateway);
            $paymentMethod = $paymentService->addPaymentMethod($user, [
                'type' => $request->type,
                'payment_method_id' => $request->payment_method_id,
                'metadata' => $request->metadata ?? [],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method added successfully.',
                'payment_method' => [
                    'id' => $paymentMethod->id,
                    'type' => $paymentMethod->type,
                    'gateway' => $paymentMethod->gateway,
                    'display_name' => $paymentMethod->display_name,
                    'is_default' => $paymentMethod->is_default,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment method addition failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add payment method. Please try again.'
            ], 500);
        }
    }

    /**
     * Set default payment method
     */
    public function setDefaultPaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = Auth::user();
        $paymentMethod = $user->paymentMethods()->findOrFail($request->payment_method_id);

        try {
            $paymentService = new PaymentService($paymentMethod->gateway);
            $paymentService->setDefaultPaymentMethod($user, $paymentMethod->id);

            return response()->json([
                'success' => true,
                'message' => 'Default payment method updated successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Default payment method update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update default payment method. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove payment method
     */
    public function removePaymentMethod($paymentMethodId)
    {
        $user = Auth::user();
        $paymentMethod = $user->paymentMethods()->findOrFail($paymentMethodId);

        if ($paymentMethod->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove default payment method. Please set another as default first.'
            ], 400);
        }

        $paymentMethod->deactivate();

        return response()->json([
            'success' => true,
            'message' => 'Payment method removed successfully.'
        ]);
    }
}
