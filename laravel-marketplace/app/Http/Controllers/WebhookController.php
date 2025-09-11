<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhooks
     */
    public function stripe(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('Stripe-Signature');

        try {
            $paymentService = new PaymentService('stripe');
            $result = $paymentService->handleWebhook($payload, $signature);

            if ($result) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 400);
        }
    }

    /**
     * Handle NLB webhooks
     */
    public function nlb(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-NLB-Signature');

        try {
            $paymentService = new PaymentService('nlb');
            $result = $paymentService->handleWebhook($payload, $signature);

            if ($result) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            Log::error('NLB webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 400);
        }
    }

    /**
     * Handle Banca Intesa webhooks
     */
    public function intesa(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Intesa-Signature');

        try {
            $paymentService = new PaymentService('intesa');
            $result = $paymentService->handleWebhook($payload, $signature);

            if ($result) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Banca Intesa webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 400);
        }
    }

    /**
     * Handle payment success redirect
     */
    public function success(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $gateway = $request->get('gateway', 'stripe');

        if ($paymentId) {
            // Redirect to success page with payment details
            return redirect()->route('payment.success.page', [
                'payment_id' => $paymentId,
                'gateway' => $gateway
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Payment completed successfully!');
    }

    /**
     * Handle payment failure redirect
     */
    public function failure(Request $request)
    {
        $error = $request->get('error', 'Payment failed');
        $gateway = $request->get('gateway', 'stripe');

        return redirect()->route('payment.failure.page', [
            'error' => $error,
            'gateway' => $gateway
        ]);
    }

    /**
     * Handle payment cancellation redirect
     */
    public function cancel(Request $request)
    {
        $gateway = $request->get('gateway', 'stripe');

        return redirect()->route('payment.cancel.page', [
            'gateway' => $gateway
        ]);
    }
}