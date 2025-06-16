@extends('layouts.app')

@section('title', 'All Sellers - Marketplace')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
    }

    /* Seller Cards */
    .seller-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .seller-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .seller-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-badge {
        background: linear-gradient(45deg, #2563eb, #3b82f6);
        color: white;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
        margin: 0.25rem;
    }

    .rating-stars {
        color: #fbbf24;
        font-size: 0.9rem;
    }

    .member-since {
        color: #6b7280;
        font-size: 0.875rem;
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

    .seller-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-people me-3"></i>Our Sellers
            </h1>
            <p class="lead mb-0">Discover trusted sellers and their amazing products</p>
        </div>
    </div>

    <div class="container py-5">
        <!-- Filters and Sort -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card">
                    <form method="GET" action="{{ route('sellers.index') }}" class="row g-3 align-items-end">
                        <!-- Search -->
                        <div class="col-md-6">
                            <label for="search" class="form-label fw-semibold">Search Sellers</label>
                            <input type="text" class="form-control filter-select" id="search" name="search" 
                                   value="{{ $search }}" placeholder="Search by seller name...">
                        </div>
                        
                        <!-- Sort -->
                        <div class="col-md-4">
                            <label for="sort" class="form-label fw-semibold">Sort By</label>
                            <select class="form-select sort-select" id="sort" name="sort">
                                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="products" {{ $sort === 'products' ? 'selected' : '' }}>Most Products</option>
                                <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Highest Rated</option>
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
                        {{ $pagination['total'] }} {{ $pagination['total'] === 1 ? 'Seller' : 'Sellers' }} Found
                        @if($search)
                            for "{{ $search }}"
                        @endif
                    </h5>
                    @if($search)
                        <a href="{{ route('sellers.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Clear Search
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sellers Grid -->
        @if(count($sellers) > 0)
            <div class="row g-4">
                @foreach($sellers as $seller)
                    <div class="col-lg-4 col-md-6">
                        <div class="seller-card">
                            <div class="seller-header">
                                <div class="d-flex flex-column align-items-center">
                                    @if($seller['avatar'])
                                        <img src="{{ asset('storage/' . $seller['avatar']) }}" 
                                             class="seller-avatar mb-3" 
                                             alt="{{ $seller['name'] }}">
                                    @else
                                        <div class="seller-avatar bg-gradient d-flex align-items-center justify-content-center mb-3" 
                                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                            <i class="bi bi-person-fill" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <h5 class="fw-bold mb-2">{{ $seller['name'] }}</h5>
                                    
                                    <div class="member-since">
                                        <i class="bi bi-calendar me-1"></i>
                                        Member since {{ \Carbon\Carbon::parse($seller['created_at'])->format('M Y') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <!-- Stats -->
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="fw-bold text-primary fs-5">{{ $seller['active_products_count'] }}</div>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-primary fs-5">
                                            @if($seller['avg_rating'] > 0)
                                                {{ $seller['avg_rating'] }}
                                            @else
                                                --
                                            @endif
                                        </div>
                                        <small class="text-muted">Rating</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-primary fs-5">{{ $seller['reviews_count'] }}</div>
                                        <small class="text-muted">Reviews</small>
                                    </div>
                                </div>
                                
                                <!-- Rating Stars -->
                                @if($seller['avg_rating'] > 0)
                                    <div class="text-center mb-3">
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($seller['avg_rating']))
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif($i <= ceil($seller['avg_rating']))
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $seller['reviews_count'] }} reviews)</small>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="text-center mb-3">
                                    @if($seller['active_products_count'] >= 10)
                                        <span class="stats-badge">
                                            <i class="bi bi-award me-1"></i>Top Seller
                                        </span>
                                    @endif
                                    @if($seller['avg_rating'] >= 4.5 && $seller['reviews_count'] >= 5)
                                        <span class="stats-badge" style="background: linear-gradient(45deg, #059669, #10b981);">
                                            <i class="bi bi-star me-1"></i>Highly Rated
                                        </span>
                                    @endif
                                    @if(\Carbon\Carbon::parse($seller['created_at'])->diffInDays(now()) <= 30)
                                        <span class="stats-badge" style="background: linear-gradient(45deg, #f59e0b, #d97706);">
                                            <i class="bi bi-lightning me-1"></i>New Seller
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Action Button -->
                                <div class="d-grid">
                                    <a href="{{ route('sellers.show', $seller['id']) }}" class="btn btn-primary">
                                        <i class="bi bi-eye me-2"></i>View Seller & Products
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
                        <nav aria-label="Sellers pagination">
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
                <i class="bi bi-people" style="font-size: 4rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                <h3 class="fw-bold mb-3">No Sellers Found</h3>
                <p class="text-muted mb-4">
                    @if($search)
                        We couldn't find any sellers matching "{{ $search }}". Try a different search term.
                    @else
                        There are no active sellers at the moment. Check back later!
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-3">
                    @if($search)
                        <a href="{{ route('sellers.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>View All Sellers
                        </a>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="bi bi-box me-2"></i>Browse Products
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
        const cards = document.querySelectorAll('.seller-card');
        
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