<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    public function createStripePaymentIntent(Order $order, array $paymentMethod = []): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => round($order->total_amount * 100), // Stripe expects amount in cents
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
                'payment_method' => $paymentMethod['id'] ?? null,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('payment.success'),
            ]);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'payment_id' => $paymentIntent->id,
                'amount' => $order->total_amount,
                'currency' => 'USD',
                'status' => $this->mapStripeStatus($paymentIntent->status),
                'gateway_response' => $paymentIntent->toArray(),
            ]);

            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'payment' => $payment,
                'client_secret' => $paymentIntent->client_secret,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ];
        }
    }

    public function confirmStripePayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            $payment = Payment::where('payment_id', $paymentIntentId)->first();
            
            if (!$payment) {
                return [
                    'success' => false,
                    'error' => 'Payment record not found',
                ];
            }

            $payment->update([
                'status' => $this->mapStripeStatus($paymentIntent->status),
                'gateway_response' => $paymentIntent->toArray(),
                'processed_at' => $paymentIntent->status === 'succeeded' ? now() : null,
            ]);

            // Update order status if payment succeeded
            if ($paymentIntent->status === 'succeeded') {
                $payment->order->update([
                    'payment_status' => 'completed',
                    'status' => 'processing',
                ]);

                $this->processCommissions($payment->order);
            }

            return [
                'success' => true,
                'payment' => $payment,
                'status' => $paymentIntent->status,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function create2CheckoutPayment(Order $order, array $paymentData): array
    {
        // 2Checkout integration would go here
        // For demo purposes, we'll simulate a successful payment
        
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => '2checkout',
            'payment_id' => '2CO_' . uniqid(),
            'amount' => $order->total_amount,
            'currency' => 'USD',
            'status' => 'completed',
            'gateway_response' => $paymentData,
            'processed_at' => now(),
        ]);

        // Update order status
        $order->update([
            'payment_status' => 'completed',
            'status' => 'processing',
        ]);

        $this->processCommissions($order);

        return [
            'success' => true,
            'payment' => $payment,
        ];
    }

    public function refundPayment(Payment $payment, ?float $amount = null): array
    {
        if (!$payment->canBeRefunded()) {
            return [
                'success' => false,
                'error' => 'Payment cannot be refunded',
            ];
        }

        $refundAmount = $amount ?? $payment->amount;

        try {
            if ($payment->payment_method === 'stripe') {
                return $this->refundStripePayment($payment, $refundAmount);
            } elseif ($payment->payment_method === '2checkout') {
                return $this->refund2CheckoutPayment($payment, $refundAmount);
            }

            return [
                'success' => false,
                'error' => 'Unsupported payment method for refund',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function refundStripePayment(Payment $payment, float $amount): array
    {
        try {
            $refund = \Stripe\Refund::create([
                'payment_intent' => $payment->payment_id,
                'amount' => round($amount * 100),
                'metadata' => [
                    'order_id' => $payment->order_id,
                ],
            ]);

            $payment->update([
                'status' => 'refunded',
                'refund_id' => $refund->id,
            ]);

            return [
                'success' => true,
                'refund' => $refund,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function refund2CheckoutPayment(Payment $payment, float $amount): array
    {
        // 2Checkout refund implementation would go here
        $payment->update([
            'status' => 'refunded',
            'refund_id' => '2CO_REFUND_' . uniqid(),
        ]);

        return [
            'success' => true,
            'refund_id' => $payment->refund_id,
        ];
    }

    private function processCommissions(Order $order): void
    {
        $commissionRate = config('services.marketplace.commission_rate') / 100;

        foreach ($order->items as $item) {
            $commissionAmount = $item->total_price * $commissionRate;
            $sellerAmount = $item->total_price - $commissionAmount;

            // Update or create seller balance
            $sellerBalance = \App\Models\SellerBalance::firstOrCreate(
                ['seller_id' => $item->seller_id],
                [
                    'available_balance' => 0,
                    'pending_balance' => 0,
                    'total_earnings' => 0,
                    'total_withdrawals' => 0,
                    'commission_paid' => 0,
                ]
            );

            $sellerBalance->increment('pending_balance', $sellerAmount);
            $sellerBalance->increment('total_earnings', $sellerAmount);
            $sellerBalance->increment('commission_paid', $commissionAmount);
        }
    }

    private function mapStripeStatus(string $stripeStatus): string
    {
        return match ($stripeStatus) {
            'requires_payment_method', 'requires_confirmation', 'requires_action' => 'pending',
            'processing' => 'processing',
            'succeeded' => 'completed',
            'requires_capture' => 'pending',
            'canceled' => 'failed',
            default => 'pending',
        };
    }

    public function processPayment(Payment $payment, string $paymentMethod): array
    {
        try {
            switch ($paymentMethod) {
                case 'stripe':
                    return $this->processStripePayment($payment);
                case '2checkout':
                    return $this->process2CheckoutPayment($payment);
                default:
                    return [
                        'success' => false,
                        'error' => 'Unsupported payment method: ' . $paymentMethod,
                    ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function processStripePayment(Payment $payment): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => round($payment->amount * 100),
                'currency' => strtolower($payment->currency),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $payment->update([
                'payment_id' => $paymentIntent->id,
                'status' => 'pending',
                'gateway_response' => $paymentIntent->toArray(),
            ]);

            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'client_secret' => $paymentIntent->client_secret,
            ];

        } catch (ApiErrorException $e) {
            $payment->update(['status' => 'failed']);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function process2CheckoutPayment(Payment $payment): array
    {
        // Simulate 2Checkout processing
        $payment->update([
            'payment_id' => '2CO_' . uniqid(),
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        // Update order status
        $payment->order->update([
            'payment_status' => 'completed',
            'status' => 'processing',
        ]);

        $this->processCommissions($payment->order);

        return [
            'success' => true,
            'payment' => $payment,
        ];
    }

    public function getPaymentMethods(): array
    {
        return [
            'stripe' => [
                'name' => 'Credit/Debit Card',
                'description' => 'Pay securely with your credit or debit card',
                'enabled' => !empty(config('services.stripe.public_key')),
            ],
            '2checkout' => [
                'name' => '2Checkout',
                'description' => 'Pay with 2Checkout payment gateway',
                'enabled' => !empty(config('services.twocheckout.merchant_id')),
            ],
        ];
    }
}