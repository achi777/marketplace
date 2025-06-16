<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Variations - {{ $productData['name'] }}</title>
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
            transform: translateY(-2px);
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
        
        .empty-state {
            background: rgba(102, 126, 234, 0.05);
            border: 2px dashed rgba(102, 126, 234, 0.2);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            margin: 2rem 0;
        }
        
        .add-variation-form {
            background: rgba(102, 126, 234, 0.05);
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
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
                        <i class="bi bi-gear me-3"></i>Manage Variations
                    </h1>
                    <p class="mb-0 opacity-90">{{ $productData['name'] }} - Product Variations</p>
                </div>
                <a href="{{ route('seller.products.show', $productData['id']) }}" class="btn btn-light btn-lg rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Product
                </a>
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('seller.products.show', $productData['id']) }}" class="text-decoration-none">
                                {{ $productData['name'] }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Variations</li>
                    </ol>
                </nav>
            </div>

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

            <!-- Add New Variation Form -->
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Variation</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.products.variations.store', $productData['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Price *</label>
                                    <input type="number" name="price" step="0.01" min="0" class="form-control form-control-custom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Compare Price</label>
                                    <input type="number" name="compare_price" step="0.01" min="0" class="form-control form-control-custom">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Stock Quantity *</label>
                                    <input type="number" name="stock_quantity" min="0" class="form-control form-control-custom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Low Stock Threshold</label>
                                    <input type="number" name="low_stock_threshold" min="0" class="form-control form-control-custom">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">SKU</label>
                                    <input type="text" name="sku" class="form-control form-control-custom">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Variation Image</label>
                                    <input type="file" name="image" accept="image/*" class="form-control form-control-custom">
                                </div>
                            </div>
                        </div>

                        <!-- Attributes -->
                        @if(count($attributesData) > 0)
                            <h6 class="mt-4 mb-3 fw-bold">Product Attributes</h6>
                            <div class="row">
                                @foreach($attributesData as $attribute)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            {{ $attribute['name'] }}
                                            @if($attribute['is_required']) <span class="text-danger">*</span> @endif
                                        </label>
                                        
                                        @if($attribute['type'] === 'select')
                                            <select name="attributes[{{ $attribute['name'] }}]" 
                                                    class="form-select form-select-custom" 
                                                    {{ $attribute['is_required'] ? 'required' : '' }}>
                                                <option value="">Select {{ $attribute['name'] }}</option>
                                                @foreach($attribute['options'] as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($attribute['type'] === 'multiselect')
                                            @foreach($attribute['options'] as $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="attributes[{{ $attribute['name'] }}][]" 
                                                           value="{{ $option }}" 
                                                           id="attr_{{ $attribute['id'] }}_{{ $loop->index }}">
                                                    <label class="form-check-label" for="attr_{{ $attribute['id'] }}_{{ $loop->index }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <input type="text" 
                                                   name="attributes[{{ $attribute['name'] }}]" 
                                                   class="form-control form-control-custom"
                                                   {{ $attribute['is_required'] ? 'required' : '' }}>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                            <label class="form-check-label fw-semibold" for="is_active">
                                Active Variation
                            </label>
                        </div>

                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-plus-circle me-2"></i>Add Variation
                        </button>
                    </form>
                </div>
            </div>

            <!-- Existing Variations -->
            <div class="card-modern">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list me-2"></i>Existing Variations
                            <span class="badge bg-primary ms-2">{{ count($variationsData) }}</span>
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($variationsData) > 0)
                        @foreach($variationsData as $variation)
                            <div class="variation-card">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h6 class="fw-bold mb-1">{{ $variation['sku'] ?: 'No SKU' }}</h6>
                                        <div class="price-display bg-gradient text-white rounded px-3 py-2 d-inline-block">
                                            ${{ number_format($variation['price'], 2) }}
                                        </div>
                                        @if($variation['compare_price'])
                                            <p class="text-muted mb-0 mt-1">
                                                <small><del>${{ number_format($variation['compare_price'], 2) }}</del></small>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <h6 class="text-muted mb-1">Stock</h6>
                                        <span class="badge {{ $variation['stock_quantity'] > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                            {{ $variation['stock_quantity'] }}
                                        </span>
                                        @if($variation['low_stock_threshold'])
                                            <p class="text-muted mb-0 mt-1">
                                                <small>Low: {{ $variation['low_stock_threshold'] }}</small>
                                            </p>
                                        @endif
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
                                        <span class="badge {{ $variation['is_active'] ? 'bg-success' : 'bg-secondary' }} fs-6 mb-2">
                                            {{ $variation['is_active'] ? 'Active' : 'Inactive' }}
                                        </span>
                                        <p class="text-muted mb-0">
                                            <small>{{ date('M d, Y', strtotime($variation['created_at'])) }}</small>
                                        </p>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="bi bi-gear text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No variations found</h5>
                            <p class="text-muted mb-4">Add your first product variation to start selling this product.</p>
                            <p class="text-muted">Use the form above to create variations with different prices, attributes, and stock levels.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>