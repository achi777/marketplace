@extends('layouts.app')

@section('title', 'All Products - Marketplace')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
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
        transform: translateY(-8px);
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
        font-size: 1rem;
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

    .category-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(34, 197, 94, 0.9);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Filters */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .sort-select, .filter-select {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: border-color 0.3s ease;
    }

    .sort-select:focus, .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Pagination */
    .pagination .page-link {
        border: none;
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        border-radius: 8px;
        color: #667eea;
        font-weight: 500;
    }

    .pagination .page-link:hover {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border-color: transparent;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-box-seam me-3"></i>All Products
            </h1>
            <p class="lead mb-0">Discover amazing products from verified sellers</p>
        </div>
    </div>

    <div class="container py-5">
        <!-- Filters and Sort -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card">
                    <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-end">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label fw-semibold">Search Products</label>
                            <input type="text" class="form-control filter-select" id="search" name="search" 
                                   value="{{ $search }}" placeholder="Search by name or description...">
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <label for="category" class="form-label fw-semibold">Category</label>
                            <select class="form-select filter-select" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Sort -->
                        <div class="col-md-3">
                            <label for="sort" class="form-label fw-semibold">Sort By</label>
                            <select class="form-select sort-select" id="sort" name="sort">
                                <option value="featured" {{ $sort === 'featured' ? 'selected' : '' }}>Featured First</option>
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

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold text-dark mb-0">
                        {{ $pagination['total'] }} {{ $pagination['total'] === 1 ? 'Product' : 'Products' }} Found
                        @if($search)
                            for "{{ $search }}"
                        @endif
                        @if($categoryId)
                            @php
                                $selectedCategory = $categories->where('id', $categoryId)->first();
                            @endphp
                            @if($selectedCategory)
                                in {{ $selectedCategory->name }}
                            @endif
                        @endif
                    </h5>
                    @if($search || $categoryId)
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        @if(count($products) > 0)
            <div class="row g-4">
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
                                
                                <div class="category-badge">
                                    {{ $product['category_name'] }}
                                </div>
                            </div>
                            
                            <div class="card-body p-4 d-flex flex-column">
                                <h6 class="card-title fw-semibold mb-2">{{ $product['name'] }}</h6>
                                
                                @if($product['short_description'])
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($product['short_description'], 100) }}
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

            <!-- Pagination -->
            @if($pagination['last_page'] > 1)
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Products pagination">
                            <ul class="pagination justify-content-center">
                                @if($pagination['prev_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['prev_page']]) }}">
                                            <i class="bi bi-chevron-left"></i> Previous
                                        </a>
                                    </li>
                                @endif
                                
                                @for($i = 1; $i <= $pagination['last_page']; $i++)
                                    @if($i == $pagination['current_page'])
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @elseif($i <= 3 || $i > $pagination['last_page'] - 3 || abs($i - $pagination['current_page']) <= 2)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                        </li>
                                    @elseif($i == 4 || $i == $pagination['last_page'] - 3)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endfor
                                
                                @if($pagination['next_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['next_page']]) }}">
                                            Next <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-search" style="font-size: 4rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                <h3 class="fw-bold mb-3">No Products Found</h3>
                <p class="text-muted mb-4">
                    @if($search || $categoryId)
                        We couldn't find any products matching your criteria. Try adjusting your search or filters.
                    @else
                        There are no products available at the moment. Check back later!
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-3">
                    @if($search || $categoryId)
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>View All Products
                        </a>
                    @endif
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">
                        <i class="bi bi-grid me-2"></i>Browse Categories
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when sort changes
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }

        // Add scroll animations
        const cards = document.querySelectorAll('.product-card');
        
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