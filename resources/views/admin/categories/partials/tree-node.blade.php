<div class="tree-node category-level-{{ $level }}" id="node-{{ $category->id }}">
    <div class="tree-content">
        <div class="flex items-center justify-between">
            <!-- Category Info Section -->
            <div class="flex items-center space-x-3 flex-1">
                <!-- Expand/Collapse Toggle -->
                @if(count($category->children_tree ?? []) > 0)
                    <button onclick="toggleCategory({{ $category->id }})" 
                            id="toggle-{{ $category->id }}" 
                            class="collapse-toggle text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                @else
                    <span class="w-4 h-4 flex items-center justify-center">
                        <i class="bi bi-dot text-gray-300"></i>
                    </span>
                @endif
                
                <!-- Category Icon -->
                <div class="tree-icon">
                    @if($category->image)
                        <img class="w-8 h-8 rounded object-cover" src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                    @else
                        <div class="w-8 h-8 rounded bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="bi bi-folder text-white text-sm"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Category Details -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2">
                        <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $category->name }}</h4>
                        @if(count($category->children_tree ?? []) > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                {{ count($category->children_tree) }} child{{ count($category->children_tree) !== 1 ? 'ren' : '' }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                        <span class="flex items-center">
                            <i class="bi bi-link mr-1"></i>
                            <code class="bg-gray-100 px-1 rounded">{{ $category->slug }}</code>
                        </span>
                        <span class="flex items-center">
                            <i class="bi bi-box-seam mr-1"></i>
                            {{ $category->products_count ?? 0 }} products
                        </span>
                        @if($category->description)
                            <span class="truncate max-w-32" title="{{ $category->description }}">
                                {{ Str::limit($category->description, 30) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Status and Actions -->
            <div class="flex items-center space-x-2">
                <!-- Status Badge -->
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <i class="bi bi-{{ $category->is_active ? 'check-circle-fill' : 'x-circle-fill' }} mr-1"></i>
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-1">
                    <a href="{{ route('admin.categories.show', $category) }}" 
                       class="p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-100 rounded transition-colors duration-200" 
                       title="View Details">
                        <i class="bi bi-eye text-xs"></i>
                    </a>
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="p-1.5 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-100 rounded transition-colors duration-200" 
                       title="Edit Category">
                        <i class="bi bi-pencil text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-1.5 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-100 rounded transition-colors duration-200" 
                                onclick="return confirm('Are you sure you want to toggle the status?')"
                                title="Toggle Status">
                            <i class="bi bi-toggle-{{ $category->is_active ? 'on' : 'off' }} text-xs"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-1.5 text-red-600 hover:text-red-900 hover:bg-red-100 rounded transition-colors duration-200" 
                                onclick="return confirm('Are you sure you want to delete this category?')"
                                title="Delete Category">
                            <i class="bi bi-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Children Container -->
    @if(count($category->children_tree ?? []) > 0)
        <div id="children-{{ $category->id }}" class="children-container mt-2 ml-4">
            @foreach($category->children_tree as $child)
                @include('admin.categories.partials.tree-node', [
                    'category' => $child, 
                    'level' => $level + 1, 
                    'isLast' => $loop->last
                ])
            @endforeach
        </div>
    @endif
</div>