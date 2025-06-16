<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">{{ $product->name }}</h2>
                <p class="text-muted small mb-0">Product details and analytics</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-pencil me-2"></i>Edit Product
                </a>
                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        
        .product-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .variation-card {
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .variation-card:hover {
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .image-gallery {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .main-image {
            border-radius: 12px;
            border: 2px solid #e9ecef;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .thumbnail:hover, .thumbnail.active {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .attribute-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.25rem;
            display: inline-block;
        }
        
        .section-title {
            color: #1a1a1a;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Product Images and Info -->
            <div class="col-lg-8">
                <div class="product-card mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Images -->
                            <div class="col-md-6">
                                <div class="image-gallery">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ Storage::url($product->images[0]) }}" 
                                             class="main-image w-100 mb-3" 
                                             alt="{{ $product->name }}" 
                                             style="height: 300px; object-fit: cover;"
                                             id="mainImage">
                                        
                                        @if(count($product->images) > 1)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($product->images as $index => $image)
                                                    <img src="{{ Storage::url($image) }}" 
                                                         class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                                         alt="Image {{ $index + 1 }}"
                                                         onclick="changeMainImage('{{ Storage::url($image) }}', this)">
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center main-image w-100" style="height: 300px;">
                                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="status-badge me-3
                                        @if($product->status === 'approved') bg-success text-white
                                        @elseif($product->status === 'pending') bg-warning text-dark
                                        @else bg-danger text-white @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                    
                                    @if($product->is_featured)
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="bi bi-star-fill me-1"></i>Featured
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="section-title">{{ $product->name }}</h3>
                                
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Category</h6>
                                    <span class="badge bg-light text-dark rounded-pill">
                                        <i class="bi bi-folder me-1"></i>{{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Price Range</h6>
                                    @if($product->min_price)
                                        <div class="h4 text-primary fw-bold">
                                            ${{ number_format($product->min_price, 2) }}
                                            @if($product->min_price !== $product->max_price)
                                                - ${{ number_format($product->max_price, 2) }}
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No variations added</span>
                                    @endif
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Short Description</h6>
                                    <p class="text-secondary">{{ $product->short_description }}</p>
                                </div>
                                
                                @if($product->weight || $product->dimensions)
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Specifications</h6>
                                        @if($product->weight)
                                            <div class="mb-1">
                                                <small class="text-muted">Weight:</small> {{ $product->weight }} kg
                                            </div>
                                        @endif
                                        @if($product->dimensions)
                                            <div class="mb-1">
                                                <small class="text-muted">Dimensions:</small> {{ $product->dimensions }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Description -->
                @if($product->description)
                    <div class="product-card mb-4">
                        <div class="card-body p-4">
                            <h4 class="section-title">Product Description</h4>
                            <div class="text-secondary">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Product Variations -->
                <div class="product-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="section-title mb-0">Product Variations</h4>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill">{{ $product->variations->count() }} variations</span>
                                <a href="{{ route('seller.products.variations', $product) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Manage Variations
                                </a>
                            </div>
                        </div>
                        
                        @forelse($product->variations as $variation)
                            <div class="variation-card p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <div class="h5 fw-bold text-primary mb-0">${{ number_format($variation->price, 2) }}</div>
                                            @if($variation->compare_price)
                                                <small class="text-muted text-decoration-line-through">
                                                    ${{ number_format($variation->compare_price, 2) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="fw-semibold">Stock: {{ $variation->stock_quantity }}</div>
                                            <small class="text-muted">SKU: {{ $variation->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div>
                                            <small class="text-muted d-block mb-1">Attributes:</small>
                                            @if($variation->attributes)
                                                @foreach($variation->attributes as $key => $value)
                                                    <span class="attribute-badge">{{ $key }}: {{ $value }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No attributes</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <span class="badge {{ $variation->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                                {{ $variation->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No variations added</h5>
                                <p class="text-muted">Add variations to make your product available for sale.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Stats and Actions -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="stats-card p-3 text-center">
                            <i class="bi bi-eye text-primary" style="font-size: 1.5rem;"></i>
                            <div class="h5 fw-bold mt-2 mb-0">{{ $product->views ?? 0 }}</div>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-card p-3 text-center">
                            <i class="bi bi-bag text-success" style="font-size: 1.5rem;"></i>
                            <div class="h5 fw-bold mt-2 mb-0">{{ $product->orders_count ?? 0 }}</div>
                            <small class="text-muted">Orders</small>
                        </div>
                    </div>
                </div>
                
                <!-- Product Status -->
                <div class="stats-card p-4 mb-4">
                    <h5 class="fw-bold mb-3">Product Status</h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Approval Status:</span>
                        <span class="status-badge
                            @if($product->status === 'approved') bg-success text-white
                            @elseif($product->status === 'pending') bg-warning text-dark
                            @else bg-danger text-white @endif">
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Visibility:</span>
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Featured:</span>
                        <span class="badge {{ $product->is_featured ? 'bg-primary' : 'bg-secondary' }} rounded-pill">
                            {{ $product->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Stock:</span>
                        <span class="fw-bold">{{ $product->total_stock ?? 0 }}</span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="stats-card p-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-primary btn-sm rounded-pill">
                            <i class="bi bi-pencil me-2"></i>Edit Product
                        </a>
                        <a href="{{ route('seller.products.variations', $product) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="bi bi-gear me-2"></i>Manage Variations
                        </a>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="copyProductLink()">
                            <i class="bi bi-link me-2"></i>Copy Product Link
                        </button>
                        @if($product->status === 'approved')
                            <a href="#" class="btn btn-outline-info btn-sm rounded-pill">
                                <i class="bi bi-eye me-2"></i>View on Store
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="stats-card p-4">
                    <h5 class="fw-bold mb-3">Product Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item d-flex align-items-center mb-3">
                            <div class="timeline-icon bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-plus text-white" style="font-size: 0.75rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Product Created</div>
                                <small class="text-muted">{{ $product->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                        
                        @if($product->updated_at != $product->created_at)
                            <div class="timeline-item d-flex align-items-center mb-3">
                                <div class="timeline-icon bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-pencil text-white" style="font-size: 0.75rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Last Updated</div>
                                    <small class="text-muted">{{ $product->updated_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($product->status === 'approved')
                            <div class="timeline-item d-flex align-items-center">
                                <div class="timeline-icon bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-check text-white" style="font-size: 0.75rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Product Approved</div>
                                    <small class="text-muted">Now visible in store</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(src, thumbnail) {
            document.getElementById('mainImage').src = src;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }

        function copyProductLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                toast.setAttribute('role', 'alert');
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-check-circle me-2"></i>Product link copied to clipboard!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;
                document.body.appendChild(toast);
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        }
    </script>
</x-app-layout>