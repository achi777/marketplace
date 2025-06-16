<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900 flex items-center">
                    <i class="bi bi-box-seam mr-3 text-blue-600"></i>
                    Product Management
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage marketplace products, variations, and inventory</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-plus-circle mr-2"></i>Add Product
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-xs font-medium">Total Products</p>
                            <p class="text-2xl font-bold">{{ $products->total() }}</p>
                        </div>
                        <i class="bi bi-box-seam text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-xs font-medium">Approved</p>
                            <p class="text-2xl font-bold">{{ $products->where('status', 'approved')->count() }}</p>
                        </div>
                        <i class="bi bi-check-circle text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-xs font-medium">Pending</p>
                            <p class="text-2xl font-bold">{{ $products->where('status', 'pending')->count() }}</p>
                        </div>
                        <i class="bi bi-clock text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-xs font-medium">Rejected</p>
                            <p class="text-2xl font-bold">{{ $products->where('status', 'rejected')->count() }}</p>
                        </div>
                        <i class="bi bi-x-circle text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-xs font-medium">Featured</p>
                            <p class="text-2xl font-bold">{{ $products->where('is_featured', true)->count() }}</p>
                        </div>
                        <i class="bi bi-star text-xl opacity-80"></i>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-funnel mr-2 text-blue-600"></i>
                        Filter Products
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-search mr-1"></i>Search
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Name, SKU, description..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-shield-check mr-1"></i>Status
                            </label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-folder mr-1"></i>Category
                            </label>
                            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-person mr-1"></i>Seller
                            </label>
                            <select name="seller" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Sellers</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ request('seller') == $seller->id ? 'selected' : '' }}>
                                        {{ $seller->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-star mr-1"></i>Featured
                            </label>
                            <select name="featured" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Products</option>
                                <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>Featured Only</option>
                                <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <i class="bi bi-search mr-2"></i>Apply
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4">
                    <form id="bulk-form" method="POST" action="{{ route('admin.products.bulk-action') }}">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700">Bulk Actions:</label>
                            <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Action</option>
                                <option value="approve">Approve Selected</option>
                                <option value="reject">Reject Selected</option>
                                <option value="feature">Feature Selected</option>
                                <option value="unfeature">Unfeature Selected</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                            <input type="text" name="rejection_reason" placeholder="Rejection reason (if rejecting)" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="display: none;">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products List -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-box-seam mr-2 text-blue-600"></i>
                        Products List
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($products as $product)
                        <div class="mb-6 last:mb-0 border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start space-x-4">
                                <!-- Product Selection -->
                                <div class="flex-shrink-0 pt-2">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </div>
                                
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($product->main_image)
                                        <img class="h-20 w-20 rounded-lg object-cover shadow-sm" src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="h-20 w-20 rounded-lg bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center shadow-sm">
                                            <i class="bi bi-image text-white text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h4 class="text-lg font-semibold text-gray-900 truncate">{{ $product->name }}</h4>
                                                @if($product->is_featured)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="bi bi-star-fill mr-1"></i>Featured
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-2">
                                                <span class="flex items-center">
                                                    <i class="bi bi-upc-scan mr-1"></i>
                                                    SKU: <code class="bg-gray-100 px-1 rounded ml-1">{{ $product->sku }}</code>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="bi bi-folder mr-1"></i>
                                                    {{ $product->category->name }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="bi bi-person mr-1"></i>
                                                    {{ $product->seller->name }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="bi bi-layers mr-1"></i>
                                                    {{ $product->variations_count }} variation(s)
                                                </span>
                                                @if($product->min_price)
                                                    <span class="flex items-center">
                                                        <i class="bi bi-currency-dollar mr-1"></i>
                                                        ${{ number_format($product->min_price, 2) }}
                                                        @if($product->max_price != $product->min_price)
                                                            - ${{ number_format($product->max_price, 2) }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $product->short_description }}</p>
                                        </div>
                                        
                                        <!-- Status and Actions -->
                                        <div class="flex flex-col items-end space-y-2 ml-4">
                                            <!-- Status Badge -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                {{ $product->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                   ($product->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                <i class="bi bi-{{ $product->status === 'approved' ? 'check-circle-fill' : 
                                                                  ($product->status === 'pending' ? 'clock-fill' : 'x-circle-fill') }} mr-1"></i>
                                                {{ ucfirst($product->status) }}
                                            </span>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex items-center space-x-1">
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-100 rounded-lg transition-colors duration-200" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-100 rounded-lg transition-colors duration-200" 
                                                   title="Edit Product">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($product->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.products.approve', $product) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="p-2 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-lg transition-colors duration-200" 
                                                                onclick="return confirm('Are you sure you want to approve this product?')"
                                                                title="Approve Product">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="p-2 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-100 rounded-lg transition-colors duration-200" 
                                                            title="{{ $product->is_featured ? 'Unfeature' : 'Feature' }} Product">
                                                        <i class="bi bi-star{{ $product->is_featured ? '-fill' : '' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-lg transition-colors duration-200" 
                                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                                            title="Delete Product">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($product->status === 'rejected' && $product->rejection_reason)
                                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-sm text-red-800">
                                                <i class="bi bi-exclamation-triangle mr-1"></i>
                                                <strong>Rejection Reason:</strong> {{ $product->rejection_reason }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-box-seam text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                                <p class="text-gray-500 mb-6">Start by adding your first product to the marketplace.</p>
                                <a href="{{ route('admin.products.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="bi bi-plus-circle mr-2"></i>Add Product
                                </a>
                            </div>
                        </div>
                    @endforelse
                    
                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle bulk action form
        document.querySelector('select[name="action"]').addEventListener('change', function() {
            const rejectionInput = document.querySelector('input[name="rejection_reason"]');
            if (this.value === 'reject') {
                rejectionInput.style.display = 'block';
                rejectionInput.required = true;
            } else {
                rejectionInput.style.display = 'none';
                rejectionInput.required = false;
            }
        });

        // Handle bulk form submission
        document.getElementById('bulk-form').addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one product.');
                return;
            }
            
            const action = document.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action.');
                return;
            }
            
            // Add selected product IDs to form
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
            
            if (!confirm(`Are you sure you want to ${action} the selected products?`)) {
                e.preventDefault();
            }
        });

        // Select all functionality
        const selectAllCheckbox = document.createElement('input');
        selectAllCheckbox.type = 'checkbox';
        selectAllCheckbox.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded';
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</x-app-layout>