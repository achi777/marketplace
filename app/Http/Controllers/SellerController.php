<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        try {
            \Log::info('SellerController index method called');
            
            // Get query parameters
            $search = $request->get('search');
            $sort = $request->get('sort', 'newest');
            
            // Build base query using raw database queries
            $query = \DB::table('users')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.created_at',
                    \DB::raw('COUNT(DISTINCT products.id) as products_count'),
                    \DB::raw('COUNT(DISTINCT CASE WHEN products.status = "approved" THEN products.id END) as active_products_count'),
                    \DB::raw('0 as avg_rating'),
                    \DB::raw('0 as reviews_count')
                ])
                ->leftJoin('products', 'users.id', '=', 'products.seller_id')
                ->where('users.role', 'seller')
                ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at');
            
            // Apply search filter
            if ($search) {
                $query->where('users.name', 'like', '%' . $search . '%');
            }
            
            // Apply sorting
            switch ($sort) {
                case 'name':
                    $query->orderBy('users.name', 'asc');
                    break;
                case 'products':
                    $query->orderBy('active_products_count', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('avg_rating', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('users.created_at', 'asc');
                    break;
                default: // newest
                    $query->orderBy('users.created_at', 'desc');
            }
            
            // Get paginated results
            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            $totalCount = \DB::table('users')
                ->where('role', 'seller')
                ->when($search, function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->count();
                
            $rawSellers = $query->offset($offset)->limit($perPage)->get();
            
            // Process sellers data
            $sellers = [];
            foreach ($rawSellers as $seller) {
                $sellers[] = [
                    'id' => $seller->id,
                    'name' => $seller->name,
                    'email' => $seller->email,
                    'avatar' => null, // Avatar not implemented yet
                    'created_at' => $seller->created_at,
                    'products_count' => $seller->products_count ?: 0,
                    'active_products_count' => $seller->active_products_count ?: 0,
                    'avg_rating' => round($seller->avg_rating, 1),
                    'reviews_count' => $seller->reviews_count ?: 0
                ];
            }
            
            // Simple pagination data
            $pagination = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalCount,
                'last_page' => ceil($totalCount / $perPage),
                'has_more_pages' => $page < ceil($totalCount / $perPage),
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < ceil($totalCount / $perPage) ? $page + 1 : null
            ];
            
            return view('sellers.index', compact('sellers', 'search', 'sort', 'pagination'));
            
        } catch (\Exception $e) {
            \Log::error('Error in SellerController index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function show($sellerId, Request $request)
    {
        try {
            \Log::info('SellerController show method called for seller: ' . $sellerId);
            
            // Get seller information
            $rawSeller = \DB::table('users')
                ->select([
                    'id', 'name', 'email', 'created_at'
                ])
                ->where('id', $sellerId)
                ->where('role', 'seller')
                ->first();
            
            if (!$rawSeller) {
                abort(404, 'Seller not found');
            }

            // Convert to object with all needed properties
            $seller = (object) [
                'id' => $rawSeller->id,
                'name' => $rawSeller->name,
                'email' => $rawSeller->email,
                'created_at' => $rawSeller->created_at,
                'avatar' => null, // Avatar not implemented yet
                'bio' => null, // Bio not implemented yet
                'phone' => null, // Phone not implemented yet
                'address' => null // Address not implemented yet
            ];
            
            // Get seller statistics
            $stats = [
                'total_products' => \DB::table('products')->where('seller_id', $sellerId)->count(),
                'active_products' => \DB::table('products')->where('seller_id', $sellerId)->where('status', 'approved')->count(),
                'avg_rating' => 0, // Will implement when reviews table is created
                'reviews_count' => 0, // Will implement when reviews table is created
                'member_since' => $seller->created_at
            ];
            
            // Get seller's products
            $categoryId = $request->get('category');
            $sort = $request->get('sort', 'newest');
            
            $productsQuery = \DB::table('products')
                ->select([
                    'products.id',
                    'products.name',
                    'products.short_description',
                    'products.images',
                    'products.is_featured',
                    'categories.name as category_name'
                ])
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.seller_id', $sellerId)
                ->where('products.status', 'approved');
            
            if ($categoryId) {
                $productsQuery->where('products.category_id', $categoryId);
            }
            
            // Apply sorting
            switch ($sort) {
                case 'name':
                    $productsQuery->orderBy('products.name', 'asc');
                    break;
                case 'price_low':
                    $productsQuery->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                          ->groupBy('products.id', 'products.name', 'products.short_description', 'products.images', 'products.is_featured', 'categories.name')
                          ->orderBy(\DB::raw('MIN(product_variations.price)'), 'asc');
                    break;
                case 'price_high':
                    $productsQuery->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                          ->groupBy('products.id', 'products.name', 'products.short_description', 'products.images', 'products.is_featured', 'categories.name')
                          ->orderBy(\DB::raw('MIN(product_variations.price)'), 'desc');
                    break;
                default: // newest
                    $productsQuery->orderBy('products.created_at', 'desc');
            }
            
            $rawProducts = $productsQuery->limit(12)->get();
            
            // Process products
            $products = [];
            foreach ($rawProducts as $product) {
                // Get minimum price from variations
                $minPrice = \DB::table('product_variations')
                    ->where('product_id', $product->id)
                    ->where('is_active', true)
                    ->min('price');
                
                // Parse images JSON
                $images = [];
                if ($product->images) {
                    $imagesArray = json_decode($product->images, true);
                    $images = is_array($imagesArray) ? $imagesArray : [];
                }
                
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'short_description' => $product->short_description,
                    'category_name' => $product->category_name,
                    'is_featured' => $product->is_featured,
                    'images' => $images,
                    'main_image' => count($images) > 0 ? $images[0] : null,
                    'min_price' => $minPrice
                ];
            }
            
            // Get categories for filter (only categories with seller's products)
            $categories = \DB::table('categories')
                ->select(['categories.id', 'categories.name'])
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->where('products.seller_id', $sellerId)
                ->where('products.status', 'approved')
                ->distinct()
                ->orderBy('categories.name')
                ->get();
            
            // Get recent reviews (empty for now)
            $reviews = collect(); // Will implement when reviews table is created
            
            return view('sellers.show', compact('seller', 'stats', 'products', 'categories', 'reviews', 'categoryId', 'sort'));
            
        } catch (\Exception $e) {
            \Log::error('Error in SellerController show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function dashboard()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            
            // Check if user is seller
            if (auth()->user()->role !== 'seller') {
                abort(403, 'Access denied');
            }
            
            \Log::info('Seller dashboard accessed by user: ' . auth()->user()->email);
            
            $seller = auth()->user();
            
            // Get seller statistics using raw database queries
            $stats = [
                'total_products' => \DB::table('products')->where('seller_id', $seller->id)->count(),
                'active_products' => \DB::table('products')->where('seller_id', $seller->id)->where('status', 'approved')->count(),
                'pending_products' => \DB::table('products')->where('seller_id', $seller->id)->where('status', 'pending')->count(),
                'draft_products' => \DB::table('products')->where('seller_id', $seller->id)->where('status', 'draft')->count(),
            ];

            // Get order statistics (if order tables exist)
            try {
                $stats['total_orders'] = \DB::table('order_items')
                    ->where('seller_id', $seller->id)
                    ->distinct('order_id')
                    ->count();
                    
                $stats['pending_orders'] = \DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.seller_id', $seller->id)
                    ->where('orders.status', 'pending')
                    ->distinct('order_items.order_id')
                    ->count();
                    
                $stats['total_revenue'] = \DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.seller_id', $seller->id)
                    ->where('orders.status', '!=', 'cancelled')
                    ->sum('order_items.total_price') ?: 0;
            } catch (\Exception $e) {
                // If order tables don't exist, set default values
                $stats['total_orders'] = 0;
                $stats['pending_orders'] = 0;
                $stats['total_revenue'] = 0;
            }

            // Get recent products
            $recentProductsRaw = \DB::table('products')
                ->select(['id', 'name', 'status', 'images', 'created_at'])
                ->where('seller_id', $seller->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $recentProducts = [];
            foreach ($recentProductsRaw as $product) {
                $images = [];
                if ($product->images) {
                    $imagesArray = json_decode($product->images, true);
                    $images = is_array($imagesArray) ? $imagesArray : [];
                }

                $recentProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'status' => $product->status,
                    'main_image' => count($images) > 0 ? $images[0] : null,
                    'created_at' => $product->created_at
                ];
            }

            // Get recent orders (if order tables exist)
            $recentOrders = [];
            try {
                $recentOrdersRaw = \DB::table('orders')
                    ->select([
                        'orders.id',
                        'orders.status', 
                        'orders.total_amount',
                        'orders.created_at',
                        'users.name as customer_name'
                    ])
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->where('order_items.seller_id', $seller->id)
                    ->orderBy('orders.created_at', 'desc')
                    ->limit(5)
                    ->get();

                foreach ($recentOrdersRaw as $order) {
                    $recentOrders[] = [
                        'id' => $order->id,
                        'status' => $order->status,
                        'total_amount' => $order->total_amount,
                        'customer_name' => $order->customer_name,
                        'created_at' => $order->created_at
                    ];
                }
            } catch (\Exception $e) {
                // If order tables don't exist, empty array is fine
            }

            // Convert arrays to collections for consistent handling in view
            $recentOrders = collect($recentOrders);
            $recentProducts = collect($recentProducts);
            
            \Log::info('Seller dashboard rendering with data', [
                'stats' => $stats,
                'recent_orders_count' => $recentOrders->count(),
                'recent_products_count' => $recentProducts->count()
            ]);
            
            return view('seller.minimal-dashboard', compact('stats', 'recentOrders', 'recentProducts'));
            
        } catch (\Exception $e) {
            \Log::error('Error in SellerController dashboard: ' . $e->getMessage());
            
            // Return with empty data on error
            $stats = [
                'total_products' => 0,
                'active_products' => 0,
                'pending_products' => 0,
                'draft_products' => 0,
                'total_orders' => 0,
                'pending_orders' => 0,
                'total_revenue' => 0
            ];
            $recentOrders = collect([]);
            $recentProducts = collect([]);
            
            return view('seller.minimal-dashboard', compact('stats', 'recentOrders', 'recentProducts'));
        }
    }
}
