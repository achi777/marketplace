<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold d-flex align-items-center">
                    <i class="bi bi-pencil me-3 text-primary"></i>
                    Edit Attribute: {{ $attribute->name }}
                </h2>
                <p class="text-muted small mb-0">Update attribute settings and options</p>
            </div>
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Back to Attributes
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
            margin-bottom: 2rem;
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
        
        .form-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem;
        }
        
        .form-header h3 {
            margin: 0;
            font-weight: 700;
            color: #343a40;
            display: flex;
            align-items: center;
        }
        
        .form-header .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
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
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            border-radius: 12px;
            color: #6c757d;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }
        
        .option-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .option-item:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid #667eea;
            border-radius: 6px;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        @media (max-width: 768px) {
            .form-card {
                margin-bottom: 1.5rem;
            }
            
            .form-header {
                padding: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <form method="POST" action="{{ route('admin.attributes.update', $attribute) }}" id="attribute-form">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="form-card">
                <div class="form-header">
                    <h3>
                        <div class="section-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        Basic Information
                    </h3>
                    <p class="text-muted small mb-0 ms-5">Define the core properties of your attribute</p>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Attribute Name -->
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold text-muted">
                                <i class="bi bi-tag me-2 text-primary"></i>Attribute Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $attribute->name) }}" required
                                   class="form-control"
                                   placeholder="Enter attribute name (e.g., Size, Color, Material)">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold text-muted">
                                <i class="bi bi-folder me-2 text-primary"></i>Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" required class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $attribute->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Attribute Type -->
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-semibold text-muted">
                                <i class="bi bi-type me-2 text-primary"></i>Attribute Type <span class="text-danger">*</span>
                            </label>
                            <select name="type" id="type" required class="form-select">
                                <option value="">Select Type</option>
                                <option value="text" {{ old('type', $attribute->type) === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="textarea" {{ old('type', $attribute->type) === 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="number" {{ old('type', $attribute->type) === 'number' ? 'selected' : '' }}>Number</option>
                                <option value="select" {{ old('type', $attribute->type) === 'select' ? 'selected' : '' }}>Select (Dropdown)</option>
                                <option value="multiselect" {{ old('type', $attribute->type) === 'multiselect' ? 'selected' : '' }}>Multi-select</option>
                                <option value="radio" {{ old('type', $attribute->type) === 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                <option value="checkbox" {{ old('type', $attribute->type) === 'checkbox' ? 'selected' : '' }}>Checkboxes</option>
                                <option value="boolean" {{ old('type', $attribute->type) === 'boolean' ? 'selected' : '' }}>Boolean (Yes/No)</option>
                                <option value="date" {{ old('type', $attribute->type) === 'date' ? 'selected' : '' }}>Date</option>
                            </select>
                            @error('type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label fw-semibold text-muted">
                                <i class="bi bi-sort-numeric-up me-2 text-primary"></i>Sort Order
                            </label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   value="{{ old('sort_order', $attribute->sort_order) }}" min="0"
                                   class="form-control" placeholder="0">
                            @error('sort_order')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Options (for select, multiselect, radio, checkbox types) -->
            <div id="options-section" class="form-card">
                <div class="form-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>
                            <div class="section-icon">
                                <i class="bi bi-list"></i>
                            </div>
                            Attribute Options
                        </h3>
                        <button type="button" onclick="addOption()" class="btn btn-primary">
                            <i class="bi bi-plus me-2"></i>Add Option
                        </button>
                    </div>
                    <p class="text-muted small mb-0 ms-5">Define the available options for this attribute</p>
                </div>
                <div class="p-4">
                    <div id="options-container">
                        <!-- Existing options will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="form-card">
                <div class="form-header">
                    <h3>
                        <div class="section-icon">
                            <i class="bi bi-gear"></i>
                        </div>
                        Additional Settings
                    </h3>
                    <p class="text-muted small mb-0 ms-5">Configure attribute behavior and availability</p>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <!-- Required -->
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_required" value="1" 
                                       {{ old('is_required', $attribute->is_required) ? 'checked' : '' }}
                                       class="form-check-input" id="is_required">
                                <label class="form-check-label fw-semibold" for="is_required">
                                    <i class="bi bi-asterisk me-2 text-danger"></i>Required Attribute
                                </label>
                            </div>
                            <small class="text-muted">Users must provide a value for this attribute when adding products</small>
                        </div>

                        <!-- Filterable -->
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_filterable" value="1" 
                                       {{ old('is_filterable', $attribute->is_filterable) ? 'checked' : '' }}
                                       class="form-check-input" id="is_filterable">
                                <label class="form-check-label fw-semibold" for="is_filterable">
                                    <i class="bi bi-funnel me-2 text-warning"></i>Use for Filtering
                                </label>
                            </div>
                            <small class="text-muted">Allow customers to filter products based on this attribute</small>
                        </div>

                        <!-- Active -->
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $attribute->is_active) ? 'checked' : '' }}
                                       class="form-check-input" id="is_active">
                                <label class="form-check-label fw-semibold" for="is_active">
                                    <i class="bi bi-check-circle me-2 text-success"></i>Active
                                </label>
                            </div>
                            <small class="text-muted">Only active attributes are available for product creation</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Attribute
                </button>
            </div>
        </form>
    </div>

    <script>
        let optionCount = 0;
        const existingOptions = @json(old('options', $attribute->options ?? []));

        // Show/hide options section based on attribute type
        function toggleOptionsSection() {
            const type = document.getElementById('type').value;
            const optionsSection = document.getElementById('options-section');
            const optionTypes = ['select', 'multiselect', 'radio', 'checkbox'];
            
            if (optionTypes.includes(type)) {
                optionsSection.style.display = 'block';
            } else {
                optionsSection.style.display = 'none';
            }
        }

        document.getElementById('type').addEventListener('change', toggleOptionsSection);

        // Add option functionality
        function addOption(value = '') {
            const container = document.getElementById('options-container');
            const optionHtml = `
                <div class="option-item">
                    <div class="d-flex align-items-center gap-3">
                        <input type="text" name="options[]" value="${value}" required
                               class="form-control flex-fill"
                               placeholder="Option value (e.g., Small, Red, Cotton)">
                        <button type="button" onclick="removeOption(this)" 
                                class="btn btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', optionHtml);
            optionCount++;
        }

        function removeOption(button) {
            button.closest('.option-item').remove();
            optionCount--;
        }

        // Load existing options
        function loadExistingOptions() {
            const container = document.getElementById('options-container');
            container.innerHTML = ''; // Clear existing options
            
            if (existingOptions && existingOptions.length > 0) {
                existingOptions.forEach(option => {
                    addOption(option);
                });
            }
        }

        // Form validation
        document.getElementById('attribute-form').addEventListener('submit', function(e) {
            const type = document.getElementById('type').value;
            const optionTypes = ['select', 'multiselect', 'radio', 'checkbox'];
            
            if (optionTypes.includes(type)) {
                const options = document.querySelectorAll('input[name="options[]"]');
                const filledOptions = Array.from(options).filter(input => input.value.trim() !== '');
                
                if (filledOptions.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one option for this attribute type.');
                    return;
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOptionsSection();
            loadExistingOptions();
        });
    </script>
</x-app-layout>