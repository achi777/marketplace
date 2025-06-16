<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'Laravel is working!';
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public endpoint for category attributes (for AJAX calls)
Route::get('/api/categories/{categoryId}/attributes', function($categoryId) {
    $category = \App\Models\Category::findOrFail($categoryId);
    $attributes = $category->attributes()->orderBy('sort_order')->get();
    return response()->json($attributes->map(function($attr) {
        return [
            'id' => $attr->id,
            'name' => $attr->name,
            'type' => $attr->type,
            'options' => $attr->options,
            'is_required' => $attr->is_required,
            'is_filterable' => $attr->is_filterable,
        ];
    }));
})->name('api.categories.attributes');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect based on user role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'seller') {
        return redirect()->route('seller.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/{category}/toggle-status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // Attribute Management
    Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class);
    
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/approve', [\App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [\App\Http\Controllers\Admin\UserController::class, 'reject'])->name('users.reject');
    
    // KYC Management (Admin)
    Route::get('/kyc', [\App\Http\Controllers\KycController::class, 'adminIndex'])->name('kyc.index');
    Route::get('/kyc/{kyc}', [\App\Http\Controllers\KycController::class, 'adminShow'])->name('kyc.show');
    Route::post('/kyc/{kyc}/approve', [\App\Http\Controllers\KycController::class, 'adminApprove'])->name('kyc.approve');
    Route::post('/kyc/{kyc}/reject', [\App\Http\Controllers\KycController::class, 'adminReject'])->name('kyc.reject');
});

// Seller routes
Route::middleware(['auth', 'verified', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    
    // Product Management
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
    Route::get('/products/{product}/variations', [\App\Http\Controllers\Seller\ProductController::class, 'variations'])->name('products.variations');
    Route::post('/products/{product}/variations', [\App\Http\Controllers\Seller\ProductController::class, 'storeVariation'])->name('products.variations.store');
    Route::get('/categories/{category}/attributes', [\App\Http\Controllers\Seller\ProductController::class, 'getCategoryAttributes'])->name('categories.attributes');
    Route::get('/analytics', [\App\Http\Controllers\Seller\ProductController::class, 'analytics'])->name('analytics');
    Route::get('/test', function() {
        return 'Test route works! User: ' . auth()->user()->name . ' Role: ' . auth()->user()->role;
    })->name('test');
});


// Category routes  
Route::get('/category-test/{slug}', function($slug) {
    return "Category test: " . $slug;
});

Route::get('/category/{categorySlug}', [CategoryController::class, 'show'])->name('category.show');

// Test if product exists
Route::get('/test-product-exists/{productId}', function($productId) {
    try {
        $exists = \DB::table('products')->where('id', $productId)->exists();
        $product = \DB::table('products')->where('id', $productId)->first();
        
        return response()->json([
            'product_id' => $productId,
            'exists' => $exists,
            'product' => $product,
            'total_products' => \DB::table('products')->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'connection_test' => 'Failed to connect to database'
        ]);
    }
});

// Test seller product edit access
Route::get('/test-seller-edit/{productId}', function($productId) {
    try {
        // Check auth
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        $user = auth()->user();
        if ($user->role !== 'seller') {
            return response()->json(['error' => 'Not a seller', 'role' => $user->role]);
        }
        
        // Check product
        $product = \DB::table('products')->where('id', $productId)->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }
        
        if ($product->seller_id !== $user->id) {
            return response()->json(['error' => 'Product belongs to different seller', 'product_seller' => $product->seller_id, 'current_user' => $user->id]);
        }
        
        return response()->json([
            'success' => true,
            'user' => $user->name,
            'product' => $product->name,
            'can_edit' => true
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Test seller product without auth
Route::get('/test-seller-product/{productId}', function($productId) {
    try {
        $product = \DB::table('products')
            ->select(['id', 'name', 'status', 'seller_id', 'category_id'])
            ->where('id', $productId)
            ->first();
            
        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'status' => $product->status,
            'seller_id' => $product->seller_id,
            'category_id' => $product->category_id,
            'data_safe' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// Test category
Route::get('/test-category/{categoryId}', function($categoryId) {
    try {
        $category = \DB::table('categories')
            ->select(['id', 'name', 'slug', 'is_active'])
            ->where('id', $categoryId)
            ->first();
            
        if (!$category) {
            return response()->json(['error' => 'Category not found']);
        }
        
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'is_active' => $category->is_active,
            'data_safe' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// Product debug route
Route::get('/debug-product/{categoryId}', function($categoryId) {
    try {
        $product = \App\Models\Product::where('category_id', $categoryId)->first();
        if (!$product) {
            return response()->json(['error' => 'No products found']);
        }
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'status' => $product->status,
            'data_safe' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // KYC Routes for Users
    Route::get('/my-kyc', [\App\Http\Controllers\KycController::class, 'index'])->name('kyc.user.index');
    Route::post('/kyc/upload', [\App\Http\Controllers\KycController::class, 'upload'])->name('kyc.upload');
    Route::get('/kyc/{document}/download', [\App\Http\Controllers\KycController::class, 'download'])->name('kyc.download');
    Route::delete('/kyc/{document}', [\App\Http\Controllers\KycController::class, 'delete'])->name('kyc.delete');
});

// Cart routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');

// Checkout routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
});

// Category routes
Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');

// Product routes
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/product/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// Seller routes
Route::get('/sellers', [\App\Http\Controllers\SellerController::class, 'index'])->name('sellers.index');

// Order routes
Route::middleware('auth')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/track', [\App\Http\Controllers\OrderController::class, 'track'])->name('orders.track');
});


// Test seller dashboard without auth (must be before seller/{seller} route)
Route::get('/test-seller-dashboard', function() {
    try {
        // Mock seller data
        $stats = [
            'total_products' => 25,
            'active_products' => 18,
            'pending_products' => 5,
            'draft_products' => 2,
            'total_orders' => 45,
            'pending_orders' => 3,
            'total_revenue' => 2850.75
        ];
        
        $recentOrders = collect([
            [
                'id' => 101,
                'customer_name' => 'John Smith',
                'total_amount' => 129.99,
                'status' => 'completed',
                'created_at' => now()->subDays(1)->toDateTimeString()
            ]
        ]);
        
        $recentProducts = collect([
            [
                'id' => 1,
                'name' => 'Test Product 1',
                'status' => 'approved',
                'main_image' => null,
                'created_at' => now()->subDays(3)->toDateTimeString()
            ]
        ]);
        
        return view('seller.test-dashboard', compact('stats', 'recentOrders', 'recentProducts'));
        
    } catch (\Exception $e) {
        return 'Test Dashboard Error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile();
    }
});

// Test seller dashboard with original view
Route::get('/test-seller-dashboard-original', function() {
    try {
        // Mock seller data
        $stats = [
            'total_products' => 25,
            'active_products' => 18,
            'pending_products' => 5,
            'draft_products' => 2,
            'total_orders' => 45,
            'pending_orders' => 3,
            'total_revenue' => 2850.75
        ];
        
        $recentOrders = collect([
            [
                'id' => 101,
                'customer_name' => 'John Smith',
                'total_amount' => 129.99,
                'status' => 'completed',
                'created_at' => now()->subDays(1)->toDateTimeString()
            ],
            [
                'id' => 102,
                'customer_name' => 'Jane Doe',
                'total_amount' => 89.50,
                'status' => 'processing',
                'created_at' => now()->subDays(2)->toDateTimeString()
            ]
        ]);
        
        $recentProducts = collect([
            [
                'id' => 1,
                'name' => 'Test Product 1',
                'status' => 'approved',
                'main_image' => null,
                'created_at' => now()->subDays(3)->toDateTimeString()
            ],
            [
                'id' => 2,
                'name' => 'Test Product 2',
                'status' => 'pending',
                'main_image' => null,
                'created_at' => now()->subDays(5)->toDateTimeString()
            ]
        ]);
        
        return view('seller.simple-dashboard', compact('stats', 'recentOrders', 'recentProducts'));
        
    } catch (\Exception $e) {
        return 'Seller Dashboard Error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile();
    }
});

// Individual seller profile route (must be after seller/* routes)
Route::get('/seller/{seller}', [\App\Http\Controllers\SellerController::class, 'show'])->name('sellers.show');

// Test admin dashboard without auth
Route::get('/admin/dashboard-test', function() {
    try {
        // Mock admin data
        $stats = [
            'total_users' => 150,
            'total_sellers' => 25,
            'total_buyers' => 120,
            'pending_sellers' => 3,
            'total_products' => 89,
            'pending_products' => 12,
            'approved_products' => 77,
            'total_orders' => 245,
            'pending_orders' => 8,
            'completed_orders' => 210,
            'pending_kyc' => 5,
            'approved_kyc_today' => 2,
            'total_revenue' => 15750.50
        ];
        
        $recentUsers = collect([
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'buyer',
                'created_at' => '2024-06-15 10:00:00'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'role' => 'seller',
                'created_at' => '2024-06-14 15:30:00'
            ]
        ]);
        
        $recentOrders = collect([
            (object)[
                'id' => 1001,
                'order_number' => 'ORD-1001',
                'user' => (object)['name' => 'Test User'],
                'total_amount' => 125.99,
                'status' => 'completed',
                'created_at' => now()
            ]
        ]);
        
        $pendingKyc = collect([]);
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'pendingKyc'));
        
    } catch (\Exception $e) {
        return 'Admin Dashboard Error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile();
    }
});


require __DIR__.'/auth.php';