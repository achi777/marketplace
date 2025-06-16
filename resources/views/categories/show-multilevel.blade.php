@extends('layouts.app')

@section('title', $category->name . ' - Marketplace')

@push('styles')
<style>
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
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
        
        .category-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
            height: 100%;
        }
        
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .category-image {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .category-card:hover .category-image {
            transform: scale(1.05);
        }
        
        .product-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            height: 220px;
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
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }
        
        .category-count {
            background: linear-gradient(45deg, #059669, #10b981);
            color: white;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        
        .btn-view {
            background: linear-gradient(45deg, #059669, #10b981);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-view:hover {
            background: linear-gradient(45deg, #047857, #059669);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(5, 150, 105, 0.3);
        }
        
        .section-divider {
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 2px;
            margin: 3rem 0 2rem;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .category-header {
            position: relative;
            overflow: hidden;
        }
        
        .category-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');
            opacity: 0.5;
        }
        
        .category-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(59, 130, 246, 0.9);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .product-category-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero-section category-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="/categories"><i class="bi bi-grid"></i> Categories</a></li>
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        @if($index === count($breadcrumbs) - 1)
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="/category/{{ $breadcrumb['slug'] }}">{{ $breadcrumb['name'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-folder me-3"></i>{{ $category->name }}
                    </h1>
                    @if($category->description)
                        <p class="lead mb-4 opacity-90">{{ $category->description }}</p>
                    @endif
                    
                    <div class="d-flex flex-wrap gap-3">
                        @if(count($childCategoriesWithCounts) > 0)
                            <div class="stats-card d-inline-block px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-folder me-3 fs-4"></i>
                                    <div>
                                        <div class="fw-bold fs-5">{{ count($childCategoriesWithCounts) }}</div>
                                        <div class="small opacity-75">Subcategories</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($productCount > 0)
                            <div class="stats-card d-inline-block px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-box-seam me-3 fs-4"></i>
                                    <div>
                                        <div class="fw-bold fs-5">{{ $productCount }}</div>
                                        <div class="small opacity-75">{{ $productCount === 1 ? 'Product' : 'Products' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <i class="bi bi-folder" style="font-size: 8rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <!-- Child Categories Section -->
        @if(count($childCategoriesWithCounts) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-semibold text-dark mb-4">
                        <i class="bi bi-folder me-2"></i>Explore Subcategories
                    </h3>
                    <p class="text-muted mb-4">Browse through specialized categories</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                @foreach($childCategoriesWithCounts as $childCategory)
                    <div class="col-lg-4 col-md-6">
                        <div class="category-card position-relative">
                            <div class="position-relative overflow-hidden">
                                @if($childCategory['image'])
                                    <img src="{{ asset('storage/' . $childCategory['image']) }}" 
                                         class="card-img-top category-image" 
                                         alt="{{ $childCategory['name'] }}">
                                @else
                                    <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center category-image" 
                                         style="background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);">
                                        <i class="bi bi-folder text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <div class="category-badge">
                                    <i class="bi bi-collection me-1"></i>{{ $childCategory['product_count'] }} items
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold mb-3">{{ $childCategory['name'] }}</h5>
                                @if($childCategory['description'])
                                    <p class="card-text text-muted mb-3">{{ $childCategory['description'] }}</p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="category-count">
                                        {{ $childCategory['product_count'] }} {{ $childCategory['product_count'] === 1 ? 'Product' : 'Products' }}
                                    </span>
                                    <a href="/category/{{ $childCategory['slug'] }}" class="btn btn-view">
                                        <i class="bi bi-arrow-right me-2"></i>Explore
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($productCount > 0)
                <div class="section-divider"></div>
            @endif
        @endif

        <!-- Products Section -->
        @if($productCount > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-semibold text-dark mb-4">
                        <i class="bi bi-box-seam me-2"></i>Available Products
                        @if(count($childCategoriesWithCounts) > 0)
                            <small class="text-muted">(from all subcategories)</small>
                        @endif
                    </h3>
                    <p class="text-muted mb-4">{{ $productCount }} amazing {{ $productCount === 1 ? 'product' : 'products' }} waiting for you</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card position-relative">
                            <div class="position-relative overflow-hidden">
                                @if($product['image'])
                                    <img src="{{ asset('storage/' . $product['image']) }}" 
                                         class="card-img-top product-image" 
                                         alt="{{ $product['name'] }}">
                                @else
                                    <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center product-image" 
                                         style="background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                @if($product['category_name'] !== $category->name)
                                    <div class="product-category-badge">
                                        {{ $product['category_name'] }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body p-4 d-flex flex-column">
                                <h6 class="card-title fw-semibold mb-3">{{ $product['name'] }}</h6>
                                
                                @if($product['min_price'])
                                    <p class="card-text mb-3">
                                        <span class="price-tag">
                                            <i class="bi bi-tag-fill me-1"></i>
                                            From ${{ number_format($product['min_price'], 2) }}
                                        </span>
                                    </p>
                                @else
                                    <p class="card-text text-muted mb-3">Price not available</p>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="/product/{{ $product['id'] }}" class="btn btn-view w-100">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Empty State -->
        @if(count($childCategoriesWithCounts) === 0 && $productCount === 0)
            <div class="empty-state">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                <h3 class="fw-bold mb-3">Nothing Here Yet</h3>
                <p class="text-muted mb-4">
                    This category is waiting for amazing content. Check back later for new subcategories and products!
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/categories" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Browse Other Categories
                    </a>
                    <a href="/search" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Search Products
                    </a>
                </div>
            </div>
        @endif

        <!-- Call to Action -->
        @if(count($childCategoriesWithCounts) > 0 || $productCount > 0)
            <div class="text-center mt-5">
                <div class="card border-0 bg-light">
                    <div class="card-body py-5">
                        <h4 class="fw-bold mb-3">Looking for something specific?</h4>
                        <p class="text-muted mb-4">Use our advanced search or browse more categories</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/categories" class="btn btn-outline-primary">
                                <i class="bi bi-grid me-2"></i>All Categories
                            </a>
                            <a href="/search" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Advanced Search
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Add scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.category-card, .product-card');
        
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