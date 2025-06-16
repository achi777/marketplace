<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">My Products</h2>
                <p class="text-muted small mb-0">Manage your product catalog</p>
            </div>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i>Add Product
            </a>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        
        .product-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background: white;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .filter-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Products</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $products->total() ?? 0 }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2.5rem;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Approved</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $products->where('status', 'approved')->count() ?? 0 }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2.5rem;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Pending</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $products->where('status', 'pending')->count() ?? 0 }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2.5rem;">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Featured</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $products->where('is_featured', true)->count() ?? 0 }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2.5rem;">
                            <i class="bi bi-star"></i>
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
                            <label class="form-label fw-semibold text-muted small">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="form-control rounded-pill border-0 bg-light" 
                                   placeholder="Search products...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted small">Status</label>
                            <select name="status" class="form-select rounded-pill border-0 bg-light">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted small">Category</label>
                            <select name="category" class="form-select rounded-pill border-0 bg-light">
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
                            <select name="featured" class="form-select rounded-pill border-0 bg-light">
                                <option value="">All Products</option>
                                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                                <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-custom flex-grow-1">
                                    <i class="bi bi-search me-1"></i>Filter
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
        @if($products && $products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="position-relative">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ Storage::url($product->images[0]) }}" 
                                         class="card-img-top" 
                                         alt="{{ $product->name }}" 
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="status-badge 
                                        @if($product->status === 'approved') bg-success text-white
                                        @elseif($product->status === 'pending') bg-warning text-dark
                                        @else bg-danger text-white @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                                
                                @if($product->is_featured)
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="bi bi-star-fill"></i> Featured
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                                <p class="text-muted small mb-3">{{ Str::limit($product->short_description, 100) }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        @if($product->min_price)
                                            <span class="h6 fw-bold text-primary mb-0">
                                                ${{ number_format($product->min_price, 2) }}
                                                @if($product->min_price !== $product->max_price)
                                                    - ${{ number_format($product->max_price, 2) }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-eye me-1"></i>{{ $product->views ?? 0 }} views
                                    </small>
                                </div>
                                
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Stock</small>
                                        <span class="fw-semibold">{{ $product->total_stock ?? 0 }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Variations</small>
                                        <span class="fw-semibold">{{ $product->variations_count ?? 0 }}</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('seller.products.show', $product) }}" 
                                       class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('seller.products.edit', $product) }}" 
                                       class="btn btn-outline-secondary btn-sm rounded-pill flex-grow-1">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-muted mb-3">No products found</h4>
                <p class="text-muted mb-4">Start by adding your first product to begin selling on our marketplace.</p>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary-custom btn-lg rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Add Your First Product
                </a>
            </div>
        @endif
    </div>
</x-app-layout>