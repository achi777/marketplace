<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\ShippingService;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $paymentService;
    protected $shippingService;

    public function __construct(PaymentService $paymentService, ShippingService $shippingService)
    {
        $this->paymentService = $paymentService;
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        $cart = $this->getCart();
        
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $cart->load(['items.productVariation.product.seller']);

        // Group items by seller for shipping calculation
        $itemsBySeller = $cart->items->groupBy('productVariation.product.seller_id');

        return view('checkout.index', compact('cart', 'itemsBySeller'));
    }

    public function getShippingRates(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zip' => 'required|string',
            'shipping_country' => 'required|string',
        ]);

        $cart = $this->getCart();
        
        if ($cart->items->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        try {
            // Get shipping rates for the cart
            $toAddress = [
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zip' => $request->shipping_zip,
                'country' => $request->shipping_country,
            ];

            // For demo purposes, use a default seller address
            $fromAddress = [
                'address' => '123 Seller St',
                'city' => 'Seller City',
                'state' => 'CA',
                'zip' => '90210',
                'country' => 'US'
            ];

            // Create packages from cart items
            $packages = [];
            $currentWeight = 0;
            
            foreach ($cart->items as $item) {
                $itemWeight = 1; // Default weight, should come from product
                $currentWeight += $itemWeight * $item->quantity;
            }

            $packages[] = [
                'weight' => max($currentWeight, 1),
                'length' => 12,
                'width' => 12,
                'height' => 6
            ];

            $rates = $this->shippingService->calculateShippingRates(
                $fromAddress,
                $toAddress,
                $packages
            );

            return response()->json([
                'success' => true,
                'rates' => $rates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping rates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:stripe,2checkout',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_zip' => 'required|string|max:20',
            'billing_country' => 'required|string|max:100',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_zip' => 'nullable|string|max:20',
            'shipping_country' => 'nullable|string|max:100',
            'same_as_billing' => 'boolean',
        ]);

        $cart = $this->getCart();
        
        if ($cart->items->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->productVariation->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$item->productVariation->product->name}"
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            // Create order
            $billingAddress = [
                'address' => $request->billing_address,
                'city' => $request->billing_city,
                'state' => $request->billing_state,
                'zip' => $request->billing_zip,
                'country' => $request->billing_country,
            ];

            $shippingAddress = $request->same_as_billing ? $billingAddress : [
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zip' => $request->shipping_zip,
                'country' => $request->shipping_country,
            ];

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $cart->subtotal,
                'tax_amount' => $cart->tax_amount,
                'shipping_cost' => 0, // Free shipping for now
                'total_amount' => $cart->total,
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variation_id' => $item->product_variation_id,
                    'seller_id' => $item->productVariation->product->seller_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);

                // Reduce stock
                $item->productVariation->decrement('stock_quantity', $item->quantity);
            }

            // Create payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_id' => '', // Will be updated by payment service
                'amount' => $order->total_amount,
                'currency' => 'USD',
                'status' => 'pending',
                'gateway_response' => null,
            ]);

            // Process payment
            $paymentResult = $this->paymentService->processPayment($payment, $request->payment_method);

            if ($paymentResult['success']) {
                // Payment status and gateway_response are already updated by PaymentService
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'completed',
                ]);

                // Clear cart
                $cart->clear();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'redirect_url' => route('payment.success', ['order' => $order->order_number])
                ]);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => json_encode($paymentResult['response']),
                ]);

                // Restore stock
                foreach ($cart->items as $item) {
                    $item->productVariation->increment('stock_quantity', $item->quantity);
                }

                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: ' . ($paymentResult['message'] ?? 'Unknown error')
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Order processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCart(): Cart
    {
        return Cart::findOrCreateForUser(
            auth()->id(),
            session()->getId()
        );
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}
