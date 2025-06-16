<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">User Profile</h2>
                <p class="text-muted small mb-0">Detailed view of {{ $user->name }}'s account information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-pencil me-2"></i>Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Users
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
        
        .profile-card, .stats-card, .data-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .profile-card::before, .stats-card::before, .data-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient, linear-gradient(135deg, #667eea 0%, #764ba2 100%));
        }
        
        .profile-card:hover, .data-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
        
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            flex-shrink: 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .info-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 0.9rem;
        }
        
        .role-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
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
        
        .btn-success {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #00a085 0%, #00b8b3 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 184, 148, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #fcbf49 0%, #d63031 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253, 203, 110, 0.4);
        }
        
        .table-modern {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .table-modern thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table-modern thead th {
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }
        
        .table-modern tbody tr {
            transition: all 0.3s ease;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }
        
        .table-modern tbody td {
            border: none;
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .product-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ddd 0%, #ccc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .info-section {
                padding: 1rem;
            }
            
            .user-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- User Profile Card -->
        <div class="profile-card mb-4">
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="info-section">
                            <h5 class="section-title">
                                <i class="bi bi-person-circle"></i>
                                Personal Information
                            </h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-semibold">Full Name</label>
                                        <h5 class="mb-0 text-dark">{{ $user->name }}</h5>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-semibold">Email Address</label>
                                        <p class="mb-0 text-dark">{{ $user->email }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-semibold">Phone Number</label>
                                        <p class="mb-0 text-dark">{{ $user->phone ?: 'Not provided' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-semibold">Account Role</label>
                                        <div>
                                            <span class="role-badge 
                                                @if($user->role === 'admin') bg-danger text-white
                                                @elseif($user->role === 'seller') bg-info text-white
                                                @else bg-success text-white @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-semibold">Account Status</label>
                                        <div>
                                            <span class="status-badge {{ $user->is_approved ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                                <i class="bi bi-{{ $user->is_approved ? 'check-circle-fill' : 'clock-fill' }} me-1"></i>
                                                {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-semibold">Member Since</label>
                                        <p class="mb-0 text-dark">{{ $user->created_at->format('M d, Y \a\t H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($user->address)
                            <div class="info-section">
                                <h5 class="section-title">
                                    <i class="bi bi-geo-alt"></i>
                                    Address Information
                                </h5>
                                <p class="mb-0 text-dark">{{ $user->address }}</p>
                            </div>
                        @endif
                        
                        @if($user->role === 'seller')
                            <div class="info-section">
                                <h5 class="section-title">
                                    <i class="bi bi-shop"></i>
                                    Seller Information
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-semibold">KYC Verification</label>
                                        <div>
                                            <span class="status-badge {{ $user->isKycVerified() ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                <i class="bi bi-{{ $user->isKycVerified() ? 'shield-check' : 'shield-x' }} me-1"></i>
                                                {{ $user->isKycVerified() ? 'Verified' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-semibold">Selling Permission</label>
                                        <div>
                                            <span class="status-badge {{ $user->canSell() ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                <i class="bi bi-{{ $user->canSell() ? 'check-circle' : 'x-circle' }} me-1"></i>
                                                {{ $user->canSell() ? 'Allowed' : 'Restricted' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="user-avatar mx-auto mb-3">
                                <i class="bi bi-person"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ ucfirst($user->role) }} Account</p>
                            
                            @if($user->role === 'seller')
                                <div class="d-flex flex-column gap-2">
                                    @if(!$user->is_approved)
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="bi bi-check-circle me-2"></i>Approve Seller
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Are you sure you want to revoke approval?')">
                                                <i class="bi bi-x-circle me-2"></i>Revoke Approval
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Orders</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_orders']) }}</h3>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-bag-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card success p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Spent</p>
                            <h3 class="mb-0 fw-bold text-dark">${{ number_format($stats['total_spent'], 2) }}</h3>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->role === 'seller')
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card info p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Products</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_products']) }}</h3>
                            </div>
                            <div class="stat-icon info">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-card warning p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Active Products</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['active_products']) }}</h3>
                            </div>
                            <div class="stat-icon warning">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Orders -->
        @if($user->orders->count() > 0)
            <div class="data-card mb-4">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-receipt me-2 text-primary"></i>
                        Recent Orders
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders->take(5) as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number ?? '#' . $order->id }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="status-badge 
                                                @if($order->status === 'delivered') bg-success text-white
                                                @elseif($order->status === 'shipped') bg-info text-white
                                                @elseif($order->status === 'processing') bg-warning text-dark
                                                @else bg-secondary text-white @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Products (for sellers) -->
        @if($user->role === 'seller' && $user->products->count() > 0)
            <div class="data-card">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-box-seam me-2 text-primary"></i>
                        Products Catalog
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->products->take(10) as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->main_image)
                                                    <img class="product-image me-3" src="{{ $product->main_image }}" alt="{{ $product->name }}">
                                                @else
                                                    <div class="product-placeholder me-3">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ $product->slug }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge 
                                                @if($product->status === 'approved') bg-success text-white
                                                @elseif($product->status === 'pending') bg-warning text-dark
                                                @else bg-danger text-white @endif">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>