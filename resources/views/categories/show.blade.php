<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 bg-white rounded-pill px-3 py-2 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.index') }}" class="text-decoration-none text-primary fw-semibold">
                        <i class="bi bi-house-door me-1"></i>Categories
                    </a>
                </li>
                @if($category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.show', $category->parent->slug) }}" class="text-decoration-none text-primary fw-semibold">
                            {{ $category->parent->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active fw-bold text-dark">{{ $category->name }}</li>
            </ol>
        </nav>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .category-header {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            position: relative;
        }
        
        .category-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .filter-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .filter-card:hover {
            transform: translateY(-2px);
        }
        
        .product-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .product-card .card-img-top {
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .card-img-top {
            transform: scale(1.05);
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
        
        .subcategory-btn {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            border: none;
            border-radius: 20px;
            padding: 6px 16px;
            font-weight: 600;
            color: #2d3436;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(251, 177, 160, 0.3);
        }
        
        .subcategory-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 177, 160, 0.4);
            color: #2d3436;
        }
        
        .sort-dropdown {
            background: white;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .sort-dropdown:hover {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .price-section .price {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .stock-badge {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            border-radius: 12px;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .out-stock-badge {
            background: linear-gradient(135deg, #e17055 0%, #fd79a8 100%);
            color: white;
            border-radius: 12px;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .pagination .page-link {
            border-radius: 12px;
            margin: 0 4px;
            border: none;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            color: #667eea;
            font-weight: 600;
        }
        
        .pagination .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .no-products {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 1rem;
            }
            
            .filter-card {
                margin-bottom: 2rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Category Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="category-header p-4">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3">
                        <div>
                            <h1 class="h2 mb-2 fw-bold text-dark">
                                <i class="bi bi-tag me-2 text-primary"></i>{{ $category->name }}
                            </h1>
                            @if($category->description)
                                <p class="text-muted mb-0">{{ $category->description }}</p>
                            @endif
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge rounded-pill px-3 py-2 text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-box me-1"></i>{{ $products->total() }} Products
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategories -->
        @if($subcategories->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded-3 p-4 shadow-sm">
                        <h5 class="mb-3 fw-bold text-dark">
                            <i class="bi bi-collection me-2 text-primary"></i>Explore Subcategories
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($subcategories as $subcategory)
                                <a href="{{ route('category.show', $subcategory->slug) }}" class="subcategory-btn">
                                    <i class="bi bi-arrow-right-circle me-1"></i>{{ $subcategory->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Filters Sidebar -->
            @if($filters->count() > 0 || true)
                <div class="col-lg-3 mb-4">
                    <div class="filter-card">
                        <div class="card-header bg-transparent border-0 p-4">
                            <h6 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="bi bi-funnel me-2 text-primary"></i>Filters
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form method="GET" action="{{ route('category.show', $category->slug) }}" id="filterForm">
                                <!-- Search Filter -->
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-dark mb-3">
                                        <i class="bi bi-search me-2"></i>Search
                                    </h6>
                                    <input type="text" name="search" class="form-control" placeholder="Search products..." 
                                           value="{{ request('search') }}" style="border-radius: 8px;">
                                </div>
                                
                                <!-- Price Filter -->
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-dark mb-3">
                                        <i class="bi bi-currency-dollar me-2"></i>Price Range
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="min_price" class="form-control form-control-sm" 
                                                   placeholder="Min" value="{{ request('min_price') }}" min="0" step="0.01" style="border-radius: 8px;">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="max_price" class="form-control form-control-sm" 
                                                   placeholder="Max" value="{{ request('max_price') }}" min="0" step="0.01" style="border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dynamic Attribute Filters -->
                                @foreach($filters as $filter)
                                    @if(isset($availableFilterValues[$filter->id]) && $availableFilterValues[$filter->id]->count() > 0)
                                        <div class="mb-4">
                                            <h6 class="fw-semibold text-dark mb-3">
                                                <i class="bi bi-tag me-2"></i>{{ $filter->name }}
                                            </h6>
                                            
                                            @if($filter->type === 'select' || $filter->type === 'radio')
                                                @foreach($availableFilterValues[$filter->id] as $value)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input filter-checkbox" type="checkbox" 
                                                               name="attr_{{ $filter->id }}[]" 
                                                               value="{{ $value }}"
                                                               id="filter_{{ $filter->id }}_{{ $loop->index }}" 
                                                               {{ in_array($value, (array) request('attr_' . $filter->id, [])) ? 'checked' : '' }}
                                                               style="border-radius: 6px;">
                                                        <label class="form-check-label fw-medium" for="filter_{{ $filter->id }}_{{ $loop->index }}">
                                                            {{ $value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @elseif($filter->type === 'multiselect' || $filter->type === 'checkbox')
                                                @foreach($availableFilterValues[$filter->id] as $value)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input filter-checkbox" type="checkbox" 
                                                               name="attr_{{ $filter->id }}[]" 
                                                               value="{{ $value }}"
                                                               id="filter_{{ $filter->id }}_{{ $loop->index }}" 
                                                               {{ in_array($value, (array) request('attr_' . $filter->id, [])) ? 'checked' : '' }}
                                                               style="border-radius: 6px;">
                                                        <label class="form-check-label fw-medium" for="filter_{{ $filter->id }}_{{ $loop->index }}">
                                                            {{ $value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @elseif($filter->type === 'boolean')
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input filter-checkbox" type="checkbox" 
                                                           name="attr_{{ $filter->id }}[]" 
                                                           value="1"
                                                           id="filter_{{ $filter->id }}_yes" 
                                                           {{ in_array('1', (array) request('attr_' . $filter->id, [])) ? 'checked' : '' }}
                                                           style="border-radius: 6px;">
                                                    <label class="form-check-label fw-medium" for="filter_{{ $filter->id }}_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input filter-checkbox" type="checkbox" 
                                                           name="attr_{{ $filter->id }}[]" 
                                                           value="0"
                                                           id="filter_{{ $filter->id }}_no" 
                                                           {{ in_array('0', (array) request('attr_' . $filter->id, [])) ? 'checked' : '' }}
                                                           style="border-radius: 6px;">
                                                    <label class="form-check-label fw-medium" for="filter_{{ $filter->id }}_no">
                                                        No
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                                        <i class="bi bi-check-circle me-2"></i>Apply Filters
                                    </button>
                                    <a href="{{ route('category.show', $category->slug) }}" class="btn btn-outline-secondary w-100 btn-sm">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
            @else
                <div class="col-12">
            @endif
                
                <!-- Products Grid -->
                @if($products->count() > 0)
                    <!-- Sort Options -->
                    <div class="bg-white rounded-3 p-3 mb-4 shadow-sm">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-grid me-2 text-primary"></i>
                                <span class="fw-semibold text-dark">
                                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} results
                                </span>
                            </div>
                            <div class="dropdown">
                                <button class="btn sort-dropdown dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-sort-down me-2"></i>Sort by: 
                                    @switch(request('sort', 'featured'))
                                        @case('price_low') Price: Low to High @break
                                        @case('price_high') Price: High to Low @break
                                        @case('newest') Newest @break
                                        @case('name') Name A-Z @break
                                        @default Featured
                                    @endswitch
                                </button>
                                <ul class="dropdown-menu shadow border-0" style="border-radius: 12px;">
                                    <li><a class="dropdown-item fw-medium {{ request('sort') === 'featured' || !request('sort') ? 'active' : '' }}" 
                                           href="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}">
                                           <i class="bi bi-star me-2"></i>Featured</a></li>
                                    <li><a class="dropdown-item fw-medium {{ request('sort') === 'price_low' ? 'active' : '' }}" 
                                           href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}">
                                           <i class="bi bi-arrow-up me-2"></i>Price: Low to High</a></li>
                                    <li><a class="dropdown-item fw-medium {{ request('sort') === 'price_high' ? 'active' : '' }}" 
                                           href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}">
                                           <i class="bi bi-arrow-down me-2"></i>Price: High to Low</a></li>
                                    <li><a class="dropdown-item fw-medium {{ request('sort') === 'newest' ? 'active' : '' }}" 
                                           href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">
                                           <i class="bi bi-clock me-2"></i>Newest</a></li>
                                    <li><a class="dropdown-item fw-medium {{ request('sort') === 'name' ? 'active' : '' }}" 
                                           href="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}">
                                           <i class="bi bi-sort-alpha-down me-2"></i>Name A-Z</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                    <div class="product-card h-100">
                                    <div class="position-relative overflow-hidden" style="height: 220px;">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ \Storage::url($product->images[0]) }}" class="card-img-top w-100 h-100" alt="{{ $product->name }}" style="object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        
                                        
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <button class="btn btn-sm rounded-circle bg-white shadow-sm" style="width: 40px; height: 40px;">
                                                <i class="bi bi-heart text-muted"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-2 text-truncate" title="{{ $product->name }}">
                                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                                {{ $product->name }}
                                            </a>
                                        </h6>
                                        
                                        <div class="d-flex align-items-center mb-2">
                                            <small class="text-muted fw-medium">by </small>
                                            <small class="text-primary fw-semibold ms-1">Seller</small>
                                        </div>
                                        
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="d-flex me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= 4 ? '-fill text-warning' : '' }}" style="font-size: 0.8rem;"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted fw-medium">({{ rand(10, 999) }} reviews)</small>
                                        </div>
                                        
                                        <div class="price-section mb-3">
                                            <span class="price h5 mb-0 fw-bold">Price Available</span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="stock-badge">
                                                <i class="bi bi-check-circle me-1"></i>Available
                                            </span>
                                            
                                            <button class="btn btn-primary-custom btn-sm" onclick="event.preventDefault(); event.stopPropagation(); alert('View product for details');">
                                                <i class="bi bi-eye me-1"></i>View
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-5">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="no-products text-center">
                        <div class="mb-4">
                            <i class="bi bi-box display-1 text-muted"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-3">No Products Found</h3>
                        <p class="text-muted mb-4">There are currently no products in this category.</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-primary-custom">
                            <i class="bi bi-arrow-left me-2"></i>Browse Other Categories
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function addToCartFromListing(variationId) {
            if (!variationId) {
                alert('Product variation not available');
                return;
            }
            
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
                    showNotification(data.message, 'success');
                    updateCartCount();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding to cart', 'error');
            });
        }
    </script>
</x-app-layout>