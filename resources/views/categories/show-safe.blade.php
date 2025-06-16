<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name ?? 'Category' }} - Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1>{{ $category->name ?? 'Category' }}</h1>
        <p class="text-muted">{{ $category->description ?? 'No description available' }}</p>
        
        <div class="row">
            <div class="col-12">
                <h3>Products ({{ $products ? $products->total() : 0 }})</h3>
                
                @if($products && $products->count() > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name ?? 'No name' }}</h5>
                                        <p class="card-text">Status: {{ $product->status ?? 'N/A' }}</p>
                                        <p class="card-text">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                        <small class="text-muted">Product ID: {{ $product->id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(method_exists($products, 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <div class="alert alert-info">
                        <h4>No products found</h4>
                        <p>There are currently no products in this category.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>