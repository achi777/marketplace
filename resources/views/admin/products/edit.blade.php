<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900 flex items-center">
                    <i class="bi bi-pencil mr-3 text-blue-600"></i>
                    Edit Product: {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update product information, variations, and settings</p>
            </div>
            <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                <i class="bi bi-arrow-left mr-2"></i>Back to Product
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" id="product-form">
                @csrf
                @method('PUT')
                
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
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
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
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
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
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}"
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $seller->id }}" {{ old('seller_id', $product->seller_id) == $seller->id ? 'selected' : '' }}>
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
                                      placeholder="Brief product description (max 500 characters)">{{ old('short_description', $product->short_description) }}</textarea>
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
                                      placeholder="Detailed product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                @if($product->images && count($product->images) > 0)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="bi bi-images mr-2 text-blue-600"></i>
                                Current Images
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($product->images as $index => $image)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($image) }}" alt="Product Image" 
                                             class="w-full h-32 object-cover rounded-lg">
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-sm text-gray-600 mt-4">
                                <i class="bi bi-info-circle mr-1"></i>
                                Upload new images below to replace current images, or leave empty to keep existing images.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Product Images -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-image mr-2 text-blue-600"></i>
                            Update Product Images
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                            <div class="mb-4">
                                <label for="images" class="cursor-pointer">
                                    <span class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 inline-block">
                                        <i class="bi bi-upload mr-2"></i>Choose New Images
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
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-layers mr-2 text-blue-600"></i>
                            Current Product Variations
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Existing variations - manage these in the variations section</p>
                    </div>
                    <div class="p-6">
                        @if($product->variations->count() > 0)
                            <div class="space-y-4">
                                @foreach($product->variations as $variation)
                                    <div class="border border-gray-200 rounded-lg p-4 {{ $variation->is_active ? '' : 'bg-gray-50' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-4 mb-2">
                                                    <h4 class="font-medium text-gray-900">SKU: {{ $variation->sku }}</h4>
                                                    @if(!$variation->is_active)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Inactive
                                                        </span>
                                                    @endif
                                                    @if($variation->is_low_stock)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="bi bi-exclamation-triangle mr-1"></i>Low Stock
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Price:</span>
                                                        <div class="font-medium">${{ number_format($variation->price, 2) }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Stock:</span>
                                                        <div class="font-medium">{{ $variation->stock_quantity }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Low Stock Alert:</span>
                                                        <div class="font-medium">{{ $variation->low_stock_threshold ?? 'Not set' }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Status:</span>
                                                        <div class="font-medium">{{ $variation->is_active ? 'Active' : 'Inactive' }}</div>
                                                    </div>
                                                </div>
                                                
                                                @if($variation->attributes && count($variation->attributes) > 0)
                                                    <div class="mt-3">
                                                        <span class="text-gray-500 text-sm">Attributes:</span>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($variation->attributes as $key => $value)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                                                    {{ ucfirst($key) }}: {{ $value }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if($variation->image)
                                                <div class="ml-4">
                                                    <img src="{{ Storage::url($variation->image) }}" alt="Variation" 
                                                         class="w-16 h-16 object-cover rounded-lg">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    To modify product variations, please use the dedicated variation management interface after saving these changes.
                                </p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="bi bi-layers text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500">No variations found for this product.</p>
                            </div>
                        @endif
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
                            <input type="number" name="weight" id="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0"
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
                            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $product->dimensions) }}"
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
                                <option value="pending" {{ old('status', $product->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="approved" {{ old('status', $product->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $product->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rejection Reason (if rejected) -->
                        <div id="rejection-reason-field" class="md:col-span-3" style="{{ old('status', $product->status) === 'rejected' ? '' : 'display: none;' }}">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason
                            </label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Please provide a reason for rejection...">{{ old('rejection_reason', $product->rejection_reason) }}</textarea>
                            @error('rejection_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured -->
                        <div class="md:col-span-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700">Featured Product</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Featured products appear in special sections and get more visibility</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.products.show', $product) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                        <i class="bi bi-check-circle mr-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        // Show/hide rejection reason field
        document.getElementById('status').addEventListener('change', function() {
            const rejectionField = document.getElementById('rejection-reason-field');
            if (this.value === 'rejected') {
                rejectionField.style.display = 'block';
                document.getElementById('rejection_reason').required = true;
            } else {
                rejectionField.style.display = 'none';
                document.getElementById('rejection_reason').required = false;
            }
        });
    </script>
</x-app-layout>