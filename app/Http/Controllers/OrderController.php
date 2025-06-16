<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Use raw database queries instead of Eloquent to avoid segmentation faults
            $ordersQuery = \DB::table('orders')
                ->select([
                    'orders.*',
                    \DB::raw('COUNT(order_items.id) as items_count'),
                    \DB::raw('SUM(order_items.total_price) as total_amount')
                ])
                ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id');
            
            if ($user->role === 'admin') {
                // Admin can see all orders
                $ordersQuery = $ordersQuery;
            } elseif ($user->role === 'seller') {
                // Seller can see orders containing their products
                $ordersQuery = $ordersQuery->where('order_items.seller_id', $user->id);
            } else {
                // Buyer can see their own orders
                $ordersQuery = $ordersQuery->where('orders.user_id', $user->id);
            }
            
            $ordersRaw = $ordersQuery
                ->groupBy('orders.id', 'orders.user_id', 'orders.status', 'orders.total_amount', 'orders.shipping_address', 'orders.billing_address', 'orders.created_at', 'orders.updated_at')
                ->orderBy('orders.created_at', 'desc')
                ->limit(20)
                ->get();
            
            // Convert to array format
            $orders = [];
            foreach ($ordersRaw as $order) {
                $orders[] = [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total_amount' => $order->total_amount ?: 0,
                    'items_count' => $order->items_count ?: 0,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at
                ];
            }
            
            return view('orders.index', compact('orders'));
            
        } catch (\Exception $e) {
            \Log::error('Error in OrderController index: ' . $e->getMessage());
            
            // Return empty orders on error
            $orders = [];
            return view('orders.index', compact('orders'));
        }
    }

    public function show(Order $order)
    {
        // Check if user can access this order
        if (!$this->userCanAccessOrder($order)) {
            abort(403, 'Unauthorized access to order');
        }
        
        $order->load(['items.productVariation.product.seller', 'payments', 'user']);
        
        return view('orders.show', compact('order'));
    }

    public function track(Order $order)
    {
        // Check if user can access this order
        if (!$this->userCanAccessOrder($order)) {
            abort(403, 'Unauthorized access to order');
        }
        
        $order->load(['items.productVariation.product.seller']);
        
        return view('orders.track', compact('order'));
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
}