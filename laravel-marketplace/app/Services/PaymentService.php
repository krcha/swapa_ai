<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    protected $gateway;
    protected $config;

    public function __construct($gateway = 'stripe')
    {
        $this->gateway = $gateway;
        $this->config = config("payment.gateways.{$gateway}");
    }

    /**
     * Process payment for subscription
     */
    public function processPayment(Subscription $subscription, array $paymentData)
    {
        try {
            switch ($this->gateway) {
                case 'stripe':
                    return $this->processStripePayment($subscription, $paymentData);
                case 'nlb':
                    return $this->processNlbPayment($subscription, $paymentData);
                case 'intesa':
                    return $this->processIntesaPayment($subscription, $paymentData);
                default:
                    throw new Exception("Unsupported payment gateway: {$this->gateway}");
            }
        } catch (Exception $e) {
            Log::error("Payment processing failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process Stripe payment
     */
    protected function processStripePayment(Subscription $subscription, array $paymentData)
    {
        // Stripe payment processing logic
        $stripe = new \Stripe\StripeClient($this->config['secret_key']);
        
        try {
            // Create payment intent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $subscription->plan->price * 100, // Convert to cents
                'currency' => 'rsd',
                'payment_method' => $paymentData['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('payment.success'),
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'plan_id' => $subscription->plan_id,
                ],
            ]);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'amount' => $subscription->plan->price,
                'currency' => 'RSD',
                'gateway' => 'stripe',
                'gateway_payment_id' => $paymentIntent->id,
                'status' => $paymentIntent->status === 'succeeded' ? 'completed' : 'pending',
                'metadata' => json_encode($paymentIntent->metadata),
            ]);

            return [
                'success' => $paymentIntent->status === 'succeeded',
                'payment_id' => $payment->id,
                'gateway_payment_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'status' => $paymentIntent->status,
            ];

        } catch (\Stripe\Exception\CardException $e) {
            throw new Exception("Card error: " . $e->getMessage());
        } catch (\Stripe\Exception\RateLimitException $e) {
            throw new Exception("Rate limit error: " . $e->getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            throw new Exception("Invalid request: " . $e->getMessage());
        } catch (\Stripe\Exception\AuthenticationException $e) {
            throw new Exception("Authentication error: " . $e->getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            throw new Exception("Network error: " . $e->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new Exception("Stripe API error: " . $e->getMessage());
        }
    }

    /**
     * Process NLB payment (Serbian bank)
     */
    protected function processNlbPayment(Subscription $subscription, array $paymentData)
    {
        // NLB payment processing stub
        // This would integrate with NLB's payment gateway API
        
        $payment = Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->plan->price,
            'currency' => 'RSD',
            'gateway' => 'nlb',
            'gateway_payment_id' => 'nlb_' . uniqid(),
            'status' => 'pending',
            'metadata' => json_encode($paymentData),
        ]);

        // Simulate payment processing
        return [
            'success' => true,
            'payment_id' => $payment->id,
            'gateway_payment_id' => $payment->gateway_payment_id,
            'redirect_url' => $this->config['redirect_url'],
            'status' => 'pending',
        ];
    }

    /**
     * Process Banca Intesa payment (Serbian bank)
     */
    protected function processIntesaPayment(Subscription $subscription, array $paymentData)
    {
        // Banca Intesa payment processing stub
        // This would integrate with Banca Intesa's payment gateway API
        
        $payment = Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->plan->price,
            'currency' => 'RSD',
            'gateway' => 'intesa',
            'gateway_payment_id' => 'intesa_' . uniqid(),
            'status' => 'pending',
            'metadata' => json_encode($paymentData),
        ]);

        // Simulate payment processing
        return [
            'success' => true,
            'payment_id' => $payment->id,
            'gateway_payment_id' => $payment->gateway_payment_id,
            'redirect_url' => $this->config['redirect_url'],
            'status' => 'pending',
        ];
    }

    /**
     * Handle webhook from payment gateway
     */
    public function handleWebhook(array $payload, string $signature)
    {
        switch ($this->gateway) {
            case 'stripe':
                return $this->handleStripeWebhook($payload, $signature);
            case 'nlb':
                return $this->handleNlbWebhook($payload, $signature);
            case 'intesa':
                return $this->handleIntesaWebhook($payload, $signature);
            default:
                throw new Exception("Unsupported payment gateway: {$this->gateway}");
        }
    }

    /**
     * Handle Stripe webhook
     */
    protected function handleStripeWebhook(array $payload, string $signature)
    {
        $endpoint_secret = $this->config['webhook_secret'];
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                json_encode($payload),
                $signature,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook: Invalid payload');
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook: Invalid signature');
            return false;
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;
            default:
                Log::info('Stripe webhook: Unhandled event type ' . $event->type);
        }

        return true;
    }

    /**
     * Handle NLB webhook
     */
    protected function handleNlbWebhook(array $payload, string $signature)
    {
        // NLB webhook handling logic
        Log::info('NLB webhook received', $payload);
        return true;
    }

    /**
     * Handle Banca Intesa webhook
     */
    protected function handleIntesaWebhook(array $payload, string $signature)
    {
        // Banca Intesa webhook handling logic
        Log::info('Banca Intesa webhook received', $payload);
        return true;
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSucceeded($paymentIntent)
    {
        $payment = Payment::where('gateway_payment_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update(['status' => 'completed']);
            
            // Activate subscription
            $subscription = $payment->subscription;
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);

            // Generate invoice
            $this->generateInvoice($payment);
        }
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailed($paymentIntent)
    {
        $payment = Payment::where('gateway_payment_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update(['status' => 'failed']);
            
            // Update subscription status
            $subscription = $payment->subscription;
            $subscription->update(['status' => 'payment_failed']);
        }
    }

    /**
     * Handle invoice payment succeeded
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        // Handle recurring payment success
        Log::info('Invoice payment succeeded', ['invoice_id' => $invoice->id]);
    }

    /**
     * Handle invoice payment failed
     */
    protected function handleInvoicePaymentFailed($invoice)
    {
        // Handle recurring payment failure
        Log::info('Invoice payment failed', ['invoice_id' => $invoice->id]);
    }

    /**
     * Generate invoice for payment
     */
    public function generateInvoice(Payment $payment)
    {
        $invoice = $payment->invoice()->create([
            'subscription_id' => $payment->subscription_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'status' => 'paid',
            'invoice_number' => 'INV-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'due_date' => now(),
            'paid_at' => now(),
        ]);

        // Generate PDF invoice
        $this->generateInvoicePdf($invoice);

        return $invoice;
    }

    /**
     * Generate PDF invoice
     */
    protected function generateInvoicePdf($invoice)
    {
        // This would generate a PDF invoice
        // For now, just log the action
        Log::info('Invoice PDF generated', ['invoice_id' => $invoice->id]);
    }

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(User $user)
    {
        return $user->paymentMethods()->where('is_active', true)->get();
    }

    /**
     * Add payment method for user
     */
    public function addPaymentMethod(User $user, array $paymentMethodData)
    {
        return $user->paymentMethods()->create([
            'type' => $paymentMethodData['type'],
            'gateway' => $this->gateway,
            'gateway_payment_method_id' => $paymentMethodData['payment_method_id'],
            'is_default' => $user->paymentMethods()->count() === 0,
            'is_active' => true,
            'metadata' => json_encode($paymentMethodData),
        ]);
    }

    /**
     * Set default payment method
     */
    public function setDefaultPaymentMethod(User $user, $paymentMethodId)
    {
        // Remove default from all payment methods
        $user->paymentMethods()->update(['is_default' => false]);
        
        // Set new default
        $user->paymentMethods()
            ->where('id', $paymentMethodId)
            ->update(['is_default' => true]);
    }
}
