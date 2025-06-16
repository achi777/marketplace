@extends('layouts.app')

@section('title', $productData['name'] . ' - Marketplace')

@push('styles')
<style>
        
        .product-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: white;
        }
        
        .breadcrumb-item.active {
            color: white;
        }
        
        .product-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }
        
        .product-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .product-main-image {
            height: 500px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        
        .product-main-image:hover {
            transform: scale(1.05);
        }
        
        .image-badge {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
        }
        
        .variation-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            background: #f9fafb;
            position: relative;
            overflow: hidden;
        }
        
        .variation-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
        
        .variation-card.featured {
            border-color: #f59e0b;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }
        
        .price-display {
            background: linear-gradient(45deg, #059669, #10b981);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        
        .btn-add-to-cart {
            background: linear-gradient(45deg, #dc2626, #ef4444);
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        
        .btn-add-to-cart:hover {
            background: linear-gradient(45deg, #b91c1c, #dc2626);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }
        
        .btn-add-to-cart:disabled {
            background: #9ca3af;
            transform: none;
            box-shadow: none;
        }
        
        .product-info-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: none;
        }
        
        .seller-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 1.5rem;
        }
        
        .related-product-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .related-product-image {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .related-product-card:hover .related-product-image {
            transform: scale(1.1);
        }
        
        .stock-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .stock-badge.in-stock {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .stock-badge.out-of-stock {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .attribute-badge {
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.2rem;
            display: inline-block;
        }
        
        .section-title {
            color: #1f2937;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-left: 1rem;
        }
        
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 30px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            border-radius: 2px;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .carousel-control-prev {
            left: 20px;
        }
        
        .carousel-control-next {
            right: 20px;
        }
    </style>
@endpush

@section('content')
    <!-- Product Header -->
    <div class="product-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="/categories"><i class="bi bi-grid"></i> Categories</a></li>
                    <li class="breadcrumb-item"><a href="/category/smartphones"><i class="bi bi-phone"></i> {{ $productData['category_name'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $productData['name'] }}</li>
                </ol>
            </nav>
            
            <div class="row align-items-center mt-3">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">{{ $productData['name'] }}</h1>
                    <p class="lead opacity-90">{{ $productData['short_description'] }}</p>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <i class="bi bi-phone" style="font-size: 6rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">

        <div class="row g-4">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-image-container">
                    @if(count($images) > 0)
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="d-block w-100 product-main-image" 
                                             alt="{{ $productData['name'] }}">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                            <div class="image-badge">
                                <i class="bi bi-award-fill me-1"></i>Premium Quality
                            </div>
                        </div>
                    @else
                        <div class="product-image-container d-flex align-items-center justify-content-center" style="height: 500px; background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);">
                            <div class="text-center">
                                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                                <p class="text-muted mt-3">No Image Available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-info-card">
                    <!-- Seller Info -->
                    <div class="seller-info mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-shop text-white fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ $productData['seller_name'] }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    Verified Seller
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Product Variations -->
                    @if(count($variationsData) > 0)
                        <h4 class="section-title">Available Options</h4>
                        @foreach($variationsData as $index => $variation)
                            <div class="variation-card {{ $index === 0 ? 'featured' : '' }} mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="mb-2">
                                            @if(count($variation['attributes']) > 0)
                                                @foreach($variation['attributes'] as $key => $value)
                                                    <span class="attribute-badge">{{ ucfirst($key) }}: {{ $value }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                        <small class="text-muted">SKU: {{ $variation['sku'] }}</small>
                                    </div>
                                    <div class="col-md-5 text-end">
                                        <div class="price-display mb-2">
                                            ${{ number_format($variation['price'], 2) }}
                                            @if($variation['compare_price'])
                                                <div style="font-size: 0.8rem; opacity: 0.7;">
                                                    <del>${{ number_format($variation['compare_price'], 2) }}</del>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    @if($variation['stock_quantity'] > 0)
                                        <span class="stock-badge in-stock">
                                            <i class="bi bi-check-circle me-1"></i>
                                            {{ $variation['stock_quantity'] }} in stock
                                        </span>
                                        <button class="btn btn-add-to-cart" onclick="addToCartProduct({{ $variation['id'] }})">
                                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    @else
                                        <span class="stock-badge out-of-stock">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Out of stock
                                        </span>
                                        <button class="btn btn-add-to-cart" disabled>
                                            <i class="bi bi-cart-x me-2"></i>Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Currently Unavailable</h5>
                            <p class="text-muted">This product is currently out of stock or has no available variations.</p>
                        </div>
                    @endif

                    <!-- Quick Info -->
                    <div class="mt-4 p-3 bg-light rounded-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-shield-check text-success fs-4"></i>
                                <p class="small mt-2 mb-0 fw-semibold">Secure Payment</p>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-truck text-primary fs-4"></i>
                                <p class="small mt-2 mb-0 fw-semibold">Fast Delivery</p>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-clockwise text-info fs-4"></i>
                                <p class="small mt-2 mb-0 fw-semibold">Easy Returns</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        @if($productData['description'])
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-info-card">
                        <h4 class="section-title">Product Description</h4>
                        <div class="fs-6 lh-lg text-dark">
                            {{ $productData['description'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Product Specifications -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="product-info-card">
                    <h4 class="section-title">Product Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted" style="width: 40%;">Category</td>
                                    <td>{{ $productData['category_name'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">SKU</td>
                                    <td><code>{{ $productData['sku'] }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Listed Date</td>
                                    <td>{{ date('M d, Y', strtotime($productData['created_at'])) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted" style="width: 40%;">Sold By</td>
                                    <td>{{ $productData['seller_name'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Availability</td>
                                    <td>
                                        @if(count($variationsData) > 0)
                                            <span class="badge bg-success">In Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Variations</td>
                                    <td>{{ count($variationsData) }} option{{ count($variationsData) !== 1 ? 's' : '' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(count($relatedProducts) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="section-title">You Might Also Like</h4>
                    <div class="row g-4">
                        @foreach($relatedProducts as $related)
                            <div class="col-lg-3 col-md-6">
                                <div class="related-product-card h-100">
                                    <div class="position-relative overflow-hidden">
                                        @if(count($related['images']) > 0)
                                            <img src="{{ asset('storage/' . $related['images'][0]) }}" 
                                                 class="card-img-top related-product-image" 
                                                 alt="{{ $related['name'] }}">
                                        @else
                                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center related-product-image" 
                                                 style="background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);">
                                                <i class="bi bi-image text-muted fs-2"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title fw-semibold">{{ $related['name'] }}</h6>
                                        @if($related['min_price'])
                                            <div class="price-tag mb-3">
                                                <i class="bi bi-tag-fill me-1"></i>
                                                From ${{ number_format($related['min_price'], 2) }}
                                            </div>
                                        @endif
                                        <div class="d-grid">
                                            <a href="/product/{{ $related['id'] }}" class="btn btn-outline-primary">
                                                <i class="bi bi-eye me-2"></i>View Product
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function addToCartProduct(variationId) {
        // Use the global addToCart function from the layout
        if (typeof addToCart === 'function') {
            addToCart(variationId, 1);
        } else {
            // Fallback - directly make the request
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_variation_id: variationId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Reload page to update cart count
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding to cart');
            });
        }
    }
</script>
@endpush