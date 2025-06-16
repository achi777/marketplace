<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Edit Product</h2>
                <p class="text-muted small mb-0">Update your product information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('seller.products.show', $product) }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-eye me-2"></i>View Product
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
        
        .form-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .form-section {
            border-bottom: 1px solid #f1f3f4;
            padding: 2rem;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            color: #1a1a1a;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .section-subtitle {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8f9fa;
            cursor: pointer;
        }
        
        .upload-area:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .image-preview {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
        }
        
        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        
        .image-preview .remove-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            border: none;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .variation-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-card mb-4">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h3 class="section-title">Basic Information</h3>
                            <p class="section-subtitle">Update the basic details about your product</p>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required
                                               placeholder="Enter a descriptive product name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">Select category...</option>
                                            @foreach($categories ?? [] as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Short Description <span class="text-danger">*</span></label>
                                <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}" required
                                       placeholder="Brief description for product listings (max 160 characters)" maxlength="160">
                                <div class="form-text">This will be shown in product listings and search results.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Detailed Description</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Detailed product description, features, specifications...">{{ old('description', $product->description) }}</textarea>
                                <div class="form-text">Provide comprehensive details about your product.</div>
                            </div>
                        </div>

                        <!-- Product Images -->
                        <div class="form-section">
                            <h3 class="section-title">Product Images</h3>
                            <p class="section-subtitle">Update product images</p>
                            
                            <!-- Current Images -->
                            @if($product->images && count($product->images) > 0)
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3">Current Images</h6>
                                    <div id="currentImages">
                                        @foreach($product->images as $index => $image)
                                            <div class="image-preview" data-image="{{ $image }}">
                                                <img src="{{ Storage::url($image) }}" alt="Product Image">
                                                <button type="button" class="remove-btn" onclick="removeCurrentImage(this, '{{ $image }}')">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="existing_images" id="existingImages" value="{{ json_encode($product->images) }}">
                                </div>
                            @endif
                            
                            <!-- Upload New Images -->
                            <div class="upload-area" id="uploadArea">
                                <i class="bi bi-cloud-upload text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-2">Add New Images</h5>
                                <p class="text-muted mb-3">Drag & drop images here or click to browse</p>
                                <input type="file" name="new_images[]" multiple accept="image/*" id="imageInput" style="display: none;">
                                <button type="button" class="btn btn-outline-primary rounded-pill" onclick="document.getElementById('imageInput').click()">
                                    <i class="bi bi-image me-2"></i>Choose Images
                                </button>
                            </div>
                            
                            <div id="newImagePreview" class="mt-3"></div>
                        </div>

                        <!-- Product Specifications -->
                        <div class="form-section">
                            <h3 class="section-title">Product Specifications</h3>
                            <p class="section-subtitle">Update physical and shipping details</p>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Weight (kg)</label>
                                        <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}"
                                               placeholder="0.5">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Dimensions</label>
                                        <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions', $product->dimensions) }}"
                                               placeholder="L x W x H (e.g., 20 x 15 x 5 cm)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Settings -->
                        <div class="form-section">
                            <h3 class="section-title">Product Settings</h3>
                            <p class="section-subtitle">Configure product availability and visibility</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" 
                                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="isActive">
                                            Active Product
                                        </label>
                                        <div class="form-text">Enable to make product visible in store</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" 
                                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="isFeatured">
                                            Featured Product
                                        </label>
                                        <div class="form-text">Feature in homepage and special sections</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-section">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    <small><i class="bi bi-info-circle me-1"></i>Changes will need admin approval if product status changes significantly</small>
                                </div>
                                
                                <div class="d-flex gap-3">
                                    <a href="{{ route('seller.products.show', $product) }}" class="btn btn-outline-secondary rounded-pill px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-check-circle me-2"></i>Update Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Product Status -->
                    <div class="form-card mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Current Status</h5>
                            
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
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Featured:</span>
                                <span class="badge {{ $product->is_featured ? 'bg-primary' : 'bg-secondary' }} rounded-pill">
                                    {{ $product->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Variations -->
                    <div class="form-card mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Variations</h5>
                                <span class="badge bg-primary rounded-pill">{{ $product->variations->count() }}</span>
                            </div>
                            
                            @forelse($product->variations->take(3) as $variation)
                                <div class="variation-card mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">${{ number_format($variation->price, 2) }}</div>
                                            <small class="text-muted">Stock: {{ $variation->stock_quantity }}</small>
                                        </div>
                                        <span class="badge {{ $variation->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                            {{ $variation->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <i class="bi bi-box text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0 mt-2">No variations</p>
                                </div>
                            @endforelse
                            
                            @if($product->variations->count() > 3)
                                <div class="text-center mt-3">
                                    <small class="text-muted">{{ $product->variations->count() - 3 }} more variations...</small>
                                </div>
                            @endif
                            
                            <div class="d-grid mt-3">
                                <a href="{{ route('seller.products.variations', $product) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-gear me-2"></i>Manage Variations
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="form-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Quick Actions</h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('seller.products.show', $product) }}" class="btn btn-outline-info btn-sm rounded-pill">
                                    <i class="bi bi-eye me-2"></i>View Product
                                </a>
                                <a href="{{ route('seller.products.variations', $product) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                                    <i class="bi bi-gear me-2"></i>Manage Variations
                                </a>
                                @if($product->status === 'approved')
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill">
                                        <i class="bi bi-globe me-2"></i>View in Store
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let existingImages = @json($product->images ?? []);
        let newFiles = [];

        // Image upload functionality
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const newImagePreview = document.getElementById('newImagePreview');

        uploadArea.addEventListener('click', () => imageInput.click());
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        imageInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            for (let file of files) {
                if (file.type.startsWith('image/') && (existingImages.length + newFiles.length) < 10) {
                    newFiles.push(file);
                    displayNewImage(file);
                }
            }
            updateFileInput();
        }

        function displayNewImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="New Image">
                    <button type="button" class="remove-btn" onclick="removeNewImage(this, '${file.name}')">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                newImagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }

        function removeCurrentImage(btn, imagePath) {
            existingImages = existingImages.filter(img => img !== imagePath);
            btn.parentElement.remove();
            document.getElementById('existingImages').value = JSON.stringify(existingImages);
        }

        function removeNewImage(btn, fileName) {
            newFiles = newFiles.filter(file => file.name !== fileName);
            btn.parentElement.remove();
            updateFileInput();
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            newFiles.forEach(file => dt.items.add(file));
            imageInput.files = dt.files;
        }
    </script>
</x-app-layout>