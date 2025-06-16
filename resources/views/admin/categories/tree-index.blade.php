<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-900 flex items-center">
                    <i class="bi bi-diagram-3 mr-3 text-blue-600"></i>
                    Category Tree Management
                </h2>
                <p class="text-sm text-gray-600 mt-1">Organize your marketplace categories in a hierarchical tree structure</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="toggleTreeView()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-list-ul mr-2"></i>Toggle View
                </button>
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <i class="bi bi-plus-circle mr-2"></i>Add Category
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .tree-view {
            font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
        }
        
        .tree-node {
            position: relative;
            margin: 0;
            padding: 0;
        }
        
        .tree-line {
            position: relative;
            padding-left: 0;
        }
        
        .tree-line::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 50%;
            width: 20px;
            border-left: 2px solid #d1d5db;
            border-bottom: 2px solid #d1d5db;
        }
        
        .tree-line.last::before {
            border-left: 2px solid #d1d5db;
            border-bottom: 2px solid #d1d5db;
            height: 50%;
        }
        
        .tree-line.has-siblings::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            bottom: -100vh;
            width: 2px;
            background: #d1d5db;
        }
        
        .tree-icon {
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            border-radius: 4px;
        }
        
        .tree-content {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin: 4px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .tree-content:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }
        
        .category-level-0 { margin-left: 0; }
        .category-level-1 { margin-left: 30px; }
        .category-level-2 { margin-left: 60px; }
        .category-level-3 { margin-left: 90px; }
        
        .collapse-toggle {
            cursor: pointer;
            user-select: none;
            transition: transform 0.2s ease;
        }
        
        .collapse-toggle.collapsed {
            transform: rotate(-90deg);
        }
        
        .children-container {
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .children-container.collapsed {
            max-height: 0;
        }
        
        @media (max-width: 768px) {
            .category-level-1 { margin-left: 20px; }
            .category-level-2 { margin-left: 40px; }
            .category-level-3 { margin-left: 60px; }
            
            .tree-content {
                padding: 8px;
            }
        }
    </style>

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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-xs font-medium">Total Categories</p>
                            <p class="text-2xl font-bold">{{ collect($categoryTree)->flatten()->count() + collect($categoryTree)->sum(function($cat) { return count($cat->children_tree ?? []); }) }}</p>
                        </div>
                        <i class="bi bi-folder2-open text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-xs font-medium">Root Categories</p>
                            <p class="text-2xl font-bold">{{ count($categoryTree) }}</p>
                        </div>
                        <i class="bi bi-diagram-2 text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-xs font-medium">Max Depth</p>
                            <p class="text-2xl font-bold">{{ $categoryTree ? 3 : 0 }}</p>
                        </div>
                        <i class="bi bi-arrow-down text-xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-xs font-medium">Active Categories</p>
                            <p class="text-2xl font-bold">{{ collect($categoryTree)->flatten()->where('is_active', true)->count() }}</p>
                        </div>
                        <i class="bi bi-check-circle text-xl opacity-80"></i>
                    </div>
                </div>
            </div>

            <!-- Tree View Container -->
            <div id="tree-view" class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-diagram-3 mr-2 text-blue-600"></i>
                            Category Tree Structure
                        </h3>
                        <div class="flex items-center space-x-3">
                            <button onclick="expandAll()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i class="bi bi-arrows-expand mr-1"></i>Expand All
                            </button>
                            <button onclick="collapseAll()" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                <i class="bi bi-arrows-collapse mr-1"></i>Collapse All
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 tree-view">
                    @forelse($categoryTree as $category)
                        @include('admin.categories.partials.tree-node', ['category' => $category, 'level' => 0, 'isLast' => $loop->last])
                    @empty
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-folder-x text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                                <p class="text-gray-500 mb-6">Create your first category to start organizing your marketplace.</p>
                                <a href="{{ route('admin.categories.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="bi bi-plus-circle mr-2"></i>Create First Category
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- List View (Hidden by default) -->
            <div id="list-view" class="bg-white rounded-xl shadow-lg border border-gray-200" style="display: none;">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-list-ul mr-2 text-blue-600"></i>
                        Category List View
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($paginatedCategories as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($category->parent_id)
                                                    <span class="mr-2 text-gray-400">└─</span>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $category->parent ? $category->parent->name : 'Root Category' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $category->products_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.categories.show', $category) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" 
                                                        onclick="return confirm('Are you sure you want to toggle the status?')">
                                                    <i class="bi bi-toggle-{{ $category->is_active ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($paginatedCategories->hasPages())
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            {{ $paginatedCategories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTreeView() {
            const treeView = document.getElementById('tree-view');
            const listView = document.getElementById('list-view');
            
            if (treeView.style.display === 'none') {
                treeView.style.display = 'block';
                listView.style.display = 'none';
            } else {
                treeView.style.display = 'none';
                listView.style.display = 'block';
            }
        }

        function toggleCategory(categoryId) {
            const childrenContainer = document.getElementById('children-' + categoryId);
            const toggle = document.getElementById('toggle-' + categoryId);
            
            if (childrenContainer.style.display === 'none') {
                childrenContainer.style.display = 'block';
                toggle.innerHTML = '<i class="bi bi-chevron-down"></i>';
                toggle.classList.remove('collapsed');
            } else {
                childrenContainer.style.display = 'none';
                toggle.innerHTML = '<i class="bi bi-chevron-right"></i>';
                toggle.classList.add('collapsed');
            }
        }

        function expandAll() {
            document.querySelectorAll('[id^="children-"]').forEach(container => {
                container.style.display = 'block';
            });
            document.querySelectorAll('[id^="toggle-"]').forEach(toggle => {
                toggle.innerHTML = '<i class="bi bi-chevron-down"></i>';
                toggle.classList.remove('collapsed');
            });
        }

        function collapseAll() {
            document.querySelectorAll('[id^="children-"]').forEach(container => {
                container.style.display = 'none';
            });
            document.querySelectorAll('[id^="toggle-"]').forEach(toggle => {
                toggle.innerHTML = '<i class="bi bi-chevron-right"></i>';
                toggle.classList.add('collapsed');
            });
        }
    </script>
</x-app-layout>