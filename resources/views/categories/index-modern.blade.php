@extends('layouts.app')

@section('title', 'All Categories - Marketplace')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
    }

    /* Category Cards */
    .category-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .category-image {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.05);
    }

    .category-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-card:hover .category-overlay {
        opacity: 1;
    }

    .category-count {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Dropdown submenu enhancement */
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
    }
</style>
@endpush

@section('content')

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Explore Our Categories</h1>
            <p class="lead mb-4">Discover amazing products across all categories</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" placeholder="What are you looking for?" style="border-radius: 25px 0 0 25px;">
                        <button class="btn btn-light" type="button" style="border-radius: 0 25px 25px 0;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold text-dark">Browse by Category</h2>
                <p class="text-muted">Find exactly what you're looking for</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($categoriesWithCounts as $category)
                <div class="col-lg-4 col-md-6">
                    <div class="card category-card">
                        <div class="position-relative overflow-hidden">
                            @if($category['image'])
                                <img src="{{ asset('storage/' . $category['image']) }}" 
                                     class="card-img-top category-image" 
                                     alt="{{ $category['name'] }}">
                            @else
                                <div class="card-img-top category-image d-flex align-items-center justify-content-center" 
                                     style="background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);">
                                    <i class="bi bi-grid text-muted" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            
                            <div class="category-overlay">
                                <a href="/category/{{ $category['slug'] }}" class="btn btn-light btn-lg fw-semibold">
                                    <i class="bi bi-arrow-right me-2"></i>Browse Now
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body text-center p-4">
                            <h5 class="card-title fw-bold mb-3">{{ $category['name'] }}</h5>
                            @if($category['description'])
                                <p class="card-text text-muted mb-3">{{ $category['description'] }}</p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="category-count">
                                    {{ $category['product_count'] }} {{ $category['product_count'] === 1 ? 'Product' : 'Products' }}
                                </span>
                                <a href="/category/{{ $category['slug'] }}" class="btn btn-outline-primary">
                                    Explore <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Enhanced dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Handle submenu hovers
        const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
        
        dropdownSubmenus.forEach(function(submenu) {
            submenu.addEventListener('mouseenter', function() {
                const submenuDropdown = this.querySelector('.dropdown-menu');
                if (submenuDropdown) {
                    submenuDropdown.classList.add('show');
                }
            });
            
            submenu.addEventListener('mouseleave', function() {
                const submenuDropdown = this.querySelector('.dropdown-menu');
                if (submenuDropdown) {
                    submenuDropdown.classList.remove('show');
                }
            });
        });
    });
</script>
@endpush