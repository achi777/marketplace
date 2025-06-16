<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/categories">Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <h1>{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-muted">{{ $category->description }}</p>
                @endif
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Products ({{ $products->count() }})</h3>
                
                @if($products->count() > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                        <span class="badge bg-success">{{ ucfirst($product->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <h4>No products found</h4>
                        <p>There are currently no approved products in this category.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>