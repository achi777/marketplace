<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Sales Analytics</h2>
                <p class="text-muted small mb-0">Track your product performance and sales metrics</p>
            </div>
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .analytics-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: transform 0.3s ease;
        }
        
        .analytics-card:hover {
            transform: translateY(-5px);
        }
        
        .analytics-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
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
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3436;
            line-height: 1;
        }
        
        .stat-label {
            color: #636e72;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        }
        
        .gradient-warning {
            background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
        }
        
        .gradient-danger {
            background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
        }
        
        .gradient-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #636e72 0%, #2d3436 100%);
        }
        
        .chart-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }
        
        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .progress-bar-modern {
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }
        
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Key Metrics -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-primary me-3">
                            <i class="bi bi-box"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $stats['total_products'] }}</div>
                            <div class="stat-label">Total Products</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-success me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $stats['approved_products'] }}</div>
                            <div class="stat-label">Approved</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-warning me-3">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $stats['pending_products'] }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-danger me-3">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $stats['rejected_products'] }}</div>
                            <div class="stat-label">Rejected</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Metrics -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-info me-3">
                            <i class="bi bi-stack"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ number_format($stats['total_stock']) }}</div>
                            <div class="stat-label">Total Stock</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-secondary me-3">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $stats['low_stock_items'] }}</div>
                            <div class="stat-label">Low Stock Items</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 col-lg-4">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon gradient-success me-3">
                            <i class="bi bi-percent"></i>
                        </div>
                        <div>
                            <div class="stat-number">
                                {{ $stats['total_products'] > 0 ? number_format(($stats['approved_products'] / $stats['total_products']) * 100, 1) : 0 }}%
                            </div>
                            <div class="stat-label">Approval Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="row g-4">
            <!-- Product Status Breakdown -->
            <div class="col-lg-6">
                <div class="analytics-card p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-pie-chart me-2 text-primary"></i>Product Status Overview
                    </h5>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Approved Products</span>
                            <span class="text-success fw-bold">{{ $stats['approved_products'] }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern gradient-success" 
                                 style="width: {{ $stats['total_products'] > 0 ? ($stats['approved_products'] / $stats['total_products']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Pending Products</span>
                            <span class="text-warning fw-bold">{{ $stats['pending_products'] }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern gradient-warning" 
                                 style="width: {{ $stats['total_products'] > 0 ? ($stats['pending_products'] / $stats['total_products']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Rejected Products</span>
                            <span class="text-danger fw-bold">{{ $stats['rejected_products'] }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern gradient-danger" 
                                 style="width: {{ $stats['total_products'] > 0 ? ($stats['rejected_products'] / $stats['total_products']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inventory Status -->
            <div class="col-lg-6">
                <div class="analytics-card p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-boxes me-2 text-primary"></i>Inventory Health
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded-3">
                                <div class="h4 fw-bold text-info mb-1">{{ number_format($stats['total_stock']) }}</div>
                                <small class="text-muted fw-semibold">Total Units</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded-3">
                                <div class="h4 fw-bold text-warning mb-1">{{ $stats['low_stock_items'] }}</div>
                                <small class="text-muted fw-semibold">Low Stock</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center p-3 bg-light rounded-3">
                                <div class="h4 fw-bold text-success mb-1">
                                    {{ $stats['total_stock'] > 0 ? number_format(($stats['total_stock'] - $stats['low_stock_items']) / $stats['total_stock'] * 100, 1) : 0 }}%
                                </div>
                                <small class="text-muted fw-semibold">Healthy Stock Level</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($stats['low_stock_items'] > 0)
                        <div class="alert alert-warning border-0 rounded-3 mt-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Low Stock Alert</h6>
                                    <p class="mb-0">{{ $stats['low_stock_items'] }} product variation(s) are running low on stock.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="analytics-card p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-lightning me-2 text-primary"></i>Quick Actions
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('seller.products.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle me-2"></i>Add New Product
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('seller.products.index') }}?status=pending" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-clock me-2"></i>View Pending
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('seller.products.index') }}?status=rejected" class="btn btn-outline-danger w-100 py-3">
                                <i class="bi bi-x-circle me-2"></i>Fix Rejected
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="bi bi-list me-2"></i>All Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>