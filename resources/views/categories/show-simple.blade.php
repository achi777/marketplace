<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <h1>{{ $category->name }}</h1>
        <p class="text-muted">{{ $category->description ?? 'No description available' }}</p>
        
        <div class="row">
            <div class="col-12">
                <h3>Products ({{ $products->total() }})</h3>
                
                @if($products->count() > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ $product->short_description }}</p>
                                        
                                        @if($product->images && is_array($product->images) && count($product->images) > 0)
                                            <img src="{{ \Storage::url($product->images[0]) }}" 
                                                 class="img-fluid mb-2" 
                                                 style="height: 200px; object-fit: cover;" 
                                                 alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-light p-4 text-center">
                                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mt-2">No image</p>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Product ID: {{ $product->id }}</small>
                                            <span class="badge bg-primary">{{ $product->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <h4>No products found</h4>
                        <p>There are currently no products in this category.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>