<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Category Management</h2>
                <p class="text-muted small mb-0">Organize your marketplace categories with hierarchical structure</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i>Add Category
            </a>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .stats-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card.primary {
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stats-card.success {
            --gradient: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        }
        
        .stats-card.warning {
            --gradient: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
        }
        
        .stats-card.info {
            --gradient: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: var(--gradient);
            flex-shrink: 0;
        }
        
        .stat-icon.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-icon.success {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        }
        
        .stat-icon.warning {
            background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
        }
        
        .stat-icon.info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }
        
        .filter-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .filter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .category-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .category-item {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .category-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
            background: white;
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin: 0 2px;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .subcategory-indicator {
            border-left: 3px solid #667eea;
            padding-left: 1rem;
            margin-left: 1rem;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .category-item {
                padding: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Categories</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $categories->total() ?? 0 }}</h3>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card success p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Active Categories</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $categories->where('is_active', true)->count() ?? 0 }}</h3>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card info p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Root Categories</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $parentCategories->count() ?? 0 }}</h3>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-diagram-2"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card warning p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Subcategories</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $categories->where('parent_id', '!=', null)->count() ?? 0 }}</h3>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="filter-card mb-4">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-funnel me-2 text-primary"></i>
                    Filter Categories
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.categories.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-search me-1"></i>Search
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by name or description..." 
                                   class="form-control">
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-toggle-on me-1"></i>Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-diagram-2 me-1"></i>Hierarchy Level
                            </label>
                            <select name="parent" class="form-select">
                                <option value="">All Levels</option>
                                <option value="root" {{ request('parent') === 'root' ? 'selected' : '' }}>Root Categories Only</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ request('parent') == $parent->id ? 'selected' : '' }}>
                                        Under "{{ $parent->name }}"
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-custom flex-grow-1">
                                    <i class="bi bi-search me-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Hierarchical View -->
        <div class="category-card">
            <div class="card-header bg-transparent border-0 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-diagram-3 me-2 text-primary"></i>
                        Category Hierarchy
                    </h5>
                    <div class="d-flex align-items-center gap-3 small text-muted">
                        <span class="d-flex align-items-center">
                            <i class="bi bi-dot text-primary me-1" style="font-size: 1.5rem;"></i>Root Category
                        </span>
                        <span class="d-flex align-items-center">
                            <i class="bi bi-arrow-return-right text-secondary me-1"></i>Subcategory
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @forelse($categories as $category)
                    <div class="category-item {{ $category->parent_id ? 'subcategory-indicator' : '' }}">
                        <div class="d-flex align-items-start">
                            <!-- Category Icon/Image -->
                            <div class="me-4 flex-shrink-0">
                                @if($category->image)
                                    <img class="rounded-3 shadow-sm" src="{{ Storage::url($category->image) }}" 
                                         alt="{{ $category->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="category-icon">
                                        <i class="bi bi-{{ $category->parent_id ? 'folder' : 'folder2-open' }}"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Category Info -->
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <h5 class="mb-0 fw-bold text-dark">{{ $category->name }}</h5>
                                        @if($category->is_featured ?? false)
                                            <span class="badge bg-warning text-dark fw-semibold">
                                                <i class="bi bi-star-fill me-1"></i>Featured
                                            </span>
                                        @endif
                                        <span class="status-badge {{ $category->is_active ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            <i class="bi bi-{{ $category->is_active ? 'check-circle-fill' : 'x-circle-fill' }} me-1"></i>
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.attributes.index', ['category_id' => $category->id]) }}" 
                                           class="action-btn btn btn-outline-secondary btn-sm" 
                                           title="Manage Attributes" data-bs-toggle="tooltip">
                                            <i class="bi bi-tags"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.show', $category) }}" 
                                           class="action-btn btn btn-outline-primary btn-sm" 
                                           title="View Details" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="action-btn btn btn-outline-info btn-sm" 
                                           title="Edit Category" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="action-btn btn btn-outline-warning btn-sm" 
                                                    onclick="return confirm('Are you sure you want to toggle the status?')"
                                                    title="Toggle Status" data-bs-toggle="tooltip">
                                                <i class="bi bi-toggle-{{ $category->is_active ? 'on' : 'off' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')"
                                                    title="Delete Category" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="row g-4 small text-muted mb-3">
                                    <div class="col-md-3">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-link me-2 text-primary"></i>
                                            <code class="bg-light px-2 py-1 rounded border">{{ $category->slug }}</code>
                                        </span>
                                    </div>
                                    @if($category->parent)
                                        <div class="col-md-3">
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-folder me-2 text-primary"></i>
                                                Parent: <strong class="ms-1">{{ $category->parent->name }}</strong>
                                            </span>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-box-seam me-2 text-primary"></i>
                                            <strong>{{ $category->products_count ?? ($category->products ? $category->products->count() : 0) }}</strong> products
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-sort-numeric-up me-2 text-primary"></i>
                                            Order: <strong class="ms-1">{{ $category->sort_order }}</strong>
                                        </span>
                                    </div>
                                </div>
                                
                                @if($category->description)
                                    <p class="mb-3 text-muted" style="line-height: 1.6;">
                                        {{ Str::limit($category->description, 200) }}
                                    </p>
                                @endif
                                
                                @php
                                    $childrenCount = $category->children_count ?? ($category->children ? $category->children->count() : 0);
                                @endphp
                                @if($childrenCount > 0)
                                    <div class="pt-3 border-top">
                                        <span class="badge bg-info text-dark fw-semibold">
                                            <i class="bi bi-diagram-3 me-1"></i>
                                            Has {{ $childrenCount }} subcategorie(s)
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mx-auto mb-4" style="max-width: 400px;">
                            <div class="category-icon bg-light text-muted mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="bi bi-folder-x"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">No categories found</h4>
                            <p class="text-muted mb-4">Get started by creating your first category to organize your marketplace products.</p>
                            <a href="{{ route('admin.categories.create') }}" 
                               class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create First Category
                            </a>
                        </div>
                    </div>
                @endforelse
                
                <!-- Pagination -->
                @if($categories->hasPages())
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex justify-content-center">
                            {{ $categories->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</x-app-layout>