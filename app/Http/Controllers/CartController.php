<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        try {
            $cart = $this->getCartData();
            return view('cart.index', compact('cart'));
        } catch (\Exception $e) {
            \Log::error('Error in CartController index: ' . $e->getMessage());
            
            // Return empty cart on error
            $cart = [
                'items' => [],
                'item_count' => 0,
                'subtotal' => 0,
                'tax_amount' => 0,
                'total' => 0
            ];
            return view('cart.index', compact('cart'));
        }
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_variation_id' => 'required|integer',
                'quantity' => 'required|integer|min:1|max:99',
            ]);

            // Get product variation details
            $variation = \DB::table('product_variations')
                ->select(['id', 'product_id', 'sku', 'price', 'stock_quantity', 'is_active'])
                ->where('id', $request->product_variation_id)
                ->where('is_active', true)
                ->first();

            if (!$variation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product variation not found',
                ], 404);
            }

            if ($request->quantity > $variation->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available',
                ], 400);
            }

            // Get product details
            $product = \DB::table('products')
                ->select(['id', 'name', 'images'])
                ->where('id', $variation->product_id)
                ->where('status', 'approved')
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            // Add to session cart
            $cart = session()->get('cart', []);
            $itemKey = 'item_' . $variation->id;

            if (isset($cart[$itemKey])) {
                $cart[$itemKey]['quantity'] += $request->quantity;
            } else {
                $cart[$itemKey] = [
                    'variation_id' => $variation->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_images' => $product->images,
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'quantity' => $request->quantity,
                ];
            }

            session()->put('cart', $cart);

            $cartData = $this->getCartData();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cart_count' => $cartData['item_count'],
                'cart_total' => number_format($cartData['total'], 2),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error adding item to cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding item to cart',
            ], 500);
        }
    }

    public function update(Request $request, $variationId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:0|max:99',
            ]);

            $cart = session()->get('cart', []);
            $itemKey = 'item_' . $variationId;

            if (!isset($cart[$itemKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart',
                ], 404);
            }

            if ($request->quantity <= 0) {
                unset($cart[$itemKey]);
                $message = 'Item removed from cart';
            } else {
                $cart[$itemKey]['quantity'] = $request->quantity;
                $message = 'Cart updated';
            }

            session()->put('cart', $cart);
            $cartData = $this->getCartData();

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartData['item_count'],
                'cart_total' => number_format($cartData['total'], 2),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart',
            ], 500);
        }
    }

    public function remove($variationId)
    {
        try {
            $cart = session()->get('cart', []);
            $itemKey = 'item_' . $variationId;

            if (isset($cart[$itemKey])) {
                unset($cart[$itemKey]);
                session()->put('cart', $cart);

                $cartData = $this->getCartData();

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => $cartData['item_count'],
                    'cart_total' => number_format($cartData['total'], 2),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart',
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Error removing item from cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing item from cart',
            ], 500);
        }
    }

    public function clear()
    {
        try {
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared',
                'cart_count' => 0,
                'cart_total' => '0.00',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart',
            ], 500);
        }
    }

    public function count()
    {
        try {
            $cartData = $this->getCartData();
            
            return response()->json([
                'count' => $cartData['item_count'],
                'total' => number_format($cartData['total'], 2),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting cart count: ' . $e->getMessage());
            return response()->json([
                'count' => 0,
                'total' => '0.00',
            ]);
        }
    }

    private function getCartData(): array
    {
        $cart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;
        $itemCount = 0;

        foreach ($cart as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            $itemCount += $item['quantity'];

            // Parse images
            $images = [];
            if ($item['product_images']) {
                $imagesArray = json_decode($item['product_images'], true);
                $images = is_array($imagesArray) ? $imagesArray : [];
            }

            $items[] = [
                'variation_id' => $item['variation_id'],
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'product_images' => $images,
                'main_image' => count($images) > 0 ? $images[0] : null,
                'sku' => $item['sku'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'item_total' => $itemTotal,
            ];
        }

        $taxAmount = $subtotal * 0.08; // 8% tax
        $total = $subtotal + $taxAmount;

        return [
            'items' => $items,
            'item_count' => $itemCount,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ];
    }
}