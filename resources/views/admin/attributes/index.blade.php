<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">
                    Attribute Management
                    @if(request('category_id'))
                        @php
                            $selectedCategory = \App\Models\Category::find(request('category_id'));
                        @endphp
                        @if($selectedCategory)
                            <span class="text-primary">- {{ $selectedCategory->name }}</span>
                        @endif
                    @endif
                </h2>
                <p class="text-muted small mb-0">Define dynamic attributes for product categories</p>
            </div>
            <div class="d-flex gap-2">
                @if(request('category_id'))
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Back to Categories
                    </a>
                @endif
                <a href="{{ route('admin.attributes.create', request('category_id') ? ['category_id' => request('category_id')] : []) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Add Attribute
                </a>
            </div>
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
        
        .filter-card, .attributes-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .filter-card::before, .attributes-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .attribute-item {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .attribute-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
            background: white;
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
        
        .type-badge {
            border-radius: 25px;
            padding: 0.25rem 0.75rem;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.25rem 0.75rem;
            font-weight: 600;
            font-size: 0.7rem;
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
        
        .option-tag {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            color: #495057;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.125rem;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .attribute-item {
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
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Attributes</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $attributes->total() }}</h3>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-tags"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card success p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Active</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $attributes->where('is_active', true)->count() }}</h3>
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
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Required</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $attributes->where('is_required', true)->count() }}</h3>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card warning p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Filterable</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $attributes->where('is_filterable', true)->count() }}</h3>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-funnel"></i>
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
                    Filter Attributes
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.attributes.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-folder me-1"></i>Category
                            </label>
                            <select name="category_id" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-type me-1"></i>Type
                            </label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="text" {{ request('type') === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="textarea" {{ request('type') === 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="number" {{ request('type') === 'number' ? 'selected' : '' }}>Number</option>
                                <option value="select" {{ request('type') === 'select' ? 'selected' : '' }}>Select</option>
                                <option value="multiselect" {{ request('type') === 'multiselect' ? 'selected' : '' }}>Multi-select</option>
                                <option value="radio" {{ request('type') === 'radio' ? 'selected' : '' }}>Radio</option>
                                <option value="checkbox" {{ request('type') === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                <option value="boolean" {{ request('type') === 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="date" {{ request('type') === 'date' ? 'selected' : '' }}>Date</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-asterisk me-1"></i>Required
                            </label>
                            <select name="is_required" class="form-select">
                                <option value="">All</option>
                                <option value="yes" {{ request('is_required') === 'yes' ? 'selected' : '' }}>Required Only</option>
                                <option value="no" {{ request('is_required') === 'no' ? 'selected' : '' }}>Optional Only</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attributes List -->
        <div class="attributes-card">
            <div class="card-header bg-transparent border-0 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-tags me-2 text-primary"></i>
                        Attributes Library
                    </h5>
                    <div class="text-muted small">
                        {{ $attributes->total() ?? 0 }} total attributes
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @forelse($attributes as $attribute)
                    <div class="attribute-item">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <h5 class="mb-0 fw-bold text-dark">{{ $attribute->name }}</h5>
                                        <span class="type-badge 
                                            @if($attribute->type === 'text') bg-primary text-white
                                            @elseif($attribute->type === 'select') bg-success text-white
                                            @elseif($attribute->type === 'number') bg-info text-white
                                            @else bg-secondary text-white @endif">
                                            {{ ucfirst($attribute->type) }}
                                        </span>
                                        @if($attribute->is_required)
                                            <span class="status-badge bg-danger text-white">
                                                <i class="bi bi-asterisk me-1"></i>Required
                                            </span>
                                        @endif
                                        @if($attribute->is_filterable)
                                            <span class="status-badge bg-warning text-dark">
                                                <i class="bi bi-funnel me-1"></i>Filterable
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Status and Actions -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="status-badge {{ $attribute->is_active ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            <i class="bi bi-{{ $attribute->is_active ? 'check-circle-fill' : 'x-circle-fill' }} me-1"></i>
                                            {{ $attribute->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        
                                        <a href="{{ route('admin.attributes.show', $attribute) }}" 
                                           class="action-btn btn btn-outline-primary btn-sm" 
                                           title="View Details" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.attributes.edit', $attribute) }}" 
                                           class="action-btn btn btn-outline-info btn-sm" 
                                           title="Edit Attribute" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.attributes.toggle-status', $attribute) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="action-btn btn btn-outline-warning btn-sm" 
                                                    onclick="return confirm('Are you sure you want to toggle the status?')"
                                                    title="Toggle Status" data-bs-toggle="tooltip">
                                                <i class="bi bi-toggle-{{ $attribute->is_active ? 'on' : 'off' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.attributes.destroy', $attribute) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this attribute?')"
                                                    title="Delete Attribute" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="row g-4 small text-muted mb-3">
                                    <div class="col-md-3">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-folder me-2 text-primary"></i>
                                            <strong>{{ $attribute->category->name }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-sort-numeric-up me-2 text-primary"></i>
                                            Order: <strong class="ms-1">{{ $attribute->sort_order }}</strong>
                                        </span>
                                    </div>
                                    @if($attribute->options && count($attribute->options) > 0)
                                        <div class="col-md-6">
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-list me-2 text-primary"></i>
                                                <strong>{{ count($attribute->options) }}</strong> option(s)
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($attribute->options && count($attribute->options) > 0)
                                    <div class="pt-3 border-top">
                                        <p class="small text-muted mb-2 fw-semibold">Available Options:</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($attribute->options as $option)
                                                <span class="option-tag">{{ $option }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mx-auto mb-4" style="max-width: 400px;">
                            <div class="stat-icon bg-light text-muted mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background: #f8f9fa !important;">
                                <i class="bi bi-tags"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">No attributes found</h4>
                            <p class="text-muted mb-4">Start by creating your first attribute to define product properties.</p>
                            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create First Attribute
                            </a>
                        </div>
                    </div>
                @endforelse
                
                <!-- Pagination -->
                @if($attributes->hasPages())
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex justify-content-center">
                            {{ $attributes->appends(request()->query())->links() }}
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