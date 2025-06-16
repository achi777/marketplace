@extends('layouts.app')

@section('title', $product->name . ' - Marketplace')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-box-seam me-2 text-primary"></i>{{ $product->name }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-primary fw-semibold">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.index') }}" class="text-decoration-none text-primary fw-semibold">
                            Categories
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.show', $product->category->slug) }}" class="text-decoration-none text-primary fw-semibold">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-bold text-dark">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@push('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .product-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .image-gallery {
            background: #f8f9fa;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .thumbnail:hover, .thumbnail.active {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .price-display {
            color: #e74c3c;
            font-weight: bold;
            font-size: 2rem;
        }
        
        .original-price {
            color: #95a5a6;
            text-decoration: line-through;
            font-size: 1.2rem;
        }
        
        .variation-selector {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .variation-selector:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .variation-selector.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .btn-add-to-cart {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 30px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 184, 148, 0.3);
        }
        
        .btn-add-to-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 184, 148, 0.4);
            color: white;
        }
        
        .btn-wishlist {
            background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 20px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-wishlist:hover {
            transform: translateY(-2px);
            color: white;
        }
        
        .seller-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            color: white;
            padding: 1.5rem;
        }
        
        .attribute-badge {
            background: linear-gradient(135deg, #ddd6fe 0%, #c7d2fe 100%);
            color: #4c51bf;
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 4px;
            display: inline-block;
        }
        
        .related-product-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .stock-indicator {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            border-radius: 12px;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .out-of-stock {
            background: linear-gradient(135deg, #e17055 0%, #fd79a8 100%);
        }
        
        @media (max-width: 768px) {
            .main-image {
                height: 300px;
            }
            
            .price-display {
                font-size: 1.5rem;
            }
        }
@endpush

@section('content')

    <div class="container py-4">
        <div class="row g-4">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-card p-4">
                    <div class="image-gallery">
                        @if($images && count($images) > 0)
                            <div class="text-center mb-3">
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $product->name }}" 
                                     class="main-image" id="mainImage">
                            </div>
                            
                            @if(count($images) > 1)
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    @foreach($images as $index => $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" 
                                             class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                             onclick="changeMainImage(this, {{ $index }})">
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-image display-1 text-muted"></i>
                                <p class="text-muted mt-3">No images available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-card p-4">
                    <div class="mb-3">
                        <h1 class="h2 fw-bold text-dark mb-2">{{ $product->name }}</h1>
                        <p class="text-muted">{{ $product->short_description }}</p>
                    </div>
                    
                    <!-- Seller Info -->
                    <div class="seller-card mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shop me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Sold by {{ $product->seller->name }}</h6>
                                <small class="opacity-75">Verified Seller</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pricing -->
                    <div class="mb-4">
                        @if($product->variations->count() > 0)
                            @php $firstVariation = $product->variations->first(); @endphp
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="price-display">${{ number_format($firstVariation->price, 2) }}</span>
                                @if($firstVariation->compare_price && $firstVariation->compare_price > $firstVariation->price)
                                    <span class="original-price">${{ number_format($firstVariation->compare_price, 2) }}</span>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                                        {{ round((($firstVariation->compare_price - $firstVariation->price) / $firstVariation->compare_price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        @if($product->in_stock)
                            <span class="stock-indicator">
                                <i class="bi bi-check-circle me-1"></i>In Stock ({{ $product->total_stock }} available)
                            </span>
                        @else
                            <span class="stock-indicator out-of-stock">
                                <i class="bi bi-x-circle me-1"></i>Out of Stock
                            </span>
                        @endif
                    </div>
                    
                    <!-- Variations -->
                    @if($product->variations->count() > 1)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Available Options</h6>
                            <div class="row g-2">
                                @foreach($product->variations as $variation)
                                    <div class="col-md-6">
                                        <div class="variation-selector" data-variation-id="{{ $variation->id }}"
                                             data-price="{{ $variation->price }}" 
                                             data-compare-price="{{ $variation->compare_price }}"
                                             data-stock="{{ $variation->stock_quantity }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if($variation->attribute_display)
                                                        <div class="fw-semibold">{{ $variation->attribute_display }}</div>
                                                    @endif
                                                    <div class="text-success fw-bold">${{ number_format($variation->price, 2) }}</div>
                                                </div>
                                                <small class="text-muted">{{ $variation->stock_quantity }} left</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Add to Cart -->
                    @if($product->in_stock)
                        <div class="row g-3 mb-4">
                            <div class="col-3">
                                <div class="d-flex align-items-center border rounded-3">
                                    <button class="btn btn-sm" onclick="changeQuantity(-1)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->total_stock }}" 
                                           class="form-control border-0 text-center" style="box-shadow: none;">
                                    <button class="btn btn-sm" onclick="changeQuantity(1)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-7">
                                <button class="btn btn-add-to-cart w-100" onclick="addToCartProduct()">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-wishlist w-100">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Product Attributes -->
                    @if($product->attributeValues->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Product Details</h6>
                            <div class="d-flex flex-wrap">
                                @foreach($product->attribute_values_grouped as $attributeName => $attributeData)
                                    <span class="attribute-badge">
                                        <strong>{{ $attributeName }}:</strong> {{ $attributeData['value'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Description -->
        @if($product->description)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="product-card p-4">
                        <h4 class="fw-bold mb-3">
                            <i class="bi bi-file-text me-2 text-primary"></i>Product Description
                        </h4>
                        <div class="text-muted">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="product-card p-4">
                        <h4 class="fw-bold mb-4">
                            <i class="bi bi-lightning me-2 text-primary"></i>Related Products
                        </h4>
                        <div class="row g-3">
                            @foreach($relatedProducts as $relatedProduct)
                                <div class="col-md-3 col-sm-6">
                                    <div class="related-product-card h-100">
                                        <div class="position-relative">
                                            @if($relatedProduct->main_image)
                                                <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" 
                                                     class="card-img-top" alt="{{ $relatedProduct->name }}" 
                                                     style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="height: 200px;">
                                                    <i class="bi bi-image text-muted fs-1"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title fw-bold text-truncate mb-2">
                                                <a href="{{ route('products.show', $relatedProduct->slug) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ $relatedProduct->name }}
                                                </a>
                                            </h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                @if($relatedProduct->min_price)
                                                    <span class="fw-bold text-success">
                                                        ${{ number_format($relatedProduct->min_price, 2) }}
                                                    </span>
                                                @endif
                                                <small class="text-muted">by {{ $relatedProduct->seller->name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
        let selectedVariationId = {{ $product->variations->first()->id ?? 'null' }};
        
        function changeMainImage(thumbnail, index) {
            // Update main image
            const mainImage = document.getElementById('mainImage');
            mainImage.src = thumbnail.src;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }
        
        function changeQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            const newValue = parseInt(quantityInput.value) + change;
            const maxStock = parseInt(quantityInput.max);
            
            if (newValue >= 1 && newValue <= maxStock) {
                quantityInput.value = newValue;
            }
        }
        
        // Variation selection
        document.querySelectorAll('.variation-selector').forEach(selector => {
            selector.addEventListener('click', function() {
                // Remove active class from all selectors
                document.querySelectorAll('.variation-selector').forEach(s => s.classList.remove('selected'));
                
                // Add active class to clicked selector
                this.classList.add('selected');
                
                // Update selected variation
                selectedVariationId = this.dataset.variationId;
                
                // Update price display
                const price = parseFloat(this.dataset.price);
                const comparePrice = this.dataset.comparePrice ? parseFloat(this.dataset.comparePrice) : null;
                const stock = parseInt(this.dataset.stock);
                
                document.querySelector('.price-display').textContent = '$' + price.toFixed(2);
                document.getElementById('quantity').max = stock;
                
                if (document.getElementById('quantity').value > stock) {
                    document.getElementById('quantity').value = Math.max(1, stock);
                }
            });
        });
        
        // Set first variation as selected by default
        if (document.querySelector('.variation-selector')) {
            document.querySelector('.variation-selector').classList.add('selected');
        }
        
        function addToCartProduct() {
            const quantity = document.getElementById('quantity').value;
            
            if (!selectedVariationId) {
                showNotification('Please select a product variation', 'error');
                return;
            }
            
            // Use the global addToCart function from the layout
            addToCart(selectedVariationId, parseInt(quantity));
        }
</script>
@endpush