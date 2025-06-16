<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }
        
        .category-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
        }
        
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .product-image {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .category-card:hover .product-image {
            transform: scale(1.05);
        }
        
        .price-tag {
            background: linear-gradient(45deg, #2563eb, #3b82f6);
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }
        
        .btn-view-product {
            background: linear-gradient(45deg, #059669, #10b981);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-view-product:hover {
            background: linear-gradient(45deg, #047857, #059669);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(5, 150, 105, 0.3);
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
        
        .empty-state-icon {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }
        
        .product-badge {
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
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section category-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="/categories"><i class="bi bi-grid"></i> Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-phone me-3"></i>{{ $category->name }}
                    </h1>
                    @if($category->description)
                        <p class="lead mb-4 opacity-90">{{ $category->description }}</p>
                    @endif
                    
                    <div class="stats-card d-inline-block px-4 py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-box-seam me-3 fs-4"></i>
                            <div>
                                <div class="fw-bold fs-5">{{ $productCount }}</div>
                                <div class="small opacity-75">{{ $productCount === 1 ? 'Product' : 'Products' }} Available</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <i class="bi bi-phone" style="font-size: 8rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container pb-5">
        @if($productCount > 0)
            <!-- Filters & Sorting -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4 class="fw-semibold text-dark">
                        Discover {{ $productCount }} Amazing {{ $productCount === 1 ? 'Product' : 'Products' }}
                    </h4>
                    <p class="text-muted">Find the perfect device for your needs</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active">
                            <i class="bi bi-grid"></i> Grid
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> List
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="category-card h-100 position-relative">
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
                                
                                <div class="product-badge">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold mb-3">{{ $product['name'] }}</h5>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    @if($product['min_price'])
                                        <span class="price-tag">
                                            <i class="bi bi-tag-fill me-1"></i>
                                            From ${{ number_format($product['min_price'], 2) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Price not available</span>
                                    @endif
                                    
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <i class="bi bi-eye"></i> View Product
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <a href="/product/{{ $product['id'] }}" class="btn btn-view-product">
                                        <i class="bi bi-arrow-right me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-5">
                <div class="card border-0 bg-light">
                    <div class="card-body py-5">
                        <h4 class="fw-bold mb-3">Can't find what you're looking for?</h4>
                        <p class="text-muted mb-4">Browse more categories or contact our support team</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/categories" class="btn btn-outline-primary">
                                <i class="bi bi-grid me-2"></i>Browse All Categories
                            </a>
                            <a href="/search" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Advanced Search
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <h3 class="fw-bold mb-3">No Products Yet</h3>
                <p class="text-muted mb-4">
                    This category is waiting for amazing products. Be the first to discover them when they arrive!
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on scroll
            const cards = document.querySelectorAll('.category-card');
            
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
</body>
</html>