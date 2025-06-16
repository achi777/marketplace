@extends('layouts.app')

@section('title', 'Shopping Cart - Marketplace')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-cart3 me-2 text-primary"></i>{{ __('Shopping Cart') }}
            </h2>
            <p class="text-muted small mb-0">Review your items and proceed to checkout</p>
        </div>
        @if($cart && count($cart['items']) > 0)
            <div>
                <span class="badge rounded-pill px-3 py-2 text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="bi bi-bag me-1"></i>{{ $cart['item_count'] }} Items
                </span>
            </div>
        @endif
    </div>
@endsection

@push('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .cart-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .cart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .cart-item {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            border: none !important;
        }
        
        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .quantity-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            background: white;
            transition: all 0.3s ease;
        }
        
        .quantity-control:hover {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .quantity-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .price-display {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .original-price {
            color: #95a5a6;
            text-decoration: line-through;
            font-size: 0.9rem;
        }
        
        .btn-remove {
            background: linear-gradient(135deg, #e17055 0%, #fd79a8 100%);
            border: none;
            border-radius: 20px;
            padding: 4px 12px;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }
        
        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(225, 112, 85, 0.4);
            color: white;
        }
        
        .btn-clear {
            background: linear-gradient(135deg, #636e72 0%, #2d3436 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-clear:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(99, 110, 114, 0.4);
            color: white;
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 24px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 184, 148, 0.3);
        }
        
        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 184, 148, 0.4);
            color: white;
        }
        
        .recommended-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .recommended-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .empty-cart {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .stock-indicator {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        @media (max-width: 768px) {
            .cart-item {
                margin-bottom: 1.5rem;
            }
            
            .cart-item .row > div {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        @if($cart && count($cart['items']) > 0)
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-card">
                        <div class="card-header bg-transparent border-0 p-4">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="bi bi-basket me-2 text-primary"></i>
                                Cart Items ({{ $cart['item_count'] }} items)
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            @foreach($cart['items'] as $item)
                                <div class="cart-item p-4" data-variation-id="{{ $item['variation_id'] }}">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-2 col-sm-3">
                                            <div class="position-relative">
                                                @if($item['main_image'])
                                                    <img src="{{ $item['main_image'] }}" 
                                                         class="img-fluid rounded-3" 
                                                         alt="{{ $item['product_name'] }}"
                                                         style="height: 100px; width: 100%; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 100px;">
                                                        <i class="bi bi-image text-muted fs-2"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-9">
                                            <h6 class="mb-2 fw-bold text-dark">{{ $item['product_name'] }}</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <small class="text-muted fw-medium">by </small>
                                                <small class="text-primary fw-semibold ms-1">Seller</small>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-upc me-1"></i>SKU: {{ $item['sku'] }}
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-6">
                                            <div class="text-center">
                                                <div class="price-display">${{ number_format($item['price'], 2) }}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-6">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <button class="quantity-btn me-2" type="button" onclick="updateQuantity({{ $item['variation_id'] }}, {{ $item['quantity'] - 1 }})">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="form-control text-center quantity-control" value="{{ $item['quantity'] }}" min="1" max="99" readonly style="width: 60px;">
                                                <button class="quantity-btn ms-2" type="button" onclick="updateQuantity({{ $item['variation_id'] }}, {{ $item['quantity'] + 1 }})">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                            <div class="text-center mt-2">
                                                <span class="stock-indicator">In Stock</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 text-center">
                                            <div class="price-display fs-5 mb-2">${{ number_format($item['item_total'], 2) }}</div>
                                            <button class="btn btn-remove" onclick="removeItem({{ $item['variation_id'] }})">
                                                <i class="bi bi-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 p-4">
                            <button class="btn btn-clear" onclick="clearCart()">
                                <i class="bi bi-trash3 me-2"></i>Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="cart-card sticky-top" style="top: 20px;">
                        <div class="card-header bg-transparent border-0 p-4">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="bi bi-receipt me-2 text-primary"></i>
                                Order Summary
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="bg-light rounded-3 p-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold text-dark">Subtotal ({{ $cart['item_count'] }} items):</span>
                                    <span class="fw-bold" id="cart-subtotal">${{ number_format($cart['subtotal'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold text-dark">Tax:</span>
                                    <span class="fw-bold" id="cart-tax">${{ number_format($cart['tax_amount'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-semibold text-dark">Shipping:</span>
                                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); color: white;">FREE</span>
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark fs-5">Total:</span>
                                    <span class="fw-bold price-display fs-4" id="cart-total">${{ number_format($cart['total'], 2) }}</span>
                                </div>
                            </div>
                            
                            @auth
                                <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100 mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-checkout w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Checkout
                                </a>
                            @endauth
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Secure checkout with SSL encryption
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recommended Products -->
                    <div class="cart-card mt-4">
                        <div class="card-header bg-transparent border-0 p-4">
                            <h6 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="bi bi-lightbulb me-2 text-primary"></i>
                                You might also like
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                @php
                                    $recommendedProducts = \DB::table('products')
                                        ->select(['id', 'name', 'images'])
                                        ->where('status', 'approved')
                                        ->inRandomOrder()
                                        ->limit(4)
                                        ->get();
                                @endphp
                                
                                @foreach($recommendedProducts as $product)
                                    <div class="col-6">
                                        <div class="recommended-card">
                                            <div class="position-relative">
                                                @php
                                                    $images = json_decode($product->images, true) ?: [];
                                                    $mainImage = count($images) > 0 ? $images[0] : null;
                                                @endphp
                                                @if($mainImage)
                                                    <img src="{{ $mainImage }}" class="card-img-top" alt="{{ $product->name }}" style="height: 120px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body p-3">
                                                <h6 class="card-title small fw-bold text-truncate mb-2">{{ $product->name }}</h6>
                                                @php
                                                    $minPrice = \DB::table('product_variations')
                                                        ->where('product_id', $product->id)
                                                        ->where('is_active', true)
                                                        ->min('price');
                                                @endphp
                                                @if($minPrice)
                                                    <div class="price-display small">${{ number_format($minPrice, 2) }}</div>
                                                @endif
                                                <button class="btn btn-sm w-100 mt-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600;">
                                                    <i class="bi bi-plus me-1"></i>Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart text-center">
                <div class="mb-4">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet. Start shopping to fill it up!</p>
                <a href="{{ route('categories.index') }}" class="btn btn-checkout btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        @endif
    </div>

    <script>
        function updateQuantity(variationId, quantity) {
            if (quantity < 0) quantity = 0;
            
            fetch(`/cart/update/${variationId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (quantity === 0) {
                        location.reload(); // Reload to remove the item
                    } else {
                        updateCartTotals();
                        updateCartCount();
                    }
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating cart', 'error');
            });
        }

        function removeItem(variationId) {
            if (!confirm('Remove this item from your cart?')) return;
            
            fetch(`/cart/remove/${variationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error removing item', 'error');
            });
        }

        function clearCart() {
            if (!confirm('Remove all items from your cart?')) return;
            
            fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error clearing cart', 'error');
            });
        }

        function updateCartTotals() {
            // This would typically fetch updated totals from the server
            // For now, we'll reload the page to get fresh data
            location.reload();
        }
    </script>
    </div>
@endsection

@push('scripts')
<script>
    // Additional cart-specific JavaScript can go here
</script>
@endpush