<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900 flex items-center">
                    <i class="bi bi-eye mr-3 text-blue-600"></i>
                    Product Details: {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">View complete product information and variations</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-pencil mr-2"></i>Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-arrow-left mr-2"></i>Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Product Overview -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-info-circle mr-2 text-blue-600"></i>
                            Product Information
                        </h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $product->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($product->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            <i class="bi bi-{{ $product->status === 'approved' ? 'check-circle-fill' : 
                                              ($product->status === 'pending' ? 'clock-fill' : 'x-circle-fill') }} mr-1"></i>
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Product Images -->
                        <div class="lg:col-span-1">
                            @if($product->images && count($product->images) > 0)
                                <div class="space-y-4">
                                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    @if(count($product->images) > 1)
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach(array_slice($product->images, 1, 4) as $image)
                                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                                    <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}" 
                                                         class="w-full h-full object-cover">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="bi bi-image text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500">No images</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Basic Info -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                        <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                        <code class="text-gray-900">{{ $product->sku }}</code>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                        <code class="text-gray-900">{{ $product->slug }}</code>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                        <p class="text-gray-900">{{ $product->category->name }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seller</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                        <p class="text-gray-900">{{ $product->seller->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $product->seller->email }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                        @if($product->is_featured)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="bi bi-star-fill mr-1"></i>Featured
                                            </span>
                                        @else
                                            <span class="text-gray-500">Not Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                    <p class="text-gray-900">{{ $product->short_description }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 max-h-40 overflow-y-auto">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $product->description }}</p>
                                </div>
                            </div>

                            <!-- Physical Properties -->
                            @if($product->weight || $product->dimensions)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if($product->weight)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                                <p class="text-gray-900">{{ $product->weight }} kg</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($product->dimensions)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Dimensions</label>
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                                <p class="text-gray-900">{{ $product->dimensions }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejection Reason (if applicable) -->
            @if($product->status === 'rejected' && $product->rejection_reason)
                <div class="bg-white rounded-xl shadow-lg border border-red-200 mb-8">
                    <div class="px-6 py-4 border-b border-red-200 bg-red-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-red-900 flex items-center">
                            <i class="bi bi-exclamation-triangle mr-2"></i>
                            Rejection Reason
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-red-800">{{ $product->rejection_reason }}</p>
                    </div>
                </div>
            @endif

            <!-- Product Variations -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-layers mr-2 text-blue-600"></i>
                            Product Variations ({{ $product->variations->count() }})
                        </h3>
                        <div class="text-sm text-gray-600">
                            Total Stock: {{ $product->total_stock }} | 
                            Price Range: ${{ number_format($product->min_price, 2) }}
                            @if($product->max_price != $product->min_price)
                                - ${{ number_format($product->max_price, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($product->variations->count() > 0)
                        <div class="space-y-4">
                            @foreach($product->variations as $variation)
                                <div class="border border-gray-200 rounded-lg p-4 {{ $variation->is_active ? '' : 'bg-gray-50' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-2">
                                                <h4 class="font-medium text-gray-900">
                                                    SKU: {{ $variation->sku }}
                                                </h4>
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
                                                    <div class="font-medium">
                                                        ${{ number_format($variation->price, 2) }}
                                                        @if($variation->compare_price && $variation->compare_price > $variation->price)
                                                            <span class="text-sm text-gray-500 line-through ml-1">
                                                                ${{ number_format($variation->compare_price, 2) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <span class="text-gray-500">Stock:</span>
                                                    <div class="font-medium {{ $variation->stock_quantity <= 0 ? 'text-red-600' : ($variation->is_low_stock ? 'text-yellow-600' : 'text-green-600') }}">
                                                        {{ $variation->stock_quantity }}
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <span class="text-gray-500">Low Stock Alert:</span>
                                                    <div class="font-medium">{{ $variation->low_stock_threshold ?? 'Not set' }}</div>
                                                </div>
                                                
                                                @if($variation->discount_percentage)
                                                    <div>
                                                        <span class="text-gray-500">Discount:</span>
                                                        <div class="font-medium text-green-600">{{ $variation->discount_percentage }}% off</div>
                                                    </div>
                                                @endif
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
                    @else
                        <div class="text-center py-8">
                            <i class="bi bi-layers text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No variations found for this product.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order History -->
            @if($product->orderItems->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-cart mr-2 text-blue-600"></i>
                            Order History ({{ $product->orderItems->count() }} orders)
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product->orderItems->take(10) as $orderItem)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('orders.show', $orderItem->order) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $orderItem->order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $orderItem->order->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $orderItem->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($orderItem->price * $orderItem->quantity, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $orderItem->order->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $orderItem->order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                                       ($orderItem->order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                                        ($orderItem->order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($orderItem->order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($product->orderItems->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 most recent orders. Total: {{ $product->orderItems->count() }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Product Actions -->
            <div class="flex justify-between items-center">
                @if($product->status === 'pending')
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('admin.products.approve', $product) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                                    onclick="return confirm('Are you sure you want to approve this product?')">
                                <i class="bi bi-check-circle mr-2"></i>Approve Product
                            </button>
                        </form>
                        
                        <button type="button" onclick="showRejectModal()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="bi bi-x-circle mr-2"></i>Reject Product
                        </button>
                    </div>
                @else
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="bi bi-star{{ $product->is_featured ? '-fill' : '' }} mr-2"></i>
                                {{ $product->is_featured ? 'Unfeature' : 'Feature' }}
                            </button>
                        </form>
                    </div>
                @endif

                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="bi bi-pencil mr-2"></i>Edit Product
                    </a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                                onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                            <i class="bi bi-trash mr-2"></i>Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Product</h3>
                <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Please provide a detailed reason for rejection..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideRejectModal()"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                            Reject Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
</x-app-layout>