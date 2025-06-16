<!DOCTYPE html>
<html>
<head>
    <title>Debug Category</title>
</head>
<body>
    <h1>Category: {{ $category->name }}</h1>
    <p>ID: {{ $category->id }}</p>
    <p>Slug: {{ $category->slug }}</p>
    
    <h2>Products Count: {{ $products->count() }}</h2>
    
    @foreach($products as $product)
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h3>{{ $product->name ?? 'No name' }}</h3>
            <p>ID: {{ $product->id ?? 'No ID' }}</p>
            <p>Status: {{ $product->status ?? 'No status' }}</p>
        </div>
    @endforeach
</body>
</html>