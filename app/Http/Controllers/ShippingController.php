<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\ShippingService;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function getRates(Request $request)
    {
        $request->validate([
            'from_address' => 'required|array',
            'to_address' => 'required|array',
            'packages' => 'required|array',
        ]);

        try {
            $rates = $this->shippingService->calculateShippingRates(
                $request->from_address,
                $request->to_address,
                $request->packages
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

    public function createShipment(Request $request, Order $order)
    {
        $request->validate([
            'carrier' => 'required|in:ups,fedex',
            'service_code' => 'required|string',
        ]);

        // Verify order belongs to authenticated seller
        if (!$this->userCanManageOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        try {
            $shipment = $this->shippingService->createShipment(
                $order,
                $request->carrier,
                $request->service_code
            );

            // Update order with tracking information
            $order->update([
                'tracking_number' => $shipment['tracking_number'],
                'carrier' => $shipment['carrier'],
                'status' => 'shipped',
                'shipped_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'shipment' => $shipment,
                'message' => 'Shipment created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shipment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trackShipment(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'carrier' => 'required|in:ups,fedex',
        ]);

        try {
            $tracking = $this->shippingService->trackShipment(
                $request->tracking_number,
                $request->carrier
            );

            return response()->json([
                'success' => true,
                'tracking' => $tracking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track shipment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trackOrder(Order $order)
    {
        if (!$order->tracking_number || !$order->carrier) {
            return response()->json([
                'success' => false,
                'message' => 'No tracking information available for this order'
            ], 404);
        }

        try {
            $tracking = $this->shippingService->trackShipment(
                $order->tracking_number,
                $order->carrier
            );

            return response()->json([
                'success' => true,
                'tracking' => $tracking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOrderShippingRates(Order $order)
    {
        // Verify user can access this order
        if (!$this->userCanAccessOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        try {
            // Get seller address (in production, this should come from seller profile)
            $fromAddress = [
                'address' => '123 Seller St',
                'city' => 'Seller City',
                'state' => 'CA',
                'zip' => '90210',
                'country' => 'US'
            ];

            $toAddress = $order->shipping_address;
            $packages = $this->shippingService->getPackageDetailsFromOrder($order);

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

    protected function userCanAccessOrder(Order $order): bool
    {
        $user = auth()->user();
        
        // User can access their own orders
        if ($order->user_id === $user->id) {
            return true;
        }
        
        // Sellers can access orders containing their products
        if ($user->role === 'seller') {
            return $order->items()->where('seller_id', $user->id)->exists();
        }
        
        // Admins can access all orders
        if ($user->role === 'admin') {
            return true;
        }
        
        return false;
    }

    protected function userCanManageOrder(Order $order): bool
    {
        $user = auth()->user();
        
        // Sellers can manage orders containing their products
        if ($user->role === 'seller') {
            return $order->items()->where('seller_id', $user->id)->exists();
        }
        
        // Admins can manage all orders
        if ($user->role === 'admin') {
            return true;
        }
        
        return false;
    }
}