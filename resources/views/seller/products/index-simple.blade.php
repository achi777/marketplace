<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products - Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>My Products</h1>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Product
                    </a>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('seller.products.index') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search products..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                @if(count($products) > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card h-100">
                                    @if($product['image'])
                                        <img src="{{ asset('storage/' . $product['image']) }}" 
                                             class="card-img-top" 
                                             style="height: 200px; object-fit: cover;" 
                                             alt="{{ $product['name'] }}">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ $product['name'] }}</h6>
                                        <p class="card-text small text-muted">SKU: {{ $product['sku'] }}</p>
                                        
                                        @if($product['min_price'])
                                            <p class="card-text text-primary fw-bold">
                                                From ${{ number_format($product['min_price'], 2) }}
                                            </p>
                                        @else
                                            <p class="card-text text-muted">No price set</p>
                                        @endif
                                        
                                        <div class="mb-2">
                                            @if($product['status'] == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($product['status'] == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($product['status'] == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($product['status']) }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-auto">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('seller.products.show', $product['id']) }}" 
                                                   class="btn btn-outline-primary btn-sm">View</a>
                                                @if($product['status'] != 'approved')
                                                    <a href="{{ route('seller.products.edit', $product['id']) }}" 
                                                       class="btn btn-outline-secondary btn-sm">Edit</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <h4>No products found</h4>
                        <p>You haven't created any products yet, or no products match your current filters.</p>
                        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">Create Your First Product</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>