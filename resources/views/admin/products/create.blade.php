<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900 flex items-center">
                    <i class="bi bi-plus-circle mr-3 text-blue-600"></i>
                    Add New Product
                </h2>
                <p class="text-sm text-gray-600 mt-1">Create a new product with variations and attributes</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                <i class="bi bi-arrow-left mr-2"></i>Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="product-form">
                @csrf
                
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-info-circle mr-2 text-blue-600"></i>
                            Basic Information
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Enter product name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                SKU <span class="text-gray-400">(auto-generated if empty)</span>
                            </label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Product SKU">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Slug <span class="text-gray-400">(auto-generated if empty)</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="product-url-slug">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seller -->
                        <div>
                            <label for="seller_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Seller <span class="text-red-500">*</span>
                            </label>
                            <select name="seller_id" id="seller_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Select Seller</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                        {{ $seller->name }} ({{ $seller->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('seller_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="md:col-span-2">
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Short Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="short_description" id="short_description" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Brief product description (max 500 characters)">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Detailed product description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-image mr-2 text-blue-600"></i>
                            Product Images
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                            <div class="mb-4">
                                <label for="images" class="cursor-pointer">
                                    <span class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 inline-block">
                                        <i class="bi bi-upload mr-2"></i>Choose Images
                                    </span>
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Upload multiple images (JPEG, PNG, JPG, GIF). Max 2MB each.</p>
                        </div>
                        <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Product Variations -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="bi bi-layers mr-2 text-blue-600"></i>
                                Product Variations
                            </h3>
                            <button type="button" onclick="addVariation()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="bi bi-plus mr-2"></i>Add Variation
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Define different variations of this product (size, color, etc.)</p>
                    </div>
                    <div class="p-6">
                        <div id="variations-container">
                            <!-- Variations will be added here -->
                        </div>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-gear mr-2 text-blue-600"></i>
                            Additional Settings
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Weight -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                Weight (kg)
                            </label>
                            <input type="number" name="weight" id="weight" value="{{ old('weight') }}" step="0.01" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="0.00">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dimensions -->
                        <div>
                            <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">
                                Dimensions (LxWxH)
                            </label>
                            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="20x15x10 cm">
                            @error('dimensions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured -->
                        <div class="md:col-span-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700">Featured Product</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Featured products appear in special sections and get more visibility</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        <i class="bi bi-check-circle mr-2"></i>Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let variationCount = 0;

        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });

        // Image preview functionality
        document.getElementById('images').addEventListener('change', function() {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                        <div class="absolute top-2 right-2">
                            <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">${file.name}</span>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });

        // Add variation functionality
        function addVariation() {
            const container = document.getElementById('variations-container');
            const variationHtml = `
                <div class="variation-item border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900">Variation ${variationCount + 1}</h4>
                        <button type="button" onclick="removeVariation(this)" class="text-red-600 hover:text-red-800">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                            <input type="number" name="variations[${variationCount}][price]" step="0.01" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Compare Price</label>
                            <input type="number" name="variations[${variationCount}][compare_price]" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                            <input type="number" name="variations[${variationCount}][stock_quantity]" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Alert</label>
                            <input type="number" name="variations[${variationCount}][low_stock_threshold]" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="5">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Attributes (JSON format)</label>
                        <textarea name="variations[${variationCount}][attributes]" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder='{"size": "Large", "color": "Red"}'></textarea>
                        <p class="text-xs text-gray-500 mt-1">Define attributes in JSON format, e.g., {"size": "Large", "color": "Red"}</p>
                    </div>
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="variations[${variationCount}][is_active]" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', variationHtml);
            variationCount++;
        }

        function removeVariation(button) {
            button.closest('.variation-item').remove();
        }

        // Add at least one variation by default
        document.addEventListener('DOMContentLoaded', function() {
            addVariation();
        });

        // Form validation
        document.getElementById('product-form').addEventListener('submit', function(e) {
            const variations = document.querySelectorAll('.variation-item');
            if (variations.length === 0) {
                e.preventDefault();
                alert('Please add at least one product variation.');
                return;
            }
        });
    </script>
</x-app-layout>