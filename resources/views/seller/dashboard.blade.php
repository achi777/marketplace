<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h3 mb-1 fw-bold text-dark">
                    <i class="bi bi-shop me-2 text-primary"></i>Seller Dashboard
                </h2>
                <p class="text-muted small mb-0">Manage your store and track your performance</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/seller/products/create" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-2"></i>Add Product
                </a>
                <a href="/seller/products" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-box-seam me-2"></i>My Products
                </a>
            </div>
        </div>
    </x-slot>

    @push('styles')

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
    @endpush

    <div class="container py-4">
        {{-- KYC verification notice - disabled for now
        <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-info-circle-fill" style="font-size: 1.5rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">Welcome to Seller Dashboard</h6>
                    <p class="mb-0">Manage your products, track orders, and grow your business.</p>
                </div>
            </div>
        </div>
        --}}

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-gradient p-4 text-white">
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
                <div class="stats-card stats-gradient success p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Active Products</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['active_products']) }}</h3>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
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
                        @if(count($recentOrders) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-semibold">Order #</th>
                                            <th class="fw-semibold">Customer</th>
                                            <th class="fw-semibold">Date</th>
                                            <th class="fw-semibold">Items</th>
                                            <th class="fw-semibold">Status</th>
                                            <th class="fw-semibold">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td class="fw-semibold text-primary">#{{ $order['id'] }}</td>
                                                <td>{{ $order['customer_name'] }}</td>
                                                <td class="text-muted">{{ date('M d, Y', strtotime($order['created_at'])) }}</td>
                                                <td><span class="badge bg-light text-dark rounded-pill">Items</span></td>
                                                <td>
                                                    <span class="badge rounded-pill 
                                                        @if($order['status'] === 'delivered') bg-success
                                                        @elseif($order['status'] === 'shipped') bg-info
                                                        @elseif($order['status'] === 'processing') bg-warning text-dark
                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($order['status']) }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">${{ number_format($order['total_amount'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">No orders yet</h6>
                                <p class="text-muted">Orders from customers will appear here once you start selling.</p>
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
                            <a href="/seller/products" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-box-seam me-3 text-success"></i>
                                <span class="fw-semibold">Manage Products</span>
                            </a>
                            <a href="/seller/products/create" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-plus-circle me-3 text-primary"></i>
                                <span class="fw-semibold">Add New Product</span>
                            </a>
                            <a href="/orders" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-bag-check me-3 text-warning"></i>
                                <span class="fw-semibold">View All Orders</span>
                            </a>
                            <a href="/profile" class="quick-action-btn d-flex align-items-center">
                                <i class="bi bi-person me-3 text-info"></i>
                                <span class="fw-semibold">Profile Settings</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="main-card">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-person-check me-2 text-primary"></i>
                            Account Status
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">KYC Status:</span>
                                <span class="badge rounded-pill bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Active
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Approval Status:</span>
                                <span class="badge rounded-pill bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Approved
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center">
                            <div class="p-3 bg-light rounded-3">
                                @if(auth()->check() && auth()->user()->role === 'seller')
                                    <i class="bi bi-check-circle-fill text-success mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="text-success mb-1">Ready to Sell!</h6>
                                    <small class="text-muted">You can now start selling on our marketplace</small>
                                @else
                                    <i class="bi bi-check-circle-fill text-success mb-2" style="font-size: 2rem;"></i>
                                    <h6 class="text-success mb-1">Ready to Sell!</h6>
                                    <small class="text-muted">You can now start selling on our marketplace</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>