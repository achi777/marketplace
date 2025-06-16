@extends('layouts.app')

@section('title', $seller->name . ' - Seller Profile - Marketplace')

@push('styles')
<style>
    /* Hero Section */
    .seller-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
    }

    .seller-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 6px solid white;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Stats Cards */
    .stats-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Product Cards */
    .product-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .price-tag {
        background: linear-gradient(45deg, #2563eb, #3b82f6);
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-block;
    }

    .featured-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Review Cards */
    .review-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .rating-stars {
        color: #fbbf24;
    }

    /* Filters */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .section-title {
        border-left: 4px solid #667eea;
        padding-left: 1rem;
        margin-bottom: 2rem;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
    <!-- Seller Hero Section -->
    <div class="seller-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        @if($seller->avatar)
                            <img src="{{ asset('storage/' . $seller->avatar) }}" 
                                 class="seller-avatar me-4" 
                                 alt="{{ $seller->name }}">
                        @else
                            <div class="seller-avatar bg-white d-flex align-items-center justify-content-center me-4" 
                                 style="color: #667eea;">
                                <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h1 class="display-5 fw-bold mb-2">{{ $seller->name }}</h1>
                            <p class="lead mb-2">
                                <i class="bi bi-calendar me-2"></i>
                                Member since {{ \Carbon\Carbon::parse($seller->created_at)->format('F Y') }}
                            </p>
                            @if($seller->bio)
                                <p class="mb-0 opacity-90">{{ $seller->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $stats['active_products'] }}</div>
                                <div>Active Products</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">
                                    @if($stats['avg_rating'] > 0)
                                        {{ round($stats['avg_rating'], 1) }}
                                    @else
                                        --
                                    @endif
                                </div>
                                <div>Average Rating</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $stats['reviews_count'] }}</div>
                                <div>Reviews</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $stats['total_products'] }}</div>
                                <div>Total Products</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <!-- Products Section -->
        <div class="row">
            <div class="col-12">
                <h2 class="section-title fw-bold">Products by {{ $seller->name }}</h2>
            </div>
        </div>

        <!-- Product Filters -->
        @if(count($products) > 0 || $categoryId || $sort !== 'newest')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="filter-card">
                        <form method="GET" action="{{ route('sellers.show', $seller->id) }}" class="row g-3 align-items-end">
                            <!-- Category Filter -->
                            @if(count($categories) > 0)
                                <div class="col-md-6">
                                    <label for="category" class="form-label fw-semibold">Filter by Category</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            
                            <!-- Sort -->
                            <div class="col-md-4">
                                <label for="sort" class="form-label fw-semibold">Sort By</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel me-1"></i>Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Products Grid -->
        @if(count($products) > 0)
            <div class="row g-4 mb-5">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card position-relative">
                            <div class="position-relative overflow-hidden">
                                @if($product['main_image'])
                                    <img src="{{ asset('storage/' . $product['main_image']) }}" 
                                         class="card-img-top product-image" 
                                         alt="{{ $product['name'] }}">
                                @else
                                    <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center product-image" 
                                         style="background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                @if($product['is_featured'])
                                    <div class="featured-badge">
                                        <i class="bi bi-star-fill me-1"></i>Featured
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body p-4 d-flex flex-column">
                                <h6 class="card-title fw-semibold mb-2">{{ $product['name'] }}</h6>
                                
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag me-1"></i>{{ $product['category_name'] }}
                                </p>
                                
                                @if($product['short_description'])
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($product['short_description'], 80) }}
                                    </p>
                                @endif
                                
                                @if($product['min_price'])
                                    <div class="mb-3">
                                        <span class="price-tag">
                                            <i class="bi bi-tag-fill me-1"></i>
                                            From ${{ number_format($product['min_price'], 2) }}
                                        </span>
                                    </div>
                                @else
                                    <p class="text-muted mb-3">Price not available</p>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="{{ route('products.show', $product['id']) }}" class="btn btn-primary w-100">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($products) >= 12)
                <div class="text-center mb-5">
                    <a href="{{ route('products.index', ['seller' => $seller->id]) }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>View All Products from {{ $seller->name }}
                    </a>
                </div>
            @endif
        @else
            <!-- No Products -->
            <div class="empty-state mb-5">
                <i class="bi bi-box" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                <h4 class="fw-bold mb-2">No Products Found</h4>
                <p class="text-muted">
                    @if($categoryId)
                        This seller doesn't have any products in the selected category.
                    @else
                        This seller hasn't listed any products yet.
                    @endif
                </p>
                @if($categoryId)
                    <a href="{{ route('sellers.show', $seller->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>View All Products
                    </a>
                @endif
            </div>
        @endif

        <!-- Reviews Section -->
        @if(count($reviews) > 0)
            <div class="row">
                <div class="col-12">
                    <h3 class="section-title fw-bold">Customer Reviews</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    @foreach($reviews as $review)
                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-semibold mb-1">{{ $review->reviewer_name }}</h6>
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                                </small>
                            </div>
                            @if($review->comment)
                                <p class="mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    @if($stats['avg_rating'] > 0)
                        <div class="review-card text-center">
                            <h4 class="fw-bold mb-2">{{ round($stats['avg_rating'], 1) }}/5</h4>
                            <div class="rating-stars fs-5 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($stats['avg_rating']))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i <= ceil($stats['avg_rating']))
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-muted mb-0">Based on {{ $stats['reviews_count'] }} reviews</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when filters change
        const categorySelect = document.getElementById('category');
        const sortSelect = document.getElementById('sort');
        
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }

        // Add scroll animations
        const cards = document.querySelectorAll('.product-card, .review-card');
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush