<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products - Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            margin: 2rem auto;
            max-width: 1400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
        }
        
        .product-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            background: white;
            height: 100%;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .stats-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--card-color, #667eea), transparent);
        }
        
        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .filter-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(15px);
        }
        
        .form-control-custom, .form-select-custom {
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .empty-state {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            padding: 4rem 2rem;
            text-align: center;
            margin: 2rem 0;
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="bi bi-shop me-2"></i>Marketplace
            </a>
            <div class="navbar-nav ms-auto">
                <a href="/seller/dashboard" class="nav-link text-muted">
                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                </a>
                <span class="navbar-text text-primary fw-semibold">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                </span>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Header -->
        <div class="header-gradient">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 fw-bold">
                        <i class="bi bi-box-seam me-3"></i>My Products
                    </h1>
                    <p class="mb-0 opacity-90">Manage your product catalog and inventory</p>
                </div>
                <a href="{{ route('seller.products.create') }}" class="btn btn-light btn-lg rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Add Product
                </a>
            </div>
        </div>

        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card p-4" style="--card-color: #667eea;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Products</h6>
                                <h2 class="mb-0 fw-bold text-primary">{{ count($products ?? []) }}</h2>
                            </div>
                            <div class="text-primary" style="font-size: 3rem; opacity: 0.7;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card p-4" style="--card-color: #28a745;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Approved</h6>
                                <h2 class="mb-0 fw-bold text-success">{{ count(array_filter($products ?? [], fn($p) => ($p['status'] ?? '') === 'approved')) }}</h2>
                            </div>
                            <div class="text-success" style="font-size: 3rem; opacity: 0.7;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card p-4" style="--card-color: #ffc107;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Pending</h6>
                                <h2 class="mb-0 fw-bold text-warning">{{ count(array_filter($products ?? [], fn($p) => ($p['status'] ?? '') === 'pending')) }}</h2>
                            </div>
                            <div class="text-warning" style="font-size: 3rem; opacity: 0.7;">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card p-4" style="--card-color: #17a2b8;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Featured</h6>
                                <h2 class="mb-0 fw-bold text-info">{{ count(array_filter($products ?? [], fn($p) => isset($p['is_featured']) && $p['is_featured'])) }}</h2>
                            </div>
                            <div class="text-info" style="font-size: 3rem; opacity: 0.7;">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-card mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('seller.products.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted small">Search Products</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control form-control-custom" 
                                       placeholder="Search by name or SKU...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">Status</label>
                                <select name="status" class="form-select form-select-custom">
                                    <option value="">All Status</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">Category</label>
                                <select name="category" class="form-select form-select-custom">
                                    <option value="">All Categories</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">Featured</label>
                                <select name="featured" class="form-select form-select-custom">
                                    <option value="">All Products</option>
                                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-gradient flex-grow-1">
                                        <i class="bi bi-search me-1"></i>Filter Products
                                    </button>
                                    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-pill">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products && count($products ?? []) > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="position-relative overflow-hidden">
                                    @if(isset($product['image']) && $product['image'])
                                        <img src="{{ Storage::url($product['image']) }}" 
                                             class="card-img-top product-image w-100" 
                                             alt="{{ $product['name'] ?? 'Product' }}">
                                    @else
                                        <div class="bg-gradient d-flex align-items-center justify-content-center product-image" 
                                             style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="status-badge 
                                            @if(($product['status'] ?? '') === 'approved') bg-success text-white
                                            @elseif(($product['status'] ?? '') === 'pending') bg-warning text-dark
                                            @else bg-danger text-white @endif">
                                            {{ ucfirst($product['status'] ?? 'unknown') }}
                                        </span>
                                    </div>
                                    
                                    @if(isset($product['is_featured']) && $product['is_featured'])
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                                <i class="bi bi-star-fill me-1"></i>Featured
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-2">{{ $product['name'] ?? 'Unnamed Product' }}</h5>
                                    <p class="text-muted small mb-3">{{ Str::limit($product['short_description'] ?? '', 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            @if(isset($product['min_price']) && $product['min_price'])
                                                <span class="price-tag">
                                                    ${{ number_format($product['min_price'], 2) }}
                                                    @if(isset($product['max_price']) && $product['min_price'] !== $product['max_price'])
                                                        - ${{ number_format($product['max_price'], 2) }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-eye me-1"></i>{{ $product['views'] ?? 0 }} views
                                        </small>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded-3">
                                                <small class="text-muted d-block">Stock</small>
                                                <span class="fw-bold text-primary">{{ $product['total_stock'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded-3">
                                                <small class="text-muted d-block">SKU</small>
                                                <span class="fw-bold text-info">{{ $product['sku'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('seller.products.show', $product['id']) }}" 
                                           class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="{{ route('seller.products.edit', $product['id']) }}" 
                                           class="btn btn-outline-secondary btn-sm rounded-pill flex-grow-1">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Simple pagination message -->
                <div class="text-center mt-4">
                    <p class="text-muted">Showing {{ count($products ?? []) }} products</p>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="mb-4">
                        <i class="bi bi-box-seam" style="font-size: 5rem; color: #667eea; opacity: 0.7;"></i>
                    </div>
                    <h3 class="text-dark mb-3">No products found</h3>
                    <p class="text-muted mb-4 lead">Start by adding your first product to begin selling on our marketplace.</p>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-gradient btn-lg px-5 py-3">
                        <i class="bi bi-plus-circle me-2"></i>Add Your First Product
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>