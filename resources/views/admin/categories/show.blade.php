<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Category Details') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="bi bi-pencil mr-2"></i>Edit Category
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="bi bi-arrow-left mr-2"></i>Back to Categories
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Name</label>
                                    <p class="text-lg text-gray-900">{{ $category->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Slug</label>
                                    <p class="text-sm text-gray-700 font-mono bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Parent Category</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $category->parent ? $category->parent->name : 'Root Category' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Sort Order</label>
                                    <p class="text-sm text-gray-900">{{ $category->sort_order }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            @if($category->image)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Category Image</label>
                                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" 
                                         class="w-48 h-48 object-cover rounded-lg shadow-md">
                                </div>
                            @endif
                            
                            @if($category->description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Description</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ $category->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Subcategories ({{ $category->children->count() }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($category->children as $child)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $child->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $child->slug }}</p>
                                        </div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.categories.show', $child) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Products in Category -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Products in Category ({{ $category->products->count() }})</h3>
                    @if($category->products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seller</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($category->products->take(10) as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($product->main_image)
                                                        <img class="h-10 w-10 rounded object-cover mr-3" src="{{ $product->main_image }}" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded bg-gray-300 flex items-center justify-center mr-3">
                                                            <i class="bi bi-image text-gray-600"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $product->slug }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $product->seller->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($product->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($product->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($category->products->count() > 10)
                            <div class="mt-4 text-sm text-gray-500">
                                Showing 10 of {{ $category->products->count() }} products.
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">No products in this category yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>