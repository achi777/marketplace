<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $productData['name'] }} - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.products.index') }}">My Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $productData['name'] }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-8">
                <!-- Product Info -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Information</h5>
                        <div>
                            @if($productData['status'] == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($productData['status'] == 'pending')
                                <span class="badge bg-warning">Pending Review</span>
                            @elseif($productData['status'] == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($productData['status']) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>{{ $productData['name'] }}</h4>
                                <p class="text-muted">{{ $productData['short_description'] }}</p>
                                
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>SKU:</strong></td>
                                        <td>{{ $productData['sku'] ?? 'Not set' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category:</strong></td>
                                        <td>{{ $productData['category_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ date('M d, Y', strtotime($productData['created_at'])) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if(count($productData['images']) > 0)
                                    <img src="{{ asset('storage/' . $productData['images'][0]) }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px;" 
                                         alt="{{ $productData['name'] }}">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($productData['description'])
                            <div class="mt-3">
                                <h6>Description:</h6>
                                <p>{{ $productData['description'] }}</p>
                            </div>
                        @endif

                        @if($productData['status'] == 'rejected' && $productData['rejection_reason'])
                            <div class="alert alert-danger mt-3">
                                <h6>Rejection Reason:</h6>
                                <p class="mb-0">{{ $productData['rejection_reason'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Variations -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Product Variations ({{ count($variationsData) }})</h5>
                    </div>
                    <div class="card-body">
                        @if(count($variationsData) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Attributes</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($variationsData as $variation)
                                            <tr>
                                                <td>{{ $variation['sku'] }}</td>
                                                <td>
                                                    <strong>${{ number_format($variation['price'], 2) }}</strong>
                                                    @if($variation['compare_price'])
                                                        <br><small class="text-muted"><del>${{ number_format($variation['compare_price'], 2) }}</del></small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $variation['stock_quantity'] > 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $variation['stock_quantity'] }} units
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(count($variation['attributes']) > 0)
                                                        @foreach($variation['attributes'] as $key => $value)
                                                            <small class="badge bg-light text-dark">{{ ucfirst($key) }}: {{ $value }}</small><br>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">None</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $variation['is_active'] ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $variation['is_active'] ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h6>No variations found</h6>
                                <p class="mb-0">This product doesn't have any variations yet. Add variations to make it available for purchase.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($productData['status'] != 'approved')
                                <a href="{{ route('seller.products.edit', $productData['id']) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit Product
                                </a>
                            @endif
                            
                            <a href="{{ route('seller.products.variations', $productData['id']) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-gear"></i> Manage Variations
                            </a>
                            
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Back to Products
                            </a>
                            
                            @if($productData['status'] != 'approved')
                                <hr>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                    <i class="bi bi-trash"></i> Delete Product
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary">{{ count($variationsData) }}</h4>
                                <small class="text-muted">Variations</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ array_sum(array_column($variationsData, 'stock_quantity')) }}</h4>
                                <small class="text-muted">Total Stock</small>
                            </div>
                        </div>
                    </div>
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