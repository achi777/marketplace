<!DOCTYPE html>
<html>
<head>
    <title>{{ $category->name }}</title>
</head>
<body>
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description ?? 'No description available' }}</p>
    <p>Products: {{ $products->count() ?? 0 }}</p>
    
    @if($products->count() > 0)
        <ul>
            @foreach($products as $product)
                <li>{{ $product->name ?? 'No name' }} (ID: {{ $product->id }})</li>
            @endforeach
        </ul>
    @else
        <p>No approved products in this category.</p>
    @endif
</body>
</html>