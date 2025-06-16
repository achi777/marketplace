@extends('layouts.app')

@section('title', config('app.name', 'Marketplace') . ' - Your Premium Shopping Destination')

@push('styles')
<style>
            
            .hero-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 70vh;
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
            }
            
            .hero-gradient::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.1) 0%, transparent 50%);
            }
            
            .floating-card {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .floating-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 35px 55px rgba(0, 0, 0, 0.15);
            }
            
            .product-card {
                border: none;
                border-radius: 16px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                background: white;
            }
            
            .product-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }
            
            .category-card {
                border: none;
                border-radius: 20px;
                background: white;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                text-align: center;
                padding: 2rem 1rem;
            }
            
            .category-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            }
            
            .section-title {
                font-weight: 700;
                font-size: 2.5rem;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin-bottom: 1rem;
            }
            
            .btn-primary-custom {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 25px;
                padding: 12px 30px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            }
            
            .btn-primary-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                color: white;
                font-size: 2rem;
            }
            
            .stats-card {
                background: white;
                border-radius: 16px;
                padding: 2rem;
                text-align: center;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                transition: transform 0.3s ease;
            }
            
            .stats-card:hover {
                transform: translateY(-5px);
            }
            
            .price-badge {
                background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 25px;
                font-weight: 600;
                font-size: 0.9rem;
            }
            
            .newsletter-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 4rem 0;
                position: relative;
                overflow: hidden;
            }
            
            .newsletter-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            }
            
            .scroll-fade-in {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease;
            }
            
            .scroll-fade-in.visible {
                opacity: 1;
                transform: translateY(0);
            }
            
            @media (max-width: 768px) {
                .hero-gradient {
                    min-height: 60vh;
                    text-align: center;
                }
                
                .section-title {
                    font-size: 2rem;
                }
                
                .category-card {
                    padding: 1.5rem 1rem;
                }
            }
        </style>
@endpush

@section('content')

        <!-- Hero Section -->
        <section class="hero-gradient">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="text-white">
                            <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInLeft">
                                Discover Amazing Products
                            </h1>
                            <p class="lead mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                                Shop from thousands of sellers worldwide and find exactly what you're looking for. 
                                Quality products, competitive prices, fast delivery.
                            </p>
                            <div class="animate__animated animate__fadeInUp animate__delay-2s">
                                <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3 rounded-pill px-4">
                                    <i class="bi bi-shop me-2"></i>Start Shopping
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                                    <i class="bi bi-person-plus me-2"></i>Become a Seller
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="floating-card rounded-4 p-4 text-white">
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="bi bi-people display-4 mb-2"></i>
                                    <h4>1M+</h4>
                                    <small>Happy Customers</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-box-seam display-4 mb-2"></i>
                                    <h4>50K+</h4>
                                    <small>Products</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-shop display-4 mb-2"></i>
                                    <h4>5K+</h4>
                                    <small>Sellers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-5" style="margin-top: 4rem;">
            <div class="container">
                <div class="text-center mb-5 scroll-fade-in">
                    <h2 class="section-title">Shop by Category</h2>
                    <p class="lead text-muted">Explore our wide range of product categories</p>
                </div>
                
                <div class="row g-4">
                    @forelse($categories as $category)
                        <div class="col-6 col-md-3 scroll-fade-in">
                            <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                                <div class="category-card">
                                    @php
                                        $icons = [
                                            'Electronics' => 'bi-phone',
                                            'Clothing' => 'bi-palette',
                                            'Home & Garden' => 'bi-house',
                                            'Sports' => 'bi-dribbble',
                                        ];
                                        $colors = ['text-primary', 'text-success', 'text-warning', 'text-danger'];
                                        $icon = $icons[$category->name] ?? 'bi-box';
                                        $color = $colors[$loop->index % count($colors)];
                                    @endphp
                                    <div class="feature-icon">
                                        <i class="bi {{ $icon }}"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">{{ $category->name }}</h5>
                                    <p class="text-muted small">{{ $category->description }}</p>
                                </div>
                            </a>
                        </div>
                    @empty
                        @foreach(['Electronics', 'Clothing', 'Home & Garden', 'Sports'] as $index => $categoryName)
                            <div class="col-6 col-md-3 scroll-fade-in">
                                <div class="category-card">
                                    @php
                                        $icons = ['bi-phone', 'bi-palette', 'bi-house', 'bi-dribbble'];
                                        $colors = ['text-primary', 'text-success', 'text-warning', 'text-danger'];
                                    @endphp
                                    <div class="feature-icon">
                                        <i class="bi {{ $icons[$index] }}"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">{{ $categoryName }}</h5>
                                    <p class="text-muted small">Discover amazing {{ strtolower($categoryName) }} products</p>
                                </div>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5 scroll-fade-in">
                    <h2 class="section-title">Featured Products</h2>
                    <p class="lead text-muted">Hand-picked products just for you</p>
                </div>
                
                <div class="row g-4">
                    @forelse($featuredProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3 scroll-fade-in">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                <div class="product-card">
                                <div class="position-relative">
                                    @if($product->main_image)
                                        <img src="{{ Storage::url($product->main_image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="bg-gradient-to-r from-gray-200 to-gray-300 d-flex align-items-center justify-content-center" style="height: 250px;">
                                            <i class="bi bi-image text-gray-500" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    
                                    @if($product->variations->first() && $product->variations->first()->discount_percentage)
                                        <span class="price-badge position-absolute top-0 start-0 m-3">
                                            -{{ $product->variations->first()->discount_percentage }}%
                                        </span>
                                    @endif
                                    
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <button class="btn btn-light btn-sm rounded-circle">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-truncate flex-grow-1 me-2">{{ $product->name }}</h6>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-star-fill text-warning me-1" style="font-size: 0.8rem;"></i>
                                            <small class="text-muted">4.5</small>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small mb-3">by {{ $product->seller->name }}</p>
                                    
                                    @if($product->min_price)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                @if($product->min_price === $product->max_price)
                                                    <span class="h6 fw-bold text-primary mb-0">${{ number_format($product->min_price, 2) }}</span>
                                                @else
                                                    <span class="h6 fw-bold text-primary mb-0">${{ number_format($product->min_price, 2) }}+</span>
                                                @endif
                                                
                                                @if($product->variations->first() && $product->variations->first()->compare_price)
                                                    <small class="text-muted text-decoration-line-through ms-2">
                                                        ${{ number_format($product->variations->first()->compare_price, 2) }}
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            <button class="btn btn-primary-custom btn-sm">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <small class="{{ $product->in_stock ? 'text-success' : 'text-danger' }}">
                                            <i class="bi bi-{{ $product->in_stock ? 'check-circle' : 'x-circle' }}"></i> 
                                            {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        @for ($i = 1; $i <= 8; $i++)
                            <div class="col-6 col-md-4 col-lg-3 scroll-fade-in">
                                <div class="product-card">
                                    <div class="position-relative">
                                        <div class="bg-gradient-to-r from-blue-100 to-purple-100 d-flex align-items-center justify-content-center" style="height: 250px;">
                                            <i class="bi bi-box text-blue-500" style="font-size: 3rem;"></i>
                                        </div>
                                        <span class="price-badge position-absolute top-0 start-0 m-3">-20%</span>
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <button class="btn btn-light btn-sm rounded-circle">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold">Premium Product {{ $i }}</h6>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-star-fill text-warning me-1" style="font-size: 0.8rem;"></i>
                                                <small class="text-muted">4.5</small>
                                            </div>
                                        </div>
                                        
                                        <p class="text-muted small mb-3">by Demo Seller</p>
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="h6 fw-bold text-primary mb-0">${{ 99 + $i }}.99</span>
                                                <small class="text-muted text-decoration-line-through ms-2">${{ 119 + $i }}.99</small>
                                            </div>
                                            <button class="btn btn-primary-custom btn-sm">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <small class="text-success">
                                                <i class="bi bi-check-circle"></i> In Stock
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="py-5">
            <div class="container">
                <div class="text-center mb-5 scroll-fade-in">
                    <h2 class="section-title">Why Choose Our Marketplace?</h2>
                    <p class="lead text-muted">We provide the best shopping experience</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4 scroll-fade-in">
                        <div class="stats-card">
                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h4 class="fw-bold">Secure Shopping</h4>
                            <p class="text-muted">Your data and payments are 100% secure with our advanced encryption technology.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 scroll-fade-in">
                        <div class="stats-card">
                            <div class="feature-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h4 class="fw-bold">Fast Delivery</h4>
                            <p class="text-muted">Get your orders delivered quickly with our nationwide shipping network.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 scroll-fade-in">
                        <div class="stats-card">
                            <div class="feature-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h4 class="fw-bold">24/7 Support</h4>
                            <p class="text-muted">Our customer support team is available round the clock to help you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="newsletter-section position-relative">
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center text-white">
                        <h2 class="fw-bold mb-4">Stay in the Loop</h2>
                        <p class="lead mb-4">Get the latest deals, new arrivals, and exclusive offers delivered to your inbox.</p>
                        
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="input-group input-group-lg">
                                    <input type="email" class="form-control border-0 rounded-start-pill" placeholder="Enter your email address">
                                    <button class="btn btn-light rounded-end-pill px-4" type="button">
                                        <i class="bi bi-envelope-check me-2"></i>Subscribe
                                    </button>
                                </div>
                                <small class="text-light opacity-75 mt-2 d-block">We respect your privacy. Unsubscribe at any time.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection

@push('scripts')
<script>
    // Scroll fade in animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.scroll-fade-in').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endpush