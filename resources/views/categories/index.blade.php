<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h3 mb-1 fw-bold text-dark">
                    <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>{{ __('All Categories') }}
                </h2>
                <p class="text-muted small mb-0">Discover products organized by category</p>
            </div>
            <div>
                <span class="badge rounded-pill px-3 py-2 text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="bi bi-collection me-1"></i>{{ $categories->count() }} Categories
                </span>
            </div>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .category-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .category-card:hover::before {
            opacity: 1;
        }
        
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .category-card .card-img-top {
            transition: transform 0.3s ease;
        }
        
        .category-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .category-title {
            color: #2d3436;
            font-weight: 700;
            transition: color 0.3s ease;
            text-decoration: none;
        }
        
        .category-title:hover {
            color: #667eea;
        }
        
        .subcategory-badge {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            color: #2d3436;
            border-radius: 12px;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 2px;
        }
        
        .subcategory-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(251, 177, 160, 0.4);
            color: #2d3436;
        }
        
        .more-badge {
            background: linear-gradient(135deg, #ddd6fe 0%, #c7d2fe 100%);
            color: #4c51bf;
            border-radius: 12px;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            margin: 2px;
        }
        
        .btn-explore {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }
        
        .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .no-categories {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 768px) {
            .category-card {
                margin-bottom: 1.5rem;
            }
        }
    </style>

    <div class="container py-4">
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-6 col-lg-4">
                    <div class="category-card h-100">
                        <div class="position-relative overflow-hidden" style="height: 240px;">
                            @if($category->image)
                                <img src="{{ $category->image }}" class="card-img-top w-100 h-100" alt="{{ $category->name }}" style="object-fit: cover;">
                            @else
                                <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <i class="bi bi-folder display-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <a href="{{ route('category.show', $category->slug) }}" class="category-title">
                                    {{ $category->name }}
                                </a>
                            </h5>
                            
                            @if($category->description)
                                <p class="card-text text-muted mb-3">{{ Str::limit($category->description, 100) }}</p>
                            @endif
                            
                            @if($category->children->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-muted fw-semibold small mb-2">
                                        <i class="bi bi-diagram-3 me-1"></i>Subcategories:
                                    </h6>
                                    <div class="d-flex flex-wrap">
                                        @foreach($category->children->take(4) as $child)
                                            <a href="{{ route('category.show', $child->slug) }}" class="subcategory-badge">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                        @if($category->children->count() > 4)
                                            <span class="more-badge">+{{ $category->children->count() - 4 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                            <a href="{{ route('category.show', $category->slug) }}" class="btn btn-explore w-100">
                                <i class="bi bi-arrow-right-circle me-2"></i>Explore Category
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($categories->isEmpty())
            <div class="no-categories text-center">
                <div class="mb-4">
                    <i class="bi bi-folder-x display-1 text-muted"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">No Categories Found</h3>
                <p class="text-muted mb-4">Categories will appear here once they are added by administrators.</p>
                <a href="{{ route('home') }}" class="btn btn-explore">
                    <i class="bi bi-house me-2"></i>Back to Homepage
                </a>
            </div>
        @endif
    </div>
</x-app-layout>