<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Add New Product</h2>
                <p class="text-muted small mb-0">Create a new product listing</p>
            </div>
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
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
        
        .upload-area.dragover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
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
        
        .variation-row {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
        }
        
        .attribute-input {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
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

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            
            <div class="form-card mb-4">
                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    <p class="section-subtitle">Enter the basic details about your product</p>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                                       placeholder="Enter a descriptive product name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required id="category_id">
                                    <option value="">Select category...</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Description <span class="text-danger">*</span></label>
                        <input type="text" name="short_description" class="form-control" value="{{ old('short_description') }}" required
                               placeholder="Brief description for product listings (max 160 characters)" maxlength="160">
                        <div class="form-text">This will be shown in product listings and search results.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Detailed Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Detailed product description, features, specifications...">{{ old('description') }}</textarea>
                        <div class="form-text">Provide comprehensive details about your product.</div>
                    </div>
                </div>

                <!-- Category Attributes -->
                <div class="form-section" id="attributesSection" style="display: none;">
                    <h3 class="section-title">Product Attributes</h3>
                    <p class="section-subtitle">Fill in the category-specific attributes for your product</p>
                    
                    <div id="attributeFields">
                        <!-- Dynamic attribute fields will be loaded here -->
                    </div>
                </div>

                <!-- Product Images -->
                <div class="form-section">
                    <h3 class="section-title">Product Images</h3>
                    <p class="section-subtitle">Upload high-quality images of your product</p>
                    
                    <div class="upload-area" id="uploadArea">
                        <i class="bi bi-cloud-upload text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">Drag & drop images here</h5>
                        <p class="text-muted mb-3">or click to browse files</p>
                        <input type="file" name="images[]" multiple accept="image/*" id="imageInput" style="display: none;">
                        <button type="button" class="btn btn-outline-primary rounded-pill" onclick="document.getElementById('imageInput').click()">
                            <i class="bi bi-image me-2"></i>Choose Images
                        </button>
                    </div>
                    
                    <div id="imagePreview" class="mt-3"></div>
                    <div class="form-text mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Upload up to 10 images. First image will be used as the main product image. Supported formats: JPG, PNG, WebP. Max size: 5MB per image.
                    </div>
                </div>

                <!-- Product Specifications -->
                <div class="form-section">
                    <h3 class="section-title">Product Specifications</h3>
                    <p class="section-subtitle">Add physical and shipping details</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Weight (kg)</label>
                                <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight') }}"
                                       placeholder="0.5">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dimensions</label>
                                <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions') }}"
                                       placeholder="L x W x H (e.g., 20 x 15 x 5 cm)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variations -->
                <div class="form-section">
                    <h3 class="section-title">Product Variations</h3>
                    <p class="section-subtitle">Create different versions of your product (colors, sizes, etc.)</p>
                    
                    <div id="variationsContainer">
                        <div class="variation-row" data-variation="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-semibold">Variation #1</h6>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill remove-variation" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">SKU</label>
                                    <input type="text" name="variations[0][sku]" class="form-control" 
                                           placeholder="Unique identifier">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="variations[0][price]" class="form-control" required
                                               placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Compare Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="variations[0][compare_price]" class="form-control"
                                               placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="variations[0][stock_quantity]" class="form-control" required
                                           placeholder="10" min="0">
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <label class="form-label fw-semibold">Attributes</label>
                                <div id="attributesContainer-0" class="category-attributes-container">
                                    <p class="text-muted small mb-2">Select a category to load available attributes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary rounded-pill" id="addVariation">
                        <i class="bi bi-plus-circle me-2"></i>Add Another Variation
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="form-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                            <label class="form-check-label fw-semibold" for="isActive">
                                Publish product immediately
                            </label>
                            <div class="form-text">Uncheck to save as draft</div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-check-circle me-2"></i>Create Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let variationCount = 1;
        let attributeCount = {0: 1};

        // Image upload functionality
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        let selectedFiles = [];

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
                if (file.type.startsWith('image/') && selectedFiles.length < 10) {
                    selectedFiles.push(file);
                    displayImage(file);
                }
            }
            updateFileInput();
        }

        function displayImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-btn" onclick="removeImage(this, '${file.name}')">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }

        function removeImage(btn, fileName) {
            selectedFiles = selectedFiles.filter(file => file.name !== fileName);
            btn.parentElement.remove();
            updateFileInput();
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            imageInput.files = dt.files;
        }

        // Category change event to load attributes
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            console.log('Category changed to:', categoryId);
            loadCategoryAttributes(categoryId);
        });

        // Function to load category attributes
        function loadCategoryAttributes(categoryId) {
            console.log('loadCategoryAttributes called with:', categoryId);
            
            if (!categoryId) {
                console.log('No category ID, clearing containers');
                // Clear all attribute containers
                document.querySelectorAll('.category-attributes-container').forEach(container => {
                    container.innerHTML = '<p class="text-muted small mb-2">Select a category to load available attributes</p>';
                });
                return;
            }

            console.log('Fetching attributes for category:', categoryId);
            // Fetch category attributes
            fetch(`/api/categories/${categoryId}/attributes`)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(attributes => {
                    console.log('Attributes received:', attributes);
                    // Update all variation containers
                    document.querySelectorAll('.category-attributes-container').forEach(container => {
                        updateAttributesContainer(container, attributes);
                    });
                })
                .catch(error => {
                    console.error('Error loading attributes:', error);
                    document.querySelectorAll('.category-attributes-container').forEach(container => {
                        container.innerHTML = '<p class="text-danger small mb-2">Error loading attributes: ' + error.message + '</p>';
                    });
                });
        }

        // Function to update attributes container
        function updateAttributesContainer(container, attributes) {
            const variationIndex = container.id.split('-')[1];
            console.log('updateAttributesContainer called for variation:', variationIndex, 'with attributes:', attributes);
            
            if (!attributes || attributes.length === 0) {
                console.log('No attributes found, showing empty message');
                container.innerHTML = '<p class="text-muted small mb-2">No attributes available for this category</p>';
                return;
            }

            let html = '';
            attributes.forEach(attribute => {
                
                html += `<div class="mb-3">
                    <label class="form-label fw-semibold">
                        ${attribute.name}
                        ${attribute.is_required ? '<span class="text-danger">*</span>' : ''}
                    </label>`;

                if (attribute.type === 'text') {
                    html += `<input type="text" name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-control" placeholder="Enter ${attribute.name.toLowerCase()}"
                             ${attribute.is_required ? 'required' : ''}>`;
                
                } else if (attribute.type === 'textarea') {
                    html += `<textarea name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-control" rows="3" placeholder="Enter ${attribute.name.toLowerCase()}"
                             ${attribute.is_required ? 'required' : ''}></textarea>`;
                
                } else if (attribute.type === 'number') {
                    html += `<input type="number" name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-control" step="0.01" placeholder="Enter ${attribute.name.toLowerCase()}"
                             ${attribute.is_required ? 'required' : ''}>`;
                
                } else if (attribute.type === 'select') {
                    html += `<select name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-select" ${attribute.is_required ? 'required' : ''}>
                        <option value="">Select ${attribute.name}</option>`;
                    if (attribute.options) {
                        attribute.options.forEach(option => {
                            html += `<option value="${option}">${option}</option>`;
                        });
                    }
                    html += `</select>`;
                
                } else if (attribute.type === 'radio') {
                    if (attribute.options) {
                        attribute.options.forEach((option, index) => {
                            html += `<div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="variations[${variationIndex}][attributes][${attribute.name}]" 
                                       value="${option}" id="${attribute.name}_${variationIndex}_${index}"
                                       ${attribute.is_required && index === 0 ? 'required' : ''}>
                                <label class="form-check-label" for="${attribute.name}_${variationIndex}_${index}">
                                    ${option}
                                </label>
                            </div>`;
                        });
                    }
                
                } else if (attribute.type === 'checkbox') {
                    if (attribute.options) {
                        attribute.options.forEach((option, index) => {
                            html += `<div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="variations[${variationIndex}][attributes][${attribute.name}][]" 
                                       value="${option}" id="${attribute.name}_${variationIndex}_${index}">
                                <label class="form-check-label" for="${attribute.name}_${variationIndex}_${index}">
                                    ${option}
                                </label>
                            </div>`;
                        });
                    }
                
                } else if (attribute.type === 'color') {
                    html += `<select name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-select" ${attribute.is_required ? 'required' : ''}>
                        <option value="">Select ${attribute.name}</option>`;
                    if (attribute.options) {
                        attribute.options.forEach(option => {
                            html += `<option value="${option}">${option}</option>`;
                        });
                    }
                    html += `</select>`;
                
                } else if (attribute.type === 'boolean') {
                    html += `<select name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-select" ${attribute.is_required ? 'required' : ''}>
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>`;
                
                } else if (attribute.type === 'date') {
                    html += `<input type="date" name="variations[${variationIndex}][attributes][${attribute.name}]" 
                             class="form-control" ${attribute.is_required ? 'required' : ''}>`;
                }

                html += `</div>`;
            });

            container.innerHTML = html;
        }

        // Add variation functionality
        document.getElementById('addVariation').addEventListener('click', function() {
            const container = document.getElementById('variationsContainer');
            const newVariation = document.createElement('div');
            newVariation.className = 'variation-row';
            newVariation.dataset.variation = variationCount;
            attributeCount[variationCount] = 1;
            
            newVariation.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">Variation #${variationCount + 1}</h6>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill remove-variation">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">SKU</label>
                        <input type="text" name="variations[${variationCount}][sku]" class="form-control" 
                               placeholder="Unique identifier">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="variations[${variationCount}][price]" class="form-control" required
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Compare Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="variations[${variationCount}][compare_price]" class="form-control"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="variations[${variationCount}][stock_quantity]" class="form-control" required
                               placeholder="10" min="0">
                    </div>
                </div>
                
                <div class="mt-3">
                    <label class="form-label fw-semibold">Attributes</label>
                    <div id="attributesContainer-${variationCount}" class="category-attributes-container">
                        <p class="text-muted small mb-2">Category attributes will appear here</p>
                    </div>
                </div>
            `;
            
            container.appendChild(newVariation);
            
            // Load attributes for new variation if category is selected
            const categoryId = document.getElementById('category_id').value;
            if (categoryId) {
                const newContainer = newVariation.querySelector('.category-attributes-container');
                fetch(`/api/categories/${categoryId}/attributes`)
                    .then(response => response.json())
                    .then(attributes => {
                        updateAttributesContainer(newContainer, attributes);
                    })
                    .catch(error => {
                        console.error('Error loading attributes for new variation:', error);
                    });
            }
            
            variationCount++;
            
            // Show remove buttons if there's more than one variation
            updateRemoveButtons();
        });

        // Remove variation functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-variation')) {
                e.target.closest('.variation-row').remove();
                updateRemoveButtons();
            }
        });

        // Add attribute functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-attribute')) {
                const btn = e.target.closest('.add-attribute');
                const variationIndex = btn.dataset.variation;
                const container = document.getElementById(`attributesContainer-${variationIndex}`);
                const attributeIndex = attributeCount[variationIndex];
                
                const newAttributeRow = document.createElement('div');
                newAttributeRow.className = 'row g-2 mb-2';
                newAttributeRow.innerHTML = `
                    <div class="col-md-4">
                        <input type="text" name="variations[${variationIndex}][attributes][${attributeIndex}][name]" class="form-control attribute-input"
                               placeholder="Attribute name">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="variations[${variationIndex}][attributes][${attributeIndex}][value]" class="form-control attribute-input"
                               placeholder="Attribute value">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-attribute">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                
                container.appendChild(newAttributeRow);
                attributeCount[variationIndex]++;
            }
        });

        // Remove attribute functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-attribute')) {
                e.target.closest('.row').remove();
            }
        });

        function updateRemoveButtons() {
            const variations = document.querySelectorAll('.variation-row');
            const removeButtons = document.querySelectorAll('.remove-variation');
            
            removeButtons.forEach(btn => {
                btn.style.display = variations.length > 1 ? 'block' : 'none';
            });
        }

        // Load category attributes when the page loads if a category is selected
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            if (categorySelect.value) {
                loadCategoryAttributes(categorySelect.value);
            }
        });

        function generateAttributeField(attribute) {
            const div = document.createElement('div');
            div.className = 'mb-3';
            
            const label = `<label class="form-label fw-semibold">${attribute.name} ${attribute.is_required ? '<span class="text-danger">*</span>' : ''}</label>`;
            const fieldName = `attribute_${attribute.id}`;
            let input = '';
            
            switch (attribute.type) {
                case 'text':
                    input = `<input type="text" name="${fieldName}" class="form-control" ${attribute.is_required ? 'required' : ''}>`;
                    break;
                case 'textarea':
                    input = `<textarea name="${fieldName}" class="form-control" rows="3" ${attribute.is_required ? 'required' : ''}></textarea>`;
                    break;
                case 'number':
                    input = `<input type="number" name="${fieldName}" class="form-control" step="0.01" ${attribute.is_required ? 'required' : ''}>`;
                    break;
                case 'select':
                    input = `<select name="${fieldName}" class="form-select" ${attribute.is_required ? 'required' : ''}>
                        <option value="">Select...</option>
                        ${attribute.options.map(option => `<option value="${option}">${option}</option>`).join('')}
                    </select>`;
                    break;
                case 'multiselect':
                    input = `<select name="${fieldName}[]" class="form-select" multiple ${attribute.is_required ? 'required' : ''}>
                        ${attribute.options.map(option => `<option value="${option}">${option}</option>`).join('')}
                    </select>
                    <div class="form-text">Hold Ctrl/Cmd to select multiple options</div>`;
                    break;
                case 'radio':
                    input = attribute.options.map((option, index) => 
                        `<div class="form-check">
                            <input class="form-check-input" type="radio" name="${fieldName}" value="${option}" id="${fieldName}_${index}" ${attribute.is_required ? 'required' : ''}>
                            <label class="form-check-label" for="${fieldName}_${index}">${option}</label>
                        </div>`
                    ).join('');
                    break;
                case 'checkbox':
                    input = attribute.options.map((option, index) => 
                        `<div class="form-check">
                            <input class="form-check-input" type="checkbox" name="${fieldName}[]" value="${option}" id="${fieldName}_${index}">
                            <label class="form-check-label" for="${fieldName}_${index}">${option}</label>
                        </div>`
                    ).join('');
                    break;
                case 'boolean':
                    input = `<div class="form-check">
                        <input class="form-check-input" type="checkbox" name="${fieldName}" value="1" id="${fieldName}">
                        <label class="form-check-label" for="${fieldName}">Yes</label>
                    </div>`;
                    break;
                case 'date':
                    input = `<input type="date" name="${fieldName}" class="form-control" ${attribute.is_required ? 'required' : ''}>`;
                    break;
            }
            
            div.innerHTML = label + input;
            return div;
        }

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const variations = document.querySelectorAll('.variation-row');
            let hasValidVariation = false;
            
            variations.forEach(variation => {
                const price = variation.querySelector('input[name*="[price]"]').value;
                const stock = variation.querySelector('input[name*="[stock_quantity]"]').value;
                
                if (price && stock) {
                    hasValidVariation = true;
                }
            });
            
            if (!hasValidVariation) {
                e.preventDefault();
                alert('Please add at least one variation with price and stock quantity.');
            }
        });
    </script>
</x-app-layout>