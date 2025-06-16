<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Admin Dashboard</h2>
                <p class="text-muted small mb-0">Manage your marketplace operations</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Add Category
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-box-seam me-2"></i>Manage Products
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        
        .stats-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-gradient {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-info) 100%);
        }
        
        .stats-gradient.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .stats-gradient.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .stats-gradient.info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        
        .main-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .quick-action-btn {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            background: white;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #495057;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
            color: #667eea;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 10px;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-gradient p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Users</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_users']) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-gradient success p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Products</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_products']) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-gradient info p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Orders</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_orders']) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-bag-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-gradient warning p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Revenue</p>
                            <h3 class="mb-0 fw-bold">${{ number_format($stats['total_revenue'], 2) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-8">
                <div class="main-card">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-clock-history me-2 text-primary"></i>
                            Recent Orders
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if(isset($recentOrders) && $recentOrders && $recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-semibold">Order #</th>
                                            <th class="fw-semibold">Customer</th>
                                            <th class="fw-semibold">Date</th>
                                            <th class="fw-semibold">Status</th>
                                            <th class="fw-semibold">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td class="fw-semibold text-primary">{{ $order->order_number }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge rounded-pill 
                                                        @if($order->status === 'delivered') bg-success
                                                        @elseif($order->status === 'shipped') bg-info
                                                        @elseif($order->status === 'processing') bg-warning text-dark
                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">No orders yet</h6>
                                <p class="text-muted">Orders will appear here once customers start placing them.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="main-card mb-4">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-lightning me-2 text-primary"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <a href="{{ route('admin.users.index') }}" class="quick-action-btn d-flex align-items-center position-relative">
                                <i class="bi bi-people me-3 text-primary"></i>
                                <span class="fw-semibold">Manage Users</span>
                                @if($stats['pending_sellers'] > 0)
                                    <span class="badge-notification">{{ $stats['pending_sellers'] }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-folder me-3 text-success"></i>
                                <span class="fw-semibold">Manage Categories</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="quick-action-btn d-flex align-items-center position-relative">
                                <i class="bi bi-box-seam me-3 text-info"></i>
                                <span class="fw-semibold">Manage Products</span>
                                @if($stats['pending_products'] > 0)
                                    <span class="badge-notification">{{ $stats['pending_products'] }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.attributes.index') }}" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-tags me-3 text-warning"></i>
                                <span class="fw-semibold">Manage Attributes</span>
                            </a>
                            <a href="{{ route('admin.kyc.index') }}" class="quick-action-btn d-flex align-items-center position-relative">
                                <i class="bi bi-file-earmark-check me-3 text-secondary"></i>
                                <span class="fw-semibold">Review KYC Documents</span>
                                @if($stats['pending_kyc'] > 0)
                                    <span class="badge-notification">{{ $stats['pending_kyc'] }}</span>
                                @endif
                            </a>
                            <a href="{{ route('orders.index') }}" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-bag-check me-3 text-primary"></i>
                                <span class="fw-semibold">View All Orders</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="main-card">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-graph-up me-2 text-primary"></i>
                            System Overview
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded-3">
                                    <div class="h5 fw-bold mb-1 text-primary">{{ $stats['total_sellers'] }}</div>
                                    <small class="text-muted fw-semibold">Total Sellers</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded-3">
                                    <div class="h5 fw-bold mb-1 text-warning">{{ $stats['pending_sellers'] }}</div>
                                    <small class="text-muted fw-semibold">Pending Approval</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded-3">
                                    <div class="h5 fw-bold mb-1 text-success">{{ $stats['approved_products'] }}</div>
                                    <small class="text-muted fw-semibold">Live Products</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded-3">
                                    <div class="h5 fw-bold mb-1 text-info">{{ $stats['pending_products'] }}</div>
                                    <small class="text-muted fw-semibold">Pending Review</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>