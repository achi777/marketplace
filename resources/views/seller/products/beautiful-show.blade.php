<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $productData['name'] }} - Product Details</title>
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
        
        .product-hero {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            display: inline-block;
        }
        
        .card-modern {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            background: white;
            margin-bottom: 2rem;
        }
        
        .card-modern .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            padding: 1.5rem 2rem;
            font-weight: 600;
            color: #495057;
        }
        
        .card-modern .card-body {
            padding: 2rem;
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
        
        .btn-outline-modern {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            border-radius: 25px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-modern:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .product-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        
        .variation-card {
            background: rgba(102, 126, 234, 0.05);
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .variation-card:hover {
            border-color: rgba(102, 126, 234, 0.3);
            background: rgba(102, 126, 234, 0.1);
        }
        
        .stats-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: 2px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s ease;
        }
        
        .stats-box:hover {
            transform: translateY(-5px);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .breadcrumb-modern {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }
        
        .breadcrumb-modern .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .info-item {
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        
        .info-item h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .alert-modern {
            border: none;
            border-radius: 15px;
            border-left: 4px solid;
            padding: 1.5rem;
        }
        
        .price-display {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 15px;
            display: inline-block;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .attribute-badge {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            margin: 0.25rem;
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 500;
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
                        <i class="bi bi-box-seam me-3"></i>{{ $productData['name'] }}
                    </h1>
                    <p class="mb-0 opacity-90">Product Details & Management</p>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    @if($productData['status'] == 'approved')
                        <span class="status-badge bg-success text-white">
                            <i class="bi bi-check-circle me-2"></i>Approved
                        </span>
                    @elseif($productData['status'] == 'pending')
                        <span class="status-badge bg-warning text-dark">
                            <i class="bi bi-clock me-2"></i>Pending Review
                        </span>
                    @elseif($productData['status'] == 'rejected')
                        <span class="status-badge bg-danger text-white">
                            <i class="bi bi-x-circle me-2"></i>Rejected
                        </span>
                    @else
                        <span class="status-badge bg-secondary text-white">
                            {{ ucfirst($productData['status']) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4">
            <!-- Breadcrumb -->
            <div class="breadcrumb-modern">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('seller.dashboard') }}" class="text-decoration-none">
                                <i class="bi bi-house me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('seller.products.index') }}" class="text-decoration-none">
                                <i class="bi bi-box-seam me-1"></i>My Products
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $productData['name'] }}</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Product Hero -->
                    <div class="product-hero">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="fw-bold mb-3">{{ $productData['name'] }}</h2>
                                <p class="lead text-muted mb-4">{{ $productData['short_description'] }}</p>
                                
                                <div class="info-grid">
                                    <div class="info-item">
                                        <h6>SKU</h6>
                                        <p class="mb-0 fw-semibold">{{ $productData['sku'] ?? 'Not set' }}</p>
                                    </div>
                                    <div class="info-item">
                                        <h6>Category</h6>
                                        <p class="mb-0 fw-semibold">{{ $productData['category_name'] }}</p>
                                    </div>
                                    <div class="info-item">
                                        <h6>Created</h6>
                                        <p class="mb-0 fw-semibold">{{ date('M d, Y', strtotime($productData['created_at'])) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="product-image">
                                    @if(count($productData['images']) > 0)
                                        <img src="{{ asset('storage/' . $productData['images'][0]) }}" 
                                             class="img-fluid w-100" 
                                             style="height: 400px; object-fit: cover;" 
                                             alt="{{ $productData['name'] }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                            <div class="text-center">
                                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                                <p class="text-muted mt-3">No image available</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($productData['description'])
                        <div class="card-modern">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Product Description</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $productData['description'] }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Rejection Reason -->
                    @if($productData['status'] == 'rejected' && $productData['rejection_reason'])
                        <div class="alert alert-danger alert-modern border-danger">
                            <h6 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle me-2"></i>Rejection Reason</h6>
                            <p class="mb-0">{{ $productData['rejection_reason'] }}</p>
                        </div>
                    @endif

                    <!-- Product Variations -->
                    <div class="card-modern">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-gear me-2"></i>Product Variations
                                    <span class="badge bg-primary ms-2">{{ count($variationsData) }}</span>
                                </h5>
                                <a href="{{ route('seller.products.variations', $productData['id']) }}" class="btn btn-outline-modern btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Manage
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($variationsData) > 0)
                                @foreach($variationsData as $variation)
                                    <div class="variation-card">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h6 class="fw-bold mb-1">{{ $variation['sku'] }}</h6>
                                                <span class="price-display">
                                                    ${{ number_format($variation['price'], 2) }}
                                                </span>
                                                @if($variation['compare_price'])
                                                    <p class="text-muted mb-0 mt-1">
                                                        <small><del>${{ number_format($variation['compare_price'], 2) }}</del></small>
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <h6 class="text-muted mb-1">Stock</h6>
                                                <span class="badge {{ $variation['stock_quantity'] > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                    {{ $variation['stock_quantity'] }} units
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted mb-2">Attributes</h6>
                                                @if(count($variation['attributes']) > 0)
                                                    @foreach($variation['attributes'] as $key => $value)
                                                        <span class="attribute-badge">{{ ucfirst($key) }}: {{ $value }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No attributes</span>
                                                @endif
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge {{ $variation['is_active'] ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                    {{ $variation['is_active'] ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-gear text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="text-muted mt-3">No variations found</h5>
                                    <p class="text-muted mb-4">Add variations to make this product available for purchase.</p>
                                    <a href="{{ route('seller.products.variations', $productData['id']) }}" class="btn btn-gradient">
                                        <i class="bi bi-plus-circle me-2"></i>Add Variations
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="stats-box mb-4">
                        <h5 class="fw-bold mb-3">Quick Stats</h5>
                        <div class="row">
                            <div class="col-6">
                                <h2 class="text-primary fw-bold">{{ count($variationsData) }}</h2>
                                <p class="text-muted mb-0">Variations</p>
                            </div>
                            <div class="col-6">
                                <h2 class="text-success fw-bold">{{ array_sum(array_column($variationsData, 'stock_quantity')) }}</h2>
                                <p class="text-muted mb-0">Total Stock</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-modern">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-tools me-2"></i>Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                @if($productData['status'] != 'approved')
                                    <a href="{{ route('seller.products.edit', $productData['id']) }}" class="btn btn-gradient">
                                        <i class="bi bi-pencil me-2"></i>Edit Product
                                    </a>
                                @endif
                                
                                <a href="{{ route('seller.products.variations', $productData['id']) }}" class="btn btn-outline-modern">
                                    <i class="bi bi-gear me-2"></i>Manage Variations
                                </a>
                                
                                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-modern">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                                </a>
                                
                                @if($productData['status'] != 'approved')
                                    <hr class="my-3">
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                        <i class="bi bi-trash me-2"></i>Delete Product
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if(isset($productData['weight']) && $productData['weight'] || isset($productData['dimensions']) && $productData['dimensions'])
                        <div class="card-modern">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-box me-2"></i>Shipping Info</h6>
                            </div>
                            <div class="card-body">
                                @if(isset($productData['weight']) && $productData['weight'])
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1">Weight</h6>
                                        <p class="mb-0 fw-semibold">{{ $productData['weight'] }}</p>
                                    </div>
                                @endif
                                @if(isset($productData['dimensions']) && $productData['dimensions'])
                                    <div>
                                        <h6 class="text-muted mb-1">Dimensions</h6>
                                        <p class="mb-0 fw-semibold">{{ $productData['dimensions'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                // Create form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("seller.products.destroy", $productData["id"]) }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method spoofing
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>