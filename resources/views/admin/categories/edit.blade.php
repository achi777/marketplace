<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Edit Category</h2>
                <p class="text-muted small mb-0">Update category information and settings</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 0.9rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .form-control::placeholder {
            color: #a0a6b1;
            font-weight: 400;
        }
        
        .form-text {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
            border: 2px solid #e9ecef;
            border-radius: 6px;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            font-weight: 500;
            color: #2d3436;
        }
        
        .current-image-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            margin-bottom: 1.5rem;
        }
        
        .current-image {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .file-upload-area {
            border: 2px dashed #e9ecef;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .file-upload-area:hover {
            border-color: #667eea;
            background: #f0f2ff;
        }
        
        .file-upload-area.dragover {
            border-color: #667eea;
            background: #f0f2ff;
        }
        
        .file-upload-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .slug-preview {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        
        .required-asterisk {
            color: #dc3545;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
            }
            
            .container {
                padding: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card p-4">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Basic Information
                            </h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        Category Name <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Enter category name">
                                    <div id="slug-preview" class="slug-preview" style="display: none;">
                                        URL: <span id="slug-text"></span>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="slug" class="form-label">
                                        URL Slug <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required
                                           class="form-control @error('slug') is-invalid @enderror"
                                           placeholder="category-url-slug">
                                    <div class="form-text">This will be used in the URL. Auto-generated from name.</div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Enter a detailed description of the category">{{ old('description', $category->description) }}</textarea>
                                <div class="form-text">Provide a clear description to help customers understand this category.</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Organization Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-diagram-3"></i>
                                Organization
                            </h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select name="parent_id" id="parent_id"
                                            class="form-select @error('parent_id') is-invalid @enderror">
                                        <option value="">Root Category (No Parent)</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Select a parent category to create a subcategory.</div>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                                           class="form-control @error('sort_order') is-invalid @enderror"
                                           placeholder="0">
                                    <div class="form-text">Lower numbers appear first in listings.</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-image"></i>
                                Category Image
                            </h5>
                            
                            @if($category->image)
                                <div class="current-image-section">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="bi bi-image-fill me-2 text-primary"></i>Current Image
                                    </h6>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ Storage::url($category->image) }}" 
                                             alt="{{ $category->name }}" 
                                             class="current-image"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <p class="mb-1 fw-semibold">{{ $category->name }}.jpg</p>
                                            <p class="mb-0 text-muted small">Upload a new image to replace this one</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="file-upload-area" id="fileUploadArea">
                                <div class="file-upload-icon">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <h6 class="mb-2">{{ $category->image ? 'Upload new image to replace' : 'Drag & drop your image here' }}</h6>
                                <p class="text-muted mb-3">or click to browse files</p>
                                <input type="file" name="image" id="image" accept="image/*" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       style="display: none;">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image').click()">
                                    Choose File
                                </button>
                                <div class="form-text mt-3">Supported formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB</div>
                            </div>
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Settings Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-gear"></i>
                                Settings
                            </h5>
                            
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                       class="form-check-input">
                                <label for="is_active" class="form-check-label">
                                    <strong>Active Category</strong>
                                    <br><small class="text-muted">Make this category visible to customers</small>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            
            document.getElementById('slug').value = slug;
            
            // Show/hide slug preview
            const slugPreview = document.getElementById('slug-preview');
            const slugText = document.getElementById('slug-text');
            if (slug) {
                slugText.textContent = '{{ url("/category") }}/' + slug;
                slugPreview.style.display = 'block';
            } else {
                slugPreview.style.display = 'none';
            }
        });

        // File upload handling
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        // Drag and drop functionality
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        // Click to upload
        fileUploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelect(this.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Form validation enhancement
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            
            if (!nameInput.value.trim()) {
                e.preventDefault();
                nameInput.focus();
                return false;
            }
            
            if (!slugInput.value.trim()) {
                e.preventDefault();
                slugInput.focus();
                return false;
            }
        });
    </script>
</x-app-layout>