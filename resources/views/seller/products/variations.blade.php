<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Product Variations</h2>
                <p class="text-muted small mb-0">Manage variations for: {{ $product->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('seller.products.show', $product) }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Product
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .variation-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            margin-bottom: 2rem;
        }
        
        .variation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            background: white;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .attribute-group {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
        }
        
        .attribute-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="container py-4">
        <!-- Existing Variations -->
        <div class="variation-card">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Current Variations ({{ $product->variations->count() }})
                </h5>
            </div>
            <div class="card-body p-4">
                @forelse($product->variations as $variation)
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <strong>${{ number_format($variation->price, 2) }}</strong>
                                @if($variation->compare_price)
                                    <small class="text-muted text-decoration-line-through ms-2">
                                        ${{ number_format($variation->compare_price, 2) }}
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <span class="badge {{ $variation->is_in_stock ? 'bg-success' : 'bg-danger' }}">
                                    Stock: {{ $variation->stock_quantity }}
                                </span>
                            </div>
                            <div class="col-md-4">
                                @if($variation->attributes)
                                    <small class="text-muted">{{ $variation->attribute_display }}</small>
                                @else
                                    <small class="text-muted">No attributes set</small>
                                @endif
                            </div>
                            <div class="col-md-2 text-end">
                                <span class="badge {{ $variation->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $variation->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-box display-1 mb-3"></i>
                            <h5>No variations created yet</h5>
                            <p>Add your first product variation below</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Add New Variation -->
        <div class="variation-card">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>
                    Add New Variation
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('seller.products.variations.store', $product) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Pricing -->
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-semibold text-muted">
                                        <i class="bi bi-currency-dollar me-2 text-primary"></i>Price <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="price" id="price" step="0.01" min="0" required
                                           class="form-control" placeholder="0.00">
                                </div>
                                <div class="col-md-6">
                                    <label for="compare_price" class="form-label fw-semibold text-muted">
                                        <i class="bi bi-tag me-2 text-primary"></i>Compare Price
                                    </label>
                                    <input type="number" name="compare_price" id="compare_price" step="0.01" min="0"
                                           class="form-control" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="stock_quantity" class="form-label fw-semibold text-muted">
                                        <i class="bi bi-box me-2 text-primary"></i>Stock Quantity <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" min="0" required
                                           class="form-control" placeholder="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="low_stock_threshold" class="form-label fw-semibold text-muted">
                                        <i class="bi bi-exclamation-triangle me-2 text-primary"></i>Low Stock Alert
                                    </label>
                                    <input type="number" name="low_stock_threshold" id="low_stock_threshold" min="0"
                                           class="form-control" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <!-- Category Attributes -->
                        @if($product->category->attributes->count() > 0)
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-tags me-2"></i>Product Attributes
                                </h6>
                                <div class="row g-3">
                                    @foreach($product->category->attributes as $attribute)
                                        @if($attribute->is_active)
                                            <div class="col-md-6">
                                                <div class="attribute-group">
                                                    <label class="attribute-label">
                                                        {{ $attribute->name }}
                                                        @if($attribute->is_required) <span class="text-danger">*</span> @endif
                                                    </label>
                                                    
                                                    @if($attribute->type === 'text')
                                                        <input type="text" name="attributes[{{ $attribute->name }}]" 
                                                               class="form-control" 
                                                               placeholder="Enter {{ strtolower($attribute->name) }}"
                                                               {{ $attribute->is_required ? 'required' : '' }}>
                                                    
                                                    @elseif($attribute->type === 'textarea')
                                                        <textarea name="attributes[{{ $attribute->name }}]" 
                                                                  class="form-control" rows="3"
                                                                  placeholder="Enter {{ strtolower($attribute->name) }}"
                                                                  {{ $attribute->is_required ? 'required' : '' }}></textarea>
                                                    
                                                    @elseif($attribute->type === 'number')
                                                        <input type="number" name="attributes[{{ $attribute->name }}]" 
                                                               class="form-control" step="0.01"
                                                               placeholder="Enter {{ strtolower($attribute->name) }}"
                                                               {{ $attribute->is_required ? 'required' : '' }}>
                                                    
                                                    @elseif($attribute->type === 'select')
                                                        <select name="attributes[{{ $attribute->name }}]" 
                                                                class="form-select"
                                                                {{ $attribute->is_required ? 'required' : '' }}>
                                                            <option value="">Select {{ $attribute->name }}</option>
                                                            @if($attribute->options)
                                                                @foreach($attribute->options as $option)
                                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    
                                                    @elseif($attribute->type === 'radio')
                                                        @if($attribute->options)
                                                            @foreach($attribute->options as $option)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" 
                                                                           name="attributes[{{ $attribute->name }}]" 
                                                                           value="{{ $option }}"
                                                                           id="{{ $attribute->name }}_{{ $loop->index }}"
                                                                           {{ $attribute->is_required && $loop->first ? 'required' : '' }}>
                                                                    <label class="form-check-label" for="{{ $attribute->name }}_{{ $loop->index }}">
                                                                        {{ $option }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    
                                                    @elseif($attribute->type === 'checkbox')
                                                        @if($attribute->options)
                                                            @foreach($attribute->options as $option)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" 
                                                                           name="attributes[{{ $attribute->name }}][]" 
                                                                           value="{{ $option }}"
                                                                           id="{{ $attribute->name }}_{{ $loop->index }}">
                                                                    <label class="form-check-label" for="{{ $attribute->name }}_{{ $loop->index }}">
                                                                        {{ $option }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    
                                                    @elseif($attribute->type === 'boolean')
                                                        <select name="attributes[{{ $attribute->name }}]" 
                                                                class="form-select"
                                                                {{ $attribute->is_required ? 'required' : '' }}>
                                                            <option value="">Select</option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    
                                                    @elseif($attribute->type === 'date')
                                                        <input type="date" name="attributes[{{ $attribute->name }}]" 
                                                               class="form-control"
                                                               {{ $attribute->is_required ? 'required' : '' }}>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Variation Image -->
                        <div class="col-md-6">
                            <label for="image" class="form-label fw-semibold text-muted">
                                <i class="bi bi-image me-2 text-primary"></i>Variation Image
                            </label>
                            <input type="file" name="image" id="image" accept="image/*" class="form-control">
                            <small class="text-muted">Optional: Upload an image specific to this variation</small>
                        </div>

                        <!-- Active Status -->
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                                <label class="form-check-label fw-semibold" for="is_active">
                                    <i class="bi bi-check-circle me-2 text-success"></i>Active Variation
                                </label>
                            </div>
                            <small class="text-muted">Only active variations are visible to customers</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Variation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>