<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function success(Request $request)
    {
        $orderNumber = $request->get('order');
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->route('dashboard')->with('error', 'Order not found');
        }

        // Verify this order belongs to the current user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        return view('payment.success', compact('order'));
    }

    public function cancel(Request $request)
    {
        return view('payment.cancel');
    }

    public function stripeWebhook(Request $request)
    {
        try {
            // Verify webhook signature
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');
            $endpointSecret = config('services.stripe.webhook_secret');

            if ($endpointSecret) {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sigHeader,
                    $endpointSecret
                );
            } else {
                $event = json_decode($payload, true);
            }

            // Handle the event
            switch ($event['type']) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event['data']['object'];
                    $this->handleSuccessfulPayment($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event['data']['object'];
                    $this->handleFailedPayment($paymentIntent);
                    break;

                case 'charge.dispute.created':
                    $dispute = $event['data']['object'];
                    $this->handleChargeback($dispute);
                    break;

                default:
                    Log::info('Unhandled Stripe webhook event: ' . $event['type']);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handling failed'], 400);
        }
    }

    private function handleSuccessfulPayment($paymentIntent)
    {
        $payment = Payment::where('gateway_transaction_id', $paymentIntent['id'])->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'gateway_response' => json_encode($paymentIntent),
            ]);

            $order = $payment->order;
            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
                
                // Process commission distribution
                $this->paymentService->distributeCommissions($order);
            }

            Log::info('Payment confirmed via webhook', ['payment_id' => $payment->id]);
        }
    }

    private function handleFailedPayment($paymentIntent)
    {
        $payment = Payment::where('gateway_transaction_id', $paymentIntent['id'])->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => json_encode($paymentIntent),
            ]);

            $order = $payment->order;
            if ($order) {
                $order->update(['status' => 'cancelled']);
                
                // Restore product stock
                foreach ($order->items as $item) {
                    $item->productVariation->increment('stock_quantity', $item->quantity);
                }
            }

            Log::info('Payment failed via webhook', ['payment_id' => $payment->id]);
        }
    }

    private function handleChargeback($dispute)
    {
        $chargeId = $dispute['charge'];
        $payment = Payment::whereJsonContains('gateway_response->id', $chargeId)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'disputed',
                'gateway_response' => json_encode(array_merge(
                    json_decode($payment->gateway_response, true) ?? [],
                    ['dispute' => $dispute]
                )),
            ]);

            Log::warning('Chargeback received', [
                'payment_id' => $payment->id,
                'dispute_id' => $dispute['id'],
                'amount' => $dispute['amount'],
                'reason' => $dispute['reason']
            ]);
        }
    }
}
