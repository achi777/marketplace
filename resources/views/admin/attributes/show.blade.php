<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900 flex items-center">
                    <i class="bi bi-eye mr-3 text-blue-600"></i>
                    Attribute Details: {{ $attribute->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">View attribute information and settings</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.attributes.edit', $attribute) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-pencil mr-2"></i>Edit Attribute
                </a>
                <a href="{{ route('admin.attributes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Attribute Overview -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-info-circle mr-2 text-blue-600"></i>
                        Attribute Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Attribute Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Attribute Name</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-gray-900 font-medium">{{ $attribute->name }}</p>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-gray-900">{{ $attribute->category->name }}</p>
                            </div>
                        </div>

                        <!-- Attribute Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $attribute->type === 'text' ? 'bg-blue-100 text-blue-800' : 
                                       ($attribute->type === 'select' ? 'bg-green-100 text-green-800' : 
                                        ($attribute->type === 'number' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($attribute->type) }}
                                </span>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-gray-900">{{ $attribute->sort_order }}</p>
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-gray-900">{{ $attribute->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                        </div>

                        <!-- Updated Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-gray-900">{{ $attribute->updated_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attribute Settings -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-gear mr-2 text-blue-600"></i>
                        Settings & Flags
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Required -->
                        <div class="text-center">
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center 
                                    {{ $attribute->is_required ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400' }}">
                                    <i class="bi bi-asterisk text-2xl"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Required</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $attribute->is_required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $attribute->is_required ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        <!-- Filterable -->
                        <div class="text-center">
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center 
                                    {{ $attribute->is_filterable ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-400' }}">
                                    <i class="bi bi-funnel text-2xl"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Filterable</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $attribute->is_filterable ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $attribute->is_filterable ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="text-center">
                            <div class="flex flex-col items-center space-y-2">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center 
                                    {{ $attribute->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="bi bi-{{ $attribute->is_active ? 'check-circle' : 'x-circle' }} text-2xl"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Status</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $attribute->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $attribute->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attribute Options (if applicable) -->
            @if($attribute->options && count($attribute->options) > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-list mr-2 text-blue-600"></i>
                            Available Options
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Options available for this {{ $attribute->type }} attribute</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($attribute->options as $index => $option)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $option }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Option {{ $index + 1 }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Usage Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-info-circle mr-2 text-blue-600"></i>
                        Usage Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- How to Use -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">How this attribute works:</h4>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <ul class="space-y-2 text-sm text-blue-800">
                                    <li class="flex items-start">
                                        <i class="bi bi-dot text-blue-600 mr-1 mt-1"></i>
                                        This attribute is available for products in the <strong>{{ $attribute->category->name }}</strong> category
                                    </li>
                                    @if($attribute->is_required)
                                        <li class="flex items-start">
                                            <i class="bi bi-dot text-blue-600 mr-1 mt-1"></i>
                                            Sellers <strong>must</strong> provide a value for this attribute when creating products
                                        </li>
                                    @else
                                        <li class="flex items-start">
                                            <i class="bi bi-dot text-blue-600 mr-1 mt-1"></i>
                                            This attribute is <strong>optional</strong> when creating products
                                        </li>
                                    @endif
                                    @if($attribute->is_filterable)
                                        <li class="flex items-start">
                                            <i class="bi bi-dot text-blue-600 mr-1 mt-1"></i>
                                            Customers can <strong>filter products</strong> based on this attribute
                                        </li>
                                    @endif
                                    <li class="flex items-start">
                                        <i class="bi bi-dot text-blue-600 mr-1 mt-1"></i>
                                        Input type: <strong>{{ ucfirst($attribute->type) }}</strong>
                                        @if(in_array($attribute->type, ['select', 'multiselect', 'radio', 'checkbox']) && $attribute->options)
                                            with {{ count($attribute->options) }} predefined option(s)
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- JSON Example -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Example usage in product variations:</h4>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <pre class="text-sm text-gray-800 overflow-x-auto"><code>{
    "{{ strtolower($attribute->name) }}": "@if($attribute->options && count($attribute->options) > 0){{ $attribute->options[0] }}@else{{ $attribute->type === 'number' ? '42' : ($attribute->type === 'boolean' ? 'true' : 'Sample Value') }}@endif"
}</code></pre>
                                <p class="text-xs text-gray-600 mt-2">This is how the attribute would appear in a product variation's attributes JSON field</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <form method="POST" action="{{ route('admin.attributes.toggle-status', $attribute) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 {{ $attribute->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors duration-200"
                                onclick="return confirm('Are you sure you want to {{ $attribute->is_active ? 'deactivate' : 'activate' }} this attribute?')">
                            <i class="bi bi-toggle-{{ $attribute->is_active ? 'off' : 'on' }} mr-2"></i>
                            {{ $attribute->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.attributes.edit', $attribute) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="bi bi-pencil mr-2"></i>Edit Attribute
                    </a>
                    <form method="POST" action="{{ route('admin.attributes.destroy', $attribute) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                                onclick="return confirm('Are you sure you want to delete this attribute? This action cannot be undone.')">
                            <i class="bi bi-trash mr-2"></i>Delete Attribute
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>