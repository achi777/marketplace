<!DOCTYPE html>
<html>
<head>
    <title>Minimal Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <!-- Simple Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Marketplace</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white">
                    Welcome, {{ auth()->user()->name }}
                </span>
                <a href="/logout" class="nav-link text-white">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-light py-3">
        <div class="container">
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
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ number_format($stats['total_products']) }}</h3>
                        <p class="mb-0">Total Products</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ number_format($stats['active_products']) }}</h3>
                        <p class="mb-0">Active Products</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-info">{{ number_format($stats['total_orders']) }}</h3>
                        <p class="mb-0">Total Orders</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning">${{ number_format($stats['total_revenue'], 2) }}</h3>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders and Products -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($recentOrders && $recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td>#{{ $order['id'] }}</td>
                                                <td>{{ $order['customer_name'] }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $order['status'] === 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order['status']) }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($order['total_amount'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">No orders yet</h6>
                                <p class="text-muted">Orders from customers will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="/seller/products" class="btn btn-outline-success">
                                <i class="bi bi-box-seam me-2"></i>Manage Products
                            </a>
                            <a href="/seller/products/create" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add New Product
                            </a>
                            <a href="/orders" class="btn btn-outline-warning">
                                <i class="bi bi-bag-check me-2"></i>View All Orders
                            </a>
                            <a href="/profile" class="btn btn-outline-info">
                                <i class="bi bi-person me-2"></i>Profile Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>